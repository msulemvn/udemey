<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function courseCategories()
    {
        return $this->hasMany(CourseCategory::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = static::generateUniqueSlug($category->title);
        });

        static::saving(function ($category) {
            // Check if the title has changed and update the slug accordingly
            if ($category->isDirty('title')) {
                $category->slug = static::generateUniqueSlug($category->title);
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
