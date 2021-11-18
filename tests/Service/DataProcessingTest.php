<?php

namespace Paynow\Tests\Service;

use Paynow\Service\DataProcessing;
use Paynow\Tests\TestCase;

class DataProcessingTest extends TestCase
{
    public function testShouldRetrieveAllNoticeListSuccessfully()
    {
        // given
        $this->testHttpClient->mockResponse('data_processing_notices_success.json', 200);
        $this->client->setHttpClient($this->testHttpClient);
        $dataProcessing = new DataProcessing($this->client);

        // when
        $notices = $dataProcessing->getNotices('pl-PL')->getAll();

        // then
        $this->assertNotEmpty($notices);
    }
}
