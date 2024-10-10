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

        return ApiResponse::success( $page->toArray(), 'Page created successfully!', Response::HTTP_CREATED);
    }

    public function updatePage($pageId, UpdatePageDTO $updatePageDTO)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', statusCode:Response::HTTP_NOT_FOUND);
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
            return ApiResponse::error(message:'Page not found!',statusCode: Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success($page->toArray(), 'Page retrieved successfully!');
    }

    public function getPageById( $pageId)
    {
        $page = Page::where('id', $pageId)->first();

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', statusCode: Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success($page->toArray(), 'Page retrieved successfully!');
    }

    public function getPages()
    {
        $pages = Page::all();

        if(!$pages)
        {
            return ApiResponse::error(message:'Pages not found!', statusCode:Response::HTTP_NOT_FOUND);
        }
        else
        {
            return ApiResponse::success($pages->toArray(), message:'Pages retrieved successfully!', statusCode:Response::HTTP_OK);
        }
    }

    public function deletePage(int $pageId)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', statusCode:Response::HTTP_NOT_FOUND);
        }

        $page->delete();

        return ApiResponse::success(null, 'Page deleted successfully!');
    }
}
