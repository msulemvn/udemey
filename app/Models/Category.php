<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // A category can have many course categories
    public function courseCategories()
    {
        return $this->hasMany(CourseCategory::class);
    }
}
