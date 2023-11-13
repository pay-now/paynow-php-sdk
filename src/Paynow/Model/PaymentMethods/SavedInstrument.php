<?php

namespace Paynow\Model\PaymentMethods;

class SavedInstrument
{
    private $name;
    private $expirationDate;
    private $brand;
    private $image;
    private $token;

    public function __construct($name, $expirationDate, $brand, $image, $token)
    {
        $this->name = $name;
        $this->expirationDate = $expirationDate;
        $this->brand = $brand;
        $this->image = $image;
        $this->token = $token;
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

    public function isExpired(?int $now = null): bool
    {
        if (!$this->expirationDate || strpos($this->expirationDate, '/') === false) {
            return false;
        }

        if (empty($now)) {
            $now = time();
        }

        [$expirationMonth, $expirationYear] = explode('/', $this->expirationDate);
        $expirationMonth = intval($expirationMonth);
        $expirationYear = intval($expirationYear);
        $currentMonth = intval(date('m', $now));
        $currentYear = intval(date('y', $now));

        return $currentYear > $expirationYear || ($currentYear === $expirationYear && $currentMonth > $expirationMonth);
    }
}
