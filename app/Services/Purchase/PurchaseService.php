<?php

namespace App\Services\Purchase;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\Enrollment;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PurchaseService
{
    /************************************ Get all purchases   ************************************/

    public function index()
    {

        // Get all purchases with related users and courses
        $purchases = Purchase::with(['user', 'course'])
            ->orderBy('purchase_date', 'desc')
            ->get();
        if ($purchases->isEmpty()) {
            return [
                'errors' => ['purchases' => ['No purchases found in the system']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['message' => 'Purchased courses retrieved successfully', 'data' => $purchases->toArray()];
    }

    /************************************ course purchase   ************************************/

    public function checkout($request)
    {

        $courses = $request->input('courses');

        if (empty($courses) || !is_array($courses)) {
            return [
                'errors' => ['courses' => ['No courses selected for purchase.']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }


        try {
            DB::beginTransaction();
            foreach ($courses as $courseData) {
                $courseId = $courseData['id']; // Extract course ID from the data
                $course = Course::find($courseId); // Fetch the course details


                if (!$course) {
                    return [
                        'errors' => ['course' => ['Course ID ' . $courseId . ' not found.']],
                        'statusCode' => Response::HTTP_NOT_FOUND
                    ];
                }

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


            return ['message' => 'Purchased courses retrieved successfully'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
}
