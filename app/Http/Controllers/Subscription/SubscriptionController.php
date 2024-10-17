<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    // Subscribe a student by ID
    public function subscribe()
    {
        return $this->subscriptionService->subscribe();
    }

    // Check subscription status for a student by ID
    public function checkMySubscription()
    {
        $studentId = auth()->id();  // Get the authenticated user's ID

        if (!$studentId) {
            return ApiResponse::error('User not authenticated', statusCode: Response::HTTP_UNAUTHORIZED);
        }

        return $this->subscriptionService->checkSubscription($studentId);
    }

    public function getAllActiveSubscriptions()
    {
        return $this->subscriptionService->getAllActiveSubscriptions();
    }
}