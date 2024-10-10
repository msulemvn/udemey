<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'discounted_price',
        'thumbnail_url',
        'user_id',
        'course_categories_id'
    ];

   // A Course belongs to a Course Category
   public function category()
   {
       return $this->belongsTo(CourseCategory::class, 'course_categories_id');
   }


    // A Course has many Articles
    public function article()
    {
        return $this->hasMany(Article::class);
    }
}
