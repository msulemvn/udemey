<?php

namespace App\Services\CourseCategory;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\CourseCategory;
use PHPUnit\Framework\Constraint\Count;
use App\DTOs\CourseCategory\CourseCategoryDTO;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\CourseCategory\CourseCategoryServiceInterface;

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
            return $courseCategories;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get courseCategories', exception: $th);
        }
    }

    /************************************ Get a specific course category ************************************/

    public function show($id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            return $courseCategory;
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
            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $courseCategoryDTO = new CourseCategoryDTO($request);
            $courseCategory = CourseCategory::create($courseCategoryDTO->toArray());
            return $courseCategory;
        } catch (\Exception $e) {
            return ApiResponse::error(message: 'Failed to create course category', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    /************************************ Update a course category ************************************/

    public function update($request, $id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $courseCategoryDTO = new CourseCategoryDTO($request);

            $courseCategory->update($courseCategoryDTO->toArray());
            return $courseCategory;
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
            return $courseCategory;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get course', exception: $th);
        }
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-');

        $existingSlug = COurseCategory::where('slug', $slug)->first();

        if ($existingSlug) {
            // If slug already exists, append a number to make it unique
            $count = 1;
            while ($existingSlug) {
                $newSlug = $slug . '-' . $count;
                $existingSlug = COurseCategory::where('slug', $newSlug)->first();
                $count++;
            }
            return $newSlug;
        }

        return $slug;
    }
}
