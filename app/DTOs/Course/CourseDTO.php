<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;

class CourseDTO extends BaseDTO
{
    public $title;
    public $slug;
    public $description;
    public $price;
    public $discounted_price;
    public $thumbnail_url;
    public $user_id;
    public $course_categories_id;

    public function __construct($courseData)
    {
        $this->title = $courseData['title'];
        $this->slug = $courseData['slug'];
        $this->description = $courseData['description'];
        $this->price = $courseData['price'];
        $this->discounted_price = $courseData['discounted_price'] ?? null; // Optional field
        $this->thumbnail_url = $courseData['thumbnail_url'] ?? null; // Optional field
        $this->user_id = $courseData['user_id']; // Ensure this is passed in or authenticated
        $this->course_categories_id = $courseData['course_categories_id'];
    }
}
