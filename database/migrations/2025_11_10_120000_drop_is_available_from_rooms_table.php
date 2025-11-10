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
        if (Schema::hasTable('rooms') && Schema::hasColumn('rooms', 'is_available')) {
            Schema::table('rooms', function (Blueprint $table) {
                // drop the is_available column
                $table->dropColumn('is_available');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rooms') && ! Schema::hasColumn('rooms', 'is_available')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->boolean('is_available')->default(true)->after('name');
            });
        }
    }
};
