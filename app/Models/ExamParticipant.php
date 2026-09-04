<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamParticipant extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_participant';

    protected $fillable = [
        'id_exam',
        'nama',
        'nis_email',
        'kelas',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'id_exam', 'id_exam');
    }
}
