<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;
use Paynow\Response\Refund\RefundAccepted;

class Refund extends Service
{
    /**
     * Refund payment
     *
     * @param string $paymentId
     * @param null $idempotencyKey
     * @param null $amount
     * @param null $reason
     * @return RefundAccepted
     * @throws PaynowException
     */
    public function create(string $paymentId, $idempotencyKey = null, $amount = null, $reason = null): RefundAccepted
    {
        try {
            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->post(
                    Configuration::API_VERSION . '/payments/' . $paymentId . '/refunds',
                    [
                        'amount' => $amount,
                        'reason' => $reason
                    ],
                    $idempotencyKey
                )
                ->decode();
            return new RefundAccepted($decodedApiResponse->refundId, $decodedApiResponse->status);
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody()
            );
        }
    }
}
