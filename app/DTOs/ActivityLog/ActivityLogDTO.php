<?php

namespace App\DTOs\ActivityLog;

use App\DTOs\BaseDTO;

class ActivityLogDTO extends BaseDTO
{
    public $request_log_id;
    public $description;
    public $showable;

    public function __construct($data)
    {
        $this->request_log_id = $data['request']->request_log_id;
        $this->description = $data['description'];
        $this->showable = $data['showable'];
    }
}
