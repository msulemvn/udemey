<?php

namespace App\DTOs;

class MenuItemDTO
{
  public string $name;
  public ?int $pageId;
  public ?int $articleCategoryId;
  public int $position;

  public function __construct(string $name, ?int $pageId, ?int $articleCategoryId, int $position)
  {
      $this->name = $name;
      $this->pageId = $pageId;
      $this->articleCategoryId = $articleCategoryId;
      $this->position = $position;
  }
}
