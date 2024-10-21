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

        $courseCategories = $this->courseCategoryService->index();
        return ApiResponse::success(message: 'All course categories retrieved successfully', data: $courseCategories->toArray());
    }

    public function show($id)
    {
        $courseCategory = $this->courseCategoryService->show($id);
        return ApiResponse::success(message: 'Course category retrieved successfully', data: $courseCategory->toArray());
    }

    public function store(CreateCourseCategoryRequest $request)
    {
        $courseCategory = $this->courseCategoryService->store($request);
        return ApiResponse::success(message: 'course categories created successfully', data: $courseCategory->toArray(), statusCode: Response::HTTP_CREATED);
    }

    public function update(UpdateCourseCategoryRequest $request, $id)
    {
        $courseCategory = $this->courseCategoryService->update($request, $id);
        return ApiResponse::success(message: 'Course category updated successfully', data: $courseCategory->toArray());
    }

    public function destroy($id)
    {
        return $this->courseCategoryService->destroy($id);
    }

    public function getCourseWithCourseCategories($id)
    {
        $courseCategory = $this->courseCategoryService->getCourseWithCourseCategories($id);
        return ApiResponse::success(message: 'Course retrieved successfully', data: $courseCategory->Course->toArray());
    }
}
