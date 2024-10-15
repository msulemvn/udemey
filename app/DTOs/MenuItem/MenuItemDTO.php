<?php

namespace App\DTOs\MenuItem;
use App\DTOs\BaseDTO;
class MenuItemDTO extends BaseDTO
{
    public string $name;
    public ?int $page_id;
    public ?int $article_category_id;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->page_id = $data['page_id'] ?? null;
        $this->article_category_id = $data['article_category_id'] ?? null;
    }
}