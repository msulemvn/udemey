<?php

namespace App\Helpers;

use Illuminate\Console\Concerns\HasParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;

class ApiResponse
{
    use HasRoles, HasPermissions;
    /**
     * Returns a successful JSON response with optional data, a customizable success message, user data, and an authorisation token.
     *
     * @param mixed $data
     * @param string $message
     * @param string|null $token
     * @return JsonResponse
     */
    public static function success(
        $data = [],
        string $message = 'success',
        int $statusCode = 200,
    ): JsonResponse {
        $response['message'] = $message;

        if ($data != null && !is_array($data)) {
            $data = $data->toArray();
        }

        if (isset($data['access_token'])) {
            $access_token = $data['access_token'];
            $data = array_diff_key($data, array('access_token' => true));
        }
        if ($data) {
            $response['data'] = $data;
        }

        if (debug_backtrace()[1]['function'] == 'login') {
            $user = Auth::user();
            $role = $user->getRoleNames()[0];
            if ($role)
                $response['data']['role'] = $role;
            $roleName = $response['data']['role'];
            $role = Role::findByName($roleName);
            if ($role) {
                $permissions = $role->permissions()->pluck('name')->toArray();
                $response['data']['permissions'] = $permissions;
            }
        }

        if (isset($access_token)) {
            $response['data']['access_token'] = $access_token;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Returns a JSON response with an error message and a specified status code.
     *
     * @param string $message The error message to be included in the response. Defaults to 'An error occurred'.
     * @param int $statusCode The HTTP status code for the response. Defaults to 400.
     * @return JsonResponse
     */
    public static function error(string $error = 'An error occurred', int $statusCode = 400)
    {
        return response()->json([
            'error' => $error
        ], $statusCode);
    }

    public static function validationError(
        $message = 'Validation errors',
        $errors = [],
        int $statusCode = 422,
    ): JsonResponse {
        $response['message'] = $message;

        if ($errors != null && !is_array($errors)) {
            $errors = $errors->toArray();
        }

        return response()->json($response, $statusCode);
    }
}
