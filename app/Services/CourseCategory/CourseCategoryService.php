<?php

namespace App\Services\CourseCategory;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\CourseCategory;
use App\DTOs\CourseCategory\CourseCategoryDTO;
use Symfony\Component\HttpFoundation\Response;

class CourseCategoryService
{
    /************************************ Get all course Categories  ************************************/

    public function index()
    {
        $courseCategories = CourseCategory::all();
        if (!$courseCategories) {
            return [
                'errors' => ['course Categories' => ['No categories found']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['message' => 'All course categories retrieved successfully', 'data' => $courseCategories->toArray()];
    }

    /************************************ Get course Category By Id  ************************************/

    public function show($id)
    {
        $courseCategory = CourseCategory::find($id);
        if (!$courseCategory) {
            return [
                'errors' => ['course Categories' => ['No course category found with the given ID']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['message' => 'Course category retrieved successfully', 'data' => $courseCategory->toArray()];
    }
    /************************************ create course Category   ************************************/

    public function store($request)
    {
        try {
            $courseCategoryDTO = new CourseCategoryDTO($request);
            $courseCategory = CourseCategory::create($courseCategoryDTO->toArray());
            return ['message' => 'course categories created successfully', 'data' => $courseCategory->toArray(), 'statusCode' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
    /************************************ Update course Category   ************************************/

    public function update($request, $id)
    {
        try {
            $courseCategory = CourseCategory::find($id);
            if (!$courseCategory) {
                return [
                    'errors' => ['course Category' => ['No course category found with the given ID']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }

            $courseCategoryDTO = new CourseCategoryDTO($request);

            $courseCategory->update($courseCategoryDTO->toArray());
            return ['message' => 'course categories created successfully', 'data' => $courseCategory->toArray()];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
    /************************************ Delete course Category   ************************************/

    public function destroy($request)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($request);
            $courseCategory->delete();
            return ['message' => 'Course category deleted successfully'];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
    /************************************ Delete courses   ************************************/

    public function getCourseWithCourseCategories($id)
    {
        $courseCategory = CourseCategory::with('course')->find($id);
        if (!$courseCategory) {
            return [
                'errors' => ['course Category' => ['No course category found with the given ID']],
                'statusCode' => Response::HTTP_NOT_FOUND

            ];
        }
        $courses = $courseCategory->course;
        return ['message' => 'Course retrieved successfully', 'data' => $courses->toArray()];
    }
}
