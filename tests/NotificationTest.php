<?php

namespace Paynow;

class NotificationTest extends TestCase
{
    public function testVerifyPayloadSuccessfully()
    {
        // given
        $payload = file_get_contents('tests/resources/notification.json');
        $headers = ['Signature' => 'UZgTT6iSv174R/OyQ2DWRCE9UCmvdXDS8rbQQcjk+AA='];

        // when
        new Notification('s3ecret-k3y', $payload, $headers);

        // then
        $this->assertTrue(true);
    }

    /**
     * @expectedException \Paynow\Exception\SignatureVerificationException
     */
    public function testShouldThrowExceptionOnIncorrectSignature()
    {
        // given
        $payload = file_get_contents('tests/resources/notification.json');
        $headers = ['Signature' => 'Aq/VmN15rtjVbuy9F7Yw+Ym76H+VZjVSuHGpg4dwitY='];

        // when
        new Notification('s3ecret-k3y', $payload, $headers);

        // then
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowExceptionOnMissingPayload()
    {
        // given
        $payload = null;
        $headers = [];

        // when
        new Notification('s3ecret-k3y', null, null);

        // then
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowExceptionOnMissingPayloadHeaders()
    {
        // given
        $payload = file_get_contents('tests/resources/notification.json');
        $headers = null;

        // when
        new Notification('s3ecret-k3y', null, null);

        // then
    }
}
