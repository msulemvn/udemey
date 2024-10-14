<?php

namespace App\Http\Controllers\Article;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Article\ArticleService;
use App\Http\Requests\Article\ArticleRequest;
use App\DTOs\Article\ArticleDTO;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    // Get all articles
    public function index()
    {
        $response = $this->articleService->getAllArticles();
        if ($response['status'] === 'success') {
            return ApiResponse::success(data: ['articles' => $response['data']]);
        }
        return ApiResponse::error(message: $response['message'], exception: $response['exception'], statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Create a new article
    public function store(ArticleRequest $request)
    {
        $dto = ArticleDTO::fromRequest($request->validated());
        $response = $this->articleService->createArticle($dto);

        if ($response['status'] === 'success') {
            return ApiResponse::success(data: ['article' => $response['data']]);
        }
        return ApiResponse::error(message: $response['message'], request: $request, exception: $response['exception'], statusCode: Response::HTTP_UNAUTHORIZED);
    }

    // Show a specific article by ID
    public function show($id)
    {
        $response = $this->articleService->getArticleById($id);
        if ($response['status'] === 'success') {
            return ApiResponse::success(data: ['article' => $response['data']]);
        }
        return ApiResponse::error($response['message']);
    }

    // Show a specific article by Slug
    public function showBySlug($slug, Request $request)
    {
        try {
            $article = $this->articleService->getArticleBySlug($slug);

            if ($article['status'] === 'error') {
                return ApiResponse::error(message: $article['message'], request: $request, statusCode: Response::HTTP_NOT_FOUND);
            }

            return ApiResponse::success(data: ['article' => $article['data']]);
        } catch (Exception $e) {
            return ApiResponse::error(message: 'Failed to retrieve article', request: $request, exception: $e, statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update an article
    public function update(ArticleRequest $request, $id)
    {
        $dto = new ArticleDTO($request);

        $response = $this->articleService->updateArticle($dto, $id);

        if ($response['status'] === 'success') {
            return ApiResponse::success(data: ['article' => $response['data']], message: 'Article updated successfully');
        }
        return ApiResponse::error(message: $response['message'], request: $request, exception: $response['exception'], statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Delete an article
    public function destroy($id)
    {
        $response = $this->articleService->deleteArticle($id);
        if ($response['status'] === 'success') {
            return ApiResponse::success(message: $response['message']);
        }
        return ApiResponse::error(message: $response['message'], statusCode: Response::HTTP_NOT_FOUND);
    }
}
