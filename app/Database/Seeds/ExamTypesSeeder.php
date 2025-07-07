<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExamTypesSeeder extends Seeder
{
    public function run()
    {
        // Clear existing exam types
        $this->db->table('exam_types')->truncate();

        // Sample assessment types for different Nigerian school structures
        $examTypes = [
            // Continuous Assessment Types (Tests)
            [
                'name' => 'First Continuous Assessment',
                'code' => 'FIRST_CA',
                'description' => 'First continuous assessment test of the term',
                'default_total_marks' => 30,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Second Continuous Assessment',
                'code' => 'SECOND_CA',
                'description' => 'Second continuous assessment test of the term',
                'default_total_marks' => 30,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Third Continuous Assessment',
                'code' => 'THIRD_CA',
                'description' => 'Third continuous assessment test of the term',
                'default_total_marks' => 30,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Class Test',
                'code' => 'CLASS_TEST',
                'description' => 'Quick class assessment test',
                'default_total_marks' => 20,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Weekly Assessment',
                'code' => 'WEEKLY_TEST',
                'description' => 'Weekly assessment test',
                'default_total_marks' => 25,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Main Examination Types
            [
                'name' => 'Terminal Examination',
                'code' => 'TERMINAL_EXAM',
                'description' => 'End of term examination',
                'default_total_marks' => 70,
                'is_test' => 0,
                'assessment_category' => 'main_examination',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Mid-Term Examination',
                'code' => 'MIDTERM_EXAM',
                'description' => 'Mid-term examination',
                'default_total_marks' => 60,
                'is_test' => 0,
                'assessment_category' => 'main_examination',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Mock Examination',
                'code' => 'MOCK_EXAM',
                'description' => 'Mock examination for final preparation',
                'default_total_marks' => 100,
                'is_test' => 0,
                'assessment_category' => 'main_examination',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Practice Assessment Types
            [
                'name' => 'Practice Test',
                'code' => 'PRACTICE_TEST',
                'description' => 'Practice test for skill development',
                'default_total_marks' => 50,
                'is_test' => 1,
                'assessment_category' => 'practice',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Skill Assessment',
                'code' => 'SKILL_TEST',
                'description' => 'Assessment of specific skills',
                'default_total_marks' => 40,
                'is_test' => 1,
                'assessment_category' => 'practice',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert the exam types
        $this->db->table('exam_types')->insertBatch($examTypes);

        echo "Exam types seeded successfully!\n";
        echo "Added " . count($examTypes) . " assessment types:\n";
        echo "- " . count(array_filter($examTypes, fn($type) => $type['assessment_category'] === 'continuous_assessment')) . " Continuous Assessment types\n";
        echo "- " . count(array_filter($examTypes, fn($type) => $type['assessment_category'] === 'main_examination')) . " Main Examination types\n";
        echo "- " . count(array_filter($examTypes, fn($type) => $type['assessment_category'] === 'practice')) . " Practice Assessment types\n";
    }
}
