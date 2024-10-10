<?php

namespace App\DTOs\Student;

use App\DTOs\BaseDTO;

class StudentDTO extends BaseDTO
{
    public $account_id;
    public function __construct($applicationData)
    {
        $this->account_id = $applicationData['account_id'];
    }
}
