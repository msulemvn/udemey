<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Category\CategoryDTO;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Category\CategoryServiceInterface;
use Symfony\Component\HttpFoundation\Response;



class CategoryService implements CategoryServiceInterface
{
    /************************************ Display a listing of the categories ************************************/

    public function index()
    {
        try {
            $categories = Category::all();
            if ($categories->isEmpty()) {
                return ApiResponse::error(message: 'No categories available', statusCode: Response::HTTP_NOT_FOUND);
            }
            return $categories;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get categories', exception: $th);
        }
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
            return ApiResponse::error(message: 'Failed to create category');
        }
    }

    /************************************ Show the specified category ************************************/

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return ApiResponse::success(message: 'Category retrieved successfully', data: $category->toarray());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(message: 'No Category Found', statusCode: Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get categorie', exception: $th);
        }
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
            return ApiResponse::error(message: 'Failed to update category', statusCode: Response::HTTP_NOT_FOUND);
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
            return ApiResponse::error(message: 'Failed to delete Category', exception: $th);
        }
    }

    public function getCategoryCourseCategories($id)
    {
        try {
            $category = Category::with('courseCategories')->find($id);

            if (!$category) {
                return ApiResponse::error(message: 'Course Categories not found', statusCode: Response::HTTP_NOT_FOUND);
            }
            return ApiResponse::success(message: 'Course categories retrieved successfully', data: $category->courseCategories->toarray());
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get course Categories', exception: $th);
        }
    }
}
