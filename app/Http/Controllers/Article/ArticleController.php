<?php

namespace App\Http\Controllers\Article;

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
        return $this->articleService->getAllArticles();
    }

    // Create a new article
    public function store(ArticleRequest $request)
    {
        return $this->articleService->createArticle($request);
    }

    // Show a specific article by ID
    public function show($id)
    {
        return $this->articleService->getArticleById($id);
    }

    // Show a specific article by Slug
    public function showBySlug($slug)
    {
        return $this->articleService->getArticleBySlug($slug);
    }

    // Update an article
    public function update(ArticleRequest $request, $id)
    {
        return $this->articleService->updateArticle($request, $id);
    }

    // Delete an article
    public function destroy($id)
    {
        return $this->articleService->deleteArticle($id);
    }
}
