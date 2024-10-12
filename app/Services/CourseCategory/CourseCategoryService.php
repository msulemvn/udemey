<?php

namespace App\Services\CourseCategory;

use CourseCategoryDTO;
use App\Helpers\ApiResponse;
use App\Models\CourseCategory;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CourseCategory\CourseCategoryServiceInterface;
use Symfony\Component\HttpFoundation\Response;



class CourseCategoryService implements CourseCategoryServiceInterface
{
    /************************************ Get all course categories ************************************/

    public function index()
    {
        try {
            $courseCategories = CourseCategory::all();

            if ($courseCategories->isEmpty()) {
                return ApiResponse::error(message: 'No course categories available at the moment', statusCode: Response::HTTP_NOT_FOUND);
            }
            return ApiResponse::success(message: 'All course categories retrieved successfully', data: $courseCategories->toarray());
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get courseCategories', exception: $th);
        }
    }

    /************************************ Get a specific course category ************************************/

    public function show($id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            return ApiResponse::success(message: 'Course category retrieved successfully', data: $courseCategory->toarray());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(message: 'No Course category Found', statusCode: Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get course Categories', exception: $th);
        }
    }

    /************************************ Create a new course category ************************************/

    public function store($request)
    {
        try {

            $courseCategoryDTO = new CourseCategoryDTO($request->validated());
            $courseCategory = CourseCategory::create($courseCategoryDTO->toArray());
            return ApiResponse::success(message: 'All course categories retrieved successfully', data: $courseCategory, statusCode: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return ApiResponse::error(message: 'Failed to create course category', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    /************************************ Update a course category ************************************/

    public function update($request, $id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            $courseCategoryDTO = new CourseCategoryDTO($request->validated());

            // Update course category
            $courseCategory->update($courseCategoryDTO->toArray());
            return ApiResponse::success(message: 'Course category updated successfully', data: $courseCategory, statusCode: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return ApiResponse::error(message: 'Failed to update course category', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    /************************************ Delete a course category ************************************/

    public function destroy($id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            $courseCategory->delete();
            return ApiResponse::success(message: 'Course category deleted successfully', data: $courseCategory, statusCode: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return ApiResponse::error(message: 'Failed to delete course category');
        }
    }

    public function getCoursewithCourseCategories($id)
    {
        try {
            $courseCategory = CourseCategory::with('course')->find($id);
            if (!$courseCategory) {
                return ApiResponse::error(message: 'course not found', statusCode: Response::HTTP_NOT_FOUND);
            }

            // Return the course categories associated with the category
            return ApiResponse::success(message: 'Course retrieved successfully', data: $courseCategory->Course->toarray());
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get course', exception: $th);
        }
    }
}
