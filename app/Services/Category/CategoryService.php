<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Category\CategoryDTO;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Category\CategoryServiceInterface;


class CategoryService implements CategoryServiceInterface
{
    /************************************ Display a listing of the categories ************************************/

    public function index()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return ApiResponse::error(error: 'No categories available', statusCode: 200);
        }
        return ApiResponse::success(message: 'All categories retrieved successfully', data: $categories, statusCode: 201);
    }

    /************************************ Store a newly created category ************************************/

    public function store($request)
    {
        try {
            $validatedData = $request->validated();

            // Generate slug from title
            $slug = Str::slug($validatedData['title'], '-');

            // Ensure the slug is unique
            $slugCount = Category::where('slug', 'LIKE', "{$slug}%")->count();
            if ($slugCount > 0) {
                $slug .= '-' . ($slugCount + 1);
            }

            // Prepare the DTO (Data Transfer Object)
            $categoryDTO = new CategoryDTO([
                'title' => $validatedData['title'],
                'slug' => $slug,
            ]);

            // Create the category
            $category = Category::create($categoryDTO->toArray());

            return ApiResponse::success(message: 'Category created successfully', data: $category, statusCode: 201);
        } catch (\Exception $e) {
            // Log the error and return a generic response
            Log::error('Error creating category', ['message' => $e->getMessage()]);
            return ApiResponse::error(error: 'Failed to create category', statusCode: 500);
        }
    }

    /************************************ Show the specified category ************************************/

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return ApiResponse::success(message: 'Category retrieved successfully', data: $category);
    }

    /************************************ Update the specified category ************************************/

    public function update($request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            // Prepare the DTO
            $slug = Str::slug($request['title'], '-');
            $slugCount = Category::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
            if ($slugCount > 0) {
                $slug .= '-' . ($slugCount + 1);
            }

            $categoryDTO = new CategoryDTO([
                'title' => $request['title'],
                'slug' => $slug,
            ]);

            // Update the category
            $category->update($categoryDTO->toArray());

            return ApiResponse::success(message: 'Category updated successfully', data: $category);
        } catch (\Exception $e) {
            Log::error('Error updating category', ['message' => $e->getMessage()]);
            return ApiResponse::error(error: 'Failed to update category', statusCode: 500);
        }
    }

    /************************************ Remove the specified category ************************************/

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return ApiResponse::success(message: 'Category deleted successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(error: 'Category not found', statusCode: 404);
        }
    }

    public function getCategoryCourseCategories($id)
    {
        // Retrieve the category along with its course categories
        $category = Category::with('courseCategories')->find($id);

        // Check if the category exists
        if (!$category) {
            return ApiResponse::error(error: 'Category not found', statusCode: 404);
        }

        // Return the course categories associated with the category
        return ApiResponse::success(message: 'Course categories retrieved successfully', data: $category->courseCategories);
    }
}
