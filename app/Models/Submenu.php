<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    protected $fillable = ['menu_id', 'nama_submenu', 'url', 'urutan'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
