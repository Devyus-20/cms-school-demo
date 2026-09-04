<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('ppdb_aktif')->default(false)->after('footer');
            $table->string('ppdb_tahun')->nullable()->after('ppdb_aktif');
            $table->text('ppdb_keterangan')->nullable()->after('ppdb_tahun');
            $table->string('ppdb_link_daftar')->nullable()->after('ppdb_keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ppdb_aktif', 'ppdb_tahun', 'ppdb_keterangan', 'ppdb_link_daftar']);
        });
    }
};
