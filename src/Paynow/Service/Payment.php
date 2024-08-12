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
            $apiVersion = Configuration::API_VERSION;

            if (array_key_exists('paymentMethodToken', $data) ||
                array_key_exists('externalId', $data['buyer'] ?? []) ||
                array_key_exists('address', $data['buyer'] ?? [])) {
                $apiVersion = Configuration::API_VERSION_V3;
            }

            if (empty($idempotencyKey)) {
                $idempotencyKey = ($data['externalId'] ?? null) ? md5($data['externalId']) : md5('_' . $this->getClient()->getConfiguration()->getApiKey());
            }

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    '/' . $apiVersion . '/payments',
                    $data,
                    $idempotencyKey
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
     * @param bool $applePayEnabled
     * @param string|null $idempotencyKey
     * @param string|null $buyerExternalId
     * @return PaymentMethods
     * @throws PaynowException
     */
    public function getPaymentMethods(?string $currency = null, ?int $amount = 0, bool $applePayEnabled = true, ?string $idempotencyKey = null, ?string $buyerExternalId = null): PaymentMethods
    {
        $parameters = [];

        if ($amount > 0) {
            $parameters['amount'] = $amount;
        }

        $parameters['applePayEnabled'] = $applePayEnabled;

        if (!empty($currency)) {
            $parameters['currency'] = $currency;
        }

        if (!empty($buyerExternalId)) {
            $parameters['externalBuyerId'] = $buyerExternalId;
        }

        try {
            $apiVersion = Configuration::API_VERSION_V2;

            if (!empty($buyerExternalId) || !empty($idempotencyKey)) {
                $apiVersion = Configuration::API_VERSION_V3;
            }

            if (empty($idempotencyKey) && !empty($buyerExternalId)) {
                $idempotencyKey = md5($currency . '_' . $amount . '_' . $this->getClient()->getConfiguration()->getApiKey());
            }

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    $apiVersion . '/payments/paymentmethods',
                    http_build_query($parameters, '', '&'),
                    $idempotencyKey
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
     * @param string $externalBuyerId
     * @param string $token
     * @param string $idempotencyKey
     * @throws PaynowException
     */
    public function removeSavedInstrument(string $externalBuyerId, string $token, string $idempotencyKey): void
    {
        $parameters = [
            'externalBuyerId' => $externalBuyerId,
            'token' => $token,
        ];

        try {
            $this->getClient()
                ->getHttpClient()
                ->delete(
                    Configuration::API_VERSION_V3 . '/payments/paymentmethods/saved',
                    $idempotencyKey,
                    http_build_query($parameters, '', '&')
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
            if (empty($idempotencyKey)) {
                $idempotencyKey = md5($paymentId);
            }

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    Configuration::API_VERSION_V3 . "/payments/$paymentId/status",
                    null,
                    $idempotencyKey
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
