<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan tipe_ujian pada tabel exams jika belum ada
        if (Schema::hasTable('exams') && !Schema::hasColumn('exams', 'tipe_ujian')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->enum('tipe_ujian', ['uh', 'uts', 'uas', 'lainnya'])->default('uh')->after('mata_pelajaran');
            });
        }

        // 2. Tabel Nilai Manual Siswa (UH, UTS, UAS per siswa)
        if (!Schema::hasTable('nilai_siswa_manual')) {
            Schema::create('nilai_siswa_manual', function (Blueprint $table) {
                $table->id('id_nilai');
                $table->unsignedBigInteger('siswa_id')->unique();
                $table->decimal('nilai_uh', 5, 2)->nullable();
                $table->decimal('nilai_uts', 5, 2)->nullable();
                $table->decimal('nilai_uas', 5, 2)->nullable();
                $table->timestamps();

                $table->foreign('siswa_id')->references('id_siswa')->on('siswa')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa_manual');

        if (Schema::hasTable('exams') && Schema::hasColumn('exams', 'tipe_ujian')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn('tipe_ujian');
            });
        }
    }
};
