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
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    Configuration::API_VERSION . '/payments',
                    $data,
                    $idempotencyKey ?? $data['externalId']
                )
                ->decode();
            return new Authorize($decodedApiResponse->redirectUrl, $decodedApiResponse->paymentId, $decodedApiResponse->status);
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
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(Configuration::API_VERSION . "/payments/$paymentId/status")
                ->decode();

            return new Status($decodedApiResponse->paymentId, $decodedApiResponse->status);
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }
}
