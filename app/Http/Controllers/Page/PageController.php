<?php

namespace App\Http\Controllers\Page;

use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
use App\DTOs\Page\UpdatePageDTO;
use App\Services\Page\PageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\PageRequest;
use App\Http\Requests\Page\CreatePageRequest;
use App\Http\Requests\Page\UpdatePageRequest;
use App\Models\Page;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function create(CreatePageRequest $request)
    {
        $finalResponse = $this->pageService->createPage($request);

        return ApiResponse::success(data:$finalResponse->toArray(),message:'Page created successfully!', statusCode:Response::HTTP_CREATED);
        
    }

    public function update(UpdatePageRequest $request, $pageId)
    {
        $updatedPage = $this->pageService->updatePage($pageId, $request->all());
        if (!$updatedPage) {
            return ApiResponse::error(message: 'Page not found!', statusCode: Response::HTTP_NOT_FOUND);
        }
        return ApiResponse::success(data: $updatedPage->toArray(), message: 'Page updated successfully!',
        statusCode: Response::HTTP_OK
        );
    }

    public function getPageBySlug(string $slug)
    {
        $finalResponse = $this->pageService->getPageBySlug($slug);
        return ApiResponse::success(data:$finalResponse->toArray(), message:'Page retrieved successfully!', statusCode:Response::HTTP_OK);

    }

    public function getPageById($pageId)
    {
        $finalResponse = $this->pageService->getPageById($pageId);
        return ApiResponse::success(data:$finalResponse->toArray(), message:'Page retrieved successfully!', statusCode:Response::HTTP_OK);

    }

    public function getPages()
    {

      try 
      {
        $finalResponse = $this->pageService->getPages();

        if ($finalResponse->isEmpty()) 
        {
          return ApiResponse::error(message: 'Pages not found!', statusCode: Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(data: $finalResponse->toArray(), message: 'Pages retrieved successfully!', statusCode: Response::HTTP_OK);
      }catch(Exception $e) 
        {
          return ApiResponse::error(
              message: 'Unable to fetch pages',
              exception: $e,
              statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
          );
        }   
    }
    
    public function destroy($pageId)
    {
      try
      {
        $page = Page::find($pageId);
        
        if (!$page)
        {
          return ApiResponse::error(message: 'No page found with the provided ID',statusCode: Response::HTTP_NOT_FOUND);
        }
        $this->pageService->deletePage($pageId);
        return ApiResponse::success(message:'Page deleted successfully!', statusCode:Response::HTTP_OK);
      }catch(Exception $e)
      {
        return ApiResponse::error(
          message: 'Failed to delete the page',
          exception: $e,
          statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
      );
      } 
    }
    public function restore($pageId)
    {
        $restoredPage = $this->pageService->restorePage($pageId);

        if (!$restoredPage) {
            return ApiResponse::error(
                message: 'Page not found!',
                statusCode: Response::HTTP_NOT_FOUND
            );
        }
        return ApiResponse::success(
            message: 'Page restored successfully!',
            statusCode: Response::HTTP_OK
        );
    }

}