<?php

namespace App\Http\Controllers;

use App\DTOs\PageDTO;
use App\DTOs\UpdatePageDTO;
use App\Helpers\ApiResponse;
use App\Http\Requests\PageRequest;
use App\Services\PageService;
use App\Models\Page;

class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function create(PageRequest $request)
    {
        $pageDTO = new PageDTO(
            $request->input('title'),
            $request->input('body'),
            // $request->input('slug')
        );

        $page = $this->pageService->createPage($pageDTO);

        return response()->json(['page' => $page], 201);
    }

    public function updatePageById(Page $page, $pageId)
    {
        $updatePageDTO = new UpdatePageDTO(
            // $request->input('title'),
            $request->input('body'),
            // $request->input('slug')
        );

        $updatedPage = $this->pageService->updatePage($page, $updatePageDTO);

        return response()->json(['page' => $updatedPage]);
    }

    public function getPageBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)->first();
    }

    public function deletePage($pageId)
    {

        $page = Page::find($pageId);

        if ($page) {
            $page->delete();
            return ApiResponse::success('page deleted successfully!');
        }
        else
        {
            return ApiResponse::success('Unable to delete page!');

        }
    }
}
