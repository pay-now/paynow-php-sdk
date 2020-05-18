<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\ApiResponse;
use Paynow\HttpClient\HttpClientException;

class Payment extends Service
{
    /**
     * Authorize payment
     *
     * @param array $data
     * @param string $idempotencyKey
     * @throws PaynowException
     * @return ApiResponse
     */
    public function authorize(array $data, $idempotencyKey = null): ApiResponse
    {
        try {
            return $this->getClient()
                ->getHttpClient()
                ->post(
                    Configuration::API_VERSION.'/payments',
                    $data,
                    $idempotencyKey ?? $data['externalId']
                );
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }

    /**
     * @param string $paymentId
     * @throws PaynowException
     * @return ApiResponse
     */
    public function status(string $paymentId): ApiResponse
    {
        try {
            return $this->getClient()
                ->getHttpClient()
                ->get(Configuration::API_VERSION."/payments/$paymentId/status");
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }
}
