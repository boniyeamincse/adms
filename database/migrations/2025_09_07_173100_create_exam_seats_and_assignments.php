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
        Schema::create('exam_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('exam_hall_id')->constrained('exam_halls');
            $table->string('seat_no')->unique();
            $table->integer('row_no')->unsigned();
            $table->integer('col_no')->unsigned();
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_handicap_seat')->default(false);
            $table->boolean('near_emergency_exit')->default(false);
            $table->timestamps();

            $table->unique(['exam_id', 'seat_no']);
            $table->unique(['exam_hall_id', 'row_no', 'col_no'], 'unique_hall_position');
        });

        Schema::create('exam_seat_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_seat_id')->constrained('exam_seats')->onDelete('cascade');
            $table->foreignId('admit_card_id')->constrained('admit_cards');
            $table->boolean('is_present')->default(false); // For verification status
            $table->timestamp('assigned_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->unique(['exam_seat_id'], 'unique_seat_assignment');
            $table->unique(['admit_card_id'], 'unique_student_assignment');
        });

        Schema::table('admit_cards', function (Blueprint $table) {
            $table->integer('assigned_seat_row')->nullable();
            $table->integer('assigned_seat_col')->nullable();
            $table->string('assigned_hall_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_seat_assignments');
        Schema::dropIfExists('exam_seats');

        Schema::table('admit_cards', function (Blueprint $table) {
            $table->dropColumn(['assigned_seat_row', 'assigned_seat_col', 'assigned_hall_name']);
        });
    }
};