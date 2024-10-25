<?php
namespace App\Repositories;

use App\DTOs\MenuItem\MenuItemDTO;
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

    public function create($data)
    {
        return MenuItem::create($data);
    }

    public function update($data, $id)
    {
        $menuItem = MenuItem::find($id);
        if (!$menuItem) {
            return false;
        }
        $menuItem->update($data);
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