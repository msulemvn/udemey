<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    
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
