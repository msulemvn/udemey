<?php

namespace App\Services\Article;

use App\Models\Article;
use App\DTOs\Article\ArticleDTO;
use Illuminate\Support\Str;
use Exception;

class ArticleService
{
    // Get all articles logic
    public function getAllArticles()
    {
        try {
            $articles = Article::all();
            return ['status' => 'success', 'data' => $articles];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to retrieve articles', 'exception' => $e];
        }
    }

    // Create article logic with slug uniqueness check
    public function createArticle(ArticleDTO $dto)
    {
        try {
            // Automatically generate a slug from the title if it's not provided
            if (!$dto->slug) {
                $dto->slug = $this->checkSlugExists($dto->title);
            }

            $article = Article::create((array)$dto);
            return ['status' => 'success', 'data' => $article];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to create article', 'exception' => $e];
        }
    }

    // Get a specific article by ID
    public function getArticleById($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return ['status' => 'error', 'message' => 'Article not found'];
            }
            return ['status' => 'success', 'data' => $article];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to retrieve article', 'exception' => $e];
        }
    }

    public function getArticleBySlug($slug)
    {
        try {
            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                return ['status' => 'error', 'message' => 'Article not found'];
            }

            return ['status' => 'success', 'data' => $article];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to retrieve article', 'exception' => $e->getMessage()];
        }
    }

    // Update article logic with slug uniqueness check
    public function updateArticle(ArticleDTO $dto, $id)
    {
        try {
            $article = Article::find($id);
            if ($article) {
                // If the title is updated, generate and check slug
                if (isset($dto->title)) {
                    $dto->slug = $this->checkSlugExists($dto->title, $id);
                }

                $article->update((array)$dto);
                return ['status' => 'success', 'data' => $article];
            }
            return ['status' => 'error', 'message' => 'Article not found'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to update article', 'exception' => $e];
        }
    }

    // Delete article logic
    public function deleteArticle($id)
    {
        try {
            $article = Article::find($id);
            if ($article) {
                $article->delete();
                return ['status' => 'success', 'message' => 'Article deleted successfully'];
            }
            return ['status' => 'error', 'message' => 'Article not found'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Failed to delete article', 'exception' => $e];
        }
    }

    // Helper function to check slug uniqueness
    protected function checkSlugExists($title, $articleId = null)
    {
        // Generate slug from title
        $slug = Str::slug($title, '-');

        // Check if slug exists and does not belong to the current article (if updating)
        $existingSlug = Article::where('slug', $slug)
            ->where('id', '!=', $articleId) // Exclude current article during update
            ->exists();

        if ($existingSlug) {
            throw new Exception('Slug already exists for another article.');
        }

        return $slug;
    }
}
