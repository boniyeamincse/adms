<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\SchoolClass;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define subjects for each class with subject codes
        $subjectsByGrade = [
            // Class 1-5: Primary level subjects
            ['classes' => ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'], 'subjects' => [
                ['name' => 'English', 'code' => 'ENG'],
                ['name' => 'Mathematics', 'code' => 'MAT'],
                ['name' => 'Science', 'code' => 'SCI'],
                ['name' => 'Social Studies', 'code' => 'SOC'],
                ['name' => 'Bengali', 'code' => 'BEN'],
                ['name' => 'Islamic Studies', 'code' => 'ISL'],
                ['name' => 'Arts & Crafts', 'code' => 'ART'],
                ['name' => 'Physical Education', 'code' => 'PED'],
            ]],

            // Class 6-8: Middle school subjects
            ['classes' => ['Class 6', 'Class 7', 'Class 8'], 'subjects' => [
                ['name' => 'English', 'code' => 'ENG'],
                ['name' => 'Mathematics', 'code' => 'MAT'],
                ['name' => 'General Science', 'code' => 'GSC'],
                ['name' => 'Bangladesh History', 'code' => 'BHS'],
                ['name' => 'Bangladesh Studies', 'code' => 'BST'],
                ['name' => 'Bengali', 'code' => 'BEN'],
                ['name' => 'Islamic Studies', 'code' => 'ISL'],
                ['name' => 'Geography', 'code' => 'GEO'],
                ['name' => 'Physics', 'code' => 'PHY'],
                ['name' => 'Chemistry', 'code' => 'CHE'],
                ['name' => 'Biology', 'code' => 'BIO'],
                ['name' => 'Computer Science', 'code' => 'CSE'],
                ['name' => 'Arts & Crafts', 'code' => 'ART'],
                ['name' => 'Physical Education', 'code' => 'PED'],
            ]],

            // Class 9-10: Secondary level subjects
            ['classes' => ['Class 9', 'Class 10'], 'subjects' => [
                ['name' => 'English 1st Paper', 'code' => 'ENG1'],
                ['name' => 'English 2nd Paper', 'code' => 'ENG2'],
                ['name' => 'Mathematics', 'code' => 'MAT'],
                ['name' => 'Physics', 'code' => 'PHY'],
                ['name' => 'Chemistry', 'code' => 'CHE'],
                ['name' => 'Biology', 'code' => 'BIO'],
                ['name' => 'Higher Mathematics', 'code' => 'HMA'],
                ['name' => 'Bangladesh History', 'code' => 'BHS'],
                ['name' => 'Geography', 'code' => 'GEO'],
                ['name' => 'Civics', 'code' => 'CIV'],
                ['name' => 'Economics', 'code' => 'ECO'],
                ['name' => 'Accounting', 'code' => 'ACC'],
                ['name' => 'Business Studies', 'code' => 'BUS'],
                ['name' => 'Computer Science', 'code' => 'CSE'],
                ['name' => 'Bengali 1st Paper', 'code' => 'BEN1'],
                ['name' => 'Bengali 2nd Paper', 'code' => 'BEN2'],
                ['name' => 'Islamic Studies', 'code' => 'ISL'],
                ['name' => 'Hindu Religion', 'code' => 'HRD'],
                ['name' => 'Christian Religion', 'code' => 'CRD'],
                ['name' => 'Home Science', 'code' => 'HSC'],
            ]],

            // Class 11-12: Higher secondary subjects
            ['classes' => ['Class 11', 'Class 12'], 'subjects' => [
                ['name' => 'English 1st Paper', 'code' => 'ENG1'],
                ['name' => 'English 2nd Paper', 'code' => 'ENG2'],
                ['name' => 'Mathematics', 'code' => 'MAT'],
                ['name' => 'Physics', 'code' => 'PHY'],
                ['name' => 'Chemistry', 'code' => 'CHE'],
                ['name' => 'Biology', 'code' => 'BIO'],
                ['name' => 'Statistics', 'code' => 'STA'],
                ['name' => 'Bangladesh Studies', 'code' => 'BST'],
                ['name' => 'Economics', 'code' => 'ECO'],
                ['name' => 'Accounting', 'code' => 'ACC'],
                ['name' => 'Business Organization', 'code' => 'BOO'],
                ['name' => 'Logic', 'code' => 'LOG'],
                ['name' => 'Sociology', 'code' => 'SOC'],
                ['name' => 'Psychology', 'code' => 'PSY'],
                ['name' => 'Computer Science', 'code' => 'CSE'],
                ['name' => 'ICT', 'code' => 'ICT'],
                ['name' => 'Bengali', 'code' => 'BEN'],
                ['name' => 'Islamic Studies', 'code' => 'ISL'],
                ['name' => 'Islamic History', 'code' => 'ISH'],
            ]],

            // Kindergarten and pre-school subjects
            ['classes' => ['Nursery', 'Pre-Kindergarten', 'Kindergarten'], 'subjects' => [
                ['name' => 'English', 'code' => 'ENG'],
                ['name' => 'Mathematics', 'code' => 'MAT'],
                ['name' => 'Basic Science', 'code' => 'BSC'],
                ['name' => 'Creative Arts', 'code' => 'CTA'],
                ['name' => 'Music & Dance', 'code' => 'MUS'],
                ['name' => 'Physical Activity', 'code' => 'PHA'],
                ['name' => 'Pre-writing Skills', 'code' => 'PWS'],
                ['name' => 'Social Skills', 'code' => 'SFS'],
                ['name' => 'Story Time', 'code' => 'STY'],
            ]],
        ];

        // Create subjects for each class
        foreach ($subjectsByGrade as $group) {
            $classes = SchoolClass::whereIn('class_name', $group['classes'])->get();

            foreach ($classes as $class) {
                foreach ($group['subjects'] as $subjectData) {
                    // Generate unique code by adding class number to standard code
                    $classNumber = ctype_digit(substr($class->class_name, -2)) ? substr($class->class_name, -2) : substr($class->class_name, -1);
                    $uniqueCode = $subjectData['code'] . $classNumber;

                    Subject::updateOrCreate(
                        [
                            'subject_name' => $subjectData['name'],
                            'class_id' => $class->id
                        ],
                        [
                            'subject_code' => $uniqueCode,
                            'class_id' => $class->id
                        ]
                    );
                }
            }
        }
    }
}
