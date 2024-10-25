<?php

namespace App\DTOs\MenuItem;

use App\DTOs\BaseDTO;

class MenuItemDTO extends BaseDTO
{
  public ?string $title;
  public ?int $order;
  public ?bool $is_active;
  public ?int $menu_id;
  public ?int $page_id;

  public function __construct($data)
 {
   $this->title = $data['title'] ?? null;
   $this->order = $data['order'] ?? null;
   $this->is_active = $data['is_active'] ?? null;
   $this->menu_id = $data['menu_id'] ?? null;
   $this->page_id = $data['page_id'] ?? null;
 }
}