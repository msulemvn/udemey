<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;
    protected $table = 'menu_items';
    protected $guarded = [];


    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
