<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        // Automatically create the slug when creating a new page
        static::creating(function ($page)
        {
            // Generate the slug from the title if it's not already set
            $page->slug = Str::slug($page->title);
        });

        static::saving(function ($page) {
            // Check if the title has changed and update the slug accordingly
            if ($page->isDirty('title')) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    // protected $guarded = [
    //     'slug'
    // ];
    protected $fillable = [
        'title',
        'body',
    ];
}
