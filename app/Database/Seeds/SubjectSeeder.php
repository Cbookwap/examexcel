<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH101',
                'description' => 'Basic Mathematics and Algebra',
                'credits' => 3,
                'category' => 'Science',
                'teacher_id' => 2,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Computer Science',
                'code' => 'CS101',
                'description' => 'Introduction to Programming',
                'credits' => 4,
                'category' => 'Technology',
                'teacher_id' => 2,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Physics',
                'code' => 'PHY101',
                'description' => 'General Physics',
                'credits' => 3,
                'category' => 'Science',
                'teacher_id' => 2,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'English',
                'code' => 'ENG101',
                'description' => 'English Language and Literature',
                'credits' => 2,
                'category' => 'Language',
                'teacher_id' => 2,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('subjects')->insertBatch($data);
    }
}
