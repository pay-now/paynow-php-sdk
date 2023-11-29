<?php

namespace Paynow\Service;

use InvalidArgumentException;
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
     * @throws PaynowException
     * @return Status
     */
    public function create(string $paymentId, string $idempotencyKey, int $amount, $reason = null): Status
    {
        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    '/' . Configuration::API_VERSION . '/payments/' . $paymentId . '/refunds',
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
     * Retrieve refund status
     * @param $refundId
     * @throws InvalidArgumentException
     * @throws PaynowException
     * @return Status
     */
    public function status($refundId): Status
    {
        if (empty($refundId)) {
            throw new InvalidArgumentException("Empty refundId parameter");
        }

        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(Configuration::API_VERSION . "/refunds/$refundId/status")
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
