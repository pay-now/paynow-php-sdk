<?php

namespace Paynow\Util;

use InvalidArgumentException;

class SignatureCalculator
{
    /** @var string */
    protected $hash;

    /**
     * @param string $signatureKey
     * @param string $data
     * @throws InvalidArgumentException
     */
    public function __construct(string $signatureKey, string $data)
    {
        $this->hash = self::generate($signatureKey, $data);
    }

    /**
     * @param string $apiKey
     * @param string $signatureKey
     * @param string $idempotencyKey
     * @param string $data
     * @param array $parameters
     * @return string
     */
    public static function generateV3(string $apiKey, string $signatureKey, string $idempotencyKey, string $data = '', array $parameters = []): string
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('You did not provide a api key');
        }

        if (empty($signatureKey)) {
            throw new InvalidArgumentException('You did not provide a Signature key');
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
            'parameters' => $parsedParameters ?: new \stdClass(),
            'body' => $data,
        ];

        return base64_encode(hash_hmac('sha256', json_encode($signatureBody, JSON_UNESCAPED_SLASHES), $signatureKey, true));
    }

    /**
     * @param string $signatureKey
     * @param string $data
     * @return string
     */
    public static function generate(string $signatureKey, string $data): string
    {
        if (empty($signatureKey)) {
            throw new InvalidArgumentException('You did not provide a Signature key');
        }

        if (empty($data)) {
            throw new InvalidArgumentException('You did not provide any data');
        }

        return base64_encode(hash_hmac('sha256', $data, $signatureKey, true));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getHash();
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
