<?php

namespace App\Http\Controllers\CourseCategory;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;
use App\Services\CourseCategory\CourseCategoryService;
use App\Http\Requests\CourseCategory\CourseCategoryCreateRequest;
use App\Http\Requests\CourseCategory\CourseCategoryUpdateRequest;

class CourseCategoryController extends Controller
{
    protected $courseCategoryService;

    public function __construct(CourseCategoryService $courseCategoryService)
    {
        $this->courseCategoryService = $courseCategoryService;
    }

    /************************************ Get all course categories ************************************/

    public function index()
    {
        return $this->courseCategoryService->index();
    }

    /************************************ Get a specific course category ************************************/

    public function show($id)
    {
        return $this->courseCategoryService->show($id);
    }

    /************************************ Create a new course category ************************************/

    public function store(CourseCategoryCreateRequest $request)
    {
        return $this->courseCategoryService->store($request);
    }

    /************************************ Update a course category ************************************/

    public function update(CourseCategoryUpdateRequest $request, $id)
    {
        return $this->courseCategoryService->update($request, $id);
    }

    /************************************ Delete a course category ************************************/

    public function destroy($id)
    {
        return $this->courseCategoryService->destroy($id);
    }

    public function getCoursewithCourseCategories($id)
    {
        return $this->courseCategoryService->getCoursewithCourseCategories($id);
    }
}
