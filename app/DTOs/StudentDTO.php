<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class StudentDTO extends BaseDTO
{
    public $account_id;
    public $phone;

    public function __construct($applicationData)
    {
        $this->account_id = $applicationData['account_id'];
        $this->phone = $applicationData['phone'];
    }
}
