<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $table = 'classes'; // Since 'class' is a reserved keyword in PHP

    protected $fillable = [
        'class_name',
        'academic_year',
    ];

    protected $casts = [
        'academic_year' => 'integer',
    ];

    // Relationships
    public function sections(): HasMany
    {
        return $this->hasMany(SectionModel::class, 'class_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'class_id');
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->class_name . ' (' . $this->academic_year . ')';
    }

    public function getTotalStudentsAttribute()
    {
        return $this->students()->count();
    }

    public function getTotalSectionsAttribute()
    {
        return $this->sections()->count();
    }
}
