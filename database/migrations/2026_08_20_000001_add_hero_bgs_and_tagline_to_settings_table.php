<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('hero_tagline')->nullable();
            $table->text('hero_bgs')->nullable();
            $table->string('hero_btn1_text')->nullable();
            $table->string('hero_btn1_link')->nullable();
            $table->string('hero_btn2_text')->nullable();
            $table->string('hero_btn2_link')->nullable();
            $table->string('hero_btn3_text')->nullable();
            $table->string('hero_btn3_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_tagline',
                'hero_bgs',
                'hero_btn1_text',
                'hero_btn1_link',
                'hero_btn2_text',
                'hero_btn2_link',
                'hero_btn3_text',
                'hero_btn3_link',
            ]);
        });
    }
};
