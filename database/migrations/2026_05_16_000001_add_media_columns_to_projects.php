<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Store uploaded screenshots and videos as JSON arrays of storage paths
            if (! Schema::hasColumn('projects', 'screenshots')) {
                $table->json('screenshots')->nullable();
            }
            if (! Schema::hasColumn('projects', 'videos')) {
                $table->json('videos')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'screenshots')) {
                $table->dropColumn('screenshots');
            }
            if (Schema::hasColumn('projects', 'videos')) {
                $table->dropColumn('videos');
            }
        });
    }
};
