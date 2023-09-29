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

    public function isExpired(): bool
    {
        if (!$this->expirationDate || strpos($this->expirationDate, '/') === false) {
            return false;
        }

        [$month, $year] = explode('/', $this->expirationDate);
        $month = intval($month);
        $year = intval($year);
        $currentMonth = intval(date('m'));
        $currentYear = intval(date('y'));

        return $currentYear > $year || ($currentYear === $year && $currentMonth > $month);
    }
}
