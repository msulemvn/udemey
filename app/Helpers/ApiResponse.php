<?php

namespace App\Helpers;

use Throwable;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\DTOs\ErrorLogs\ErrorLogsDTO;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Symfony\Component\HttpFoundation\Response;

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
        int $statusCode = Response::HTTP_OK,
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
     * @param int $statusCode The HTTP status code for the response.
     * @return JsonResponse
     */
    public static function error(string $message = 'An error occurred', Request $request = null, Throwable $exception = null,  int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        //Response::HTTP_OK
        if ($request && $exception) {
            $dto = new ErrorLogsDTO([
                'request_log_id' => $request['request_log_id'],
                'line_number' => $exception->getLine(),
                'function' => debug_backtrace()[1]['function'],
                'file' => $exception->getFile(),
                'exception_message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'ip' => $request->ip(),
            ]);
            ErrorLog::create($dto->toArray());
            Log::info('error_log_dto', $dto->toArray());
        }

        return response()->json([
            'message' => $message
        ], $statusCode);
    }

    public static function validationError(
        $message = 'Validation errors',
        $errors = [],
        int $statusCode = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        $response['message'] = $message;

        if ($errors != null && !is_array($errors)) {
            $errors = $errors->toArray();
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
