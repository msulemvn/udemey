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
    /************************************ Retrieve all courses for a specific student ************************************/

    public function index()
    {
        $Response = $this->enrollmentService->index();
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }

    /************************************ Retrieve a specific course for the student ************************************/

    public function show($courseId)
    {
        $Response = $this->enrollmentService->show($courseId);
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }
}
