<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function getAllArticles()
    {
        return Article::all();
    }

    public function createArticle($data)
    {
        //  unset($data['request_log_id']);
        return Article::create($data);
    }

    public function getArticleById($id)
    {
        return Article::find($id);
    }

    public function updateArticle($data, $id)
    {
        $article = Article::find($id);
        if ($article) {
            $article->update($data);
            return $article;
        }
        return null;
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);
        if ($article) {
            $article->delete();
            return true;
        }
        return false;
    }
}
