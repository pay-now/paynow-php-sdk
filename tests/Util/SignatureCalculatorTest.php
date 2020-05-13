<?php

namespace Paynow\Tests\Util;

use Paynow\Tests\TestCase;
use Paynow\Util\SignatureCalculator;

class SignatureCalculatorTest extends TestCase
{
    public function testNotValidSuccessfully()
    {
        // given + when
        $signatureCalculator = new SignatureCalculator('InvalidSecretKey', ['key' => 'value']);

        // then
        $this->assertNotEquals('hash', $signatureCalculator->getHash());
    }

    public function testShouldValidSuccessfully()
    {
        // given + when
        $signatureCalculator = new SignatureCalculator(
            'a621a1fb-b4d8-48ba-a6a3-2a28ed61f605',
            [
                'key1' => 'value1',
                'key2' => 'val/ue2',
            ]
        );

        // then
        $this->assertEquals('vnZs3ZaiBGFd9R6qEfgKO1psx+S2P9sFIcY4AO5hJ78=', $signatureCalculator->getHash());
    }

    public function testExceptionForEmptyData()
    {
        // given
        $this->expectException(\InvalidArgumentException::class);

        // when
        $signatureCalculator = new SignatureCalculator('a621a1fb-b4d8-48ba-a6a3-2a28ed61f605', []);

        // then
    }
}
