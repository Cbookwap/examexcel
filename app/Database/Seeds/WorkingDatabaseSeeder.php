<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkingDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Insert academic sessions
        $this->db->table('academic_sessions')->insertBatch([
            [
                'id' => 1,
                'session_name' => '2024/2025',
                'start_date' => '2024-09-01',
                'end_date' => '2025-07-31',
                'is_current' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ]
        ]);

        // Insert academic terms
        $this->db->table('academic_terms')->insertBatch([
            [
                'id' => 1,
                'session_id' => 1,
                'term_number' => 1,
                'term_name' => 'First Term',
                'start_date' => '2024-09-01',
                'end_date' => '2024-12-20',
                'is_current' => 0,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-30 20:58:37'
            ],
            [
                'id' => 2,
                'session_id' => 1,
                'term_number' => 2,
                'term_name' => 'Second Term',
                'start_date' => '2025-01-08',
                'end_date' => '2025-04-11',
                'is_current' => 0,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 3,
                'session_id' => 1,
                'term_number' => 3,
                'term_name' => 'Third Term',
                'start_date' => '2025-04-28',
                'end_date' => '2025-07-31',
                'is_current' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-30 20:58:37'
            ]
        ]);

        // Insert classes
        $this->db->table('classes')->insertBatch([
            [
                'id' => 3,
                'name' => 'Primary 1',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Primary One Class B',
                'max_students' => 30,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:32:27'
            ],
            [
                'id' => 4,
                'name' => 'Primary 2',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Primary Two Class A',
                'max_students' => 30,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:32:35'
            ],
            [
                'id' => 5,
                'name' => 'Primary 3',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Primary Three Class A',
                'max_students' => 30,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:32:50'
            ],
            [
                'id' => 6,
                'name' => 'Primary 4',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Primary Four Class A',
                'max_students' => 30,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:33:04'
            ],
            [
                'id' => 7,
                'name' => 'Primary 5',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Primary Five Class A',
                'max_students' => 30,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:33:12'
            ],
            [
                'id' => 8,
                'name' => 'Primary 6',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Primary Six Class A',
                'max_students' => 30,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:33:19'
            ],
            [
                'id' => 10,
                'name' => 'JSS 1',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Junior Secondary School 1 Class B',
                'max_students' => 35,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:33:33'
            ],
            [
                'id' => 11,
                'name' => 'JSS 2',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Junior Secondary School 2 Class A',
                'max_students' => 35,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:33:45'
            ],
            [
                'id' => 12,
                'name' => 'JSS 3',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Junior Secondary School 3 Class A',
                'max_students' => 35,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-02 01:34:39'
            ],
            [
                'id' => 13,
                'name' => 'SS 1',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Senior Secondary School 1 Science Class',
                'max_students' => 40,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-07 02:26:17'
            ],
            [
                'id' => 17,
                'name' => 'SS 2',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Senior Secondary School 2 Arts Class',
                'max_students' => 40,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-07 02:26:31'
            ],
            [
                'id' => 18,
                'name' => 'SS 3',
                'section' => '',
                'academic_year' => '2024/2025',
                'description' => 'Senior Secondary School 3 Science Class',
                'max_students' => 40,
                'class_teacher_id' => null,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-06-07 02:26:42'
            ]
        ]);

        // Insert exam types
        $this->db->table('exam_types')->insertBatch([
            [
                'id' => 1,
                'name' => 'Continuous Assessment',
                'code' => 'CA',
                'description' => 'Continuous Assessment Test',
                'default_total_marks' => 30,
                'default_duration_minutes' => 45,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 2,
                'name' => 'Mid-Term Exam',
                'code' => 'MTE',
                'description' => 'Mid-Term Examination',
                'default_total_marks' => 50,
                'default_duration_minutes' => 60,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 3,
                'name' => 'Terminal Exam',
                'code' => 'TE',
                'description' => 'Terminal Examination',
                'default_total_marks' => 70,
                'default_duration_minutes' => 90,
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ]
        ]);

        // Insert subject categories
        $this->db->table('subject_categories')->insertBatch([
            [
                'id' => 1,
                'name' => 'Core Subjects',
                'description' => 'Essential subjects for all students',
                'color' => '#007bff',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 2,
                'name' => 'Science Subjects',
                'description' => 'Science and technology related subjects',
                'color' => '#28a745',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 3,
                'name' => 'Arts Subjects',
                'description' => 'Arts and humanities subjects',
                'color' => '#ffc107',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ]
        ]);

        // Insert subjects
        $this->db->table('subjects')->insertBatch([
            [
                'id' => 1,
                'name' => 'English Language',
                'code' => 'ENG',
                'description' => 'English Language and Literature',
                'category' => 'Core Subjects',
                'category_id' => 1,
                'color' => '#007bff',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 2,
                'name' => 'Yoruba Language',
                'code' => 'YOR',
                'description' => 'Yoruba Language Studies',
                'category' => 'Core Subjects',
                'category_id' => 1,
                'color' => '#6f42c1',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 3,
                'name' => 'Igbo Language',
                'code' => 'IGB',
                'description' => 'Igbo Language Studies',
                'category' => 'Core Subjects',
                'category_id' => 1,
                'color' => '#e83e8c',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 4,
                'name' => 'Hausa Language',
                'code' => 'HAU',
                'description' => 'Hausa Language Studies',
                'category' => 'Core Subjects',
                'category_id' => 1,
                'color' => '#fd7e14',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 5,
                'name' => 'Mathematics',
                'code' => 'MTH',
                'description' => 'Mathematics and Further Mathematics',
                'category' => 'Core Subjects',
                'category_id' => 1,
                'color' => '#dc3545',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 6,
                'name' => 'Physics',
                'code' => 'PHY',
                'description' => 'Physics Science',
                'category' => 'Science Subjects',
                'category_id' => 2,
                'color' => '#17a2b8',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 7,
                'name' => 'Chemistry',
                'code' => 'CHE',
                'description' => 'Chemistry Science',
                'category' => 'Science Subjects',
                'category_id' => 2,
                'color' => '#28a745',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ],
            [
                'id' => 8,
                'name' => 'Biology',
                'code' => 'BIO',
                'description' => 'Biology Science',
                'category' => 'Science Subjects',
                'category_id' => 2,
                'color' => '#20c997',
                'is_active' => 1,
                'created_at' => '2025-05-27 13:54:10',
                'updated_at' => '2025-05-27 13:54:10'
            ]
        ]);

        // Check if admin already exists from installation
        $existingAdmin = $this->db->table('users')->where('role', 'admin')->get()->getRow();
        if (!$existingAdmin) {
            // Insert default admin user (demo only)
            $this->db->table('users')->insertBatch([
                [
                    'id' => 1,
                    'username' => 'admin',
                    'email' => 'admin@school.com',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'first_name' => 'Demo',
                    'last_name' => 'Administrator',
                    'middle_name' => null,
                    'role' => 'admin',
                    'phone' => null,
                    'address' => null,
                    'date_of_birth' => null,
                    'gender' => null,
                    'profile_picture' => null,
                    'admission_number' => null,
                    'employee_id' => 'EMP001',
                    'class_id' => null,
                    'session_id' => null,
                    'is_active' => 1,
                    'is_verified' => 1,
                    'last_login' => null,
                    'created_at' => '2025-05-27 13:54:10',
                    'updated_at' => '2025-05-27 13:54:10'
                ]
            ]);
        }
    }
}
