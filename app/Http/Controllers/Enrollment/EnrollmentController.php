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
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }

    public function show($slug)
    {
        $response = $this->enrollmentService->show($slug);
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }
}
