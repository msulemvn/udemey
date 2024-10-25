<?php

namespace App\DTOs\ErrorLog;

use App\DTOs\BaseDTO;

class ErrorLogDTO extends BaseDTO
{
    public $request_log_id;
    public $line_number;
    public $function;
    public $file;
    public $exception_message;
    public $trace;
    public $ip;

    public function __construct($data)
    {
        $this->request_log_id = $data['request']->request_log_id;
        $this->line_number = $data['exception']->getLine();
        $this->function = $data['function'];
        $this->file = $data['exception']->getFile();
        $this->exception_message = $data['exception']->getMessage();
        $this->trace = $data['exception']->getTraceAsString();
        $this->ip = $data['request']->ip();
    }
}
