<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@srmscbt.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'role' => 'admin',
                'phone' => '+1234567890',
                'gender' => 'male',
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'teacher',
                'email' => 'teacher@srmscbt.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'role' => 'teacher',
                'phone' => '+1234567891',
                'gender' => 'male',
                'employee_id' => 'EMP001',
                'department' => 'Computer Science',
                'qualification' => 'M.Sc Computer Science',
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'student',
                'email' => 'student@srmscbt.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Jane',
                'last_name' => 'Student',
                'role' => 'student',
                'phone' => '+1234567892',
                'gender' => 'female',
                'student_id' => 'STU001',
                'class_id' => 1,
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('users')->insertBatch($data);

        // Create a sample class
        $classData = [
            'name' => 'Computer Science - Year 1',
            'section' => 'A',
            'academic_year' => '2024-2025',
            'description' => 'First year computer science students',
            'max_students' => 50,
            'class_teacher_id' => 2, // Teacher ID
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('classes')->insert($classData);
    }
}
