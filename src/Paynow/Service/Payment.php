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
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    '/' . $this->getApiVersion(Configuration::API_VERSION) . '/payments',
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
     * @param string|null $buyerExternalId
     * @return PaymentMethods
     * @throws PaynowException
     */
    public function getPaymentMethods(?string $currency = null, ?int $amount = 0, ?string $idempotencyKey = null, ?string $buyerExternalId = null): PaymentMethods
    {
        $parameters = [];

        if ($amount > 0) {
            $parameters['amount'] = $amount;
        }

        if (!empty($currency)) {
            $parameters['currency'] = $currency;
        }

        if (!empty($buyerExternalId)) {
            $parameters['externalBuyerId'] = $buyerExternalId;
        }

        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    $this->getApiVersion(Configuration::API_VERSION_V2) . '/payments/paymentmethods',
                    $idempotencyKey ?? md5($currency . '_' . $amount . '_' . $this->getClient()->getConfiguration()->getApiKey()),
                    http_build_query($parameters, '', '&')
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
                    $this->getApiVersion(Configuration::API_VERSION) . "/payments/$paymentId/status",
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
