<?php

namespace App\Services\Article;

use Exception;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\DTOs\Article\ArticleDTO;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Nette\Utils\Paginator;

class ArticleService
{
    // Get all articles logic
    // public function getAllArticles()
    // {
    //     try {
    //         $articles = Article::all();

    //         // Add image URL to each article
    //         foreach ($articles as $article) {
    //             $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;
    //         }

    //         return ApiResponse::success(data: ['articles' => $articles]);
    //     } catch (Exception $e) {
    //         return ApiResponse::error(
    //             message: 'Failed to retrieve articles',
    //             exception: $e,
    //             statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
    //         );
    //     }
    // }


    public function getAllArticles()
    {
        try {
            $articles = Article::paginate(2);

            // Add image URL and base64-encoded image to each article
            foreach ($articles as $article) {
                if ($article->image_path) {
                    $imagePath = storage_path('app/public/' . $article->image_path);
                    if (file_exists($imagePath)) {
                        $imageData = file_get_contents($imagePath);
                        $base64Image = base64_encode($imageData);
                        // $article->image = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $base64Image;
                        $article->body = $article->body;
                    }
                } else {
                    $article->image = null;
                }
            }

            return ApiResponse::success(data: ['articles' => $articles]);
        } catch (\Exception $e) {
            // return ApiResponse::error(exception: $e,);
            dd();
        }
    }

    // Create article logic with slug uniqueness check
    public function createArticle($request)
    {
        try {
            // Handle image upload and save the file to a directory
            if ($request->hasFile('image_file')) {
                // Store the image in the 'articles' directory within the 'public' disk
                $file = $request->file('image_file');
                $imagePath = $file->store('articles', 'public');


                Storage::disk('public')->put($imagePath, file_get_contents($file->getRealPath()));
            } else {
                $imagePath = null;  // No image was uploaded
            }

            $dtoData = $request->validated();

            $dtoData['slug'] = $this->generateUniqueSlug($dtoData['title']);
            $dtoData['image_path'] = $imagePath;  // Assign the image path to the DTO data

            $dto = new ArticleDTO($dtoData);

            $article = Article::create($dto->toArray());

            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            return ApiResponse::success(data: ['article' => $article]);
        } catch (Exception $e) {
            return ApiResponse::error(
                message: 'Failed to create article',
                exception: $e,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    // Get a specific article by ID
    public function getArticleById($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return ApiResponse::success('Article not found', statusCode: Response::HTTP_NOT_FOUND);
            }
            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            return ApiResponse::success(data: ['article' => $article]);
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    // Get a specific article by slug
    public function getArticleBySlug($slug)
    {
        try {
            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                return ApiResponse::success('Article not found');
            }
            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            return ApiResponse::success(data: ['article' => $article]);
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    // Update article logic with slug uniqueness check
    public function updateArticle($request, $id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return ApiResponse::success('Article not found');
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

            $article->image_url = $article->image_path ? asset('storage/' . $article->image_path) : null;

            return ApiResponse::success(
                data: ['article' => $article],
                message: 'Article updated successfully'
            );
        } catch (\Exception $e) {
            // return ApiResponse::error(
            //     exception: $e,
            // );
            dd();
        }
    }

    // Delete article logic
    public function deleteArticle($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return ApiResponse::success('Article not found', statusCode: Response::HTTP_NOT_FOUND);
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

    // Helper function to generate a unique slug
    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title, '-');
        $count = Article::where('slug', 'LIKE', "$slug%")->count();

        // If slug exists, append the count to make it unique
        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

    // Helper function to check slug uniqueness for update
    protected function checkSlugExists($title, $articleId = null)
    {
        $slug = Str::slug($title, '-');

        $existingSlug = Article::where('slug', $slug)
            ->where('id', '!=', $articleId)
            ->exists();

        if ($existingSlug) {
            throw new \Exception('Slug already exists for another article.');
        }

        return $slug;
    }
}
