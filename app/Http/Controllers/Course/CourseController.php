<?php

namespace App\Http\Controllers\Course;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Course\CourseService;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;


class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $response = $this->courseService->index();
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }

    public function store(CreateCourseRequest $request)
    {
        $response = $this->courseService->store($request);
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }


    public function show($slug)
    {
        $response = $this->courseService->show($slug);
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $response = $this->courseService->update($request, $id);
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }

    public function destroy($id)
    {

        $response = $this->courseService->destroy($id);
        return ApiResponse::success(
            message: $response['message']
        );
    }
    public function getArticleWithCourse($slug)
    {
        $response = $this->courseService->getArticleWithCourse($slug);
        return ApiResponse::success(
            message: $response['message'],
            data: $response['body']
        );
    }
}
