<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'exam_name',
        'exam_type',
        'class_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relationships
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'exam_subject')
            ->withTimestamps();
    }

    public function admitCards(): HasMany
    {
        return $this->hasMany(AdmitCard::class);
    }

    // Helper methods
    public function getExamTypeTextAttribute()
    {
        return match($this->exam_type) {
            '1st' => '1st Term Exam',
            '2nd' => '2nd Term Exam',
            '3rd' => '3rd Term Exam',
            default => 'Custom Exam'
        };
    }

    public function getTotalAdmitCardsAttribute()
    {
        return $this->admitCards()->count();
    }

    public function isActive()
    {
        $now = now();
        return $this->start_date && $this->end_date &&
               $now->between($this->start_date, $this->end_date);
    }

    public function isUpcoming()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    public function hasPassed()
    {
        return $this->end_date && $this->end_date->isPast();
    }
}
