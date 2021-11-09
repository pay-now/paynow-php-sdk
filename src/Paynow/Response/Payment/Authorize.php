<?php

namespace Paynow\Response\Payment;

use Paynow\Model\Payment\Status;

class Authorize
{
    /** @var string */
    private $paymentId;

    /** @var Status|string */
    private $status;

    /** @var null|string */
    private $redirectUrl;

    public function __construct($paymentId, $status, ?string $redirectUrl = null)
    {
        $this->paymentId = $paymentId;
        $this->status = $status;
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /** @return string */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }
}
