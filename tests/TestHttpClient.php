<?php

namespace Paynow;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Paynow\HttpClient\HttpClient;

class TestHttpClient extends HttpClient
{
    public function mockResponse($responseFile, $httpStatus)
    {
        $content = null;
        if ($responseFile != null) {
            $filePath = dirname(__FILE__) . '/resources/' . $responseFile;
            $content = file_get_contents($filePath, true);
        }

        $mock = new Mock([
            new Response($httpStatus, ['Content-Type' => 'application/json'], Stream::factory($content))
        ]);

        $this->client->getEmitter()->attach($mock);
    }
}