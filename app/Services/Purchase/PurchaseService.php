<?php

namespace App\Services\Purchase;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\Enrollment;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;

use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\Purchase\PurchaseServiceInterface;

class PurchaseService implements PurchaseServiceInterface
{
    public function index()
    {
        try {

            // Get all purchases with related users and courses
            $purchases = Purchase::with(['user', 'course'])
                ->orderBy('purchase_date', 'desc')
                ->get();
            if ($purchases->isEmpty()) {
                return ApiResponse::error(
                    message: 'No purchases found',
                    errors: ['purchases' => ['No purchases found in the system']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return [
                'message' => 'Purchased courses retrieved successfully',
                'body' => $purchases->toArray(),
            ];
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve purchases',
                errors: ['purchases' => ['Unable to retrieve purchases. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function checkout()
    {
        // Receive course data from the frontend
        $courses = $request->input('courses'); // Expected to be an array of course objects


        if (empty($courses) || !is_array($courses)) {
            return ApiResponse::error(
                message: 'No courses provided',
                errors: ['courses' => ['No courses selected for purchase.']],
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }

        DB::beginTransaction();

        try {
            foreach ($courses as $courseData) {
                $courseId = $courseData['id']; // Extract course ID from the data
                $course = Course::find($courseId); // Fetch the course details

                if (!$course) {
                    // If the course does not exist, skip or handle the error
                    return ApiResponse::error(
                        message: 'Course not found',
                        errors: ['course' => ['Course ID ' . $courseId . ' not found.']],
                        statusCode: Response::HTTP_NOT_FOUND
                    );
                }

                // Create purchase record
                Purchase::create([
                    'course_id' => $course->id,
                    'user_id' => auth()->id(),
                    'amount' => $course->discounted_price ?? $course->price,
                ]);

                // Create enrollment record
                Enrollment::create([
                    'course_id' => $course->id,
                    'user_id' => auth()->id(),
                    'purchase_date' => now(),
                ]);
            }

            DB::commit();

            return [
                'message' => 'Purchase and enrollment completed successfully',
                'statusCode' => Response::HTTP_CREATED,
            ];
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback the transaction in case of any errors

            return ApiResponse::error(
                message: 'Purchase failed',
                errors: ['checkout' => ['Failed to complete the purchase. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
