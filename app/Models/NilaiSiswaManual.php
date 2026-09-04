<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSiswaManual extends Model
{
    use HasFactory;

    protected $table = 'nilai_siswa_manual';
    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'siswa_id',
        'nilai_uh',
        'nilai_uts',
        'nilai_uas',
    ];

    protected function casts(): array
    {
        return [
            'nilai_uh' => 'float',
            'nilai_uts' => 'float',
            'nilai_uas' => 'float',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }
}
