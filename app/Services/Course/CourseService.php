<?php

namespace App\Services\Course;

use App\Models\Course;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Course\CourseDTO;
use App\DTOs\Course\CourseUpdateDTO;
use App\Interfaces\Course\CourseServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CourseService implements CourseServiceInterface
{
    public function index()
    {
        try {
            $courses = Course::all();


            foreach ($courses as $course) {
                $course->short_description = json_decode($course->short_description);
            }
            return $courses;
        } catch (\Throwable $th) {
            $errors = ['courses' => ['Failed to retrieve the courses. Please try again later.']];
            return ApiResponse::error(message: 'Failed to show courses', errors: $errors, exception: $th);
        }
    }

    public function store($request)
    {
        try {
            // Generate a unique slug from title
            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;
            $request['user_id'] = auth()->id();

            if (isset($request['short_description'])) {
                $request['short_description'] = json_encode($request['short_description']);
            }


            $courseDTO = new CourseDTO($request);
            $course = Course::create($courseDTO->toArray());
            return $course;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to create course', errors: ['course' => ['Unable to create course at this time.']], exception: $th);
        }
    }

    public function show($slug)
    {
        try {
            $course = Course::where('slug', $slug)->first();

            if (!$course) {
                return ApiResponse::error(
                    message: 'Course not found',
                    errors: ['course' => ['The course with the given slug was not found.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }

            $course->short_description = json_decode($course->short_description);
            return $course;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve course',
                errors: ['course' => ['An error occurred while retrieving the course. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update($request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $slug = $this->generateUniqueSlug($request['title']);

            $courseUpdateDTO = new CourseUpdateDTO($request->all(), $slug);
            $course->update($courseUpdateDTO->toArray());
            return $course;
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                message: 'Course not found',
                errors: ['course' => ['The course with the provided ID was not found.']],
                statusCode: Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to update course',
                errors: ['course' => ['An error occurred while updating the course. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy($id)
    {
        try {

            $course = Course::findOrFail($id);
            $course->delete(); // This will perform soft delete if the model uses SoftDeletes

            return ApiResponse::success(message: 'Deleted the course successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                message: 'Course not found',
                errors: ['course' => ['The course with the provided ID was not found.']],
                statusCode: Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to delete course',
                errors: ['course' => ['An error occurred while trying to delete the course. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getArticlewithCourse($id)
    {
        try {
            $course = Course::with('articles')->find($id);

            if (!$course) {
                return ApiResponse::error(
                    message: 'Course not found',
                    errors: ['course' => ['The course with the provided ID was not found.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }

            // Check if the course has articles
            if ($course->articles->isEmpty()) {
                return ApiResponse::error(
                    message: 'No articles found for this course',
                    errors: ['articles' => ['This course does not have any articles associated with it.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $course;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve articles for the course',
                errors: ['articles' => ['An error occurred while fetching the articles. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-');

        $existingSlug = Course::where('slug', $slug)->first();

        if ($existingSlug) {
            // If slug already exists, append a number to make it unique
            $count = 1;
            while ($existingSlug) {
                $newSlug = $slug . '-' . $count;
                $existingSlug = Course::where('slug', $newSlug)->first();
                $count++;
            }
            return $newSlug;
        }

        return $slug;
    }
}
