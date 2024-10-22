<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class UserDTO extends BaseDTO
{
    public $name;
    public $email;
    public $password;

    public function __construct($request)
    {
        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->password = $request['password'];
    }
}
