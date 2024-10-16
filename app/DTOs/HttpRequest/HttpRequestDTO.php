<?php

namespace App\DTOs\HttpRequest;

use App\DTOs\BaseDTO;

class HttpRequestDTO extends BaseDTO
{
    public $session_id;
    public $ip;
    public $method;
    public $url;
    public $payload;
    public $headers;

    public function __construct($httpRequestData)
    {
        $this->session_id = $httpRequestData['session_id'];
        $this->ip = $httpRequestData['ip'];
        $this->method = $httpRequestData['method'];
        $this->url = $httpRequestData['url'];
        $this->payload = $httpRequestData['payload'];
        $this->headers = $httpRequestData['headers'];
    }
}
