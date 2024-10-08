<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Display a listing of the courses
    public function index()
    {
        $courses = Courses::all();
        return response()->json($courses);
    }

    // Store a newly created course
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discounted_price' => 'nullable|numeric',
            'thumbnail_url' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // Generate slug from title
        $validatedData = Str::slug($validatedData['title'], '-');

        // Ensure the slug is unique
        $slugCount = Courses::where('slug', 'LIKE', "{$validatedData}%")->count();
        if ($slugCount > 0) {
            $validatedData .= '-' . ($slugCount + 1);
        }

        $course = Courses::create($validatedData);
        return ApiResponse::success(message: 'You are successfully create course',data:$course);
    }

    // Display the specified course
    public function show($id)
    {
        $course = Courses::findOrFail($id);
        return response()->json($course);
    }

    // Update the specified course
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'discounted_price' => 'nullable|numeric',
            'thumbnail_url' => 'nullable|string',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        // Generate slug from title
        $validatedData = Str::slug($validatedData['title'], '-');

        // Ensure the slug is unique
        $slugCount = Courses::where('slug', 'LIKE', "{$validatedData}%")->count();
        if ($slugCount > 0) {
            $validatedData .= '-' . ($slugCount + 1);
        }

        $course = Courses::findOrFail($id);
        $course->update($validatedData);
        return ApiResponse::success(message: 'You are successfully update course',data:$course);
    }

    // Remove the specified course
    public function destroy($id)
    {
        $course = Courses::findOrFail($id);
        $course->SoftDeletes();
        return ApiResponse::success(message: 'You are successfully delete',data:$course);
        // return response()->json(null, 204);
    }
}
