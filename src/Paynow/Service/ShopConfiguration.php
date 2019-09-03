<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\ConfigurationException;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\ApiResponse;
use Paynow\HttpClient\HttpClientException;

/**
 * Class ShopConfiguration
 *
 * @package Paynow\Service
 */
class ShopConfiguration extends Service
{
    /**
     * @param array $data
     * @return ApiResponse
     * @throws PaynowException
     * @throws ConfigurationException
     */
    public function changeUrls(array $data)
    {
        try {
            return $this->getClient()
                ->getHttpClient()
                ->patch(Configuration::API_VERSION . '/configuration/shop', $data);
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }
}
