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

        static::creating(function ($page){

            $page->slug = static::generateUniqueSlug($page->title);
        });

        static::saving(function ($page) {

            if ($page->isDirty('title')) {
            
                $page->slug = static::generateUniqueSlug($page->title);
            }
        });
    }

    protected static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {

            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        return $slug;
    }
    
    protected $fillable = [
        'title',
        'body',
    ];
}
