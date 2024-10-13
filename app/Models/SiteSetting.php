<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [

        'site_title',
        'logo_path',
        'copyright',
    ];

    protected $hidden =[

        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
}
