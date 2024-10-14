<?php

namespace App\Services\Article;

use App\Models\Article;
use App\DTOs\Article\ArticleDTO;
use Illuminate\Support\Str;
use Exception;
use App\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

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
            $dto = new ArticleDTO($request->validated());

            if (!$dto->slug) {
                $dto->slug = $this->checkSlugExists($dto->title);
            }

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
