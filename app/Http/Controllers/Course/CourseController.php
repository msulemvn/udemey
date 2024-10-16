<?php

namespace App\Http\Controllers\Course;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Course\CourseService;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use Symfony\Component\HttpFoundation\Response;


class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $courses = $this->courseService->index();
        return ApiResponse::success(message: 'All courses', data: $courses->toArray());
    }

    public function store(CreateCourseRequest $request)
    {
        $course = $this->courseService->store($request);
        return ApiResponse::success(message: 'You have successfully created the course', data: $course->toArray(), statusCode: Response::HTTP_CREATED);
    }


    public function show($slug)
    {
        $course = $this->courseService->show($slug);
        return ApiResponse::success(message: 'Course fetched successfully', data: $course->toArray());
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $course = $this->courseService->update($request, $id);
        return ApiResponse::success(message: 'Course updated successfully', data: $course->fresh()->toArray());
    }

    public function destroy($id)
    {
        return $this->courseService->destroy($id);
    }
    public function getArticlewithCourse($id)
    {
        $course = $this->courseService->getArticlewithCourse($id);
        return ApiResponse::success(message: 'Articles retrieved successfully', data: $course->articles->toArray());
    }
}
