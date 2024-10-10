<?php

namespace App\DTOs\Category;

use App\DTOs\BaseDTO;

class CategoryDTO extends BaseDTO
{
    public $title;
    public $slug;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->slug = $data['slug'];
    }
}
