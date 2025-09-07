<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSeatAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_seat_id', 'admit_card_id', 'is_present', 'assigned_at'
    ];

    protected $casts = [
        'is_present' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the seat for this assignment.
     */
    public function examSeat(): BelongsTo
    {
        return $this->belongsTo(ExamSeat::class);
    }

    /**
     * Get the admit card for this assignment.
     */
    public function admitCard(): BelongsTo
    {
        return $this->belongsTo(AdmitCard::class);
    }

    /**
     * Mark student as present (for verification).
     */
    public function markPresent($isPresent = true)
    {
        $this->is_present = $isPresent;
        return $this->save();
    }

    /**
     * Get student name for display.
     */
    public function getStudentName()
    {
        return $this->admitCard ? $this->admitCard->student->name : 'Unknown';
    }

    /**
     * Get student roll number.
     */
    public function getStudentRollNo()
    {
        return $this->admitCard ? $this->admitCard->student->roll_no : '';
    }

    /**
     * Get student's class for display.
     */
    public function getStudentClass()
    {
        return $this->admitCard && $this->admitCard->student->schoolClass
            ? $this->admitCard->student->schoolClass->class_name
            : '';
    }

    /**
     * Get student photo URL if available.
     */
    public function getStudentPhotoUrl()
    {
        $student = $this->admitCard->student ?? null;
        return $student && $student->photo ? asset('storage/' . $student->photo) : null;
    }
}