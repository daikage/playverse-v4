<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->index();
            $table->enum('type', ['game', 'comic'])->index();
            $table->json('platforms')->nullable(); // ["windows","mac","android","ios"]
            $table->string('asset_path')->nullable(); // root path prefix for assets
            $table->json('pages')->nullable(); // ["comics/123/pages/0001.webp", ...]
            $table->boolean('published')->default(false);
            $table->timestamps();

            $table->unique(['author_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
