<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Category\CategoryDTO;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\Category\CategoryServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class CategoryService implements CategoryServiceInterface
{
    /************************************ Display a listing of the categories ************************************/

    public function index()
    {
        try {
            $categories = Category::all();
            if ($categories->isEmpty()) {
                return ApiResponse::error(
                    message: 'No categories available',
                    errors: ['categories' => ['No categories found in the system']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $categories;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to get categories',
                errors: ['categories' => ['An error occurred while retrieving the categories. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /************************************ Store a newly created category ************************************/

    public function store($request)
    {
        try {

            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $categoryDTO = new CategoryDTO($request);

            $categoryDTO = Category::create($categoryDTO->toArray());

            return $categoryDTO;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to create category',
                errors: ['category' => ['An error occurred while creating the category. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /************************************ Show the specified category ************************************/

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            if (!$category) {
                return ApiResponse::error(
                    message: 'Category not found',
                    errors: ['category' => ['No category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $category;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve category',
                errors: ['category' => ['An error occurred while retrieving the category. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /************************************ Update the specified category ************************************/

    public function update($request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            if (!$category) {
                return ApiResponse::error(
                    message: 'Category not found',
                    errors: ['category' => ['No category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }

            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $categoryDTO = new CategoryDTO($request);

            // Update the category
            $category->update($categoryDTO->toArray());
            return $category;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to update category',
                errors: ['category' => ['An error occurred while updating the category. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /************************************ Remove the specified category ************************************/

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return ApiResponse::success(message: 'Category deleted successfully');
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to delete category',
                errors: ['category' => ['An error occurred while deleting the category. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getCategoryCourseCategories($id)
    {
        try {
            $category = Category::with('courseCategories')->find($id);

            if (!$category) {
                return ApiResponse::error(
                    message: 'Category not found',
                    errors: ['category' => ['No category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $category;
        } catch (\Throwable $th) {
            return ApiResponse::error(
                message: 'Failed to retrieve course categories',
                errors: ['courseCategories' => ['An error occurred while retrieving course categories. Please try again later.']],
                exception: $th,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-');

        $existingSlug = Category::where('slug', $slug)->first();

        if ($existingSlug) {
            // If slug already exists, append a number to make it unique
            $count = 1;
            while ($existingSlug) {
                $newSlug = $slug . '-' . $count;
                $existingSlug = Category::where('slug', $newSlug)->first();
                $count++;
            }
            return $newSlug;
        }

        return $slug;
    }
}
