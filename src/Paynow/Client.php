<?php

namespace Paynow;

use Paynow\HttpClient\HttpClientInterface;

class Client
{
    private $configuration;

    private $httpClient;

    /**
     * Client constructor.
     *
     * @param $apiKey
     * @param $apiSignatureKey
     * @param $environment
     */
    public function __construct($apiKey, $apiSignatureKey, $environment)
    {
        $this->configuration = new Configuration();
        $this->configuration->setApiKey($apiKey);
        $this->configuration->setSignatureKey($apiSignatureKey);
        $this->configuration->setEnvironment($environment);
        $this->httpClient = new HttpClient\HttpClient($this->configuration);
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->configuration->setApplicationName($applicationName);
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return HttpClient\HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
