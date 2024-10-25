<?php

namespace App\DTOs\MenuItem;

class MenuItemDTO
{
  public string $title;
  public int $order;
  public bool $is_active;
  public int $menu_id;
  public ?int $page_id;

  public function __construct($data)
 {
   $this->title = $data['title'];
   $this->order = $data['order'];
   $this->is_active = $data['is_active'];
   $this->menu_id = $data['menu_id'];
   $this->page_id = $data['page_id'];
 }
}