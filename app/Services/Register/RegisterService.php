<?php

namespace App\Services\Register;

use App\DTOs\User\UserDTO;
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
