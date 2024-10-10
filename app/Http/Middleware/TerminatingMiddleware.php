<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \App\Models\HttpRequest;
use \App\DTOs\HttpRequest\HttpRequestDTO;


class TerminatingMiddleware
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
        $dto = new HttpRequestDTO([
            'session_id' => session()->getId(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'payload' => $request->toArray(),
            'headers' => json_encode($request->header()),
        ]);

        // Log the DTO
        $httpRequest = HttpRequest::create($dto->toArray());
        $request['request_log_id'] = $httpRequest->id;

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate($request, $response): void
    {
        HttpRequest::find($request['request_log_id'])->update(
            [
                'user_id' => $request->user()->id ?? null,
                'response' =>  json_encode($response->getOriginalContent()),
                'status_code' => $response->getStatusCode(),
            ]
        );

        // \Log::info('request_log_id', [$request['request_log_id']]);
        unset($request['request_log_id']);
    }
}
