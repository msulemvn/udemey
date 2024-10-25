<?php
namespace App\Http\Controllers\MenuItem;

use App\Helpers\ApiResponse;
use App\Services\MenuItem\MenuItemService;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItem\CreateMenuItemRequest;
use App\Http\Requests\MenuItem\UpdateMenuItemRequest;

class MenuItemController extends Controller
{
    private $menuItemService;

    public function __construct(MenuItemService $menuItemService)
    {
        $this->menuItemService = $menuItemService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateMenuItemRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateMenuItemRequest $request)
    {
        $response = $this->menuItemService->createMenuItem($request);
        return ApiResponse::success(
            data: $response['data']->toArray($response) ?? null, 
            message: $response['message'] ?? null, 
            errors: $response['errors'] ?? [], 
            statusCode: $response['statusCode']
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMenuItemRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMenuItemRequest $request, $id)
    {
        $response = $this->menuItemService->updateMenuItem($request, $id);
        if (isset($response['data'])) {
            $data = $response['data']->toArray($response);
        } else {
            $data = [];
        }
        return ApiResponse::success(
            data: $data, 
            message: $response['message'] ?? null, 
            errors: $response['errors'] ?? [], 
            statusCode: $response['statusCode'] ?? 200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $response = $this->menuItemService->deleteMenuItem($id);
        return ApiResponse::success(
            message: $response['message'] ?? null, 
            errors: $response['errors'] ?? [], 
            statusCode: $response['statusCode'] ?? 200
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $response = $this->menuItemService->getAllMenuItems();
        if (isset($response['data'])) {
            $data = $response['data']->toArray($response);
        } else {
            $data = [];
        }
        return ApiResponse::success(
            data: $data, 
            message: $response['message'] ?? null, 
            errors: $response['errors'] ?? [], 
            statusCode: $response['statusCode'] ?? 200
        );
    }
}
