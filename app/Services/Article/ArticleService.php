<?php

namespace App\Services\Article;

use App\Models\Article;
use App\DTOs\Article\ArticleDTO;
use Illuminate\Support\Str;
use Exception;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ArticleService
{
    // Get all articles logic
    public function getAllArticles()
    {
        try {
            $articles = Article::all();
            return ApiResponse::success(data: ['articles' => $articles]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to retrieve articles',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // Create article logic with slug uniqueness check
    public function createArticle($request)
    {
        try {
            // Get validated data and create a DTO
            $dto = new ArticleDTO($request->validated());

            // If slug is not provided, create a unique slug based on title
            if (!$dto->slug) {
                $dto->slug = $this->checkSlugExists($dto->title);
            }

            // Ensure image path is received from the frontend and assigned to the DTO
            if (isset($request['image_path'])) {
                $dto->image_path = $request['image_path'];  // Assign the provided path from the frontend
            }

            // Create the article with the provided data
            $article = Article::create($dto->toArray());

            return ApiResponse::success(data: ['article' => $article]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to create article',
                exception: $e,
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }
    }

    // Get a specific article by ID
    public function getArticleById($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return ApiResponse::error('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }
            return ApiResponse::success(data: ['article' => $article]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to retrieve article',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getArticleBySlug($slug)
    {
        try {
            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                return ApiResponse::error('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }

            return ApiResponse::success(data: ['article' => $article]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to retrieve article',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // Update article logic with slug uniqueness check
    public function updateArticle($request, $id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return ApiResponse::error('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }

            $dto = new ArticleDTO($request->validated());

            if (isset($dto->title)) {
                $dto->slug = $this->checkSlugExists($dto->title, $id);
            }

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($article->image_path) {
                    Storage::disk('public')->delete($article->image_path);
                }

                // Store new image
                $path = $request->file('image')->store('articles', 'public');
                $dto->image_path = $path;
            }

            $article->update($dto->toArray());

            return ApiResponse::success(
                data: ['article' => $article],
                message: 'Article updated successfully'
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to update article',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // Delete article logic
    public function deleteArticle($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return ApiResponse::error('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }

            // Delete associated image if exists
            if ($article->image_path) {
                Storage::disk('public')->delete($article->image_path);
            }

            $article->delete();
            return ApiResponse::success(message: 'Article deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to delete article',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // Helper function to check slug uniqueness
    protected function checkSlugExists($title, $articleId = null)
    {
        $slug = Str::slug($title, '-');

        $existingSlug = Article::where('slug', $slug)
            ->where('id', '!=', $articleId)
            ->exists();

        if ($existingSlug) {
            throw new Exception('Slug already exists for another article.');
        }

        return $slug;
    }
}
