<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;
use Paynow\Response\Payment\Authorize;
use Paynow\Response\Payment\Status;

class Payment extends Service
{
    /**
     * Authorize payment
     *
     * @param array $data
     * @param string $idempotencyKey
     * @throws PaynowException
     * @return Authorize
     */
    public function authorize(array $data, $idempotencyKey = null): Authorize
    {
        try {
            $decpdedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    Configuration::API_VERSION . '/payments',
                    $data,
                    $idempotencyKey ?? $data['externalId']
                )
                ->decode();
            return new Authorize($decpdedApiResponse->redirectUrl, $decpdedApiResponse->paymentId, $decpdedApiResponse->status);
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
     * @return Status
     */
    public function status(string $paymentId): Status
    {
        try {
            $decpdedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(Configuration::API_VERSION . "/payments/$paymentId/status")
                ->decode();

            return new Status($decpdedApiResponse->paymentId, $decpdedApiResponse->status);
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }
}
