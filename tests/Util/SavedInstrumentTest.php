<?php

namespace Paynow\Tests\Util;

use Paynow\Model\PaymentMethods\SavedInstrument;
use Paynow\Model\PaymentMethods\SavedInstrument\Status as SavedInstrumentStatus;
use Paynow\Tests\TestCase;

class SavedInstrumentTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testIsExpired($status, $isExpired)
    {
        // given + when
        $savedInstrument = new SavedInstrument('test', strtotime('+1 month'), 'VISA', 'test', '1234', $status);

        // then
        $this->assertEquals($isExpired, $savedInstrument->isExpired());
    }

    public function dataProvider(): array
    {
        return [
            [SavedInstrumentStatus::ACTIVE, false],
            [SavedInstrumentStatus::EXPIRED_CARD, true],
            [SavedInstrumentStatus::EXPIRED_TOKEN, true],
        ];
    }
}
