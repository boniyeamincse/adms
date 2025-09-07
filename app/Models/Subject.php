<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    protected $fillable = [
        'subject_name',
        'subject_code',
        'class_id',
    ];

    // Relationships
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_subject')
            ->withTimestamps();
    }

    // Helper methods
    public function getNameWithClassAttribute()
    {
        return $this->subject_name . ' (' . $this->schoolClass->class_name . ')';
    }
}
