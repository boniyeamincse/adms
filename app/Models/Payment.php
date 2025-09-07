<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'payment_date',
        'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('payment_date', Carbon::now()->month)
                    ->whereYear('payment_date', Carbon::now()->year);
    }

    public function scopeForMonth($query, $month, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return $query->whereMonth('payment_date', $month)
                    ->whereYear('payment_date', $year);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Helper Methods
    public function getFormattedAmountAttribute()
    {
        return '৳' . number_format($this->amount, 2);
    }

    public function getPaymentMethodIconAttribute()
    {
        return match($this->payment_method) {
            'cash' => '💵',
            'bank_transfer' => '🏦',
            'credit_card' => '💳',
            'check' => '📝',
            default => '💰'
        };
    }

    public function getFormattedPaymentDateAttribute()
    {
        return $this->payment_date ? $this->payment_date->format('d/m/Y') : 'N/A';
    }
}
