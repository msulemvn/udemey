<?php
namespace App\Services\Page;

use App\DTOs\Page\PageDTO;
use App\Helpers\ApiResponse;
use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PageService
{
    public function createPage($pageData)
    {
        try {
            $page = Page::create(
                (new PageDTO($pageData))->toArray()
            );
            return $page;
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while creating the page. Please try again.']];
            return ApiResponse::error(message: 'Page creation failed', errors: $errors);
        }
    }

    public function updatePage($pageId, array $pageData)
    {
        try {
            $page = Page::find($pageId);

            if (!$page) {
                return null;
            }

            $pageDTO = new PageDTO($pageData);

            $updateData = array_filter($pageDTO->toArray(), function ($value) {
                return !is_null($value);
            });

            $page->update($updateData);
            return $page;
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while updating the page. Please try again.']];
            return ApiResponse::error(message: 'Page update failed', errors: $errors);
        }
    }

    public function getPageBySlug(string $slug)
    {
        try {
            $page = Page::where('slug', $slug)->first();

            if (!$page) {
                return ApiResponse::error(
                    message: 'Page not found!',
                    errors: ['error' => ['The requested page does not exist.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $page;
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while retrieving the page. Please try again.']];
            return ApiResponse::error(message: 'Page retrieval failed', errors: $errors);
        }
    }

    public function getPageById($pageId)
    {
        try {
            $page = Page::where('id', $pageId)->first();

            if (!$page) {
                return ApiResponse::error(
                    message: 'Page not found!',
                    errors: ['error' => ['The requested page does not exist.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $page;
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while retrieving the page. Please try again.']];
            return ApiResponse::error(message: 'Page retrieval failed', errors: $errors);
        }
    }

    public function getPages()
    {
        try {
            $pages = Page::all();
            return $pages;
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while retrieving the pages. Please try again.']];
            return ApiResponse::error(message: 'Pages retrieval failed', errors: $errors);
        }
    }

    public function deletePage(int $pageId)
    {
        try {
            $page = Page::find($pageId);

            if (!$page) {
                return ApiResponse::error(
                    message: 'Page not found!',
                    errors: ['error' => ['The page you are trying to delete does not exist.']],
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
            return $page->delete();
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while deleting the page. Please try again.']];
            return ApiResponse::error(message: 'Page deletion failed', errors: $errors);
        }
    }

    public function restorePage($pageId)
    {
        try {
            $page = Page::withTrashed()->find($pageId);

            if (!$page) {
                return null;
            }
            $page->restore();
            return $page;
        } catch (Throwable $th) {
            $errors = ['error' => ['An error occurred while restoring the page. Please try again.']];
            return ApiResponse::error(message: 'Page restoration failed', errors: $errors);
        }
    }
}
