<?php

namespace App\Repositories;

use App\DTOs\MenuItem\MenuItemDTO;
use App\Models\Menu;
use App\Interfaces\MenuItemInterface;
use App\Models\MenuItem;

class MenuItemRepository implements MenuItemInterface
{
    public function getAll()
    {
        return MenuItem::all();
    }

    public function getById($id)
    {
        return MenuItem::find($id);
    }

    public function create(MenuItemDTO $dto)
    {
        return MenuItem::create($dto->toArray());
    }

    public function update(MenuItemDTO $dto, $id)
    {
        $menuItem = $this->getById($id);
        if (!$menuItem) {
            return null;
        }
        $menuItem->update($dto->toArray());
        return $menuItem;
    }

    public function delete($id)
    {
        $menuItem = $this->getById($id);
        if (!$menuItem) {
            return null;
        }
        $menuItem->delete();
        return true;
    }
}