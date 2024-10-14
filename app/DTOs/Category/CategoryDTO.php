<?php

namespace App\DTOs\Category;

use App\DTOs\BaseDTO;

class CategoryDTO extends BaseDTO
{
    public string $title;
    public string $slug;

    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->slug = $data['slug'];
    }
}
