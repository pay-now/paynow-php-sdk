<?php

namespace Paynow\Model\PaymentMethods;

class PaymentMethod
{
    private $id;
    private $type;
    private $name;
    private $description;
    private $image;
    private $status;
    private $authorizationType;

    public function __construct($id, $type, $name, $description, $image, $status, $authorizationType)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
        $this->authorizationType = $authorizationType;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->status == Status::ENABLED;
    }

    public function getAuthorizationType()
    {
        return $this->authorizationType;
    }
}
