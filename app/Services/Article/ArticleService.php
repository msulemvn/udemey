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
        try {
            $articles = Article::all();

            // Add image URL to each article
            foreach ($articles as $article) {
                if ($article->image_path) {
                    $article->image_path = asset('storage/' . $article->image_path);
                } else {
                    $article->image_path = null;
                }
            }

            return ApiResponse::success(data: ['articles' => $articles]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to retrieve articles',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function createArticle($request)
    {
        try {
            // Handle image upload and save the file to a directory
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $timestamp = now()->format('YmdHs');
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $file->storeAs('uploads', $newFileName, 'public');
                $imagePath = 'uploads/' . $newFileName;
            } else {
                $imagePath = null;  // No image was uploaded
            }

            $dtoData = $request->validated();

            $dtoData['slug'] = $this->generateUniqueSlug($dtoData['title']);
            $dtoData['image_path'] = $imagePath;

            $dto = new ArticleDTO($dtoData);
            $article = Article::create($dto->toArray());


            $article->image_path = $article->image_path ? asset('storage/' . $article->image_path) : null;


            return ApiResponse::success(data: ['article' => $article]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to create article',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getArticleById($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return ApiResponse::error('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }

            // Generate image URL and base64 image
            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            if ($article->image_path) {
                $imagePath = storage_path('app/public/' . $article->image_path);
                if (file_exists($imagePath)) {
                    $imageData = file_get_contents($imagePath);
                    $article->image = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
                }
            } else {
                $article->image = null;
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

            // Generate image URL and base64 image
            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            if ($article->image_path) {
                $imagePath = storage_path('app/public/' . $article->image_path);
                if (file_exists($imagePath)) {
                    $imageData = file_get_contents($imagePath);
                    $article->image = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
                }
            } else {
                $article->image = null;
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
                if ($article->image_path) {
                    Storage::disk('public')->delete($article->image_path);
                }

                $timestamp = now()->format('YmdHs');
                $originalFileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $request->file('image')->storeAs('uploads', $newFileName, 'public');
                $dto->image_path = 'uploads/' . $newFileName;
            }

            $article->update($dto->toArray());

            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

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

    public function deleteArticle($id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return ApiResponse::error('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }

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

    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-');
        $count = Article::where('slug', 'LIKE', "$slug%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

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
