<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_exam';

    protected $fillable = [
        'judul',
        'mata_pelajaran',
        'tipe_ujian',
        'deskripsi',
        'durasi_menit',
        'kkm',
        'waktu_mulai',
        'waktu_selesai',
        'token',
        'acak_soal',
        'tampilkan_nilai',
        'aktif',
        'batasi_peserta',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal' => 'boolean',
        'tampilkan_nilai' => 'boolean',
        'aktif' => 'boolean',
        'batasi_peserta' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class, 'id_exam', 'id_exam')->orderBy('urutan');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'id_exam', 'id_exam');
    }

    public function participants()
    {
        return $this->hasMany(ExamParticipant::class, 'id_exam', 'id_exam');
    }
}
