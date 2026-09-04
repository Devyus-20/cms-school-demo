<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id('id_post');
            $table->foreignId('category_id')->nullable()->constrained('categories', 'id_category')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users', 'id_user')->nullOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('isi')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('status')->default('draft');
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts', 'id_post')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags', 'id_tag')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('posts');
    }
};
