<?php

namespace App\DTOs;

class BaseDTO
{
    public function toArray(): array
    {
        return (array) $this;
    }
}
