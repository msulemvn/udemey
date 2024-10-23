<?php

namespace App\Http\Controllers\Page;

use App\Models\Page;
use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
use App\Services\Page\PageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Page\PageResource;
use App\Http\Requests\Page\GetPageRequest;
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

  public function store(CreatePageRequest $request)
  {
    $response = $this->pageService->store($request);
    return ApiResponse::success(message: $response['message'], data: $response['data'], statusCode: Response::HTTP_CREATED);
  }

  public function update(UpdatePageRequest $request, $id)
  {
    $response = $this->pageService->update($request, $id);
    return ApiResponse::success(message: $response['message'], data: $response['data']);
  }

  public function destroy(int $id)
  {
    $response = $this->pageService->destroy($id);
    if (isset($response['errors'])) {
      return ApiResponse::success(message: $response['message'], errors: $response['errors'], statusCode: $response['statusCode']);
    }
    return ApiResponse::success(message: $response['message'], statusCode: $response['statusCode']);
  }

  public function restore(int $id)
  {
    $response = $this->pageService->restore($id);
    if (isset($response['errors'])) {
      return ApiResponse::success(message: $response['message'], errors: $response['errors'], statusCode: $response['statusCode']);
    }
    return ApiResponse::success(message: $response['message'], statusCode: $response['statusCode']);
  }

  public function index(GetPageRequest $request)
  {
    
    return $this->pageService->index($request);
  }
  
}
