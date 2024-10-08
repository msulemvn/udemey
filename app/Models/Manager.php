<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Manager extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = ['created_at', 'updated_at'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
