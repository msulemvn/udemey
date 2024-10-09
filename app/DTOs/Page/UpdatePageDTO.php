<?php
namespace App\DTOs\Page;
use App\DTOs\BaseDTO;

class UpdatePageDTO extends BaseDTO
{

  public ?string $body;

  public function __construct(?string $body)
  {
    $this->body = $body;
  }

  // public function getBody()
  // {
  //   return $this->body;
  // }

}