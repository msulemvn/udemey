<?php

namespace App\Services;

use App\DTOs\PageDTO;
use App\Models\Page;
use App\DTOs\UpdatePageDTO;

class PageService
{
    public function createPage(PageDTO $pageDTO)
    {
        return Page::create([
          'title' => $pageDTO->title,
          'body' => $pageDTO->body,
          // 'slug' => $pageDTO->slug,
        ]);
    }

    public function updatePage(Page $page, UpdatePageDTO $updatePageDTO)
    {
        $page->update([
          'body' => $updatePageDTO->getBody(),
        ]);

        return $page;
    }
}
