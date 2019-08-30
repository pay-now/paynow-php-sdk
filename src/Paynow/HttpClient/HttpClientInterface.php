<?php

namespace Paynow\HttpClient;

interface HttpClientInterface
{
    public function post($url, $data);

    public function get($url);
}
