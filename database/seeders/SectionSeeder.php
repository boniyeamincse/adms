<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = \App\Models\SchoolClass::all();

        foreach ($classes as $class) {
            $sections = ['A', 'B'];

            // Add C section for higher classes
            if (!in_array($class->class_name, ['Nursery', 'Pre-Kindergarten', 'Kindergarten'])) {
                $sections[] = 'C';
            }

            foreach ($sections as $sectionName) {
                \App\Models\SectionModel::create([
                    'section_name' => $sectionName,
                    'class_id' => $class->id,
                ]);
            }
        }
    }
}
