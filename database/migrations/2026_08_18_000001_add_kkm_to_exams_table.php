<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('exams', 'kkm')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->integer('kkm')->default(75)->after('durasi_menit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('exams', 'kkm')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn('kkm');
            });
        }
    }
};
