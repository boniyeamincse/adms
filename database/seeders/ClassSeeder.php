<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['class_name' => 'Nursery', 'academic_year' => 2024],
            ['class_name' => 'Pre-Kindergarten', 'academic_year' => 2024],
            ['class_name' => 'Kindergarten', 'academic_year' => 2024],
            ['class_name' => 'Class 1', 'academic_year' => 2024],
            ['class_name' => 'Class 2', 'academic_year' => 2024],
            ['class_name' => 'Class 3', 'academic_year' => 2024],
            ['class_name' => 'Class 4', 'academic_year' => 2024],
            ['class_name' => 'Class 5', 'academic_year' => 2024],
            ['class_name' => 'Class 6', 'academic_year' => 2024],
            ['class_name' => 'Class 7', 'academic_year' => 2024],
            ['class_name' => 'Class 8', 'academic_year' => 2024],
            ['class_name' => 'Class 9', 'academic_year' => 2024],
            ['class_name' => 'Class 10', 'academic_year' => 2024],
            ['class_name' => 'Class 11', 'academic_year' => 2024],
            ['class_name' => 'Class 12', 'academic_year' => 2024],
        ];

        foreach ($classes as $class) {
            \App\Models\SchoolClass::create($class);
        }
    }
}
