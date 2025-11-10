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
        Schema::table('room_metadatas', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false)->after('description');
            $table->text('blocked_reason')->nullable()->after('is_blocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_metadatas', function (Blueprint $table) {
            $table->dropColumn(['blocked_reason', 'is_blocked']);
        });
    }
};

