<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'exam_hall_id', 'seat_no', 'row_no', 'col_no',
        'is_blocked', 'is_handicap_seat', 'near_emergency_exit'
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'is_handicap_seat' => 'boolean',
        'near_emergency_exit' => 'boolean',
    ];

    /**
     * Get the exam for this seat.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the hall for this seat.
     */
    public function examHall(): BelongsTo
    {
        return $this->belongsTo(ExamHall::class);
    }

    /**
     * Get the seat assignment (if any).
     */
    public function assignment(): HasOne
    {
        return $this->hasOne(ExamSeatAssignment::class);
    }

    /**
     * Check if seat is available for assignment.
     */
    public function isAvailable(): bool
    {
        return !$this->is_blocked && !$this->assignment;
    }

    /**
     * Check if seat is occupied.
     */
    public function isOccupied(): bool
    {
        return $this->assignment && $this->assignment->admitCard;
    }

    /**
     * Get student assigned to this seat.
     */
    public function getAssignedStudent()
    {
        return $this->assignment ? $this->assignment->admitCard->student : null;
    }

    /**
     * Check if an admit card can be assigned to this seat based on constraints.
     */
    public function canAssignAdmitCard(AdmitCard $admitCard): bool
    {
        if ($this->is_blocked || $this->isOccupied()) {
            return false;
        }

        // handicap check
        if ($this->is_handicap_seat && !$admitCard->student->needs_handicap) {
            return false; // handicap seats only for handicap students
        }

        return true;
    }

    /**
     * Get surrounding seats within minimum distance for integrity checks.
     */
    public function getSurroundingSeats($distance = 1)
    {
        return ExamSeat::where('exam_id', $this->exam_id)
                        ->where(function ($query) use ($distance) {
                            $rowRange = [$this->row_no - $distance, $this->row_no + $distance];
                            $colRange = [$this->col_no - $distance, $this->col_no + $distance];

                            $query->whereBetween('row_no', $rowRange)
                                  ->whereBetween('col_no', $colRange)
                                  ->whereNot('id', $this->id);
                        })->with('examHall')->get();
    }
}