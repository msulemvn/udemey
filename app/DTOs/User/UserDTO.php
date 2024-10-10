<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class UserDTO extends BaseDTO
{
    public $name;
    public $email;
    public $password;

    public function __construct($registrationData)
    {
        $this->name = $registrationData['name'];
        $this->email = $registrationData['email'];
        $this->password = $registrationData['password'];
    }
}
