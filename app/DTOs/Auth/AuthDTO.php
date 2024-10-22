<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class AuthDTO extends BaseDTO
{
    public string $email;
    public string $password;
    public function __construct($request)
    {
        $this->email = $request['email'];
        $this->password = $request['password'];
    }
}
