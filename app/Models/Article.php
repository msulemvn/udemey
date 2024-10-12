<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function course()
    {
        return $this->hasMany(Course::class, 'course_id');
    }
}
