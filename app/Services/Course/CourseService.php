<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Interfaces\CourseServiceInterface;
use App\Http\Requests\Course\CourseCreateRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class CourseService implements CourseServiceInterface
{
    // Display a listing of the courses
    public function index()
    {
        $courses = Course::all();
        return ApiResponse::success(message: 'All courses', data: $courses, statusCode: Response::HTTP_CREATED);
    }

    // Store a newly created course
    public function store(CourseCreateRequest $request)
    {
        // Validate the incoming request
        $validatedData = $request->validated();

        // Check if a course with the same title already exists
        $existingCourse = Course::where('title', $validatedData['title'])->first();

        if ($existingCourse) {
            // If a course with the same title exists, return an error response
            return response()->json([
                'message' => 'A course with this title already exists.'
            ], 422); // 422 Unprocessable Entity is commonly used for validation errors
        }

        // Generate slug from title
        $slug = Str::slug($validatedData['title'], '-');

        // Ensure the slug is unique
        $slugCount = Course::where('slug', 'LIKE', "{$slug}%")->count(); // Use singular model name
        if ($slugCount > 0) {
            $slug .= '-' . ($slugCount + 1);
        }

        // Create the course
        $course = Course::create([ // Use singular model name
            'title' => $validatedData['title'],
            'slug' => $slug,
            'description' => $validatedData['description'], // Fixed reference
            'price' => $validatedData['price'],
            'discounted_price' => $validatedData['discounted_price'],
            'thumbnail_url' => $validatedData['thumbnail_url'], // Use the correct field for image path 
            'user_id' => auth()->id(), // You can use Auth::id() for the current authenticated user
            'course_categories_id' => $validatedData['course_categories_id'], // Adding the category
        ]);

        return ApiResponse::success(message: 'You have successfully created the course', data: $course, statusCode: Response::HTTP_CREATED);
    }


    public function show($id)
    {
        try {
            $course = Course::findOrFail($id);
            return ApiResponse::success(message: 'Your specified course', data: $course);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(message: 'Course not found', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    // Update the specified course
    public function update(CourseUpdateRequest $request, $id)
    {
        try {
            // Check if a course with the same title already exists
            $existingCourse = Course::where('title', $request['title'])->first();

            if ($existingCourse) {
                // If a course with the same title exists, return an error response
                return response()->json([
                    'message' => 'A course with this title already exists.'
                ], 422); // 422 Unprocessable Entity is commonly used for validation errors
            }
            // Generate slug from title
            $slug = Str::slug($request['title'], '-');

            // Check if the slug is unique, excluding the current course
            $slugCount = Course::where('slug', $slug)->where('id', '!=', $id)->count();

            if ($slugCount > 0) {
                $slug .= '-' . ($slugCount + 1);
            }

            $course = Course::findOrFail($id);
            $course->update([
                'title' => $request['title'],
                'slug' => $slug,
                'description' => $request['description'],
                'price' => $request['price'],
                'discounted_price' => $request['discounted_price'],
                'thumbnail_url' => $request['thumbnail_url'],
                'course_categories_id' => $request['course_categories_id'],

            ]);

            return ApiResponse::success(message: 'You are successfully update course', data: $course);
        } catch (\Exception $e) {
            return ApiResponse::error(message: 'Course not found', statusCode: Response::HTTP_NOT_FOUND);
        }
    }
    // Remove the specified course
    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete(); // Use the delete() method for soft deletion
            return ApiResponse::success(message: 'You are successfully deleted the course');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(message: 'Course not found', statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
