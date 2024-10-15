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

    /************************************ Display a listing of the Purchases ************************************/

    public function index()
    {

        $Response = $this->purchaseService->index();
        return ApiResponse::success(
            message: $Response['message'],
            data: $Response['body']
        );
    }

    /************************************ Checkout Purchase ************************************/

    public function checkout()
    {
        $checkoutResponse = $this->purchaseService->checkout();
        return ApiResponse::success(
            message: $checkoutResponse['message'],
            statusCode: $checkoutResponse['statusCode']
        );
    }
}
