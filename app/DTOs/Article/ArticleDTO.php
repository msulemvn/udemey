<?php

namespace App\DTOs\Article;

use App\DTOs\BaseDTO;

class ArticleDTO extends BaseDTO
{
    public $title;
    public $body;
    public $slug;
    public $image_path;  // Updated to match migration
    public $user_id;
    public $course_id;
    public $status;

    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->body = $data['body'] ?? null;
        $this->slug = $data['slug'] ?? null;
        $this->image_path = $data['image_path'] ?? null;  // Updated from image_url to image_path
        $this->user_id = $data['user_id'] ?? null;
        $this->course_id = $data['course_id'];
        $this->status = $data['status'];
    }
}
