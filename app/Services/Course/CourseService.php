<?php

namespace App\Services\Course;

use App\Models\Course;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Course\CourseDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


class CourseService
{
    /************************************ Get all course  ************************************/

    public function index()
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            $course->short_description = json_decode($course->short_description);
            if (!empty($course->thumbnail)) {
                if (filter_var($course->thumbnail, FILTER_VALIDATE_URL)) {
                    $course->thumbnail = $course->thumbnail;
                } else {
                    $course->thumbnail = asset('storage/' . $course->thumbnail);
                }
            } else {
                $course->thumbnail = null;
            }
        }

        return [
            'message' => 'All courses retrieved successfully',
            'data' => $courses->toArray()
        ];
    }

    /************************************ specified course  ************************************/

    public function getCourseBySlug($slug)
    {
        $course = Course::where('slug', $slug)->first();

        if (!$course) {
            return [
                'errors' => ['course' => ['The course with the given slug is not found.']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        if (!empty($course->thumbnail)) {
            if (filter_var($course->thumbnail, FILTER_VALIDATE_URL)) {
                $course->thumbnail = $course->thumbnail;
            } else {
                $course->thumbnail = asset('storage/' . $course->thumbnail);
            }
        } else {
            $course->thumbnail = null;
        }

        $course->short_description = json_decode($course->short_description);
        return ['message' => 'Course fetched successfully', 'data' => $course->toArray()];
    }

    /************************************ Create course  ************************************/

    public function store($request)
    {
        try {
            $courseDTO = new CourseDTO($request);
            $course = Course::create($courseDTO->toArray());

            return ['message' => 'Course created successfully', 'data' => $course->toArray(), 'statusCode' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /************************************ Update course  ************************************/

    public function update($request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $currentThumbnail = $course->thumbnail;

            $courseUpdateDTO = new CourseDTO($request, $currentThumbnail);

            if ($request->hasFile('thumbnail') && $currentThumbnail) {
                Storage::disk('public')->delete($currentThumbnail);
            }

            $course->update($courseUpdateDTO->toArray());


            return [
                'message' => 'Course updated successfully',
                'data' => $course->toArray(),
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }


    /************************************ Remove the specified course ************************************/

    public function destroy($request)
    {
        try {

            $course = Course::findOrFail($request);
            if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->delete();
            return ApiResponse::success(
                message: 'Deleted the course successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function getArticleWithCourse($slug)
    {
        try {
            $course = Course::with('articles')
                ->where('slug', $slug)
                ->first();
            if (!$course) {
                return [
                    'errors' => ['course' => ['The course with the provided slug was not found']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }

            if ($course->articles->isEmpty()) {
                return [
                    'errors' => ['articles' => ['This course does not have any articles associated with it.']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }


            return [
                'message' => 'Articles retrieved successfully',
                'data' => $course->articles->toArray()  // Returning only articles
            ];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $slug, exception: $e);
        }
    }
}
