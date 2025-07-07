<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PracticeQuestionSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'category' => 'English Language',
                'question_text' => 'Choose the correct synonym for "abundant".',
                'option_a' => 'Scarce',
                'option_b' => 'Plentiful',
                'option_c' => 'Limited',
                'option_d' => 'Rare',
                'correct_answer' => 'B',
                'explanation' => 'Abundant means existing in large quantities; plentiful.',
                'difficulty' => 'easy',
                'points' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category' => 'English Language',
                'question_text' => 'What is the plural form of "child"?',
                'option_a' => 'Childs',
                'option_b' => 'Childes',
                'option_c' => 'Children',
                'option_d' => 'Childerns',
                'correct_answer' => 'C',
                'explanation' => 'Children is the irregular plural form of child.',
                'difficulty' => 'easy',
                'points' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category' => 'Mathematics',
                'question_text' => 'What is 15% of 200?',
                'option_a' => '25',
                'option_b' => '30',
                'option_c' => '35',
                'option_d' => '40',
                'correct_answer' => 'B',
                'explanation' => '15% of 200 = (15/100) × 200 = 30',
                'difficulty' => 'easy',
                'points' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category' => 'Mathematics',
                'question_text' => 'What is the square root of 144?',
                'option_a' => '11',
                'option_b' => '12',
                'option_c' => '13',
                'option_d' => '14',
                'correct_answer' => 'B',
                'explanation' => '√144 = 12 because 12² = 144',
                'difficulty' => 'easy',
                'points' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category' => 'Civic Education',
                'question_text' => 'What is democracy?',
                'option_a' => 'Rule by the military',
                'option_b' => 'Government by the people',
                'option_c' => 'Rule by one person',
                'option_d' => 'Government by the wealthy',
                'correct_answer' => 'B',
                'explanation' => 'Democracy means government by the people, for the people.',
                'difficulty' => 'easy',
                'points' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        // Insert the data
        $this->db->table('practice_questions')->insertBatch($data);
    }
}
