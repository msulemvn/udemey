<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;

class CourseUpdateDTO extends BaseDTO
{
    public string $title;
    public string $slug;
    public string $description;
    public array $short_description;
    public float $price;
    public ?float $discounted_price;
    public ?string $thumbnail_url;
    public int $course_categories_id;
    public string $duration;


    public function __construct($data, $slug)
    {
        $this->title = $data['title'];
        $this->slug = $slug;
        $this->description = $data['description'];
        $this->short_description = $data['short_description'];
        $this->price = (float)$data['price'];
        $this->discounted_price = isset($data['discounted_price']) ? (float)$data['discounted_price'] : null;
        $this->thumbnail_url = $data['thumbnail_url'] ?? null;
        $this->course_categories_id = (int)$data['course_categories_id'];
        $this->duration = $data['duration'];
    }
}
