<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\User;

class RegisterService
{
    public function registerUser(array $data): User
    {
    $dto = new UserDTO([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    $user = User::create($dto->toArray());
    $user->assignRole('Student'); // Assign Student role

    return $user;
    }
}
