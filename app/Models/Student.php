<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'name',
        'roll_no',
        'class_id',
        'section_id',
        'dob',
        'status',
        'photo',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // Relationships
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(SectionModel::class, 'section_id');
    }

    public function admitCards(): HasMany
    {
        return $this->hasMany(AdmitCard::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForMonth($query, $month, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function canGetAdmitCard()
    {
        $pendingFees = $this->fees()->where('status', 'pending')->count();
        return $pendingFees === 0 && $this->isActive();
    }
}
