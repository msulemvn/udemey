<?php

namespace App\Http\Controllers\Article;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Article\ArticleService;
use App\Http\Requests\Article\ArticleRequest;

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
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    // Create a new article
    public function store(ArticleRequest $request)
    {
        $response = $this->articleService->createArticle($request);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    // Show a specific article by ID
    public function show($id)
    {
        $response = $this->articleService->getArticleById($id);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    // Show a specific article by Slug
    public function showBySlug($slug)
    {
        $response = $this->articleService->getArticleBySlug($slug);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    // Update an article
    public function update(ArticleRequest $request, $id)
    {
        $response = $this->articleService->updateArticle($request, $id);
        return ApiResponse::success(message: $response['message'] ?? null, data: $response['data'] ?? [], errors: $response['errors'] ?? [], statusCode: $response['statusCode'] ?? 200);
    }

    // Delete an article
    public function destroy($id)
    {
        $response = $this->articleService->deleteArticle($id);
        return ApiResponse::success(
            message: $response['message'] ?? null,
            data: $response['data'] ?? [],
            errors: $response['errors'] ?? [],
            statusCode: $response['statusCode'] ?? 200
        );
    }
}
