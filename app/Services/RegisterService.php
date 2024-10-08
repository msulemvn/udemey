<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\User;

class RegisterService
{
    public function registerUser($data)
    {
        $dto = new UserDTO([
           'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        User::create($dto->toArray());
    }
}
