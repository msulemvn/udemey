<?php

namespace App\Http\Middleware;

use Closure;
use \App\Models\HttpRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use \App\DTOs\HttpRequest\HttpRequestDTO;
use Symfony\Component\HttpFoundation\Response;


class Terminating
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log the DTO
        try {
            $httpRequest = HttpRequest::create((new HttpRequestDTO($request))->toArray());
            $request['request_log_id'] = $httpRequest->id;
            return $next($request);
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate($request, $response): void
    {
        $httpRequest = HttpRequest::find($request['request_log_id']);
        if ($httpRequest) {
            $httpRequest->update(
                [
                    'user_id' => $request->user()->id ?? null,
                    'response' =>  json_encode($response->getOriginalContent()),
                    'status_code' => $response->getStatusCode(),
                ]
            );
        }
        // \Log::info('request_log_id', [$request['request_log_id']]);
        unset($request['request_log_id']);
    }
}
