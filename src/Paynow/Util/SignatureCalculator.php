<?php

namespace Paynow\Util;

use InvalidArgumentException;

class SignatureCalculator
{
    /** @var string */
    protected $hash;

    /**
     * @param string $apiKey
     * @param string $signatureKey
     * @param string $idempotencyKey
     * @param string $data
     * @param array $parameters
     */
    public function __construct(string $apiKey, string $signatureKey, string $idempotencyKey, string $data = "", array $parameters = [])
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('You did not provide a api key');
        }

        if (empty($idempotencyKey)) {
            throw new InvalidArgumentException('You did not provide a idempotency key');
        }

        $parsedParameters = [];

        foreach ($parameters as $key => $value) {
            $parsedParameters[$key] = is_array($value) ? $value : [$value];
        }

        $signatureBody = [
            'headers' => [
                'Api-Key' => $apiKey,
                'Idempotency-Key' => $idempotencyKey,
            ],
            'parameters' => $parsedParameters,
            'body' => $data,
        ];

        $this->hash = base64_encode(hash_hmac('sha256', json_encode($signatureBody), $signatureKey, true));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getHash();
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
