<?php

namespace Paynow\Model\PaymentMethods;

class SavedInstrument
{
    private $name;
    private $expirationDate;
    private $brand;
    private $image;
    private $token;
    /**
     * @var SavedInstrument\Status
     */
    private $status;

    public function __construct($name, $expirationDate, $brand, $image, $token, $status)
    {
        $this->name = $name;
        $this->expirationDate = $expirationDate;
        $this->brand = $brand;
        $this->image = $image;
        $this->token = $token;
        $this->status = $status;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isExpired(): bool
    {
        return in_array($this->status, [SavedInstrument\Status::EXPIRED_CARD, SavedInstrument\Status::EXPIRED_TOKEN]);
    }
}
