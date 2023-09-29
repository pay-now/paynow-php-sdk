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
     * @param string|null $idempotencyKey
     * @return Authorize
     * @throws PaynowException
     */
    public function authorize(array $data, ?string $idempotencyKey = null): Authorize
    {
        try {
            $apiVersion = $this->getClient()->getConfiguration()->getApiVersion() ?? Configuration::API_VERSION;

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    '/' . $apiVersion. '/payments',
                    $data,
                    $idempotencyKey ?? $data['externalId']
                )
                ->decode();

            return new Authorize(
                $decodedApiResponse->paymentId,
                $decodedApiResponse->status,
                !empty($decodedApiResponse->redirectUrl) ? $decodedApiResponse->redirectUrl : null
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

    /**
     * Retrieve available payment methods
     *
     * @param string|null $currency
     * @param int|null $amount
     * @param string|null $idempotencyKey
     * @return PaymentMethods
     * @throws PaynowException
     */
    public function getPaymentMethods(?string $currency = null, ?int $amount = 0, ?string $idempotencyKey = null): PaymentMethods
    {
        $parameters = [];
        if (!empty($currency)) {
            $parameters['currency'] = $currency;
        }

        if ($amount > 0) {
            $parameters['amount'] = $amount;
        }

        try {
            $apiVersion = $this->getClient()->getConfiguration()->getApiVersion() ?? Configuration::API_VERSION_V2;

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    $apiVersion . '/payments/paymentmethods',
                    http_build_query($parameters, '', '&'),
                    $idempotencyKey ?? md5($currency . '_' . $amount . '_' . $this->getClient()->getConfiguration()->getApiKey())
                )
                ->decode();
            return new PaymentMethods($decodedApiResponse);
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody(),
                $exception
            );
        }
    }

    /**
     * Retrieve payment status
     *
     * @param string $paymentId
     * @param string|null $idempotencyKey
     * @return Status
     * @throws PaynowException
     */
    public function status(string $paymentId, ?string $idempotencyKey = null): Status
    {
        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    Configuration::API_VERSION . "/payments/$paymentId/status",
                    $idempotencyKey ?? $paymentId
                )
                ->decode();

            return new Status($decodedApiResponse->paymentId, $decodedApiResponse->status);
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
