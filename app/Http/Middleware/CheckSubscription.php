<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $student = auth()->user();
        $subscription = Subscription::where('student_id', $student->id)->where('status', 'active')->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found'], 403);
        }

        return $next($request);
    }
}
