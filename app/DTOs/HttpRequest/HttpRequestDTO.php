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

    public function __construct($httpData)
    {
        $this->session_id = $httpData['session_id'];
        $this->ip = $httpData['ip'];
        $this->method = $httpData['method'];
        $this->url = $httpData['url'];
        $this->payload = $httpData['payload'];
        $this->headers = $httpData['headers'];
    }
}
