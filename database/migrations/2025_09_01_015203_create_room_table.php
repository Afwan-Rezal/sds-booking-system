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

        Schema::create('room_metadatas', function (Blueprint $table) {
            $table->id();
            $table->integer('capacity');
            $table->string('type'); // Lab, Lecture, Turorial Room
            $table->string('location'); // Floor
            $table->text('description')->nullable();
        });
        
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_available')->default(true);
            $table->foreignId('room_metadata_id')->nullable()->constrained('room_metadatas');
        });

        // Schema::create('room_availabilities', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        //     $table->string('day_of_week'); // e.g., Monday, Tuesday
        //     $table->time('start_time');
        //     $table->time('end_time');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_metadatas');
    }
};
