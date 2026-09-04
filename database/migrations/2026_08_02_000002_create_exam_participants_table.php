<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom batasi_peserta pada tabel exams
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('batasi_peserta')->default(false)->after('aktif');
        });

        // 2. Tabel Peserta Ujian Terdaftar (Exam Participants Whitelist)
        Schema::create('exam_participants', function (Blueprint $table) {
            $table->id('id_participant');
            $table->foreignId('id_exam')->constrained('exams', 'id_exam')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('nis_email');
            $table->string('kelas')->nullable();
            $table->timestamps();

            $table->unique(['id_exam', 'nis_email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_participants');
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('batasi_peserta');
        });
    }
};
