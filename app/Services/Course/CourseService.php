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
use Illuminate\Support\Facades\Storage;


class CourseService implements CourseServiceInterface
{
    public function index()
    {
        try {
            $courses = Course::all();


            foreach ($courses as $course) {
                $course->short_description = json_decode($course->short_description);
            }
            return [
                'message' => 'All courses',
                'body' => [
                'message' => 'All courses',
                'body' => $courses->toArray(),
            ]->toArray(),
            ];
        } catch (\Throwable $th) {
            $errors = ['courses' => ['Failed to retrieve the courses. Please try again later.']];
            return ApiResponse::error(message: 'Failed to show courses', errors: $errors, exception: $th);
        }
    }

    public function store($request)
    {
        try {
            // Handle the thumbnail upload
            $imagePath = null;

            if ($request->hasFile('thumbnail')) {
                $imagePath = $request->file('thumbnail')->store('course_image', 'public');
            }


            $slug = $this->generateUniqueSlug($request->get('title'));


            $dtoData = $request->validated();
            $dtoData['slug'] = $slug;
            $dtoData['user_id'] = auth()->id();
            $dtoData['thumbnail'] = $imagePath;

            if (isset($dtoData['short_description'])) {
                $dtoData['short_description'] = json_encode($dtoData['short_description']);
            }

            // Use a Data Transfer Object (DTO) to handle the course creation
            $courseDTO = new CourseDTO($dtoData);
            $course = Course::create($courseDTO->toArray());


            return [
                'message' => 'Course created successfully',
                'statusCode' => Response::HTTP_CREATED,
                'body' => [
                'message' => 'Course created successfully',
                'statusCode' => Response::HTTP_CREATED,
                'body' => $course->toArray(),
            ]->toArray(),
            ];
        } catch (\Throwable $th) {
            // Return error response if something goes wrong
            // Return error response if something goes wrong
            return ApiResponse::error(
                
                message: 'Failed to create course',
               
                errors: ['course' => ['Unable to create course at this time.']],
               
                exception: $th
            
            );
        }
    }



    /************************************ specified course  ************************************/

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
            return [
                'message' => 'Course fetched successfully',
                'body' => [
                'message' => 'Course fetched successfully',
                'body' => $course->toArray(),
            ]->toArray(),
            ];
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

            if ($request->hasFile('thumbnail')) {

                if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                    Storage::disk('public')->delete($course->thumbnail);
                }

                $imagePath = $request->file('thumbnail')->store('course_image', 'public');
                $course->thumbnail = $imagePath;
            }

            $slug = $this->generateUniqueSlug($request->get('title'));


            $dtoData = $request->validated();
            $dtoData['slug'] = $slug;
            $dtoData['user_id'] = auth()->id();

            if (!$request->hasFile('thumbnail')) {
                $dtoData['thumbnail'] = $course->thumbnail;
            }
            if (isset($dtoData['short_description'])) {
                $dtoData['short_description'] = json_encode($dtoData['short_description']);
            }

            // Update course using DTO
            $courseUpdateDTO = new CourseDTO($dtoData);
            $course->update($courseUpdateDTO->toArray());

            return [
                'message' => 'Course updated successfully',
                'body' => $course->toArray(),
            ];
        } catch (\Throwable $th) {
            // Return error response if something goes wrong
            return ApiResponse::error(
                message: 'Failed to update course',
                errors: ['course' => ['An error occurred while updating the course. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }



    /************************************ Remove the specified course ************************************/

    public function destroy($id)
    {
        try {

            $course = Course::findOrFail($id);
            if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $course->delete();

            return ApiResponse::success(
                message: 'Deleted the course successfully'
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
            $course = Course::with('articles')
                ->where('slug', $slug)
                ->first();

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
            return [
                'message' => 'Articles retrieved successfully',
                'body' => [
                'message' => 'Articles retrieved successfully',
                'body' => $course->articles->toArray(),
            ]->articles->toArray(),
            ];
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
