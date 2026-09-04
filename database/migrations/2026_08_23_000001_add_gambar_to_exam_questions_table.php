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
        if (Schema::hasTable('exam_questions') && !Schema::hasColumn('exam_questions', 'gambar')) {
            Schema::table('exam_questions', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('pertanyaan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('exam_questions') && Schema::hasColumn('exam_questions', 'gambar')) {
            Schema::table('exam_questions', function (Blueprint $table) {
                $table->dropColumn('gambar');
            });
        }
    }
};
