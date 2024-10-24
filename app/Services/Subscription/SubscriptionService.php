<?php

namespace App\Services\Subscription;

use Carbon\Carbon;
use App\Models\Student;
use App\Helpers\ApiResponse;
use App\Models\Subscription;
use App\Jobs\ExpireSubscriptionJob;
use Illuminate\Support\Facades\Log;
use App\DTOs\Subscription\SubscriptionDTO;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionService
{
    // Subscribe logic for students using DTO
    public function subscribe()
    {
        $student = Student::where('account_id', auth()->id())->first();

        if (!$student) {
            return ['errors' => ['Subscrie' => ['User Not Authenticated']], 'statusCode' => Response::HTTP_UNAUTHORIZED];
        }

        Log::info('Authenticated student ID: ' . $student->id);

        // Automatically set trial start and end times
        $now = Carbon::now();
        $trialEnd = $now->copy()->addMinutes(3); // 3-minute trial period

        $subscriptionDTO = new SubscriptionDTO([
            'student_id' => $student->id,
            'trial_start_at' => $now,
            'trial_end_at' => $trialEnd,
            'status' => 'active', // Default status to 'active'
        ]);

        $subscription = Subscription::create($subscriptionDTO->toArray());

        // Dispatch the job to mark the subscription as expired after 3 minutes
        ExpireSubscriptionJob::dispatch($subscription)->delay($trialEnd);

        return ['data' => ['subscription' => $subscription]];
    }

    // Check subscription status
    public function checkSubscription()
    {
        $student = Student::where('account_id', auth()->id())->first();

        if (!$student) {
            return ['errors' => ['Subscription' => ['User Not Authenticated']], 'statusCode' => Response::HTTP_UNAUTHORIZED];
        }

        // Get the latest subscription for the student
        $subscription = Subscription::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscription) {
            return ['errors' => ['Subscrie' => ['No subscription found']], 'statusCode' => Response::HTTP_NOT_FOUND];
        }

        // Check if the trial has expired
        $now = Carbon::now();

        if ($now->greaterThan($subscription->trial_end_at) || $subscription->status === 'expired') {
            return ['message' => 'Trial has expired', 'statusCode' => Response::HTTP_UNAUTHORIZED];
        }

        // Return the active subscription
        return ['message' => 'Trial is still active', 'data' => ['subscription' => $subscription], 'statusCode' => Response::HTTP_OK];
    }


    public function getAllActiveSubscriptions()
    {
        $activeSubscriptions = Subscription::with('student.user:id,name')
            ->where('status', 'active')
            ->get()->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'student_id' => $subscription->student_id,
                    'name' => $subscription->student->user->name,
                    'trial_start_at' => $subscription->trial_start_at,
                    'trial_end_at' => $subscription->trial_end_at,
                    'status' => $subscription->status,
                ];
            });
        return ['data' => ['activeSubscriptions' => $activeSubscriptions]];
    }
}
