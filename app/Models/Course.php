<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    // A Course belongs to a Course Category
    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class, 'course_categories_id');
    }

    // A Course has many Articles
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($course) {
            $course->slug = static::generateUniqueSlug($course->title);
        });

        static::saving(function ($course) {
            // Check if the title has changed and update the slug accordingly
            if ($course->isDirty('title')) {
                $course->slug = static::generateUniqueSlug($course->title);
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
