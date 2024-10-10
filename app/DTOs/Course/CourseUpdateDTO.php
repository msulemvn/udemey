<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;

class CourseUpdateDTO extends BaseDTO
{
    public $title;
    public $slug;
    public $description;
    public $price;
    public $discounted_price;
    public $thumbnail_url;
    public $course_categories_id;

    public function __construct($data, $slug)
    {
        $this->title = $data['title'];
        $this->slug = $slug;
        $this->description = $data['description'];
        $this->price = $data['price'];
        $this->discounted_price = $data['discounted_price'] ?? null;
        $this->thumbnail_url = $data['thumbnail_url'] ?? null;
        $this->course_categories_id = $data['course_categories_id'];
    }

    // Override toArray with the same signature as BaseDTO if needed
    public function toArray(): array
    {
        return parent::toArray(); // Use parent's generic toArray() if it works
    }
}
