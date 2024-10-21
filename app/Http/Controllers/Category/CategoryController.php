<?php

namespace App\Http\Controllers\Category;

use App\Helpers\ApiResponse;
use App\Services\Category\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    protected $categoryService;

    // Inject the CategoryService
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $response = $this->categoryService->index();
        return ApiResponse::success(message: $response['message'], data: $response['body']);
    }

    public function store(CreateCategoryRequest $request)
    {
        $response = $this->categoryService->store($request);
        return ApiResponse::success(message: $response['message'], data: $response['body']);
    }

    public function show($id)
    {
        $response = $this->categoryService->show($id);
        return ApiResponse::success(message: $response['message'], data: $response['body']);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $response = $this->categoryService->update($request, $id);
        return ApiResponse::success(message: $response['message'], data: $response['body']);
    }

    public function destroy($id)
    {
        $response = $this->categoryService->destroy($id);
        return ApiResponse::success(message: $response['message'], data: $response['body']);
    }

    public function getCategoryCourseCategories($id)
    {
        $response = $this->categoryService->getCategoryCourseCategories($id);
        return ApiResponse::success(message: $response['message'], data: $response['body']);
    }
}
