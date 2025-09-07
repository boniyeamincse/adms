<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamHall extends Model
{
    use HasFactory;

    protected $fillable = [
        'hall_name', 'hall_code', 'capacity', 'layout_rows', 'layout_cols',
        'blocked_seats', 'hall_amenities'
    ];

    protected $casts = [
        'blocked_seats' => 'array',
        'hall_amenities' => 'array',
    ];

    /**
     * Get all seats for this hall.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(ExamSeat::class);
    }

    /**
     * Get available seats (not blocked).
     */
    public function availableSeats()
    {
        return $this->seats()->where('is_blocked', false);
    }

    /**
     * Get handicap seats.
     */
    public function handicapSeats()
    {
        return $this->seats()->where('is_handicap_seat', true);
    }

    /**
     * Calculate usable seats after blocking.
     */
    public function getUsableSeats()
    {
        return $this->seats()->where('is_blocked', false)->count();
    }

    /**
     * Generate seat layout based on grid configuration.
     */
    public function generateSeatLayout()
    {
        $layout = [];

        for ($row = 1; $row <= $this->layout_rows; $row++) {
            for ($col = 1; $col <= $this->layout_cols; $col++) {
                $isBlocked = $this->isPositionBlocked($row, $col);
                $layout[] = [
                    'seat_no' => $this->generateSeatNo($row, $col),
                    'row_no' => $row,
                    'col_no' => $col,
                    'is_blocked' => $isBlocked,
                    'is_handicap_seat' => $this->isHandicapSeat($row, $col),
                    'near_emergency_exit' => $this->nearEmergencyExit($row, $col),
                ];
            }
        }

        return $layout;
    }

    /**
     * Check if a position is blocked.
     */
    private function isPositionBlocked($row, $col): bool
    {
        return in_array([$row, $col], $this->blocked_seats ?? []);
    }

    /**
     * Check if position is handicap seat.
     */
    private function isHandicapSeat($row, $col): bool
    {
        $amenities = $this->hall_amenities ?? [];
        return isset($amenities['handicap_seats']) &&
               in_array([$row, $col], $amenities['handicap_seats']);
    }

    /**
     * Check if position is near emergency exit.
     */
    private function nearEmergencyExit($row, $col): bool
    {
        $amenities = $this->hall_amenities ?? [];
        return isset($amenities['emergency_exits']) &&
               in_array([$row, $col], $amenities['emergency_exits']);
    }

    /**
     * Generate seat number from row/column.
     */
    private function generateSeatNo($row, $col): string
    {
        return chr(64 + $row) . str_pad($col, 2, '0', STR_PAD_LEFT);
    }
}