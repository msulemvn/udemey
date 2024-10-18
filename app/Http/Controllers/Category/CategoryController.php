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
        $Response = $this->categoryService->index();
        return ApiResponse::success(message: $Response['message'], data: $Response['body']);
    }

    public function store(CreateCategoryRequest $request)
    {
        $Response = $this->categoryService->store($request);
        return ApiResponse::success(message: $Response['message'], data: $Response['body']);
    }

    public function show($id)
    {
        $Response = $this->categoryService->show($id);
        return ApiResponse::success(message: $Response['message'], data: $Response['body']);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $Response = $this->categoryService->update($request, $id);
        return ApiResponse::success(message: $Response['message'], data: $Response['body']);
    }

    public function destroy($id)
    {
        $Response = $this->categoryService->destroy($id);
        return ApiResponse::success(message: $Response['message'], data: $Response['body']);
    }

    public function getCategoryCourseCategories($id)
    {
        $Response = $this->categoryService->getCategoryCourseCategories($id);
        return ApiResponse::success(message: $Response['message'], data: $Response['body']);
    }
}
