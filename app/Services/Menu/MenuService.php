<?php
namespace App\Services\Menu;

use App\DTOs\Menu\MenuDTO;
use App\Repositories\MenuRepository;
use App\Http\Resources\Menu\MenuResource;
use Exception;
use App\Helpers\ApiResponse;
use App\Http\Requests\Menu\CreateMenuRequest;
use Symfony\Component\HttpFoundation\Response;

class MenuService
{
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Get all menus.
     *
     * @param $request
     * @return array
     */
    public function getAllMenus()
    {
        try {
            $menus = $this->menuRepository->getAll();
            if(!$menus){
                return [
                    'message' => 'No menu found!',
                    'errors' => ['Menu' => ['No menu found!']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }
            $response = MenuResource::collection($menus);
            return [
                'data' => $response,
                'message' => 'Menu retrieved successfully!',
                'statusCode' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return ApiResponse::error(request: request(), exception: $e);
        }
    }

    /**
     * Create menu.
     *
     * @param CreateMenuRequest $request
     * @return array
     */
    public function createMenu($request)
    {
        try {
            $menuDTO = new MenuDTO($request->all());
            $menu = $this->menuRepository->create($menuDTO->toArray());
            if(!$menu){
                return [
                    'message' => 'Unable to create menu!',
                    'errors' => ['Menu' => ['Unable to create menu!']],
                    'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY
                ];
            }
            return [
                'data' => new MenuResource($menu),
                'message' => 'Menu created successfully!',
                'statusCode' => Response::HTTP_CREATED
            ];
        } catch (Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /**
     * Update menu.
     *
     * @param CreateMenuRequest $request
     * @param int $id
     * @return array
     */
    public function updateMenu($request, int $id)
    {
        try {
            $menu = $this->menuRepository->getById($id);
            if (!$menu) {
                return [
                    'message' => 'No menu found!',
                    'errors' => ['Menu' => ['No menu found!']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];            
            }
            $menuDTO = new MenuDTO($request->all());
            $menu = $this->menuRepository->update($menuDTO, $id);
            return [
                'data' => new MenuResource($menu),
                'message' => 'Menu updated successfully!',
                'statusCode' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /**
     * Delete menu.
     *
     * @param int $id
     * @return array
     */
    public function deleteMenu(int $id)
    {
        try {
            $menu = $this->menuRepository->getById($id);
            if (!$menu) {
                return [
                    'message' => 'No menu found!',
                    'errors' => ['Menu' => ['No menu found!']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];            
            }
            $this->menuRepository->delete($id);
            return [
                'message' => 'Menu deleted successfully',
                'statusCode' => Response::HTTP_OK
            ];        
        } catch (Exception $e) {
            return ApiResponse::error(request: request(), exception: $e);
        }
    }
}