<?php

namespace App\Http\Controllers\Page;

use App\DTOs\Page\PageDTO;
use App\DTOs\Page\UpdatePageDTO;
use App\Http\Requests\Page\PageRequest;
use App\Services\Page\PageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\UpdatePageRequest;

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
            $request->input('body')
        );

        return $this->pageService->createPage($pageDTO);
    }

    public function update(UpdatePageRequest $request, $pageId)
    {
        $updatePageDTO = new UpdatePageDTO(
            $request->input('body')
        );

        return $this->pageService->updatePage($pageId, $updatePageDTO);
    }

    public function getPageBySlug(string $slug)
    {
        return $this->pageService->getPageBySlug($slug);
    }

    public function destroy($pageId)
    {
        return $this->pageService->deletePage($pageId);
    }
}
