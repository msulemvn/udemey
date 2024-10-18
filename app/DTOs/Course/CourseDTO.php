<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;

class CourseDTO extends BaseDTO
{
    public string $title;
    public string $slug;
    public string $description;
    public string $short_description;
    public float $price;
    public ?float $discounted_price;
    public string $thumbnail;
    public int $user_id;
    public int $course_categories_id;
    public string $duration;


    public function __construct($courseData)
    {


        $this->title = $courseData['title'];
        $this->slug = $courseData['slug'];
        $this->description = $courseData['description'];
        $this->short_description = $courseData['short_description'];
        $this->price = (float) $courseData['price'];
        $this->discounted_price = isset($courseData['discounted_price']) ? (float) $courseData['discounted_price'] : null;
        $this->thumbnail = $courseData['thumbnail'];
        $this->user_id = (int) $courseData['user_id'];
        $this->course_categories_id = (int) $courseData['course_categories_id'];
        $this->duration = (float) $courseData['duration'];
    }
}
