<?php
namespace App\Services\Page;

use App\DTOs\Page\PageDTO;
use App\DTOs\Page\UpdatePageDTO;
use App\Helpers\ApiResponse;
use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class PageService
{
    public function createPage($pageData)
    {
        $page = Page::create(
          (new PageDTO($pageData))->toArray());
          return $page;
    }

    public function updatePage($pageId, PageDTO $updatePageDTO)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', statusCode:Response::HTTP_NOT_FOUND);
        }

        $page->update([
            'body' => $updatePageDTO->body,
        ]);
        return $page;
    }

    public function getPageBySlug(string $slug)
    {
        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            return ApiResponse::error(message:'Page not found!',statusCode: Response::HTTP_NOT_FOUND);
        }
        return $page;
    }

    public function getPageById( $pageId)
    {
        $page = Page::where('id', $pageId)->first();

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', statusCode: Response::HTTP_NOT_FOUND);
        }
        return $page;
    }

    // public function getPages()
    // {
    //     $pages = Page::all();

    //     if(!$pages)
    //     {
    //         return ApiResponse::error(message:'Pages not found!', statusCode:Response::HTTP_NOT_FOUND);
    //     }
    //     else
    //     {
    //         return $pages;
    //     }
    // }
    public function getPages()
    {
        $pages = Page::all();
        return $pages;
    }
    public function deletePage(int $pageId)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return ApiResponse::error(message:'Page not found!', statusCode:Response::HTTP_NOT_FOUND);
        }

        return $page->delete();

    }
}