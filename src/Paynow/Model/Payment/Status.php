<?php

namespace Paynow\Model\Payment;

class Status
{
    public const STATUS_NEW = 'NEW';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_ERROR = 'ERROR';
}
