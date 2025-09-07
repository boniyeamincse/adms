<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Fee extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
    }

    public function scopeForMonth($query, $month, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
    }

    // Helper Methods
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isOverdue()
    {
        return $this->status === 'overdue';
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->save();
    }

    public function markAsOverdue()
    {
        $this->status = 'overdue';
        $this->save();
    }

    public function getFormattedAmountAttribute()
    {
        return '৳' . number_format($this->amount, 2);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'paid' => 'bg-green-500 text-white',
            'pending' => 'bg-yellow-500 text-white',
            'overdue' => 'bg-red-500 text-white',
            default => 'bg-gray-500 text-white'
        };
    }
}
