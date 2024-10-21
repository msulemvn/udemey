<?php

namespace App\Services\Course;

use App\Models\Course;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Course\CourseDTO;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;


class CourseService
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
                'body' => $courses->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function store($request)
    {
        try {
            // Handle the thumbnail upload
            $imagePath = null;

            if ($request->hasFile('thumbnail')) {
                // Store the image in the public disk and get the storage path
                $file = $request->file('thumbnail');
                $timestamp = now()->timestamp;
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $imagePath = 'course_image/' . $newFileName;
                Storage::disk('public')->put($imagePath, file_get_contents($file->getRealPath()));

                // Generate the public URL for the uploaded image using Storage::url or asset
                $imagePath = Storage::url($imagePath);
            }

            $slug = $this->generateUniqueSlug($request->get('title'));

            // Prepare the validated data and add additional fields
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
                'body' => $course->toArray(),
            ];
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }



    /************************************ specified course  ************************************/

    public function show($slug)
    {
        try {
            $course = Course::where('slug', $slug)->first();

            if (!$course) {
                return ApiResponse::success(
                    message: 'Course not found',
                    errors: ['course' => ['The course with the given slug was not found.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }

            $course->short_description = json_decode($course->short_description);
            return [
                'message' => 'Course fetched successfully',
                'body' => $course->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function update($request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            // Handle the thumbnail upload and deletion of the old one
            if ($request->hasFile('thumbnail')) {

                // Delete the old thumbnail if it exists
                if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                    Storage::disk('public')->delete($course->thumbnail);
                }

                // Store the new thumbnail and generate a public URL
                $file = $request->file('thumbnail');
                $timestamp = now()->timestamp;
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $imagePath = 'course_image/' . $newFileName;
                Storage::disk('public')->put($imagePath, file_get_contents($file->getRealPath()));

                // Update the course thumbnail to the public URL
                $course->thumbnail = Storage::url($imagePath);
            }

            // Generate a unique slug based on the title
            $slug = $this->generateUniqueSlug($request->get('title'));

            // Prepare the validated data and add additional fields
            $dtoData = $request->validated();
            $dtoData['slug'] = $slug;
            $dtoData['user_id'] = auth()->id();

            // Preserve the existing thumbnail if no new file is uploaded
            if (!$request->hasFile('thumbnail')) {
                $dtoData['thumbnail'] = $course->thumbnail;
            }

            // Encode the short description to JSON format if it exists
            if (isset($dtoData['short_description'])) {
                $dtoData['short_description'] = json_encode($dtoData['short_description']);
            }

            // Use a Data Transfer Object (DTO) to handle the course update
            $courseUpdateDTO = new CourseDTO($dtoData);
            $course->update($courseUpdateDTO->toArray());

            // Return success message with the updated course details
            return [
                'message' => 'Course updated successfully',
                'body' => $course->toArray(),
            ];
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
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
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }



    public function getArticlewithCourse($slug)
    {
        try {
            $course = Course::with('articles')
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                return ApiResponse::success(
                    message: 'Course not found',
                    errors: ['course' => ['The course with the provided ID was not found.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }

            // Check if the course has articles
            if ($course->articles->isEmpty()) {
                return ApiResponse::success(
                    message: 'No articles found for this course',
                    errors: ['articles' => ['This course does not have any articles associated with it.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return [
                'message' => 'Articles retrieved successfully',
                'body' => $course->articles->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
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
