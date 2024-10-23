<?php

namespace App\Http\Controllers\Enrollment;

use App\Models\Enrollment;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Enrollment\EnrollmentService;


class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function index()
    {
        $response = $this->enrollmentService->index();
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function getCourseBySlug($slug)
    {
        $response = $this->enrollmentService->getCourseBySlug($slug);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}
