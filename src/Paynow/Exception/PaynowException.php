<?php

namespace Paynow\Exception;

use Exception;

class PaynowException extends Exception
{
    private $errors;

    public function __construct($message = '', $code = 0, $body = null)
    {
        parent::__construct($message, $code, null);

        if ($body) {
            $json = json_decode($body);
            if ($json->errors) {
                foreach ($json->errors as $error) {
                    $this->errors[] = new Error($error->errorType, $error->message);
                }
            }
        }
    }

    /**
     * @return array|null
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
