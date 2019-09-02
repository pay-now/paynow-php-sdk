<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;

class ShopConfiguration extends Service
{
    public function changeUrls(array $data)
    {
        try {
            return $this->getClient()
                ->getHttpClient()
                ->patch(Configuration::API_VERSION . '/configuration/shop', $data);
        } catch (HttpClientException $exception) {
            throw new PaynowException($exception->getMessage(), $exception->getStatus(), $exception->getBody());
        }
    }
}