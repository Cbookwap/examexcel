<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AcademicStructureSeeder extends Seeder
{
    public function run()
    {
        // Create current academic session (2024/2025)
        $sessionData = [
            'session_name' => '2024/2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-07-31',
            'is_current' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $sessionId = $this->db->table('academic_sessions')->insert($sessionData, true);

        // Create three terms for the session
        $termsData = [
            [
                'session_id' => $sessionId,
                'term_number' => 1,
                'term_name' => 'First Term',
                'start_date' => '2024-09-01',
                'end_date' => '2024-12-20',
                'is_current' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'session_id' => $sessionId,
                'term_number' => 2,
                'term_name' => 'Second Term',
                'start_date' => '2025-01-08',
                'end_date' => '2025-04-11',
                'is_current' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'session_id' => $sessionId,
                'term_number' => 3,
                'term_name' => 'Third Term',
                'start_date' => '2025-04-28',
                'end_date' => '2025-07-31',
                'is_current' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('academic_terms')->insertBatch($termsData);

        // Create sample subjects for Nigerian primary/secondary schools
        $subjectsData = [
            // Primary School Subjects
            ['name' => 'Mathematics', 'code' => 'MATH', 'description' => 'Basic Mathematics', 'category' => 'Core', 'is_active' => 1],
            ['name' => 'English Language', 'code' => 'ENG', 'description' => 'English Language and Literature', 'category' => 'Core', 'is_active' => 1],
            ['name' => 'Basic Science', 'code' => 'BSC', 'description' => 'Basic Science and Technology', 'category' => 'Core', 'is_active' => 1],
            ['name' => 'Social Studies', 'code' => 'SST', 'description' => 'Social Studies', 'category' => 'Core', 'is_active' => 1],
            ['name' => 'Civic Education', 'code' => 'CIV', 'description' => 'Civic Education', 'category' => 'Core', 'is_active' => 1],
            ['name' => 'Computer Studies', 'code' => 'CMP', 'description' => 'Computer Studies', 'category' => 'Core', 'is_active' => 1],
            ['name' => 'Creative Arts', 'code' => 'ART', 'description' => 'Creative and Cultural Arts', 'category' => 'Non-Core', 'is_active' => 1],
            ['name' => 'Physical Education', 'code' => 'PHE', 'description' => 'Physical and Health Education', 'category' => 'Non-Core', 'is_active' => 1],
            
            // Secondary School Subjects
            ['name' => 'Physics', 'code' => 'PHY', 'description' => 'Physics', 'category' => 'Science', 'is_active' => 1],
            ['name' => 'Chemistry', 'code' => 'CHE', 'description' => 'Chemistry', 'category' => 'Science', 'is_active' => 1],
            ['name' => 'Biology', 'code' => 'BIO', 'description' => 'Biology', 'category' => 'Science', 'is_active' => 1],
            ['name' => 'Geography', 'code' => 'GEO', 'description' => 'Geography', 'category' => 'Social Science', 'is_active' => 1],
            ['name' => 'History', 'code' => 'HIS', 'description' => 'History', 'category' => 'Social Science', 'is_active' => 1],
            ['name' => 'Economics', 'code' => 'ECO', 'description' => 'Economics', 'category' => 'Social Science', 'is_active' => 1],
            ['name' => 'Government', 'code' => 'GOV', 'description' => 'Government', 'category' => 'Social Science', 'is_active' => 1],
            ['name' => 'Literature in English', 'code' => 'LIT', 'description' => 'Literature in English', 'category' => 'Arts', 'is_active' => 1],
            ['name' => 'Agricultural Science', 'code' => 'AGR', 'description' => 'Agricultural Science', 'category' => 'Vocational', 'is_active' => 1],
            ['name' => 'Technical Drawing', 'code' => 'TD', 'description' => 'Technical Drawing', 'category' => 'Vocational', 'is_active' => 1],
            ['name' => 'Home Economics', 'code' => 'HE', 'description' => 'Home Economics', 'category' => 'Vocational', 'is_active' => 1],
            ['name' => 'French', 'code' => 'FRE', 'description' => 'French Language', 'category' => 'Language', 'is_active' => 1],
            ['name' => 'Hausa', 'code' => 'HAU', 'description' => 'Hausa Language', 'category' => 'Language', 'is_active' => 1],
            ['name' => 'Igbo', 'code' => 'IGB', 'description' => 'Igbo Language', 'category' => 'Language', 'is_active' => 1],
            ['name' => 'Yoruba', 'code' => 'YOR', 'description' => 'Yoruba Language', 'category' => 'Language', 'is_active' => 1],
        ];

        foreach ($subjectsData as &$subject) {
            $subject['created_at'] = date('Y-m-d H:i:s');
            $subject['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('subjects')->insertBatch($subjectsData);

        // Create sample classes for Nigerian school system
        $classesData = [
            // Primary School Classes
            ['name' => 'Primary 1', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Primary One Class A', 'max_students' => 30, 'is_active' => 1],
            ['name' => 'Primary 1', 'section' => 'B', 'academic_year' => '2024/2025', 'description' => 'Primary One Class B', 'max_students' => 30, 'is_active' => 1],
            ['name' => 'Primary 2', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Primary Two Class A', 'max_students' => 30, 'is_active' => 1],
            ['name' => 'Primary 3', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Primary Three Class A', 'max_students' => 30, 'is_active' => 1],
            ['name' => 'Primary 4', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Primary Four Class A', 'max_students' => 30, 'is_active' => 1],
            ['name' => 'Primary 5', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Primary Five Class A', 'max_students' => 30, 'is_active' => 1],
            ['name' => 'Primary 6', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Primary Six Class A', 'max_students' => 30, 'is_active' => 1],
            
            // Junior Secondary School Classes
            ['name' => 'JSS 1', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 1 Class A', 'max_students' => 35, 'is_active' => 1],
            ['name' => 'JSS 1', 'section' => 'B', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 1 Class B', 'max_students' => 35, 'is_active' => 1],
            ['name' => 'JSS 2', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 2 Class A', 'max_students' => 35, 'is_active' => 1],
            ['name' => 'JSS 3', 'section' => 'A', 'academic_year' => '2024/2025', 'description' => 'Junior Secondary School 3 Class A', 'max_students' => 35, 'is_active' => 1],
            
            // Senior Secondary School Classes
            ['name' => 'SS 1', 'section' => 'Science', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 1 Science Class', 'max_students' => 40, 'is_active' => 1],
            ['name' => 'SS 1', 'section' => 'Arts', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 1 Arts Class', 'max_students' => 40, 'is_active' => 1],
            ['name' => 'SS 1', 'section' => 'Commercial', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 1 Commercial Class', 'max_students' => 40, 'is_active' => 1],
            ['name' => 'SS 2', 'section' => 'Science', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 2 Science Class', 'max_students' => 40, 'is_active' => 1],
            ['name' => 'SS 2', 'section' => 'Arts', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 2 Arts Class', 'max_students' => 40, 'is_active' => 1],
            ['name' => 'SS 3', 'section' => 'Science', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 3 Science Class', 'max_students' => 40, 'is_active' => 1],
            ['name' => 'SS 3', 'section' => 'Arts', 'academic_year' => '2024/2025', 'description' => 'Senior Secondary School 3 Arts Class', 'max_students' => 40, 'is_active' => 1],
        ];

        foreach ($classesData as &$class) {
            $class['created_at'] = date('Y-m-d H:i:s');
            $class['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('classes')->insertBatch($classesData);

        echo "Academic structure seeded successfully!\n";
        echo "- Created academic session: 2024/2025\n";
        echo "- Created 3 terms for the session\n";
        echo "- Created " . count($subjectsData) . " Nigerian school subjects\n";
        echo "- Created " . count($classesData) . " classes (Primary, JSS, SSS)\n";
    }
}
