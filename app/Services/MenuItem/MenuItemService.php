<?php
namespace App\Services\MenuItem;

use App\DTOs\MenuItem\MenuItemDTO;
use App\Repositories\MenuItemRepository;
use App\Http\Resources\MenuItem\MenuItemResource;
use Exception;
use App\Helpers\ApiResponse;
use App\Http\Requests\MenuItem\CreateMenuItemRequest;
use Symfony\Component\HttpFoundation\Response;

class MenuItemService
{
    private $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * Create menu item.
     *
     * @param CreateMenuItemRequest $request
     * @return array
     */
    public function createMenuItem($request)
    {
        try {
            $menuItemDTO = new MenuItemDTO($request->all());
            $menuItem = $this->menuItemRepository->create($menuItemDTO->toArray());
            if(!$menuItem){
                return [
                    'message' => 'Unable to create menu item!',
                    'errors' => ['Menu Item' => ['Unable to create menu item!']],
                    'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY
                ];
            }
            return [
                'data' => new MenuItemResource($menuItem),
                'message' => 'Menu item created successfully!',
                'statusCode' => Response::HTTP_CREATED
            ];
        } catch (Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
        
    }

    /**
     * Update menu item.
     *
     * @param $request
     * @param int $id
     * @return array
     */

    public function updateMenuItem($request, int $id)
    {
        try {
            $menuItem = $this->menuItemRepository->getById($id);
            if (!$menuItem) {
                return [
                    'message' => 'No menu item found!',
                    'errors' => ['Menu Item' => ['No menu item found!']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];            
            }
            
            $menuItemDTO = new MenuItemDTO($request->all());
            
            $data = [];
            
            if ($menuItemDTO->title) {
                $data['title'] = $menuItemDTO->title;
            } else {
                $data['title'] = $menuItem->title; // Keep existing title if not provided
            }
            
            if ($menuItemDTO->order) {
                $data['order'] = $menuItemDTO->order;
            } else {
                $data['order'] = $menuItem->order; // Keep existing order if not provided
            }
            
            if ($menuItemDTO->is_active) {
                $data['is_active'] = $menuItemDTO->is_active;
            } else {
                $data['is_active'] = $menuItem->is_active; // Keep existing is_active if not provided
            }
            
            if ($menuItemDTO->menu_id) {
                $data['menu_id'] = $menuItemDTO->menu_id;
            } else {
                $data['menu_id'] = $menuItem->menu_id; // Keep existing menu_id if not provided
            }
            
            if ($menuItemDTO->page_id) {
                $data['page_id'] = $menuItemDTO->page_id;
            } else {
                $data['page_id'] = $menuItem->page_id; // Keep existing page_id if not provided
            }
            
            if (!empty($data)) {
                $menuItem = $this->menuItemRepository->update($data, $id);
            } else {
                $menuItem = $menuItem; // No changes, return existing menu item
            }
            
            $response = [
                'data' => new MenuItemResource($menuItem),
                'message' => 'Menu item updated successfully!',
                'statusCode' => Response::HTTP_OK
            ];
            
            return $response;
        } catch (Exception $e) {
            report($e);
            return ApiResponse::error(request: $request, exception: $e);
        }
    }   /**
     * Delete menu item.
     *
     * @param int $id
     * @return array
     */
    public function deleteMenuItem(int $id)
    {
        try {
            $menuItem = $this->menuItemRepository->getById($id);
            if (!$menuItem) {
                return [
                    'message' => 'No menu item found!',
                    'errors' => ['Menu Item' => ['No menu item found!']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];            
            }
            $this->menuItemRepository->delete($id);
            return [
                'message' => 'Menu item deleted successfully',
                'statusCode' => Response::HTTP_OK
            ];        
        } catch (Exception $e) {
            return ApiResponse::error(request: request(), exception: $e);
        }
    }

    /**
     * Get all menu items.
     *
     * @param $request
     * @return array
     */
    public function getAllMenuItems()
    {
        try {
            $menuItems = $this->menuItemRepository->getAll();
            if(!$menuItems){
                return [
                    'message' => 'No menu items found!',
                    'errors' => ['Menu Item' => ['No menu items found!']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }
            $response = MenuItemResource::collection($menuItems);
            return [
                'data' => $response,
                'message' => 'Menu items retrieved successfully!',
                'statusCode' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return ApiResponse::error(request: request(), exception: $e);
        }
    }
}