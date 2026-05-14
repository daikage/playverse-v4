<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->uuid('studio_uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique(); // studio identifier in URL
            $table->enum('verification_status', ['pending', 'approved', 'suspended'])->default('pending')->index();
            $table->uuid('playverse_key')->nullable()->unique();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('logo_path')->nullable();
            $table->json('links')->nullable(); // portfolio links
            $table->text('mission_statement')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
