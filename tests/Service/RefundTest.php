<?php

namespace Paynow\Tests\Service;

use Paynow\Exception\PaynowException;
use Paynow\Service\Refund;
use Paynow\Tests\TestCase;

class RefundTest extends TestCase
{
    public function testShouldRefundPaymentSuccessfully()
    {
        // given
        $this->testHttpClient->mockResponse('refund_success.json', 200);
        $this->client->setHttpClient($this->testHttpClient);
        $refundService = new Refund($this->client);

        // when
        $response = $refundService->create('NOR3-FUN-D4U-LOL', 'idempotencyKey123', 100, null);

        // then
        $this->assertNotEmpty($response->getRefundId());
        $this->assertNotEmpty($response->getStatus());
    }

    public function testShouldNotAuthorizePaymentSuccessfully()
    {
        // given
        $this->testHttpClient->mockResponse('refund_failed.json', 400);
        $this->client->setHttpClient($this->testHttpClient);
        $refundService = new Refund($this->client);

        // when
        try {
            $response = $refundService->create('NOR3-FUN-D4U-LOL', 'idempotencyKey123', 100, null);
        } catch (PaynowException $exception) {
            // then
            $this->assertEquals(400, $exception->getCode());
            $this->assertEquals('INSUFFICIENT_BALANCE_FUNDS', $exception->getErrors()[0]->getType());
            $this->assertEquals(
                'Insufficient funds on balance',
                $exception->getErrors()[0]->getMessage()
            );
        }
    }
}
