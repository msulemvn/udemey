<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\ErrorLog;
use App\DTOs\ErrorLog\ErrorLogDTO;
use App\Models\ActivityLog;
use App\DTOs\ActivityLog\ActivityLogDTO;



class ApiResponse
{
    /**
     * Returns a successful JSON response with optional data, a customizable success message, user data, and an authorisation token.
     *
     * @param mixed $data
     * @param string $message
     * @param string|null $token
     * @return JsonResponse
     */
    public static function success(
        string $message = null,
        array $data = [],
        array $errors = [],
        int $statusCode = Response::HTTP_OK,
    ) {
        $response['message'] = $message ?? Response::$statusTexts[$statusCode];
        if ($data) {
            $response['data'] = $data;
        }

        if ($errors) {
            $response['errors'] = $errors;
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
    public static function error(mixed $request, mixed $exception,  int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        try {
            $dto = new ErrorLogDTO([
                'request' => $request,
                'exception' => $exception,
                'function' => debug_backtrace()[1]['function'],
            ]);
            ErrorLog::create($dto->toArray());
            Log::info('error_log_dto', $dto->toArray());
            $response['errors']['server error'] = ['Something went wrong'];
            return ApiResponse::success(errors: $response['errors'], statusCode: $statusCode);
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public static function activity(
        $request,
        string $description,
        bool $showable = false,
    ) {
        try {
            ActivityLog::create((new ActivityLogDTO(data: ['request' => $request, 'description' => $description, 'showable' => $showable]))->toArray());
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}
