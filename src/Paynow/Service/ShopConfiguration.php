<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\ApiResponse;
use Paynow\HttpClient\HttpClientException;

class ShopConfiguration extends Service
{
	public const STATUS_ENABLED = 'ENABLED';
	public const STATUS_DISABLED = 'DISABLED';
	public const STATUS_UNINSTALLED = 'UNINSTALLED';
	public const STATUS_UPDATED = 'UPDATED';

	/**
	 * @param string $continueUrl
	 * @param string $notificationUrl
	 * @param string|null $idempotencyKey
	 * @return ApiResponse
	 * @throws PaynowException
	 */
    public function changeUrls(string $continueUrl, string $notificationUrl, ?string $idempotencyKey = null): ApiResponse
    {
        $data = [
            'continueUrl' => $continueUrl,
            'notificationUrl' => $notificationUrl,
        ];
        try {
            if (empty($idempotencyKey)) {
                $idempotencyKey = md5($this->getClient()->getConfiguration()->getApiKey());
            }

            return $this->getClient()
                ->getHttpClient()
                ->patch(
                    '/' . Configuration::API_VERSION_V3 . '/configuration/shop/urls',
                    $data,
                    $idempotencyKey
                );
        } catch (HttpClientException $exception) {
            throw new PaynowException(
                $exception->getMessage(),
                $exception->getStatus(),
                $exception->getBody(),
                $exception
            );
        }
    }

	/**
	 * @param string $status
	 * @return ApiResponse
	 * @throws PaynowException
	 */
	public function status(string $status): ApiResponse
	{
		try {
			return $this->getClient()
				->getHttpClient()
				->postWithoutAuth(
					'/' . Configuration::API_VERSION_V3 . '/configuration/shop/plugin/status',
					[
						'status' => $status,
					]
				);
		} catch (HttpClientException $exception) {
			throw new PaynowException(
				$exception->getMessage(),
				$exception->getStatus(),
				$exception->getBody(),
				$exception
			);
		}
	}
}
