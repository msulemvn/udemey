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
        return $this->categoryService->store($request);
    }

    /************************************ Display the specified category ************************************/

    public function show($id)
    {
        return $this->categoryService->show($id);
    }

    /************************************ Update the specified category ************************************/

    public function update(CategoryUpdateRequest $request, $id)
    {
        return $this->categoryService->update($request, $id);
    }

    /************************************ Remove the specified category ************************************/

    public function destroy($id)
    {
        return $this->categoryService->destroy($id);
    }
    /************************************ get Course_Categories ************************************/

    public function getCategoryCourseCategories($id)
    {
        return $this->categoryService->getCategoryCourseCategories($id);
    }
}
