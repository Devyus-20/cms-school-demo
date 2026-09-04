<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $primaryKey = 'id_gallery';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'tanggal',
        'aktif',
    ];

    protected $casts = [
        'aktif'   => 'boolean',
        'tanggal' => 'date',
    ];
}
