<?php

namespace App\Services;

use App\DTOs\MenuItemDTO;
use App\Models\MenuItem;

class MenuItemService
{
    /**
     * Create a new menu item.
     *
     * @param MenuItemDTO $menuItemDTO
     * @return MenuItem
     */
    public function createMenuItem(MenuItemDTO $menuItemDTO): MenuItem
    {
        return MenuItem::create([
            'name' => $menuItemDTO->name,
            'page_id' => $menuItemDTO->pageId,
            'article_category_id' => $menuItemDTO->articleCategoryId,
            'position' => $menuItemDTO->position,
        ]);
    }

    /**
     * Update an existing menu item.
     *
     * @param MenuItem $menuItem
     * @param MenuItemDTO $menuItemDTO
     * @return MenuItem
     */
    public function updateMenuItem(MenuItem $menuItem, MenuItemDTO $menuItemDTO) 
    {
        $menuItem->update([
            'name' => $menuItemDTO->name,
            'page_id' => $menuItemDTO->pageId,
            'article_category_id' => $menuItemDTO->articleCategoryId,
            'position' => $menuItemDTO->position,
        ]);

        return $menuItem;
    }
}
