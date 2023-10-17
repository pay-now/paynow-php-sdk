<?php

namespace Paynow\HttpClient;

interface HttpClientInterface
{
    public function post(string $url, array $data, string $idempotencyKey): ApiResponse;

    public function patch(string $url, array $data, string $idempotencyKey): ApiResponse;

    public function get(string $url, string $idempotencyKey, ?string $query = null): ApiResponse;

    public function delete(string $url, string $idempotencyKey, ?string $query = null): ApiResponse;
}
