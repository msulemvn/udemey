<?php

namespace App\DTOsSubscription;

use App\DTOs\BaseDTO;

class SubscriptionDTO extends BaseDTO
{
    public $student_id;
    public $trial_start_at;
    public $trial_end_at;
    public $status;

    public function __construct($data)
    {
        $this->student_id = $data['student_id'] ?? null;  // student_id fetched inside the service
        $this->trial_start_at = $data['trial_start_at'] ?? null;
        $this->trial_end_at = $data['trial_end_at'] ?? null;
        $this->status = $data['status'] ?? 'active'; // Default status to 'active'
    }
}
