<?php

namespace App\Models;

use App\Models\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Automatically create the slug when creating a new page
        static::creating(function ($menu) {
            $menu->slug = static::generateUniqueSlug($menu->name);
        });

        static::saving(function ($menu) {
            // Check if the name has changed and update the slug accordingly
            if ($menu->isDirty('name')) {
                $menu->slug = static::generateUniqueSlug($menu->name);
            }
        });
    }

    protected static function generateUniqueSlug($name)
    {
       
        $slug = Str::slug($name);
        $originalSlug = $slug;

        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            // Append the number to make the slug unique
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    
    public function menuItems()
    {
       return $this->hasMany(MenuItem::class);
    }

}
