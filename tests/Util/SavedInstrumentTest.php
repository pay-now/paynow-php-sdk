<?php

namespace Paynow\Tests\Util;

use Paynow\Model\PaymentMethods\SavedInstrument;
use Paynow\Tests\TestCase;

class SavedInstrumentTest extends TestCase
{
    /**
     * @dataProvider datesProvider
     */
    public function testIsExpired($currentDate, $expirationDate, $isExpired)
    {
        // given + when
        $savedInstrument = new SavedInstrument('test', $expirationDate, 'VISA', 'test', '1234');

        // then
        $this->assertEquals($isExpired, $savedInstrument->isExpired($currentDate));
    }

    public function datesProvider(): array
    {
        return [
            [time(), date('m/y'), false],
            [strtotime('+1 month'), date('m/y'), true],
            [strtotime('1 September 2025'), '09/24', true],
            [strtotime('1 September 2023'), '09/24', false],
            [strtotime('1 September 2023'), '10/23', false],
        ];
    }
}
