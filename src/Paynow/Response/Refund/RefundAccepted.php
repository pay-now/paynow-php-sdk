<?php

namespace Paynow\Response\Refund;

use Paynow\Model\Refund\Status;

class RefundAccepted
{
    /** @var string */
    private $refundId;

    /** @var Status|string */
    private $status;

    public function __construct($refundId, $status)
    {
        $this->refundId = $refundId;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getRefundId(): string
    {
        return $this->refundId;
    }

    /**
     * @return Status|string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
