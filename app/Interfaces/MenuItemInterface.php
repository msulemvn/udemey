<?php
namespace App\Interfaces;

interface MenuItemInterface 

{
    public function getAll();

    public function getById($id);

    public function create($data);

    public function update($data, $id);

    public function delete($id);
}
