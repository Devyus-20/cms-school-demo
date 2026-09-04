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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('info_pendaftaran_pembelajaran_judul')->nullable();
            $table->text('info_pendaftaran_pembelajaran_subjudul')->nullable();
            $table->text('info_pendaftaran_pembelajaran_konten')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'info_pendaftaran_pembelajaran_judul',
                'info_pendaftaran_pembelajaran_subjudul',
                'info_pendaftaran_pembelajaran_konten',
            ]);
        });
    }
};
