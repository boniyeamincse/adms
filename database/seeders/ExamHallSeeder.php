<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamHall;

class ExamHallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $halls = [
            [
                'hall_name' => 'Hall A - Ground Floor',
                'hall_code' => 'HA001',
                'capacity' => 60,
                'layout_rows' => 6,
                'layout_cols' => 10,
                'blocked_seats' => [[1, 1], [1, 10], [6, 1], [6, 10], [3, 5]], // Block corners and center row 3
                'hall_amenities' => [
                    'handicap_seats' => [[1, 2], [1, 9]],
                    'emergency_exits' => [[2, 1], [2, 10], [5, 1], [5, 10]]
                ]
            ],
            [
                'hall_name' => 'Hall B - First Floor',
                'hall_code' => 'HB002',
                'capacity' => 45,
                'layout_rows' => 5,
                'layout_cols' => 9,
                'blocked_seats' => [[3, 5]], // Block center
                'hall_amenities' => [
                    'handicap_seats' => [[1, 1]],
                    'emergency_exits' => [[1, 5], [5, 5]] // Front and back center
                ]
            ],
            [
                'hall_name' => 'Hall C - Auditorium',
                'hall_code' => 'HC003',
                'capacity' => 120,
                'layout_rows' => 10,
                'layout_cols' => 12,
                'blocked_seats' => [[1, 5], [1, 6], [1, 7], [1, 8]], // Block stage area
                'hall_amenities' => [
                    'handicap_seats' => [[2, 2], [2, 11], [3, 2], [3, 11]],
                    'emergency_exits' => [[1, 1], [1, 12], [6, 7], [8, 7]] // Multiple exits
                ]
            ]
        ];

        foreach ($halls as $hall) {
            ExamHall::create($hall);
        }
    }
}