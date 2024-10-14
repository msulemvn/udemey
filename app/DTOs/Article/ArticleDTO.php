<?php

namespace App\DTOs\Article;

use App\DTOs\BaseDTO;

class ArticleDTO extends BaseDTO
{
    public $title;
    public $body;
    public $slug;
    public $image_url;
    public $user_id;
    public $course_id;
    public $status;

    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->body = $data['body'] ?? null;
        $this->slug = $data['slug'] ?? null;
        $this->image_url = $data['image_url'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->course_id = $data['course_id'];
        $this->status = $data['status'];
    }
}
