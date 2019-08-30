<?php

namespace Paynow\Exception;

class ApiException extends PaynowException
{
    protected $statusCode;

    protected $errors;
}
