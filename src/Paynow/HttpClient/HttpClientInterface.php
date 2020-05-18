<?php

namespace Paynow\HttpClient;

interface HttpClientInterface
{
    public function post(string $url, array $data, $idempotencyKey = null): ApiResponse;

    public function patch(string $url, array $data): ApiResponse;

    public function get(string $url): ApiResponse;
}
