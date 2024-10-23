<?php

namespace App\Http\Controllers\Subscription;

use App\Services\Subscription\SubscriptionService;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

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
        $response = $this->subscriptionService->subscribe();
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    // Check subscription status for a student by ID
    public function checkMySubscription()
    {
        $response = $this->subscriptionService->checkSubscription();
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function getAllActiveSubscriptions()
    {
        $response = $this->subscriptionService->getAllActiveSubscriptions();
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}
