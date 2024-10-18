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
        $Response = $this->courseService->index();
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }

    public function store(CreateCourseRequest $request)
    {
        $Response = $this->courseService->store($request);
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }


    public function show($slug)
    {
        $Response = $this->courseService->show($slug);
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $Response = $this->courseService->update($request, $id);
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }

    public function destroy($id)
    {
        $Response = $this->courseService->destroy($id);
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }
    public function getArticlewithCourse($id)
    {
        $Response = $this->courseService->getArticlewithCourse($id);
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }
}
