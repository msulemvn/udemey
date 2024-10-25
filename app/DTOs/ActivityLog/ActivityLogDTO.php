<?php

namespace App\DTOs\ActivityLog;

use App\DTOs\BaseDTO;

class ActivityLogDTO extends BaseDTO
{
    private $request_log_id;
    private $description;
    private $showable;

    public function __construct($data)
    {
        $this->request_log_id = $data['request']->request_log_id;
        $this->request_log_id = $data['description'];
        $this->request_log_id = $data['showable'];
    }
}
