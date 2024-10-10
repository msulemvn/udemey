<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;

class CourseIndexDTO extends BaseDTO
{
    public $id;
    public $title;
    public $description;
    public $price;
    public $discounted_price;
    public $thumbnail_url;
    public $course_categories_id;

    public function __construct($course)
    {
        $this->id = $course->id;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->price = $course->price;
        $this->discounted_price = $course->discounted_price;
        $this->thumbnail_url = $course->thumbnail_url;
        $this->course_categories_id = $course->course_categories_id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'thumbnail_url' => $this->thumbnail_url,
            'course_categories_id' => $this->course_categories_id,
        ];
    }
}
