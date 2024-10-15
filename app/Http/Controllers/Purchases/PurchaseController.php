<?php

namespace App\Http\Controllers\Purchases;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;
use App\Services\Purchase\PurchaseService;



class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    public function index()
    {

        $purchases = $this->purchaseService->index();
        return ApiResponse::success(message: 'Purchased courses retrieved successfully', data: $purchases->toarray());
    }
    public function checkout()
    {
        $checkoutResponse = $this->purchaseService->checkout();
        return ApiResponse::success(
            message: $checkoutResponse['message'],
            statusCode: $checkoutResponse['statusCode']
        );
    }
}
