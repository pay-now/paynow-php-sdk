<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;

class Payment extends Service
{
    /**
     * @param array $data
     * @return mixed
     * @throws PaynowException
     * @throws \Paynow\Exception\ConfigurationException
     */
    public function authorize(array $data)
    {
        try {
            return $this->getClient()
                ->getHttpClient()
                ->post(
                    Configuration::API_VERSION . '/payments',
                    $data,
                    $data['externalId']
                )
                ->decode();
        } catch (HttpClientException $exception) {
            throw new PaynowException($exception->getMessage(), $exception->getStatus(), $exception->getBody());
        }
    }

    /**
     * @param string $paymentId
     * @return \Paynow\HttpClient\ApiResponse
     * @throws PaynowException
     */
    public function status($paymentId)
    {
        try {
            return $this->getClient()
                ->getHttpClient()
                ->get(Configuration::API_VERSION . "/payments/$paymentId/status")
                ->decode();
        } catch (HttpClientException $exception) {
            throw new PaynowException($exception->getMessage(), $exception->getStatus(), $exception->getBody());
        }
    }
}
