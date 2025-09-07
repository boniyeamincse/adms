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
        Schema::create('exam_halls', function (Blueprint $table) {
            $table->id();
            $table->string('hall_name');
            $table->string('hall_code')->unique();
            $table->integer('capacity')->unsigned();
            $table->integer('layout_rows')->unsigned();
            $table->integer('layout_cols')->unsigned();
            $table->json('blocked_seats')->nullable(); // Array of blocked seat positions
            $table->json('hall_amenities')->nullable(); // Handicap seats, emergency exits, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_halls');
    }
};