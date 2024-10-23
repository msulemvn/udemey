<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Student;
use App\DTOs\User\UserDTO;
use App\Helpers\ApiResponse;
use App\DTOs\Student\StudentDTO;

class RegisterService
{
    public function registerUser($request)
    {
        try {
            $userDTO = new UserDTO($request);

            $user = User::create($userDTO->toArray());
            $studentDTO = new StudentDTO([
                'account_id' => $user->id,
            ]);

            $student = Student::create($studentDTO->toArray());
            $user->assignRole(roles: 'Student'); // Assign Student role

            return ['message' => 'You are successfully registered', 'data' => $user->toArray()];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}
