<?php

namespace App\Services\User;

use App\DTOs\User\UserDTO;
use App\DTOs\Student\StudentDTO;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RegisterService
{
    public function registerUser($data)
    {
        try {
            $userDTO = new UserDTO([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user = User::create($userDTO->toArray());
            $studentDTO = new StudentDTO([
                'account_id' => $user->id,
            ]);

            $student = Student::create($studentDTO->toArray());
            $user->assignRole('Student');

            $token = Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
            $responseData['name'] = $data['name'];
            $responseData['email'] = $data['email'];

            $user = Auth::user();
            /** @var \App\User|null $user */
            $roleName = $user->getRoleNames()[0];
            if ($roleName) {
                $responseData['role'] = $roleName;
                $role = Role::findByName($roleName);
                $permissions = $role->permissions()->pluck('name')->toArray();
                $responseData['permissions'] = $permissions;
            }
            $responseData['2fa'] = ($user->google2fa_secret) ? true : false;
            $responseData['access_token'] = $token;


            return ['success' => true, 'data' => $responseData];
        } catch (\Throwable $th) {
            return ['success' => false, 'request' => $data, 'exception' => $th, 'errors' => ['registration' => ['User registraion failed.']]];
        }
    }
}
