<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Course;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Interfaces\Cart\CartServiceInterface;


class CartService implements CartServiceInterface
{
    /************************************ Add course to cart ************************************/
    public function addToCart($request, $slug)
    {
        try {
            // Find the course by slug
            $course = Course::where('slug', $slug)->first();

            // Add the course to the cart using the course ID
            $cartItem = Cart::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'course_id' => $course->id,
                ],
                [
                    'quantity' => 1,
                ]
            );
            return [
                'message' => 'Add course to cart successfully',
                'body' => $cartItem->toArray(),
            ];
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to add course to cart',
                errors: ['cart' => ['Unable to add course to cart. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /************************************ Remove course from cart ************************************/
    public function removeFromCart($request, $id)
    {
        try {
            $cartItem = Cart::where('user_id', auth()->id())
                ->where('course_id', $id)
                ->first();

            $cartItem->delete();

            return [
                'message' => 'Course removed from cart',
            ];

            return ApiResponse::success(message: 'Course removed from cart');
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to remove course from cart',
                errors: ['cart' => ['Unable to remove course from cart. Please try again.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /************************************ View all cart items ************************************/
    public function viewCart()
    {
        try {
            $cartItems = Cart::with('course')
                ->where('user_id', auth()->id())
                ->get();
            if ($cartItems->isEmpty()) {
                return ApiResponse::error(
                    message: 'No items in the cart',
                    errors: ['cart' => ['Your cart is currently empty.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }

            return [
                'message' => 'cart',
                'body' => $cartItems->toArray(),
            ];
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
