<?php

namespace App\Http\Controllers;

use App\DTOs\MenuItemDTO;
use App\Http\Requests\MenuItemRequest;
use App\Http\Requests\StoreMenuItemRequest;
use App\Models\MenuItem;
use App\Services\MenuItemService;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    protected MenuItemService $menuItemService;

    /**
     * MenuItemController constructor.
     *
     * @param MenuItemService $menuItemService
     */
    public function __construct(MenuItemService $menuItemService)
    {
        $this->menuItemService = $menuItemService;
    }

    /**
     * Store a new menu item.
     *
     * @param StoreMenuItemRequest $request
     * @return JsonResponse
     */
    public function create(MenuItemRequest $request): JsonResponse
    {
        $menuItemDTO = new MenuItemDTO(
            $request->input('name'),
            $request->input('page_id'),
            $request->input('article_category_id'),
            $request->input('position', 0)
        );

        $menuItem = $this->menuItemService->createMenuItem($menuItemDTO);

        return response()->json(['menu_item' => $menuItem], 201);
    }

    /**
     * Update an existing menu item.
     *
     * @param StoreMenuItemRequest $request
     * @param MenuItem $menuItem
     * @return JsonResponse
     */
    public function update(MenuItemRequest $request, MenuItem $menuItem): JsonResponse
    {
        $menuItemDTO = new MenuItemDTO(
            $request->input('name'),
            $request->input('page_id'),
            $request->input('article_category_id'),
            $request->input('position', 0)
        );

        $updatedMenuItem = $this->menuItemService->updateMenuItem($menuItem, $menuItemDTO);

        return response()->json(['menu_item' => $updatedMenuItem]);
    }
}
