<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['nama_menu', 'icon', 'urutan', 'status'];

    public function submenus()
    {
        return $this->hasMany(Submenu::class, 'menu_id');
    }
}
