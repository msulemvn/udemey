<?php

namespace App\Http\Controllers\Category;

use App\Helpers\ApiResponse;
use App\Services\Category\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;

class CategoryController extends Controller
{
    protected $categoryService;

    // Inject the CategoryService
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /************************************ Display a listing of the categories ************************************/

    public function index()
    {
        $categories = $this->categoryService->index();
        return ApiResponse::success(message: 'All categories retrieved successfully', data: $categories->toArray());
    }

    /************************************ Store a newly created category ************************************/

    public function store(CategoryCreateRequest $request)
    {
        $category = $this->categoryService->store($request);
        return ApiResponse::success(message: 'Category created successfully', data: $category->toarray());
    }

    /************************************ Display the specified category ************************************/

    public function show($id)
    {
        $category = $this->categoryService->show($id);
        return ApiResponse::success(message: 'Category retrieved successfully', data: $category->toarray());
    }

    /************************************ Update the specified category ************************************/

    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = $this->categoryService->update($request, $id);
        return ApiResponse::success(message: 'Category updated successfully', data: $category->toarray());
    }

    /************************************ Remove the specified category ************************************/

    public function destroy($id)
    {
        return $this->categoryService->destroy($id);
    }
    /************************************ get Course_Categories ************************************/

    public function getCategoryCourseCategories($id)
    {
        $category = $this->categoryService->getCategoryCourseCategories($id);
        return ApiResponse::success(message: 'Course categories retrieved successfully', data: $category->courseCategories->toarray());
    }
}
