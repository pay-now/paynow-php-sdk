<?php

namespace Paynow\Service;

use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\HttpClient\HttpClientException;
use Paynow\Response\DataProcessing\Notices;

class DataProcessing extends Service
{
    /**
     * Retrieve data processing notice
     *
     * @param string|null $locale
     * @param string|null $idempotencyKey
     * @return Notices
     * @throws PaynowException
     */
    public function getNotices(?string $locale, ?string $idempotencyKey = null): Notices
    {
        $parameters = [];
        if (!empty($locale)) {
            $parameters['locale'] = $locale;
        }

        try {
            if (empty($idempotencyKey)) {
                $idempotencyKey = md5(($locale ?? '') . '_' . $this->getClient()->getConfiguration()->getApiKey());
            }

            $decodedApiResponse = $this->getClient()
                ->getHttpClient()
                ->get(
                    Configuration::API_VERSION_V3 . "/payments/dataprocessing/notices",
                    http_build_query($parameters, '', '&'),
                    $idempotencyKey
                )
                ->decode();

            return new Notices($decodedApiResponse);
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
