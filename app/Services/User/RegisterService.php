<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Student;
use App\DTOs\User\UserDTO;
use App\Helpers\ApiResponse;
use App\DTOs\Student\StudentDTO;

class RegisterService
{
    public function registerUser(array $request): User
    {
        try {
            $userDTO = new UserDTO($request);

            $user = User::create($userDTO->toArray());
            $studentDTO = new StudentDTO([
                'account_id' => $user->id,
            ]);

            $student = Student::create($studentDTO->toArray());
            $user->assignRole('Student'); // Assign Student role

            return $user;
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}
