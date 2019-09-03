<?php

namespace Paynow\Tests\Service;

use Paynow\Exception\PaynowException;
use Paynow\Service\ShopConfiguration;
use Paynow\Tests\TestCase;

class ShopConfigurationTest extends TestCase
{
    public function testShouldUpdateShopConfigurationSuccessfully()
    {
        // given
        $this->testHttpClient->mockResponse(null, 204);
        $this->client->setHttpClient($this->testHttpClient);
        $shopConfigurationService = new ShopConfiguration($this->client);
        $configurationData = $this->loadData('shop_configuration_request.json');

        // when
        $response = $shopConfigurationService->changeUrls($configurationData);

        // then
        $this->assertEquals(204, $response->status);
    }

    public function testShouldNotUpdateShopConfigurationSuccessfully()
    {
        // given
        $this->testHttpClient->mockResponse('shop_configuration_failed.json', 400);
        $this->client->setHttpClient($this->testHttpClient);
        $shopConfigurationService = new ShopConfiguration($this->client);
        $configurationData = $this->loadData('shop_configuration_request.json');

        // when
        try {
            $response = $shopConfigurationService->changeUrls($configurationData);
        } catch (PaynowException $exception) {
            // then
            $this->assertEquals(400, $exception->getCode());
            $this->assertEquals("VALIDATION_ERROR", $exception->getErrors()[0]->errorType);
            $this->assertEquals(
                "continue_url: invalid field value",
                $exception->getErrors()[0]->message
            );
        }
    }
}