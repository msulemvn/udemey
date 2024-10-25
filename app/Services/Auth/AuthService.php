<?php

namespace App\Services\Auth;

use App\DTOs\Auth\AuthDTO;
use App\Helpers\ApiResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    /**
     * Summary of login
     * @param mixed $request
     * @return mixed
     */
    public function login($request)
    {
        try {
            // Retrieve the validated input data...
            $token = Auth::attempt((new AuthDTO($request))->toArray());
            $user = Auth::user();
            /** @var \App\Models\User|null $user */
            $roleName = $user?->getRoleNames()[0];
            if ($roleName) {
                $response['data']['role'] = $roleName;
                $role = Role::findByName($roleName);
                $permissions = $role->permissions()->pluck('name')->toArray();
                $response['data']['permissions'] = $permissions;
                $response['data']['2fa'] =  ($user->google2fa_secret) ? true : false;
            }
            if ($token) {
                $response['message'] = 'Successfully logged in.';
                $response['data']['access_token'] = $token;
                ApiResponse::activity(request: $request, description: 'User has successfully logged in', showable: true);
            } else {
                $response['errors'] =  ['access_token' => ['Login failed, Please make sure email and password are correct.']];
                $response['statusCode'] = Response::HTTP_UNAUTHORIZED;
            }
            return $response;
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}
