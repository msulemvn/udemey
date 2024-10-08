<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class UserDTO extends BaseDTO
{
    public $name;
    public $email;
    public $password;

    public function __construct($applicationData)
    {
        $this->name = $applicationData['name'];
        $this->email = $applicationData['email'];
        $this->password = $applicationData['password'];
    }
}
