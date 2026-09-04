<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_answer';

    protected $fillable = [
        'id_attempt',
        'id_question',
        'jawaban_peserta',
        'is_benar',
        'nilai_soal',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'id_attempt', 'id_attempt');
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'id_question', 'id_question');
    }
}
