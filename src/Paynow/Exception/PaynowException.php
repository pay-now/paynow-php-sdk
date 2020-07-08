<?php

namespace Paynow\Exception;

use Exception;

class PaynowException extends Exception
{
    /** @var Error[] */
    private $errors = [];

    public function __construct(string $message, int $code = 0, ?string $body = null)
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
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
