<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama_lengkap',
        'email',
        'jenis_kelamin',
        'kelas',
        'tahun_masuk',
        'status',
        'telepon',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiSiswa::class, 'siswa_id', 'id_siswa');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'siswa_id', 'id_siswa');
    }

    public function nilaiManual()
    {
        return $this->hasOne(NilaiSiswaManual::class, 'siswa_id', 'id_siswa');
    }
}
