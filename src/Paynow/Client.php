<?php

namespace Paynow;

use GuzzleHttp\ClientInterface;
use Paynow\HttpClient\HttpClientInterface;

class Client
{
    private $configuration;

    private $httpClient;

    public function __construct($apiKey, $apiSignatureKey, $environment)
    {
        $this->configuration = new Configuration();
        $this->configuration->setApiKey($apiKey);
        $this->configuration->setSignatureKey($apiSignatureKey);
        $this->configuration->setEnvironment($environment);
        $this->httpClient = new HttpClient\HttpClient($this->configuration);
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function setApplicationName($applicationName)
    {
        $this->configuration->setApplicationName($applicationName);
    }

    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
