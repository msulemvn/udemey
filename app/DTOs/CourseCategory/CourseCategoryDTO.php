<?php

use App\DTOs\BaseDTO;

class CourseCategoryDTO extends BaseDTO
{
    public int $category_id;
    public string $title;
    public string $slug;

    public function __construct(array $data)
    {

        $this->category_id = $data['category_id'];
        $this->title = $data['title'];
        $this->slug = $data['slug'];
    }
}
