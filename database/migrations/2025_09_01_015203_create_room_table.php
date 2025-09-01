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
        
        Schema::create('room', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('room_metadata_id')->nullable()->constrained('room_metadata');
            // $table->timestamps();
        });
        
        Schema::create('room_metadata', function (Blueprint $table) {
            $table->id();
            $table->integer('capacity');
            $table->string('type'); // Lab, Lecture, Turorial Room
            $table->string('location'); // Floor
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room');
        Schema::dropIfExists('room_metadata');
    }
};
