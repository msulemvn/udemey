<?php

namespace App\DTOs\CourseCategory;

use App\DTOs\BaseDTO;



class CourseCategoryDTO extends BaseDTO
{
    public int $category_id;
    public string $title;

    public function __construct($data)
    {

        $this->category_id = $data['category_id'];
        $this->title = $data['title'];
    }
}
