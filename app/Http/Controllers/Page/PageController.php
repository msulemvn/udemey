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

    return ApiResponse::success(data: $finalResponse['data'], message: 'Page created successfully!', statusCode: Response::HTTP_CREATED);
  }

  public function update(UpdatePageRequest $request, $pageId)
  {
    $response = $this->pageService->updatePage($pageId, $request->all());

    return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::success(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
  }

  public function getPageBySlug(string $slug)
  {
    $response  = $this->pageService->getPageBySlug($slug);

    return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::success(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
  }

  public function getPageById($id)
  {
    $response = $this->pageService->getPageById($id);
    return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? []) : ApiResponse::success(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
  }

  public function getPages()
  {
    $response = $this->pageService->getPages();
    return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null, data: $response['data']->toArray(request() ?? [])) : ApiResponse::success(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
  }

  public function destroy($id)
  {
    $response = $this->pageService->deletePage($id);
    return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null) : ApiResponse::success(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
   
  }

  public function restore($id)
  {
    $response = $this->pageService->restorePage($id);

    return $response['success'] ? ApiResponse::success(message: $response['message'] ?? null) : ApiResponse::success(message: $response['message'] ?? null, errors: $response['errors'] ?? null);
  }
}
