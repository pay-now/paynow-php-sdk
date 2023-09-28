<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\ApiResponse;
use Paynow\HttpClient\HttpClientException;

class ShopConfiguration extends Service
{
    /**
     * @param string $continueUrl
     * @param string $notificationUrl
     * @param string|null $idempotencyKey
     * @return ApiResponse
     * @throws PaynowException
     */
    public function changeUrls(string $continueUrl, string $notificationUrl, ?string $idempotencyKey = null): ApiResponse
    {
        $data = [
            'continueUrl' => $continueUrl,
            'notificationUrl' => $notificationUrl,
        ];
        try {
            return $this->getClient()
                ->getHttpClient()
                ->patch(
                    '/' . Configuration::API_VERSION . '/configuration/shop/urls',
                    $data,
                    $idempotencyKey ?? md5($notificationUrl . '_' . $continueUrl)
                );
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody(),
                $exception
            );
        }
    }
}
