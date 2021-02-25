<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;
use Paynow\Response\Payment\Authorize;
use Paynow\Response\Payment\Status;
use Paynow\Response\PaymentMethods\PaymentMethods;

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
     * @return PaymentMethods
     * @throws PaynowException
     */
    public function getPaymentMethods()
    {
        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(Configuration::API_VERSION . '/payments/paymentmethods')
                ->decode();
            return new PaymentMethods($decodedApiResponse);
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }

    /**
     * Retrieve payment status
     *
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
