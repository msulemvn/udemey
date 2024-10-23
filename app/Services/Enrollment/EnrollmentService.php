<?php

namespace App\Services\Enrollment;

use App\Models\Enrollment;
use Symfony\Component\HttpFoundation\Response;


class EnrollmentService
{
    /************************************ Get all Enrollments   ************************************/

    public function index()
    {

        $studentId = auth()->id();
        $courses = Enrollment::with('course')
            ->where('user_id', $studentId)
            ->get()
            ->pluck('course');
        if ($courses->isEmpty()) {
            return [
                'message' => 'No courses found for this user.',
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['data' => $courses->toArray()];
    }

    /************************************ Get all Enrollments By Slug   ************************************/

    public function getCourseBySlug($slug)
    {
        $studentId = auth()->id();

        // Find the specific enrollment
        $enrollment = Enrollment::with('course')
            ->where('user_id', $studentId)
            ->whereHas('course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->first();
        if (!$enrollment || !$enrollment->course) {
            return [
                'errors' => ['course' => ['The course with the given slug is not found for this student.']],
                'statusCode' => Response::HTTP_NOT_FOUND

            ];
        }

        $course = $enrollment->course;
        $course->short_description = json_decode($course->short_description);
        return ['data' => $course->toArray()];
    }
}
