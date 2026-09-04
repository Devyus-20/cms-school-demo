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
        // 1. Tabel Data Siswa
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nis', 50)->unique();
            $table->string('nisn', 50)->nullable()->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('kelas', 100);
            $table->string('tahun_masuk', 10)->default('2026');
            $table->enum('status', ['pending_register', 'aktif', 'alumni', 'non_aktif'])->default('pending_register');
            $table->string('telepon', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('set null');
        });

        // 2. Tabel Presensi Siswa
        Schema::create('presensi_siswa', function (Blueprint $table) {
            $table->id('id_presensi');
            $table->unsignedBigInteger('siswa_id');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('siswa_id')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });

        // 3. Tabel Tugas / Assignments
        Schema::create('tugas', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('kelas', 100);
            $table->string('mata_pelajaran', 100);
            $table->dateTime('deadline');
            $table->string('file_lampiran')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('set null');
        });

        // 4. Tabel Pengumpulan Tugas
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->unsignedBigInteger('tugas_id');
            $table->unsignedBigInteger('siswa_id');
            $table->string('file_tugas')->nullable();
            $table->text('jawaban_teks')->nullable();
            $table->dateTime('tanggal_kumpul');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan_guru')->nullable();
            $table->timestamps();

            $table->foreign('tugas_id')->references('id_tugas')->on('tugas')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('presensi_siswa');
        Schema::dropIfExists('siswa');
    }
};
