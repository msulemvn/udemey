<?php

namespace App\Http\Controllers\Article;

use App\Services\ArticleService;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    // Get all articles (restricted to subscribed students, managers, and admin)
    public function index()
    {
        try {
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager' || Auth::user()->is_subscribed) {
                $articles = $this->articleService->getAllArticles();
                return response()->json(['articles' => $articles], 200);
            }

            return response()->json(['message' => 'Access denied. You must be subscribed.'], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve articles', 'error' => $e->getMessage()], 500);
        }
    }

    // Create a new article (only for admin and managers)
    public function store(StoreArticleRequest $request)
    {
        try {

            $article = $this->articleService->createArticle($request->validated());
            return response()->json(['message' => 'Article created successfully', 'article' => $article], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create article', 'error' => $e->getMessage()], 500);
        }
    }

    // Show a specific article (restricted to subscribed students, managers, and admin)
    public function show($id)
    {
        try {
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager' || Auth::user()->is_subscribed) {
                $article = $this->articleService->getArticleById($id);

                if (!$article) {
                    return response()->json(['message' => 'Article not found'], 404);
                }

                return response()->json(['article' => $article], 200);
            }

            return response()->json(['message' => 'Access denied. You must be subscribed.'], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve article', 'error' => $e->getMessage()], 500);
        }
    }

    // Update an article (only for admin and managers)
    public function update(ArticleRequest $request, $id)
    {
        try {
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager') {
                $article = $this->articleService->updateArticle($request->all(), $id);

                if (!$article) {
                    return response()->json(['message' => 'Article not found'], 404);
                }

                return response()->json(['message' => 'Article updated successfully', 'article' => $article], 200);
            }

            return response()->json(['message' => 'Only admin or manager can update articles'], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update article', 'error' => $e->getMessage()], 500);
        }
    }

    // Delete an article (only for admin and managers)
    public function destroy($id)
    {
        try {
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager') {
                $success = $this->articleService->deleteArticle($id);

                if (!$success) {
                    return response()->json(['message' => 'Article not found'], 404);
                }

                return response()->json(['message' => 'Article deleted successfully'], 200);
            }

            return response()->json(['message' => 'Only admin or manager can delete articles'], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete article', 'error' => $e->getMessage()], 500);
        }
    }
}
