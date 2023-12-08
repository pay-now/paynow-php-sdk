<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;
use Paynow\Response\Refund\Status;

class Refund extends Service
{
    /**
     * Refund payment
     *
     * @param string $paymentId
     * @param string $idempotencyKey
     * @param int $amount
     * @param null $reason
     * @return Status
     * @throws PaynowException
     */
    public function create(string $paymentId, string $idempotencyKey, int $amount, $reason = null): Status
    {
        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    '/' . Configuration::API_VERSION_V3 . '/payments/' . $paymentId . '/refunds',
                    [
                        'amount' => $amount,
                        'reason' => $reason
                    ],
                    $idempotencyKey
                )
                ->decode();
            return new Status($decodedApiResponse->refundId, $decodedApiResponse->status);
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
     * @param $refundId
     * @param string|null $idempotencyKey
     * @return Status
     * @throws PaynowException
     */
    public function status($refundId, ?string $idempotencyKey = null): Status
    {
        try {
            if (empty($idempotencyKey)) {
                $idempotencyKey = md5($refundId);
            }

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    Configuration::API_VERSION_V3 . "/refunds/$refundId/status",
                    null,
                    $idempotencyKey
                )
                ->decode();

            return new Status($decodedApiResponse->refundId, $decodedApiResponse->status);
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
