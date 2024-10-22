<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically create the slug when creating a new article
        static::creating(function ($article) {
            $article->slug = static::generateUniqueSlug($article->title);
        });

        static::saving(function ($article) {
            // Check if the title has changed and update the slug accordingly
            if ($article->isDirty('title')) {
                $article->slug = static::generateUniqueSlug($article->title);
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
