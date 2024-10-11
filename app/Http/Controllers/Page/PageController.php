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
        $updatePageDTO = new PageDTO(
            $request->input('body')
        );

        $finalResponse = $this->pageService->updatePage($pageId, $updatePageDTO);
        return ApiResponse::success(data:$finalResponse->toArray(),message:'Page updated successfully!', statusCode:Response::HTTP_OK);

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
        $finalResponse = $this->pageService->getPages();
        return ApiResponse::success(data:$finalResponse->toArray(), message:'Pages retrieved successfully!', statusCode:Response::HTTP_OK);
    }
    
    public function destroy($pageId)
    {
        $finalResponse = $this->pageService->deletePage($pageId);
        return ApiResponse::success(message:'Page deleted successfully!', statusCode:Response::HTTP_OK);

    }
}