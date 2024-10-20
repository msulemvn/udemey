<?php

namespace App\Services\User;

use App\DTOs\User\UserDTO;
use App\DTOs\Student\StudentDTO;
use App\Models\Student;
use App\Models\User;

class RegisterService
{
    public function registerUser(array $data): User
    {
        try {
            $userDTO = new UserDTO($data);

            $user = User::create($userDTO->toArray());
            $studentDTO = new StudentDTO([
                'account_id' => $user->id,
            ]);

            $student = Student::create($studentDTO->toArray());
            $user->assignRole('Student'); // Assign Student role

            return $user;
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
