<?php

namespace App\Http\Controllers\Course;

use App\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseCreateRequest;
use App\Http\Requests\Course\CourseUpdateRequest;


class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    // Display a listing of the courses
    public function index()
    {
        return $this->courseService->index();
    }

    // Store a newly created course
    public function store(CourseCreateRequest $request)
    {
        return $this->courseService->store($request);
    }


    public function show($id)
    {
        return $this->courseService->show($id);
    }

    // Update the specified course
    public function update(CourseUpdateRequest $request, $id)
    {
        return $this->courseService->update($request, $id);
    }
    // Remove the specified course
    public function destroy($id)
    {
        return $this->courseService->destroy($id);
    }
}
