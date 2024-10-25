<?php

namespace App\Services\MenuItem;
use App\Dto\MenuItemDto;
use App\Repositories\MenuItemRepository;
use App\Interfaces\MenuItemInterface;

class MenuItemService implements MenuItemInterface
{
    private $repository;

    public function __construct(MenuItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function create(MenuItemDto $dto)
    {
        return $this->repository->create($dto);
    }

    public function update(MenuItemDto $dto, $id)
    {
        return $this->repository->update($dto, $id);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}