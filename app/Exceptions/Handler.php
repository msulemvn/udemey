<?php

namespace App\Exceptions;

use App\Models\ErrorLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\DTOs\ErrorLogsDTO;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function render($request, Throwable $e)
    {
        $dto = new ErrorLogsDTO([
            'request_log_id' => $request['request_log_id'],
            'line_number' => $e->getLine(),
            'function' => __FUNCTION__,
            'file' => $e->getFile(),
            'exception_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'ip' => $request->ip(),
        ]);
        ErrorLog::create($dto->toArray());
        \Log::info('error_log_dto', $dto->toArray());

        return response()->json(['error' => $e->getMessage()], 500);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
