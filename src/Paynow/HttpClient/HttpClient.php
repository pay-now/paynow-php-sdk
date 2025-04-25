<?php

namespace Paynow\HttpClient;

use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Paynow\Configuration;
use Paynow\Util\SignatureCalculator;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriInterface;

class HttpClient implements HttpClientInterface
{
    /** @var ClientInterface */
    protected $client;

    /** @var RequestFactoryInterface */
    protected $messageFactory;

    /** @var StreamFactoryInterface */
    protected $streamFactory;

    /** @var Configuration */
    protected $config;

    /** @var UriInterface */
    private $url;

    /** @param Configuration $config */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->messageFactory = Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        try {
            $this->client = Psr18ClientDiscovery::find();
        } catch (NotFoundException $exception) {
            $this->client = HttpClientDiscovery::find();
        }
        $this->url = Psr17FactoryDiscovery::findUriFactory()->createUri((string)$config->getUrl());
    }

    /**
     * @return string
     */
    private function getUserAgent(): string
    {
        if ($this->config->getApplicationName()) {
            return $this->config->getApplicationName() . ' (' . Configuration::USER_AGENT . ')';
        }

        return Configuration::USER_AGENT;
    }

    /**
     * @param RequestInterface $request
     * @return ApiResponse
     * @throws HttpClientException
     */
    private function send(RequestInterface $request): ApiResponse
    {
        try {
            return new ApiResponse($this->client->sendRequest($request));
        } catch (ClientExceptionInterface $exception) {
            throw new HttpClientException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param string $url
     * @param array $data
     * @param string|null $idempotencyKey
     * @return ApiResponse
     * @throws HttpClientException
     */
    public function post(string $url, array $data, ?string $idempotencyKey = null): ApiResponse
    {
		$isv3	 = strpos($url, Configuration::API_VERSION_V3) !== false;
        $headers = $this->prepareHeaders($data, [], $idempotencyKey, $isv3);

		if ($idempotencyKey && !$isv3) {
			$headers['Idempotency-Key'] = $idempotencyKey;
		}

        $request = $this->messageFactory->createRequest(
            'POST',
            $this->url->withPath($url)
        );

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        $request = $request->withBody($this->streamFactory->createStream($this->arrayAsJson($data)));

        return $this->send($request);
    }

    /**
     * @param string $url
     * @param array $data
     * @param string|null $idempotencyKey
     * @return ApiResponse
     * @throws HttpClientException
     */
    public function patch(string $url, array $data, ?string $idempotencyKey = null): ApiResponse
    {
        $headers = $this->prepareHeaders($data, [], $idempotencyKey, strpos($url, Configuration::API_VERSION_V3) !== false);
        $request = $this->messageFactory->createRequest(
            'PATCH',
            $this->url->withPath($url)
        );

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        $request = $request->withBody($this->streamFactory->createStream($this->arrayAsJson($data)));

        return $this->send($request);
    }

    /**
     * @param string $url
     * @param string|null $query
     * @param string|null $idempotencyKey
     * @return ApiResponse
     * @throws HttpClientException
     */
    public function get(string $url, ?string $query = null, ?string $idempotencyKey = null): ApiResponse
    {
        $request = $this->messageFactory->createRequest(
            'GET',
            $query ? $this->url->withPath($url)->withQuery($query) : $this->url->withPath($url)
        );

        $parameters = [];
        if ($query) {
            parse_str(urldecode($query), $parameters);
        }

        foreach ($this->prepareHeaders(null, $parameters, $idempotencyKey, strpos($url, Configuration::API_VERSION_V3) !== false) as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $this->send($request);
    }

    /**
     * @param string $url
     * @param string $idempotencyKey
     * @param string|null $query
     * @return ApiResponse
     * @throws HttpClientException
     */
    public function delete(string $url, string $idempotencyKey, ?string $query = null): ApiResponse
    {
        $request = $this->messageFactory->createRequest(
            'DELETE',
            $query ? $this->url->withPath($url)->withQuery($query) : $this->url->withPath($url)
        );

        $parameters = [];
        if ($query) {
            parse_str(urldecode($query), $parameters);
        }

        foreach ($this->prepareHeaders(null, $parameters, $idempotencyKey, strpos($url, Configuration::API_VERSION_V3) !== false) as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $this->send($request);
    }

    /**
     * @param array $data
     * @return string
     */
    private function arrayAsJson(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array|null $body
     * @param array $query
     * @param string|null $idempotencyKey
     * @param bool $isv3
     * @return array
     */
    private function prepareHeaders(?array $body = null, array $query = [], ?string $idempotencyKey = '', bool $isv3 = true): array
    {
        $headers = [
            'Api-Key' => $this->config->getApiKey(),
            'User-Agent' => $this->getUserAgent(),
            'Accept' => 'application/json',
        ];

        if ($isv3) {
            $headers['Idempotency-Key'] = $idempotencyKey;
            $headers['Signature'] = SignatureCalculator::generateV3(
                $this->config->getApiKey(),
                $this->config->getSignatureKey(),
                $idempotencyKey,
                $body ? json_encode($body, JSON_UNESCAPED_SLASHES) : '',
                $query
            );
        }

        if (!is_null($body)) {
            $headers['Content-Type'] = 'application/json';
            if (!$isv3) {
                $headers['Signature'] = SignatureCalculator::generate($this->config->getSignatureKey(), json_encode($body, JSON_UNESCAPED_SLASHES));
            }
        }

        return $headers;
    }
}
