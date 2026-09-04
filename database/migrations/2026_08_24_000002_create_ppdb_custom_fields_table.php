<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('field_key')->unique();
            $table->enum('tipe', ['text', 'number', 'textarea', 'select', 'checkbox', 'date'])->default('text');
            $table->text('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_custom_fields');
    }
};
