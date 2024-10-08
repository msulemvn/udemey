<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courses extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title', 
        'slug', 
        'description', 
        'price', 
        'discounted_price', 
        'thumbnail_url', 
        'user_id'
    ];
   // A Course belongs to a Course Category
   public function category()
   {
       return $this->belongsTo(CourseCategories::class, 'category_id');
   }

   // A Course has many Articles
   public function articles()
   {
       return $this->hasMany(Articles::class);
   }
}
