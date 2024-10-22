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

    public function __construct($request)
    {
        $this->session_id = session()->getId();
        $this->ip = $request->ip();
        $this->method = $request->method();
        $this->url = $request->fullUrl();
        $this->payload = json_encode($request->toArray());
        $this->headers = json_encode($request->header());
    }
}
