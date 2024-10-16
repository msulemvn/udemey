<?php

namespace App\Services\Enrollment;

use App\Models\Course;
use App\Models\Enrollment;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\Enrollment\EnrollmentServiceInterface;

class EnrollmentService implements EnrollmentServiceInterface
{
    public function index()
    {
        try {
            $studentId = auth()->id();

            // Fetch all enrolled courses for the student
            $courses = Enrollment::with('course')
                ->where('user_id', $studentId)
                ->get()
                ->pluck('course');
            return [
                'message' => 'Enrolled courses retrieved successfully',
                'body' => $courses->toArray(),
            ];
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve enrolled courses',
                errors: ['enrollment' => ['Unable to retrieve courses. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show($courseId)
    {
        try {
            $studentId = auth()->id();

            // Find the specific enrollment
            $enrollment = Enrollment::with('course')
                ->where('user_id', $studentId)
                ->where('course_id', $courseId)
                ->first();

            if (!$enrollment) {
                return ApiResponse::error(
                    message: 'Course not found',
                    errors: ['course' => ['The requested course is not found.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            $course = $enrollment->course;
            return [
                'message' => 'Course retrieved successfully',
                'body' => $course->toArray(),
            ];
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve course',
                errors: ['course' => ['Unable to retrieve the course. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
