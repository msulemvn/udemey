<?php
namespace App\DTOs;

class UpdatePageDTO {

 protected ?string $body;

 public function __construct(?string $body)
 {
  $this->body = $body;
 }

 public function getBody()
 {
  return $this->body;
 }
}