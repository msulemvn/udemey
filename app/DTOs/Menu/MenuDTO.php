<?php
namespace App\DTOs\Menu;

use App\DTOs\BaseDTO;

class MenuDTO extends BaseDTO
{
 public string $name;

 public function __construct($data)
 {
   $this->name = $data['name'];
 }

}