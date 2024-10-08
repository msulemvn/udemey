<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Http\Requests\Course\CourseCreateRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Display a listing of the courses
    public function index()
    {
        $courses = Courses::all();
        return response()->json($courses);
    }

    // Store a newly created course
    public function store(CourseCreateRequest $request)
    {
        // Validate the incoming request
        $validatedData = $request->validated();

        // Generate slug from title
        $slug = Str::slug($validatedData['title'], '-');

        // Ensure the slug is unique
        $slugCount = Courses::where('slug', 'LIKE', "{$slug}%")->count(); // Use singular model name
        if ($slugCount > 0) {
            $slug .= '-' . ($slugCount + 1);
        }

        // Create the course
        $course = Courses::create([ // Use singular model name
            'title' => $validatedData['title'],
            'slug' => $slug,
            'description' => $validatedData['description'], // Fixed reference
            'price' => $validatedData['price'],
            'discounted_price' => $validatedData['discounted_price'],
            'thumbnail_url' => $validatedData['thumbnail_url'], // Use the correct field for image path 
            'user_id' => auth()->id(), // You can use Auth::id() for the current authenticated user
        ]);

        return ApiResponse::success(message: 'You have successfully created the course', data: $course, statusCode: 201);
    }


    // Display the specified course
    public function show($id)
    {
        $course = Courses::findOrFail($id);
        return ApiResponse::success(message: 'Your specified course', data: $course);
    }

    // Update the specified course
    public function update(CourseUpdateRequest $request, $id)
    {

        // Generate slug from title
        $slug = Str::slug($request['title'], '-');

        // Check if the slug is unique, excluding the current course
        $slugCount = Courses::where('slug', $slug)->where('id', '!=', $id)->count();

        if ($slugCount > 0) {
            $slug .= '-' . ($slugCount + 1);
        }

        $course = Courses::findOrFail($id);
        $course->update([
            'title' => $request['title'],
            'slug' => $slug,
            'description' => $request['description'],
            'price' => $request['price'],
            'discounted_price' => $request['discounted_price'],
            'thumbnail_url' => $request['thumbnail_url'],
        ]);

        return ApiResponse::success(message: 'You are successfully update course', data: $course);
    }
    // Remove the specified course
    public function destroy($id)
    {
        $course = Courses::findOrFail($id);
        $course->delete(); // Use the delete() method for soft deletion
        return ApiResponse::success(message: 'You are successfully deleted the course');
    }
}
