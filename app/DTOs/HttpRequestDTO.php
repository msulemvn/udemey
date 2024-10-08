<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class HttpRequestDTO extends BaseDTO
{
    public $session_id;
    public $ip;
    public $method;
    public $url;
    public $payload;
    public $headers;

    public function __construct($quizInstanceData)
    {
        $this->session_id = $quizInstanceData['session_id'];
        $this->ip = $quizInstanceData['ip'];
        $this->method = $quizInstanceData['method'];
        $this->url = $quizInstanceData['url'];
        $this->payload = $quizInstanceData['payload'];
        $this->headers = $quizInstanceData['headers'];
    }
}
