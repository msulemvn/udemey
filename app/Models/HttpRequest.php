<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HttpRequest extends Model
{
    use HasFactory;
    protected $casts = [
        'payload' => 'collection'
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function error_log()
    {
        return $this->hasOne(ErrorLog::class);
    }
}
