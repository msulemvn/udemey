<?php

namespace App\Http\Controllers\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /************************************ Retrieve all courses for a specific student ************************************/

    public function index()
    {
        $studentId = auth()->id();

        // Fetch all enrolled courses for the student
        $enrollments = Enrollment::with('course')
            ->where('user_id', $studentId)
            ->get();

        return response()->json([
            'message' => 'Enrolled courses retrieved successfully',
            'data' => $enrollments
        ]);
    }

    /************************************ Retrieve a specific course for the student ************************************/

    public function show($courseId)
    {
        $studentId = auth()->id();

        // Find the specific enrollment
        $enrollment = Enrollment::with('course')
            ->where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data' => $enrollment
        ]);
    }
}
