<?php

namespace App\Services;

use App\Models\Course;
use App\DTOs\CourseDTO;
use App\Models\ErrorLog;
use App\DTOs\ErrorLogsDTO;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\CourseUpdateDTO;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CourseServiceInterface;
use App\Http\Requests\Course\CourseCreateRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class CourseService implements CourseServiceInterface
{
    /************************************ Display a listing of the courses ************************************/

    public function index()
    {

        $courses = Course::all();
        // Check if there are no courses
        if ($courses->isEmpty()) {
            // Return a custom response for no courses
            return ApiResponse::error(error: 'No courses available at the moment', statusCode: 200);
        }
        return ApiResponse::success(message: 'All courses retrieved successfully', data: $courses, statusCode: 201);
    }

    /************************************ Store a newly created course ************************************/

    public function store(CourseCreateRequest $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validated();

            // Check if a course with the same title already exists
            $existingCourse = Course::where('title', $validatedData['title'])->first();

            if ($existingCourse) {
                // If a course with the same title exists, return an error response
                return ApiResponse::error(error: 'A course with this title already exists.', statusCode: 422);
            }

            // Generate slug from title
            $slug = Str::slug($validatedData['title'], '-');

            // Ensure the slug is unique
            $slugCount = Course::where('slug', 'LIKE', "{$slug}%")->count(); // Use singular model name
            if ($slugCount > 0) {
                $slug .= '-' . ($slugCount + 1);
            }

            // Prepare the course data using the DTO
            $courseDTO = new CourseDTO([
                'title' => $validatedData['title'],
                'slug' => $slug,
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'discounted_price' => $validatedData['discounted_price'],
                'thumbnail_url' => $validatedData['thumbnail_url'],
                'user_id' => auth()->id(), // Current authenticated user ID
                'course_categories_id' => $validatedData['course_categories_id'],
            ]);

            // Create the course
            $course = Course::create($courseDTO->toArray());

            return ApiResponse::success(message: 'Course created successfully', data: $course, statusCode: 201);
        } catch (\Exception $e) {
            // Log the error in the database using the ErrorLogsDTO
            $dto = new ErrorLogsDTO([
                'request_log_id' => $request->get('request_log_id', null),
                'line_number' => $e->getLine(),
                'function' => __FUNCTION__,
                'file' => $e->getFile(),
                'exception_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            // Create a log entry in the ErrorLog model
            ErrorLog::create($dto->toArray());

            // Also log the error using Laravel's logging system
            Log::error('Error updating course', $dto->toArray());

            // Return a generic error response
            return ApiResponse::error(error: 'Failed to create course', statusCode: 500);
        }
    }

    /************************************ specified course  ************************************/

    public function show($id)
    {
        $course = Course::findOrFail($id);
        if ($course->isEmpty()) {
            // Return a custom response for no courses
            return ApiResponse::error(error: 'Course not found', statusCode: 200);
        }
        return ApiResponse::success(message: 'Course retrieved successfully', data: $course);
    }

    /************************************ Update the specified course ************************************/

    public function update(CourseUpdateRequest $request, $id)
    {
        try {
            // Check if a course with the same title already exists
            $existingCourse = Course::where('title', $request['title'])->first();

            // If a course with the same title exists, return an error response
            if ($existingCourse) {

                return ApiResponse::error(error: 'A course with this title already exists.', statusCode: 200);
            }

            // Generate slug from title
            $slug = Str::slug($request['title'], '-');

            // Check if the slug is unique, excluding the current course
            $slugCount = Course::where('slug', $slug)->where('id', '!=', $id)->count();
            if ($slugCount > 0) {
                $slug .= '-' . ($slugCount + 1);
            }

            $course = Course::findOrFail($id);

            // Prepare the DTO for updating
            $courseUpdateDTO = new CourseUpdateDTO($request->all(), $slug);

            // Update the course using the DTO
            $course->update($courseUpdateDTO->toArray());

            return ApiResponse::success(message: 'Course updated successfully', data: $course);
        } catch (\Exception $e) {
            // Log the error in the database using the ErrorLogsDTO
            $dto = new ErrorLogsDTO([
                'request_log_id' => $request->get('request_log_id', null),
                'line_number' => $e->getLine(),
                'function' => __FUNCTION__,
                'file' => $e->getFile(),
                'exception_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            // Create a log entry in the ErrorLog model
            ErrorLog::create($dto->toArray());

            // Also log the error using Laravel's logging system
            Log::error('Error updating course', $dto->toArray());

            // Return a generic error response
            return ApiResponse::error(error: 'Failed to update course', statusCode: 500);
        }
    }

    /************************************ Remove the specified course ************************************/

    public function destroy($id)
    {
        try {
            // Find the course by ID or fail with an exception
            $course = Course::findOrFail($id);

            // Perform soft deletion
            $course->delete(); // This will perform soft delete if the model uses SoftDeletes

            return ApiResponse::success(message: 'You have successfully deleted the course');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the course is not found
            return ApiResponse::error(error: 'Course not found', statusCode: 404);
        }
    }
}
