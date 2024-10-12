<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    // A Course belongs to a Course Category
    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_categories_id');
    }


    // A Course has many Articles
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
