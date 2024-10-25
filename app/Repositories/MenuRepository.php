<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Interfaces\MenuInterface;

class MenuRepository implements MenuInterface
{
    public function getAll()
    {
        return Menu::all();
    }

    public function getById($id)
    {
        return Menu::find($id);
    }

    public function create($data)
    {
        return Menu::create($data);
    }

    public function update($data, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return false;
        }
        $menu->update($data->toArray());
        return $menu;
    }

    public function delete($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return false;
        }
        $menu->delete();
        return true;
    }
}