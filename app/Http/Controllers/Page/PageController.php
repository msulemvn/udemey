<?php

namespace App\Http\Controllers\Page;

use Exception;
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
    $response = $this->pageService->createPage($request);

    return $response['success'] ?
    ApiResponse::success(
      message : 'Page created successfully!',
      data : $response['data']->toArray() ?? null,
      statusCode : Response::HTTP_CREATED
    ):
    ApiResponse::error(
      message: 'Unable to create page!',
      errors: ['page' => ['Unable to create page!']],
      statusCode: Response::HTTP_BAD_REQUEST,
    );
  }

  public function update(UpdatePageRequest $request, $id)
  {
    $response = $this->pageService->updatePage($id, $request->validated());

    if (!$response['success']) {
        return ApiResponse::error(
            message: 'Unable to update page!',
            errors: $response['errors'],
            statusCode: Response::HTTP_BAD_REQUEST
        );
    }
    return ApiResponse::success(
        message: 'Page updated successfully!',
        data: $response['data']->toArray(),
        statusCode: Response::HTTP_OK
    );
  }


  public function getPageBySlug(string $slug)
  {
    $response  = $this->pageService->getPageBySlug($slug);

    return $response['success'] ?
      ApiResponse::success(
        data: $response['data']->toArray() ?? null,
        message: 'Page retrieved successfully!',
        statusCode: Response::HTTP_OK
      ) :
      ApiResponse::error(
        message: 'No page found with the given slug!',
        errors: ['page' => ['No page found with slug: ' . $slug]],
        statusCode: Response::HTTP_NOT_FOUND,
      );
  }

  public function getPageById($pageId)
  {
    $response = $this->pageService->getPageById($pageId);
    return $response['success'] ?
    ApiResponse::success(
      data: $response['data']->toArray() ?? null,
      message: 'Page retrieved successfully!',
      statusCode: Response::HTTP_OK
    ) :
    ApiResponse::error(
      message: 'No page found with the given id!',
      errors: ['page' => ['No page found with id: ' . $pageId]],
      statusCode: Response::HTTP_NOT_FOUND,
    );
  }

  public function getPages()
  {
    try {
      $finalResponse = $this->pageService->getPages();
      return ApiResponse::success(
        message: 'Pages retrieved successfully!',
        data: $finalResponse,
        statusCode: Response::HTTP_OK
      );
    } catch (Exception $e) {
      return ApiResponse::error(
        message: 'Unable to fetch pages',
        exception: $e,
        statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
      );
    }
  }

  public function destroy($pageId)
  {
    $response = $this->pageService->deletePage($pageId);

    return $response['success'] ?
    ApiResponse::success(
      message: 'Page deleted successfully!',
      statusCode: Response::HTTP_OK
    ) :
    ApiResponse::error(
      message: 'No page found with the given id!',
      errors: ['page' => ['No page found with id: ' . $pageId]],
      statusCode: Response::HTTP_NOT_FOUND,
    );
  }

  public function restore($pageId)
  {
    $response = $this->pageService->restorePage($pageId);

    return $response['success'] ?
    ApiResponse::success(
      message: 'Page restored successfully!',
      data: $response['data']->toArray() ?? null,
      statusCode: Response::HTTP_OK
    ) :
    ApiResponse::error(
      message: 'No page found with the given id!',
      errors: ['page' => ['No page found with id: ' . $pageId]],
      statusCode: Response::HTTP_NOT_FOUND,
    );

  }
}
