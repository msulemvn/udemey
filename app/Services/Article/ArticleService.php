<?php

namespace App\Services\Article;

use Exception;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Article\ArticleDTO;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ArticleService
{
    public function getAllArticles()
    {

        $articles = Article::all();

        // Add image URL to each article
        foreach ($articles as $article) {
            if ($article->image_path) {
                $article->image_path = asset('storage/' . $article->image_path);
            } else {
                $article->image_path = null;
            }
        }
        return ['data' => ['articles' => $articles]];
    }

    public function createArticle($request)
    {
        try {
            // Initialize the DTO and handle the request
            $dto = new ArticleDTO($request);

            // Create the article from the DTO
            $article = Article::create($dto->toArray());

            // Set the image URL if the image path exists
            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            // Return response with article data and image URL
            return [
                'message' => 'Article created successfully',
                'data' => ['article' => $article],
                'statusCode' => Response::HTTP_CREATED
            ];
        } catch (Exception $e) {
            return ApiResponse::error(
                request: $request,
                exception: $e,
            );
        }
    }


    public function getArticleById($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return ['message' => 'Article not found', 'statusCode' => Response::HTTP_NOT_FOUND];
        }

        // Add image URL to the article
        $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

        return ['data' => ['article' => $article]];
    }


    public function getArticleBySlug($slug)
    {
        $article = Article::where('slug', $slug)->first();

        if (!$article) {
            return ['message' => 'Article Not Found', 'statusCode' => Response::HTTP_NOT_FOUND];
        }

        // Add image URL to the article
        $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

        return ['data' => ['article' => $article]];
    }


    public function updateArticle($request, $id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return ['message' => 'Article Not Found', 'statusCode' => Response::HTTP_NOT_FOUND];
            }

            // Pass the original $request instead of validated array
            $dto = new ArticleDTO($request);

            $article->update($dto->toArray());

            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            return ['data' => ['article' => $article], 'message' => 'Article updated successfully'];
        } catch (Exception $e) {
            return ApiResponse::error(
                request: $request,
                exception: $e,
            );
        }
    }


    public function deleteArticle($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return ['message' => 'Article Not Found', 'statusCode' => Response::HTTP_NOT_FOUND];
        }

        if ($article->image_path) {
            Storage::disk('public')->delete($article->image_path);
        }

        $article->delete();
        return ['message' => 'Article deleted successfully'];
    }
}
