<?php

namespace App\Services\CourseCategory;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\CourseCategory;
use App\DTOs\CourseCategory\CourseCategoryDTO;
use Symfony\Component\HttpFoundation\Response;

class CourseCategoryService
{
    public function index()
    {
        try {
            $courseCategories = CourseCategory::all();
            if ($courseCategories->isEmpty()) {
                return ApiResponse::failure(
                    message: 'No course categories available at the moment',
                    errors: ['courseCategories' => ['No categories found']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $courseCategories;
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function show($id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            if (!$courseCategory) {
                return ApiResponse::failure(
                    message: 'Course category not found',
                    errors: ['courseCategory' => ['No course category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $courseCategory;
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function store($request)
    {
        try {
            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $courseCategoryDTO = new CourseCategoryDTO($request);
            $courseCategory = CourseCategory::create($courseCategoryDTO->toArray());
            return $courseCategory;
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function update($request, $id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            if (!$courseCategory) {
                return ApiResponse::failure(
                    message: 'Course category not found',
                    errors: ['courseCategory' => ['No course category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $courseCategoryDTO = new CourseCategoryDTO($request);

            $courseCategory->update($courseCategoryDTO->toArray());
            return $courseCategory;
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function destroy($id)
    {
        try {
            $courseCategory = CourseCategory::findOrFail($id);
            $courseCategory->delete();
            return ApiResponse::success(message: 'Course category deleted successfully', data: $courseCategory, statusCode: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function getCoursewithCourseCategories($id)
    {
        try {
            $courseCategory = CourseCategory::with('course')->find($id);
            if (!$courseCategory) {
                return ApiResponse::failure(
                    message: 'Course not found',
                    errors: ['courseCategory' => ['No course category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $courseCategory;
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
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
