<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionModel extends Model
{
    protected $table = 'sections'; // Since the default created model doesn't auto-detect this

    protected $fillable = [
        'section_name',
        'class_id',
    ];

    // Relationships
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'section_id');
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->schoolClass->class_name . ' - ' . $this->section_name;
    }

    public function getTotalStudentsAttribute()
    {
        return $this->students()->count();
    }
}
