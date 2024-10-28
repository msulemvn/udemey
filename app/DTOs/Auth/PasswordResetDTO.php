<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class PasswordResetDTO extends BaseDTO
{
    public $email;
    public $password;
    public $password_confirmation;
    public $token;

    public function __construct($request)
    {
        $this->email = $request['email'];
        $this->password = $request['password'];
        $this->password_confirmation = $request['password_confirmation'];
        $this->token = $request['token'];
    }
}
