<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CBTSeeder extends Seeder
{
    public function run()
    {
        try {
            // Check if tables exist before seeding
            if (!$this->db->tableExists('users')) {
                echo "Warning: Users table does not exist. Skipping seeder.\n";
                return;
            }

            // Check if admin user already exists (from installation)
            $existingAdmin = $this->db->table('users')->where('role', 'admin')->get()->getRow();
            if ($existingAdmin) {
                echo "Admin user already exists from installation. Skipping all demo user creation.\n";
                echo "Use your installation credentials to login.\n";

                // Still create sample data (classes, subjects, etc.) but skip all user creation
                $this->createSampleData();
                return; // Exit early to prevent creating any demo users
            }

            // Only create demo admin if no admin exists (fallback for development)
            echo "No admin found. Creating demo users for development...\n";
            $this->createDemoUsers();

            // Create sample data regardless of whether admin exists
            $this->createSampleData();

            echo "CBT System seeded successfully!\n";
            echo "Demo Users Created (development only):\n";
            echo "Admin Login: admin@localhost.com / admin123\n";
            echo "Teacher Login: teacher@localhost.com / teacher123\n";
            echo "Student Login: alice@student.com / student123\n";

        } catch (Exception $e) {
            echo "Seeder error: " . $e->getMessage() . "\n";
            log_message('error', 'CBT Seeder failed: ' . $e->getMessage());
        }
    }

    /**
     * Create demo users for development
     */
    private function createDemoUsers()
    {
        // Create demo admin
        $this->db->table('users')->insert([
            'username' => 'admin',
            'first_name' => 'Demo',
            'last_name' => 'Administrator',
            'email' => 'admin@localhost.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "Demo admin user created (development only).\n";

        // Create sample teacher (demo only)
        $this->db->table('users')->insert([
            'username' => 'teacher',
            'first_name' => 'Demo',
            'last_name' => 'Teacher',
            'email' => 'teacher@localhost.com',
            'password' => password_hash('teacher123', PASSWORD_DEFAULT),
            'role' => 'teacher',
            'employee_id' => 'T001',
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Create sample students (demo only)
        $students = [
            ['username' => 'alice', 'first_name' => 'Alice', 'last_name' => 'Johnson', 'email' => 'alice@student.com', 'student_id' => 'S001', 'class_id' => 1],
            ['username' => 'bob', 'first_name' => 'Bob', 'last_name' => 'Smith', 'email' => 'bob@student.com', 'student_id' => 'S002', 'class_id' => 1],
            ['username' => 'carol', 'first_name' => 'Carol', 'last_name' => 'Davis', 'email' => 'carol@student.com', 'student_id' => 'S003', 'class_id' => 1],
            ['username' => 'david', 'first_name' => 'David', 'last_name' => 'Wilson', 'email' => 'david@student.com', 'student_id' => 'S004', 'class_id' => 2],
            ['username' => 'emma', 'first_name' => 'Emma', 'last_name' => 'Brown', 'email' => 'emma@student.com', 'student_id' => 'S005', 'class_id' => 2]
        ];

        foreach ($students as $student) {
            $student['password'] = password_hash('student123', PASSWORD_DEFAULT);
            $student['role'] = 'student';
            $student['is_active'] = 1;
            $student['is_verified'] = 1;
            $student['created_at'] = date('Y-m-d H:i:s');
            $student['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('users')->insert($student);
        }
    }

    /**
     * Create sample data (classes, subjects, questions, exams)
     */
    private function createSampleData()
    {
        // Create sample classes
        $classes = [
            ['name' => 'Grade 10A', 'description' => 'Grade 10 Section A', 'level' => 'Grade 10', 'capacity' => 40],
            ['name' => 'Grade 10B', 'description' => 'Grade 10 Section B', 'level' => 'Grade 10', 'capacity' => 40],
            ['name' => 'Grade 11 Science', 'description' => 'Grade 11 Science Section', 'level' => 'Grade 11', 'capacity' => 35],
            ['name' => 'Grade 12 Commerce', 'description' => 'Grade 12 Commerce Section', 'level' => 'Grade 12', 'capacity' => 30]
        ];

        foreach ($classes as $class) {
            $class['is_active'] = 1;
            $class['created_at'] = date('Y-m-d H:i:s');
            $class['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('classes')->insert($class);
        }

        // Create sample subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'description' => 'Advanced Mathematics'],
            ['name' => 'Physics', 'code' => 'PHY', 'description' => 'Physics fundamentals'],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'description' => 'Basic Chemistry'],
            ['name' => 'English', 'code' => 'ENG', 'description' => 'English Language'],
            ['name' => 'Computer Science', 'code' => 'CS', 'description' => 'Programming and CS concepts']
        ];

        foreach ($subjects as $subject) {
            $subject['is_active'] = 1;
            $subject['created_at'] = date('Y-m-d H:i:s');
            $subject['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('subjects')->insert($subject);
        }

        // Create sample questions for Mathematics
        $mathQuestions = [
            [
                'subject_id' => 1,
                'class_id' => 1,
                'question_text' => 'What is the value of 2 + 2?',
                'question_type' => 'multiple_choice',
                'difficulty' => 'easy',
                'marks' => 1.00,
                'created_by' => 2
            ],
            [
                'subject_id' => 1,
                'class_id' => 1,
                'question_text' => 'Solve for x: 2x + 5 = 15',
                'question_type' => 'multiple_choice',
                'difficulty' => 'medium',
                'marks' => 2.00,
                'created_by' => 2
            ],
            [
                'subject_id' => 1,
                'class_id' => 1,
                'question_text' => 'The square root of 16 is 4.',
                'question_type' => 'true_false',
                'difficulty' => 'easy',
                'marks' => 1.00,
                'created_by' => 2
            ],
            [
                'subject_id' => 1,
                'class_id' => 1,
                'question_text' => 'What is the derivative of x²?',
                'question_type' => 'essay',
                'difficulty' => 'medium',
                'marks' => 3.00,
                'created_by' => 2
            ]
        ];

        foreach ($mathQuestions as $question) {
            $question['is_active'] = 1;
            $question['created_at'] = date('Y-m-d H:i:s');
            $question['updated_at'] = date('Y-m-d H:i:s');
            $questionId = $this->db->table('questions')->insert($question, true);

            // Add options for MCQ questions
            if ($question['question_type'] === 'multiple_choice') {
                if ($question['question_text'] === 'What is the value of 2 + 2?') {
                    $options = [
                        ['question_id' => $questionId, 'option_text' => '3', 'is_correct' => 0, 'option_order' => 1],
                        ['question_id' => $questionId, 'option_text' => '4', 'is_correct' => 1, 'option_order' => 2],
                        ['question_id' => $questionId, 'option_text' => '5', 'is_correct' => 0, 'option_order' => 3],
                        ['question_id' => $questionId, 'option_text' => '6', 'is_correct' => 0, 'option_order' => 4]
                    ];
                } else {
                    $options = [
                        ['question_id' => $questionId, 'option_text' => 'x = 3', 'is_correct' => 0, 'option_order' => 1],
                        ['question_id' => $questionId, 'option_text' => 'x = 5', 'is_correct' => 1, 'option_order' => 2],
                        ['question_id' => $questionId, 'option_text' => 'x = 7', 'is_correct' => 0, 'option_order' => 3],
                        ['question_id' => $questionId, 'option_text' => 'x = 10', 'is_correct' => 0, 'option_order' => 4]
                    ];
                }

                foreach ($options as $option) {
                    $this->db->table('question_options')->insert($option);
                }
            } elseif ($question['question_type'] === 'true_false') {
                $options = [
                    ['question_id' => $questionId, 'option_text' => 'True', 'is_correct' => 1, 'option_order' => 1],
                    ['question_id' => $questionId, 'option_text' => 'False', 'is_correct' => 0, 'option_order' => 2]
                ];

                foreach ($options as $option) {
                    $this->db->table('question_options')->insert($option);
                }
            } elseif ($question['question_type'] === 'essay') {
                // Essay questions don't need predefined options
                // They will be graded manually
            }
        }

        // Create sample exam
        $examData = [
            'title' => 'Mathematics Mid-Term Exam',
            'description' => 'Mid-term examination covering algebra and basic calculus',
            'subject_id' => 1,
            'class_id' => 1,
            'duration' => 60,
            'total_marks' => 10.00,
            'pass_marks' => 6.00,
            'randomize_questions' => 1,
            'show_results' => 1,
            'allow_review' => 1,
            'auto_submit' => 1,
            'disable_copy_paste' => 1,
            'disable_right_click' => 1,
            'start_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'end_time' => date('Y-m-d H:i:s', strtotime('+1 week')),
            'instructions' => 'Read all questions carefully before answering. Ensure stable internet connection. Do not refresh the page. No external help allowed. Maintain academic integrity.',
            'is_active' => 1,
            'is_published' => 1,
            'created_by' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $examId = $this->db->table('exams')->insert($examData, true);

        // Assign questions to exam
        for ($i = 1; $i <= 4; $i++) {
            $this->db->table('exam_questions')->insert([
                'exam_id' => $examId,
                'question_id' => $i,
                'order_index' => $i,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
