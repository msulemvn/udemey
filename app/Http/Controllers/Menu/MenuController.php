<?php
namespace App\Http\Controllers\Menu;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Menu\MenuService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;

class MenuController extends Controller
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $response = $this->menuService->getAllMenus();
        return ApiResponse::success(
            data: $response['data']->toArray($response) ?? [], 
            message: $response['message'] ?? null, 
            errors: $response['errors'] ?? [], 
            statusCode: $response['statusCode'] ?? 200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateMenuRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateMenuRequest $request)
    {
        $response = $this->menuService->createMenu($request);
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
     * @param CreateMenuRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMenuRequest $request, $id)
    {
        $response = $this->menuService->updateMenu($request, $id);
        
        return ApiResponse::success(
            data: $response['data']->toArray($response) ?? null, 
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
        $response = $this->menuService->deleteMenu($id);
        return ApiResponse::success(
            message: $response['message'] ?? null, 
            errors: $response['errors'] ?? [], 
            statusCode: $response['statusCode'] ?? 200
        );
    }
}