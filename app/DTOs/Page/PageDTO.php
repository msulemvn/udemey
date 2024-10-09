<?php

namespace App\DTOs\Page;
use App\DTOs\BaseDTO;

class PageDTO extends BaseDTO
{
    public string $title;
    public ?string $body;

    public function __construct(string $title, ?string $body,)
    {
      $this->title = $title;
      $this->body = $body;
    }
}
