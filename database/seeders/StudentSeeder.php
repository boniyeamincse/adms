<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = \App\Models\SchoolClass::all();
        $studentNames = [
            'Mohammed Ashfaq', 'Fatima Khan', 'Ahmed Hassan', 'Ayesha Begum',
            'Rahman Ali', 'Sara Ahmed', 'Khalid Mehmed', 'Zainab Hussein',
            'Omar Farooq', 'Maryam Iqbal', 'Abdullah Rahman', 'Noor Fatima',
            'Ibrahim Hassan', 'Layla Ahmed', 'Salaam Khan', 'Huda Mehmed',
            'Yusuf Khattab', 'Rabiya Hussein', 'Hamza Ali', 'Aminah Farooq'
        ];

        $statuses = ['active', 'active', 'active', 'inactive'];

        foreach ($classes as $class) {
            $sections = $class->sections;
            $studentsPerSection = 8; // 2 students per section typically

            foreach ($sections as $section) {
                for ($i = 0; $i < $studentsPerSection; $i++) {
                    $name = $studentNames[array_rand($studentNames)];

                    // Generate roll number (different for each class/section)
                    $rollNumber = ($section->id * 100) + $i + 1;
                    $rollNumber = str_pad($rollNumber, 2, '0', STR_PAD_LEFT);

                    \App\Models\Student::create([
                        'name' => $name,
                        'roll_no' => $rollNumber,
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'dob' => fake()->date($format = 'Y-m-d', $max = 'now'),
                        'status' => $statuses[array_rand($statuses)],
                        'photo' => null,
                    ]);
                }
            }
        }
    }
}
