<?php

namespace Paynow;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $testHttpClient;

    protected $client;

    public function setUp()
    {
        $this->client = new Client(
            'TestApiKey',
            'TestSignatureKey',
            Environment::SANDBOX
        );
        $this->testHttpClient = new TestHttpClient($this->client->getConfiguration());
    }
}