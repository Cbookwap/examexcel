<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompleteSchemaFromWorkingDB extends Migration
{
    public function up()
    {
        // Drop existing tables if they exist to start fresh
        $tables = [
            'subject_time_tracking', 'teacher_subject_assignments', 'student_violations', 
            'student_term_results', 'student_answers', 'student_academic_history',
            'security_logs', 'security_settings', 'settings', 'proctoring_evidence',
            'proctoring_events', 'proctoring_sessions', 'proctoring_ai_models',
            'practice_sessions', 'practice_question_options', 'practice_questions',
            'question_options', 'question_instructions', 'questions', 'essay_grading',
            'exam_questions', 'exam_subjects', 'exam_attempts', 'exams', 'exam_types',
            'subject_classes', 'subject_categories', 'subjects', 'class_promotion_rules',
            'classes', 'active_sessions', 'academic_terms', 'academic_sessions', 'users'
        ];
        
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->forge->dropTable($table, true);
            }
        }

        // Create academic_sessions table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'session_name' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'comment' => 'e.g., 2024/2025',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'is_current' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('academic_sessions');

        // Create academic_terms table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'term_number' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=First Term, 2=Second Term, 3=Third Term',
            ],
            'term_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'e.g., First Term, Second Term, Third Term',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'is_current' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('academic_terms');

        // Create users table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'teacher', 'student'],
                'default' => 'student',
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female'],
                'null' => true,
            ],
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'admission_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'For students',
            ],
            'employee_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'For teachers and admin',
            ],
            'class_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Current class for students',
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Current session for students',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');

        // Create classes table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'section' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'academic_year' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_students' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 50,
            ],
            'class_teacher_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('classes');

        // Create subject_categories table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
                'default' => '#6c757d',
                'comment' => 'Hex color code for category display',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subject_categories');

        // Create subjects table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
                'default' => '#007bff',
                'comment' => 'Hex color code for subject display',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('subjects');

        // Create exam_types table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'default_total_marks' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 100,
                'comment' => 'Default total marks for this exam type',
            ],
            'default_duration_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 60,
                'comment' => 'Default duration in minutes',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('exam_types');

        // Create questions table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'class_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'term_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'exam_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'question_text' => [
                'type' => 'TEXT',
            ],
            'question_type' => [
                'type' => 'ENUM',
                'constraint' => ['multiple_choice', 'true_false', 'fill_blank', 'essay', 'matching'],
                'default' => 'multiple_choice',
            ],
            'difficulty_level' => [
                'type' => 'ENUM',
                'constraint' => ['easy', 'medium', 'hard'],
                'default' => 'medium',
            ],
            'marks' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 1.00,
            ],
            'time_limit_seconds' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Time limit for this specific question',
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'audio_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'video_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('questions');

        // Create question_options table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'question_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'option_text' => [
                'type' => 'TEXT',
            ],
            'is_correct' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'blank_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'order_index' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('question_id', 'questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('question_options');

        // Create exams table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Subject ID for single-subject exams, NULL for multi-subject exams',
            ],
            'class_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'exam_mode' => [
                'type' => 'ENUM',
                'constraint' => ['single_subject', 'multi_subject'],
                'default' => 'single_subject',
                'comment' => 'Exam mode: single subject or multiple subjects',
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'term_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'exam_type' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'duration_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 60,
            ],
            'total_marks' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'passing_marks' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 60.00,
            ],
            'question_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'total_questions' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total number of questions across all subjects',
            ],
            'questions_configured' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether questions have been configured for this exam',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'scheduled', 'active', 'completed', 'cancelled'],
                'default' => 'draft',
            ],
            'start_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('exams');
    }

    public function down()
    {
        $tables = [
            'subject_time_tracking', 'teacher_subject_assignments', 'student_violations', 
            'student_term_results', 'student_answers', 'student_academic_history',
            'security_logs', 'security_settings', 'settings', 'proctoring_evidence',
            'proctoring_events', 'proctoring_sessions', 'proctoring_ai_models',
            'practice_sessions', 'practice_question_options', 'practice_questions',
            'question_options', 'question_instructions', 'questions', 'essay_grading',
            'exam_questions', 'exam_subjects', 'exam_attempts', 'exams', 'exam_types',
            'subject_classes', 'subject_categories', 'subjects', 'class_promotion_rules',
            'classes', 'active_sessions', 'academic_terms', 'academic_sessions', 'users'
        ];
        
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->forge->dropTable($table, true);
            }
        }
    }
}
