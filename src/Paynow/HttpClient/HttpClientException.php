<?php

namespace Paynow\HttpClient;

class HttpClientException extends \Exception
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var int
     */
    private $status;

    public function __construct($message, $status, $body)
    {
        parent::__construct($message);
        $this->status = $status;
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getErrors() {
        return $this->errors;
    }
}
