<?php

namespace App\Services\Page;

use App\DTOs\Page\PageDTO;
use App\DTOs\Page\UpdatePageDTO;
use App\Helpers\ApiResponse;
use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class PageService
{
    public function createPage(PageDTO $pageDTO)
    {
        $page = Page::create([
          
            'title' => $pageDTO->title,
            'body' => $pageDTO->body,
        ]);

        return ApiResponse::success( $page->toArray(), 'Page created successfully!', Response::HTTP_OK);
    }

    public function updatePage($pageId, UpdatePageDTO $updatePageDTO)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', 404);
        }

        $page->update([
            'body' => $updatePageDTO->body,
        ]);

        return ApiResponse::success( $page->toArray(), 'Page updated successfully!');
    }

    public function getPageBySlug(string $slug)
    {
        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            return ApiResponse::error('Page not found!', 404);
        }

        return ApiResponse::success($page->toArray(), 'Page retrieved successfully!');
    }

    public function deletePage(int $pageId)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return ApiResponse::error('Page not found!', 404);
        }

        $page->delete();

        return ApiResponse::success('Page deleted successfully!');
    }
}
