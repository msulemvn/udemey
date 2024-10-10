<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;

class CourseDeleteDTO extends BaseDTO
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
