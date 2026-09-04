<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_tugas';
    protected $primaryKey = 'id_pengumpulan';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'file_tugas',
        'jawaban_teks',
        'tanggal_kumpul',
        'nilai',
        'catatan_guru',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kumpul' => 'datetime',
            'nilai' => 'float',
        ];
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id', 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }
}
