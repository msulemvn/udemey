<?php
namespace App\DTOs\Page;

use App\DTOs\BaseDTO;

class PageDTO extends BaseDTO
{
    public ?string $title;
    public ?string $body;

    public function __construct($pageData)
    {
      $this->title = $pageData['title'];
      $this->body = $pageData['body'];
    }
}