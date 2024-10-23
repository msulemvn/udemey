<?php

namespace App\Http\Controllers\Purchase;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;
use App\Services\Purchase\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index()
    {

        $response = $this->purchaseService->index();
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    public function checkout(Request $request)
    {
        $response = $this->purchaseService->checkout($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }
}
