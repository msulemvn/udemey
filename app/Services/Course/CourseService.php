<?php

namespace App\Services\Course;

use App\Models\Course;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Course\CourseDTO;
use App\DTOs\Course\CourseUpdateDTO;
use App\Interfaces\CourseServiceInterface;
use Symfony\Component\HttpFoundation\Response;


class CourseService implements CourseServiceInterface
{
    /************************************ Display a listing of the courses ************************************/

    public function index()
    {
        try {
            $courses = Course::all();


            foreach ($courses as $course) {
                $course->short_description = json_decode($course->short_description);
            }

            return ApiResponse::success(message: 'All courses', data: $courses->toArray());
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to show courses', exception: $th);
        }
    }

    /************************************ Store a newly created course ************************************/

    public function store($request)
    {
        try {
            // Generate a unique slug from title
            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;
            $request['user_id'] = auth()->id();

            if (isset($request['short_description'])) {
                $request['short_description'] = json_encode($request['short_description']);
            }


            $courseDTO = new CourseDTO($request);
            $course = Course::create($courseDTO->toArray());

            return ApiResponse::success(message: 'You have successfully created the course', data: $course->toArray(), statusCode: Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to create course', request: $request, exception: $th);
        }
    }

    /************************************ specified course  ************************************/

    public function show($slug)
    {
        try {
            $course = Course::where('slug', $slug)->first();

            if (!$course) {
                return ApiResponse::error(message: 'Course not found', statusCode: Response::HTTP_NOT_FOUND);
            }

            $course->short_description = json_decode($course->short_description);

            return ApiResponse::success(message: 'Course fetched successfully', data: $course->toArray());
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to show course', exception: $th, statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    /************************************ Update the specified course ************************************/

    public function update($request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $slug = $this->generateUniqueSlug($request['title']);

            $courseUpdateDTO = new CourseUpdateDTO($request->all(), $slug);
            $course->update($courseUpdateDTO->toArray());

            return ApiResponse::success(message: 'Course updated successfully', data: $course->fresh()->toArray(), statusCode: Response::HTTP_OK);
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to update course', request: $request, exception: $th, statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /************************************ Remove the specified course ************************************/

    public function destroy($id)
    {
        try {

            $course = Course::findOrFail($id);
            $course->delete(); // This will perform soft delete if the model uses SoftDeletes

            return ApiResponse::success(message: 'Deleted the course successfully');
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to delete course', exception: $th);
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
