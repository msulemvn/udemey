<?php

namespace App\Services\Subscription;

use Carbon\Carbon;
use App\Models\Student;
use App\Helpers\ApiResponse;
use App\Models\Subscription;
use App\Jobs\ExpireSubscriptionJob;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class SubscriptionService
{
    // Subscribe logic for students
    public function subscribe()
    {
        $student = Student::where('account_id', auth()->id())->first(); // Get the authenticated user's ID

        Log::info('Authenticated student ID: ' . $student->id); // Log the student ID for debugging


        if (!$student->id) {
            return ApiResponse::error('User not authenticated', statusCode: Response::HTTP_UNAUTHORIZED);
        }

        $student = Student::find($student->id);

        if (!$student) {
            return ApiResponse::error('Student not found', statusCode: Response::HTTP_NOT_FOUND);
        }

        $now = Carbon::now();
        $trialEnd = $now->copy()->addMinutes(3); // 3-minute trial

        // Create subscription for the logged-in student
        $subscription = Subscription::create([
            'student_id' => $student->id,
            'trial_start_at' => $now,
            'trial_end_at' => $trialEnd,
            'status' => 'active', // Set initial status as active
        ]);

        // Dispatch the job to mark the subscription as expired after 3 minutes
        ExpireSubscriptionJob::dispatch($subscription)->delay($trialEnd);

        return ApiResponse::success(data: ['subscription' => $subscription]);
    }

    // Check subscription status
    public function checkSubscription()
    {
        // Get the authenticated student's account ID
        $student = Student::where('account_id', auth()->id())->first();

        // Check if the student exists
        if (!$student) {
            return ApiResponse::error('Student not found', statusCode: Response::HTTP_NOT_FOUND);
        }

        // Fetch the latest subscription for the student
        $subscription = Subscription::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if a subscription exists
        if (!$subscription) {
            return ApiResponse::error('No subscription found', statusCode: Response::HTTP_NOT_FOUND);
        }

        $now = Carbon::now();

        // Check if the trial has expired or the status is 'expired'
        if ($now->greaterThan($subscription->trial_end_at) || $subscription->status === 'expired') {
            return ApiResponse::error('Trial has expired', statusCode: Response::HTTP_UNAUTHORIZED);
        }

        return ApiResponse::success(message: 'Trial is still active', data: ['subscription' => $subscription]);
    }

    public function getAllActiveSubscriptions()
    {
        try {
            // Fetch all active subscriptions with status 'active'
            $activeSubscriptions = Subscription::where('status', 'active')->get();

            return ApiResponse::success(data: ['activeSubscriptions' => $activeSubscriptions]);
        } catch (Exception $e) {
            // Log the exception if needed and return an error response
            return ApiResponse::error('An error occurred while fetching active subscriptions', statusCode: 500);
        }
    }
}
