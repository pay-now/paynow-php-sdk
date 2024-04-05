<?php

namespace Paynow\Util;

class ClientExternalIdCalculator
{
    public static function calculate(string $clientId, string $secretKey): string
    {
        return bin2hex(hash_hmac('sha256', $clientId, $secretKey, true));
    }
}
