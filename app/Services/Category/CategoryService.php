<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Category\CategoryDTO;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    public function index()
    {
        try {
            $categories = Category::all();
            if ($categories->isEmpty()) {
                return ApiResponse::success(
                    message: 'No categories available',
                    errors: ['categories' => ['No categories found in the system']],
                );
            }
            return [
                'message' => 'All categories retrieved successfully',
                'body' => $categories->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }


    public function store($request)
    {
        try {

            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $categoryDTO = new CategoryDTO($request);

            $categoryDTO = Category::create($categoryDTO->toArray());
            return [
                'message' => 'Category created successfully',
                'body' => $categoryDTO->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            if (!$category) {
                return ApiResponse::success(
                    message: 'Category not found',
                    errors: ['category' => ['No category found with the given ID']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return [
                'message' => 'Category retrieved successfully',
                'body' => $category->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function update($request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            if (!$category) {
                return ApiResponse::success(
                    message: 'Category not found',
                    errors: ['category' => ['No category found with the given ID']],
                );
            }

            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $categoryDTO = new CategoryDTO($request);

            // Update the category
            $category->update($categoryDTO->toArray());
            return [
                'message' => 'Category updated successfully',
                'body' => $category->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return [
                'message' => 'Category deleted successfully',
            ];
            return ApiResponse::success(message: '');
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    public function getCategoryCourseCategories($id)
    {
        try {
            $category = Category::with('courseCategories')->find($id);

            if (!$category) {
                return ApiResponse::success(
                    message: 'Category not found',
                    errors: ['category' => ['No category found with the given ID']],
                );
            }
            return [
                'message' => 'Course categories retrieved successfully',
                'body' => $category->toArray(),
            ];
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
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
