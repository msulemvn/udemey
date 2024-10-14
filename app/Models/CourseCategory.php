<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

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
