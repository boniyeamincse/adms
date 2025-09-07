<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmitCard extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'seat_no',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->examWithRelationships();
    }

    private function examWithRelationships(): BelongsTo
    {
        return $this->belongsTo(Exam::class)->with(['schoolClass', 'subjects']);
    }

    // Helper methods
    public function generateSeatNumber()
    {
        // Auto-generate seat number based on student roll and class
        if ($this->student && $this->exam && !$this->seat_no) {
            $examPrefix = strtoupper(substr($this->exam->exam_type, 0, 1));
            $classId = $this->exam->schoolClass->id;
            $studentRoll = str_pad($this->student->roll_no ?? '0', 3, '0', STR_PAD_LEFT);

            $this->seat_no = $examPrefix . $classId . $studentRoll;
            $this->save();
        }
    }

    public function generatePDF()
    {
        $data = [
            'admit_card' => $this->load(['student.schoolClass', 'student.section', 'exam.subjects']),
            'exam_subjects' => $this->exam->subjects,
            'school_info' => [
                'name' => 'Your School Name',
                'address' => 'School Address',
                'phone' => 'School Phone',
                'logo' => asset('images/school-logo.png')
            ]
        ];

        $pdf = Pdf::loadView('admit-cards.pdf', $data);
        return $pdf->download('admit_card_' . $this->student->name . '.pdf');
    }

    public function isValid()
    {
        return $this->exam && $this->exam->isActive() && $this->student->isActive();
    }

    protected static function booted()
    {
        static::creating(function ($admitCard) {
            $admitCard->generated_at = now();
        });
    }
}
