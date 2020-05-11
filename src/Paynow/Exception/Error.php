<?php

namespace Paynow\Exception;

class Error
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
