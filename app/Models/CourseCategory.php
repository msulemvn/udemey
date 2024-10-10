<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    // One CourseCategory has many Courses
    public function course()
    {
        return $this->hasMany(Course::class, 'course_categories_id');
    }
    // One CourseCategory belongs to one Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
