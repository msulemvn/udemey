<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategorie extends Model
{
    use HasFactory;

    // One CourseCategory has many Courses
    public function course()
    {
        return $this->hasMany(Course::class, 'course_categories_id');
    }
    
}
