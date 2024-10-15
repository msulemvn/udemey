<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\Course;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Cart\CartService;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /************************************ Add course to cart ************************************/
    public function addToCart(Request $request, $slug)
    {
        $cartItem = $this->cartService->addToCart($request, $slug);

        return ApiResponse::success(message: 'Course added to cart', data: $cartItem->toArray());
    }

    /************************************ Remove course from cart ************************************/
    public function removeFromCart(Request $request, $id)
    {
        return $this->cartService->removeFromCart($request, $id);
    }

    /************************************ View all cart items ************************************/
    public function viewCart()
    {
        $cartItems = $this->cartService->viewCart();

        return ApiResponse::success(message: 'cart', data: $cartItems->toArray());
    }
}
