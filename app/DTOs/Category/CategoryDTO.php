<?php

namespace App\DTOs\Category;

use App\DTOs\BaseDTO;

class CategoryDTO extends BaseDTO
{
    public string $title;

    public function __construct($data)
    {
        $this->title = $data['title'];
    }
}
