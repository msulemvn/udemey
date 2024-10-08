<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class ErrorLogsDTO extends BaseDTO
{
    public $request_log_id;
    public $line_number;
    public $function;
    public $file;
    public $exception_message;
    public $trace;
    public $ip;

    public function __construct($errorLogsInstance)
    {
        $this->request_log_id = $errorLogsInstance['request_log_id'];
        $this->line_number = $errorLogsInstance['line_number'];
        $this->function = $errorLogsInstance['function'];
        $this->file = $errorLogsInstance['file'];
        $this->exception_message = $errorLogsInstance['exception_message'];
        $this->trace = $errorLogsInstance['trace'];
        $this->ip = $errorLogsInstance['ip'];
    }
}
