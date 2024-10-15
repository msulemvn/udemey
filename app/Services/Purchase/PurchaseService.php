<?php

namespace App\Services\Purchase;

use App\Models\Cart;
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
            return $purchases;
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
        try {
            $cartItems = Cart::with('course')->where('user_id', auth()->id())->get();

            if ($cartItems->isEmpty()) {
                return ApiResponse::error(
                    message: 'No items in the cart',
                    errors: ['cart' => ['Your cart is empty. Please add items before checkout.']],
                    statusCode: Response::HTTP_BAD_REQUEST
                );
            }

            DB::beginTransaction();

            try {
                foreach ($cartItems as $item) {

                    Purchase::create([
                        'course_id' => $item->course_id,
                        'user_id' => auth()->id(),
                        'amount' => $item->course->discounted_price ?? $item->course->price,
                    ]);

                    Enrollment::create([
                        'course_id' => $item->course_id,
                        'user_id' => auth()->id(),
                        'purchase_date' => now(),
                    ]);

                    $item->delete();
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
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve cart items',
                errors: ['cart' => ['Unable to retrieve cart items. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
