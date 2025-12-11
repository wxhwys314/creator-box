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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['blog', 'image', 'file'])->default('blog');
            $table->string('cover', 500)->nullable();
            $table->string('title', 255);
            $table->text('content_text')->nullable();
            $table->json('media_assets')->nullable();
            $table->enum('visibility', ['all', 'supporters'])->default('all');
            $table->integer('supporter_min_amount')->default(0);
            $table->enum('comment_permission', ['all', 'supporters', 'none'])->default('all');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('likes_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
