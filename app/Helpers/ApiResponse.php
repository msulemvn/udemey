<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\ErrorLog;
use App\DTOs\ErrorLogs\ErrorLogsDTO;

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
    ): JsonResponse {
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
    public static function error(mixed $request, mixed $exception,  int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        if ($request && $exception) {
            $dto = new ErrorLogsDTO([
                'request' => $request,
                'exception' => $exception,
                'function' => debug_backtrace()[1]['function'],
            ]);
            ErrorLog::create($dto->toArray());
            Log::info('error_log_dto', $dto->toArray());
        }

        if (debug_backtrace()[1]['function'] == 'render') {
            $response['errors']['server error'] = ['Something went wrong'];
        }

        return response()->json($response, $statusCode);
    }
}
