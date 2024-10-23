<?php

namespace App\Models;

use Illuminate\Support\Str;
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
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($courseCategory) {
            $courseCategory->slug = static::generateUniqueSlug($courseCategory->title);
        });

        static::saving(function ($courseCategory) {
            // Check if the title has changed and update the slug accordingly
            if ($courseCategory->isDirty('title')) {
                $courseCategory->slug = static::generateUniqueSlug($courseCategory->title);
            }
        });
    }


    protected static function generateUniqueSlug($title)
    {

        $slug = Str::slug($title);
        $originalSlug = $slug;

        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            // Append the number to make the slug unique
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
