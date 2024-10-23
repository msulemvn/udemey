<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Category\CategoryDTO;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    /************************************ Get all category  ************************************/

    public function index()
    {
        $categories = Category::all();
        if (!$categories) {
            return [
                'errors' => ['categories' => ['No categories found in the system.']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['message' => 'All categories retrieved successfully', 'data' => $categories->toArray()];
    }
    /************************************ Get Category By Id  ************************************/

    public function getCategoryById($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return [
                'errors' => ['category' => ['No category found with the given ID']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['message' => 'Category retrieved successfully', 'data' => $category->toArray()];
    }

    /************************************ Create Category  ************************************/

    public function store($request)
    {
        try {
            $categoryDTO = new CategoryDTO($request);

            $categoryDTO = Category::create($categoryDTO->toArray());

            return ['message' => 'Category created successfully', 'data' => $categoryDTO->toArray(), 'statusCode' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }
    /************************************ Update Category  ************************************/

    public function update($request, $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return [

                    'errors' => ['category' => ['No category found with the given ID.']],
                    'statusCode' => Response::HTTP_NOT_FOUND
                ];
            }

            $categoryDTO = new CategoryDTO($request);
            $category->update($categoryDTO->toArray());
            return ['message' => 'Category updated successfully', 'data' => $category->toArray()];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /************************************ delete Category  ************************************/

    public function destroy($request)
    {
        try {
            $category = Category::findOrFail($request);
            $result = $category->delete();
            return ['message' => $result ? 'category deleted successfully.' : 'Failed to delete category.'];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    /************************************ get course Categories  ************************************/

    public function getCategoryCourseCategories($id)
    {

        $category = Category::with('courseCategories')->find($id);

        if (!$category) {
            return [
                'errors' => ['category' => ['No category found with the given ID.']],
                'statusCode' => Response::HTTP_NOT_FOUND
            ];
        }
        return ['message' => 'Course categories retrieved successfully', 'data' => $category->toArray()];
    }
}
