<?php

namespace App\DTOs\Manager;

use App\DTOs\BaseDTO;

class ManagerDTO extends BaseDTO
{
    public $account_id;

    public function __construct($applicationData)
    {
        $this->account_id = $applicationData['account_id'];
    }
}