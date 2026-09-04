<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $primaryKey = 'id_page';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Daftar slug halaman profil sekolah yang valid
     */
    public static array $profilSlugs = [
        'sejarah'         => 'Sejarah',
        'visi-dan-misi'   => 'Visi dan Misi',
        'prestasi'        => 'Prestasi',
        'ekstrakurikuler' => 'Ekstrakurikuler',
        'guru-dan-staff'  => 'Guru dan Staff',
        'fasilitas'       => 'Fasilitas',
    ];
}
