<?php

namespace Paynow\Tests;

use InvalidArgumentException;
use Paynow\Exception\SignatureVerificationException;
use Paynow\Notification;

class NotificationTest extends TestCase
{
    /**
     * @dataProvider requestsToTest
     * @param $payload
     * @param $headers
     * @throws SignatureVerificationException
     * @suppress PhanNoopNew
     */
    public function testVerifyPayloadSuccessfully($payload, $headers)
    {
        // given

        // when
        new Notification('s3ecret-k3y', $payload, $headers);

        // then
        $this->assertTrue(true);
    }

    public function requestsToTest()
    {
        $payload = $this->loadData('notification.json', true);
        return [
            [
                $payload,
                ['Signature' => 'Aq/VmN15rtjVbuy9F7Yw+Ym76H+VZjVSuHGpg4dwitY=']
            ],
            [
                $payload,
                ['signature' => 'Aq/VmN15rtjVbuy9F7Yw+Ym76H+VZjVSuHGpg4dwitY=']
            ]
        ];
    }

    /**
     * @return void
     * @throws SignatureVerificationException
     * @suppress PhanNoopNew
     */
    public function testShouldThrowExceptionOnIncorrectSignature()
    {
        // given
        $this->expectException(SignatureVerificationException::class);
        $payload = $this->loadData('notification.json', true);
        $headers = ['Signature' => 'Wq/V2N15rtjVbuy9F7Yw+Ym76H+VZjVSuHGpg4dwitY='];

        // when
        new Notification('s3ecret-k3y', $payload, $headers);

        // then
    }

    /**
     * @suppress PhanNoopNew
     * @return void
     */
    public function testShouldThrowExceptionOnMissingPayload()
    {
        // given
        $this->expectException(InvalidArgumentException::class);

        // when
        new Notification('s3ecret-k3y', null, null);

        // then
    }

    /**
     * @return void
     * @throws SignatureVerificationException
     * @suppress PhanNoopNew
     */
    public function testShouldThrowExceptionOnMissingPayloadHeaders()
    {
        // given
        $this->expectException(InvalidArgumentException::class);
        $payload = $this->loadData('notification.json', true);

        // when
        new Notification('s3ecret-k3y', $payload, null);

        // then
    }
}
