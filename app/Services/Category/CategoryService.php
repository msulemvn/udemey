<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Category\CategoryDTO;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Interfaces\Category\CategoryServiceInterface;



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

            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $categoryDTO = new CategoryDTO($request);

            $categoryDTO = Category::create($categoryDTO->toArray());

            return $categoryDTO;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get categories', exception: $th);
        }
    }

    /************************************ Show the specified category ************************************/

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            if (!$category) {
                return ApiResponse::error(message: 'No Category Found', statusCode: Response::HTTP_NOT_FOUND);
            }
            return $category;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get categorie', exception: $th);
        }
    }

    /************************************ Update the specified category ************************************/

    public function update($request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $slug = $this->generateUniqueSlug($request['title']);

            $request['slug'] = $slug;

            $categoryDTO = new CategoryDTO($request);

            // Update the category
            $category->update($categoryDTO->toArray());
            return $category;
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
            return $category;
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'Failed to get course Categories', exception: $th);
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
