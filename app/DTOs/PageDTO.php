<?php

namespace App\DTOs;

class PageDTO
{
  public string $title;
  public ?string $body;
  // public string $slug;

  public function __construct(string $title, ?string $body,)
  {
      $this->title = $title;
      $this->body = $body;
      // $this->slug = $slug;
  }
}
