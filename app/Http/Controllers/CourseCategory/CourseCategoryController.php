<?php

namespace App\Http\Controllers\CourseCategory;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CourseCategory\CourseCategoryService;
use App\Http\Requests\CourseCategory\CreateCourseCategoryRequest;
use App\Http\Requests\CourseCategory\UpdateCourseCategoryRequest;


class CourseCategoryController extends Controller
{
    protected $courseCategoryService;

    public function __construct(CourseCategoryService $courseCategoryService)
    {
        $this->courseCategoryService = $courseCategoryService;
    }

    public function index()
    {

        $response = $this->courseCategoryService->index();
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function show($id)
    {
        $response = $this->courseCategoryService->show($id);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function store(CreateCourseCategoryRequest $request)
    {
        $response = $this->courseCategoryService->store($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function update(UpdateCourseCategoryRequest $request, $id)
    {
        $response = $this->courseCategoryService->update($request, $id);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function destroy($id)
    {
        $response = $this->courseCategoryService->destroy($id);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function getCourseWithCourseCategories($id)
    {
        $response = $this->courseCategoryService->getCourseWithCourseCategories($id);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}
