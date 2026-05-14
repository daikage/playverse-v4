<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('authors')) {
            // If you don't have the authors table yet, create it first or run the earlier migration.
            // aborting here avoids breaking deploys.
            return;
        }

        Schema::table('authors', function (Blueprint $table) {
            if (! Schema::hasColumn('authors', 'verification_status')) {
                $table->enum('verification_status', ['pending', 'approved', 'suspended'])
                    ->default('pending')
                    ->index()
                    ->after('slug');
            }

            if (! Schema::hasColumn('authors', 'playverse_key')) {
                $table->uuid('playverse_key')->nullable()->unique()->after('verification_status');
            }

            if (! Schema::hasColumn('authors', 'owner_user_id')) {
                $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete()->after('playverse_key');
            }

            if (! Schema::hasColumn('authors', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('owner_user_id');
            }

            if (! Schema::hasColumn('authors', 'links')) {
                $table->json('links')->nullable()->after('logo_path');
            }

            if (! Schema::hasColumn('authors', 'mission_statement')) {
                $table->text('mission_statement')->nullable()->after('links');
            }

            if (! Schema::hasColumn('authors', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('mission_statement');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('authors')) {
            return;
        }

        Schema::table('authors', function (Blueprint $table) {
            if (Schema::hasColumn('authors', 'verification_status')) {
                $table->dropIndex(['verification_status']);
                $table->dropColumn('verification_status');
            }
            if (Schema::hasColumn('authors', 'playverse_key')) {
                $table->dropUnique(['playverse_key']);
                $table->dropColumn('playverse_key');
            }
            if (Schema::hasColumn('authors', 'owner_user_id')) {
                $table->dropConstrainedForeignId('owner_user_id');
            }
            if (Schema::hasColumn('authors', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
            if (Schema::hasColumn('authors', 'links')) {
                $table->dropColumn('links');
            }
            if (Schema::hasColumn('authors', 'mission_statement')) {
                $table->dropColumn('mission_statement');
            }
            if (Schema::hasColumn('authors', 'suspended_at')) {
                $table->dropColumn('suspended_at');
            }
        });
    }
};
