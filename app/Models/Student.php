<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['created_at', 'updated_at'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function quizInstance()
    {
        return $this->hasMany(QuizInstance::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
