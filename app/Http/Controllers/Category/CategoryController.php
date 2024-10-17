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
        $categories = $this->categoryService->index();
        return ApiResponse::success(message: 'All categories retrieved successfully', data: $categories->toArray());
    }

    public function store(CreateCategoryRequest $request)
    {
        $category = $this->categoryService->store($request);
        return ApiResponse::success(message: 'Category created successfully', data: $category->toarray());
    }

    public function show($id)
    {
        $category = $this->categoryService->show($id);
        return ApiResponse::success(message: 'Category retrieved successfully', data: $category->toarray());
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryService->update($request, $id);
        return ApiResponse::success(message: 'Category updated successfully', data: $category->toarray());
    }

    public function destroy($id)
    {
        return $this->categoryService->destroy($id);
    }

    public function getCategoryCourseCategories($id)
    {
        $category = $this->categoryService->getCategoryCourseCategories($id);
        return ApiResponse::success(message: 'Course categories retrieved successfully', data: $category->courseCategories->toarray());
    }
}
