<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'started_at',
        'expires_at',
        'status',
    ];
    protected $dates = ['deleted_at'];  // Specify the soft delete column


    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
