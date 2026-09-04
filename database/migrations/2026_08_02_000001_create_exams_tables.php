<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Ujian (Exams)
        Schema::create('exams', function (Blueprint $table) {
            $table->id('id_exam');
            $table->string('judul');
            $table->string('mata_pelajaran');
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_menit')->default(60); // Durasi dalam menit
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('token')->nullable(); // Token konfirmasi opsional
            $table->boolean('acak_soal')->default(false);
            $table->boolean('tampilkan_nilai')->default(true);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Soal Ujian (Exam Questions)
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id('id_question');
            $table->foreignId('id_exam')->constrained('exams', 'id_exam')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('jenis', ['pilihan_ganda', 'essay'])->default('pilihan_ganda');
            $table->text('pilihan_a')->nullable();
            $table->text('pilihan_b')->nullable();
            $table->text('pilihan_c')->nullable();
            $table->text('pilihan_d')->nullable();
            $table->text('pilihan_e')->nullable();
            $table->string('kunci_jawaban')->nullable(); // A, B, C, D, E atau kata kunci
            $table->integer('bobot_nilai')->default(1);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 3. Tabel Sesi / Hasil Pengerjaan Siswa (Exam Attempts)
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id('id_attempt');
            $table->foreignId('id_exam')->constrained('exams', 'id_exam')->onDelete('cascade');
            $table->string('nama_peserta');
            $table->string('nis_email');
            $table->string('kelas');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->enum('status', ['berlangsung', 'selesai'])->default('berlangsung');
            $table->float('skor_akhir')->default(0);
            $table->timestamps();
        });

        // 4. Tabel Jawaban Siswa per Soal (Exam Answers)
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id('id_answer');
            $table->foreignId('id_attempt')->constrained('exam_attempts', 'id_attempt')->onDelete('cascade');
            $table->foreignId('id_question')->constrained('exam_questions', 'id_question')->onDelete('cascade');
            $table->text('jawaban_peserta')->nullable();
            $table->boolean('is_benar')->default(false);
            $table->float('nilai_soal')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams');
    }
};
