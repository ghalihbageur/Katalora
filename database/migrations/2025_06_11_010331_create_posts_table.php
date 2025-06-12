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
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body');
            $table->string('thumbnail')->nullable();

            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('saves_count')->default(0);
            $table->unsignedBigInteger('shares_count')->default(0);
            $table->unsignedBigInteger('views')->default(0);

            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');

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
