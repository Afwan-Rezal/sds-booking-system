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
            $table->boolean('admin_can_book')->default(false)->after('description');
            $table->boolean('staff_can_book')->default(false)->after('admin_can_book');
            $table->boolean('student_can_book')->default(false)->after('staff_can_book');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_metadatas', function (Blueprint $table) {
            $table->dropColumn(['admin_can_book', 'staff_can_book', 'student_can_book']);
        });
    }
};
