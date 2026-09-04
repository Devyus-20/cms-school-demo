<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_attempt';

    protected $fillable = [
        'id_exam',
        'nama_peserta',
        'nis_email',
        'kelas',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'skor_akhir',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'id_exam', 'id_exam');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'id_attempt', 'id_attempt');
    }
}
