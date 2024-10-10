<?php

namespace App\Services\Course;

use App\Models\Course;
use App\Models\ErrorLog;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Course\CourseDTO;
use App\DTOs\Course\CourseShowDTO;
use App\DTOs\Course\CourseIndexDTO;
use Illuminate\Support\Facades\Log;
use App\DTOs\Course\CourseDeleteDTO;
use App\DTOs\Course\CourseUpdateDTO;
use App\DTOs\ErrorLogs\ErrorLogsDTO;
use App\Interfaces\CourseServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Course\CourseCreateRequest;
use App\Http\Requests\Course\CourseUpdateRequest;

class CourseService implements CourseServiceInterface
{
    /************************************ Display a listing of the courses ************************************/

    public function index()
    {
        // Get all courses
        $courses = Course::all();

        // Check if there are no courses
        if ($courses->isEmpty()) {
            return ApiResponse::error(message: 'No courses available at the moment', statusCode: Response::HTTP_NOT_FOUND);
        }

        // Map each course to a CourseIndexDTO
        $coursesDTO = $courses->map(function ($course) {
            return (new CourseIndexDTO($course))->toArray();
        });

        // Return success response with DTO data
        return ApiResponse::success(message: 'You have successfully created the course', data: $coursesDTO, statusCode: Response::HTTP_CREATED);
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
                return ApiResponse::error(message: 'A course with this title already exists.', statusCode: Response::HTTP_NOT_FOUND);
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
            return ApiResponse::success(message: 'You have successfully created the course', data: $course, statusCode: Response::HTTP_CREATED);
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
            return ApiResponse::error(message: 'Failed to create course', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    /************************************ specified course  ************************************/

    public function show($id)
    {
        try {
            // Fetch the course by ID
            $course = Course::findOrFail($id);

            // Create a CourseShowDTO instance with the fetched course
            $courseDTO = new CourseShowDTO($course);

            // Return a successful response with the DTO data

            return ApiResponse::success(data: $courseDTO->toArray(), statusCode: Response::HTTP_CREATED);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where course is not found
            return ApiResponse::error(message: 'Course not found', statusCode: Response::HTTP_NOT_FOUND);
        }
    }


    /************************************ Update the specified course ************************************/

    public function update(CourseUpdateRequest $request, $id)
    {
        try {
            // Check if a course with the same title already exists
            $existingCourse = Course::where('title', $request['title'])->first();

            // If a course with the same title exists, return an error response
            if ($existingCourse) {
                return ApiResponse::error(message: 'A course with this title already exists.', statusCode: Response::HTTP_NOT_FOUND);
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
            return ApiResponse::success(message: 'Course updated successfully', data: $course->toArray(), statusCode: Response::HTTP_CREATED);
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
            return ApiResponse::error(message: 'Failed to update course', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    /************************************ Remove the specified course ************************************/

    public function destroy(CourseDeleteDTO $courseDeleteDTO)
    {
        try {
            // Find the course by ID or fail with an exception
            $course = Course::findOrFail($courseDeleteDTO->id);

            // Perform soft deletion
            $course->delete(); // This will perform soft delete if the model uses SoftDeletes

            return ApiResponse::success(message: 'You have successfully deleted the course');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the course is not found
            return ApiResponse::error(message: 'Course not found', statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
