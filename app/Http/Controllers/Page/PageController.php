<?php

namespace App\Http\Controllers\Page;

use App\Models\Page;
use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
use App\Services\Page\PageService;
use App\Http\Controllers\Controller;
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

    return ApiResponse::success(data: $finalResponse->toArray(), message: 'Page created successfully!', statusCode: Response::HTTP_CREATED);
  }

  public function update(UpdatePageRequest $request, $pageId)
  {
    $updatedPage = $this->pageService->updatePage($pageId, $request->all());
    if (!$updatedPage) {
      return ApiResponse::failure(message: 'Page not found!');
    }
    return ApiResponse::success(
      data: $updatedPage->toArray(),
      message: 'Page updated successfully!',
    );
  }

  public function getPageBySlug(string $slug)
  {
    $response  = $this->pageService->getPageBySlug($slug);
    return $response['success'] ?
      ApiResponse::success(
        data: $response['data']->toArray() ?? null,
        message: 'Page retrieved successfully!',
      ) :
      ApiResponse::failure(
        message: 'No page found with the given slug!',
        errors: ['error' => ['No page found with slug: ' . $slug]],
      );
  }

  public function getPageById($pageId)
  {
    $response = $this->pageService->getPageById($pageId);
    return $response['success'] ?
      ApiResponse::success(
        data: $response['data']->toArray() ?? null,
        message: 'Page retrieved successfully!',
      ) :
      ApiResponse::failure(
        message: 'No page found with the given id!',
        errors: ['error' => ['No page found with id: ' . $pageId]],
      );
  }

  public function getPages($request)
  {

    try {
      $finalResponse = $this->pageService->getPages();

      if ($finalResponse->isEmpty()) {
        return ApiResponse::failure(message: 'Pages not found!');
      }

      return ApiResponse::success(data: $finalResponse->toArray(), message: 'Pages retrieved successfully!', statusCode: Response::HTTP_OK);
    } catch (\Exception $e) {
      return ApiResponse::error(
        exception: $e,
        request: $request
      );
    }
  }

  public function destroy($request)
  {
    try {
      $page = Page::find($request);

      if (!$page) {
        return ApiResponse::failure(message: 'No page found with the provided ID');
      }
      $this->pageService->deletePage($request);
      return ApiResponse::success(message: 'Page deleted successfully!');
    } catch (\Exception $e) {
      return ApiResponse::error(
        exception: $e,
        request: $request,
      );
    }
  }
  public function restore($pageId)
  {
    $restoredPage = $this->pageService->restorePage($pageId);

    if (!$restoredPage) {
      return ApiResponse::failure();
    }
    return ApiResponse::success(
      message: 'Page restored successfully!',
    );
  }
}
