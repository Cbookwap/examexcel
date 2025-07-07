<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCBTTables extends Migration
{
    public function up()
    {
        // Classes Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'section' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'academic_year' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_students' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 50,
            ],
            'class_teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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

        // Subjects Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'credits' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('subjects');

        // Questions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'question_text' => [
                'type' => 'TEXT',
            ],
            'question_type' => [
                'type'       => 'ENUM',
                'constraint' => ['mcq', 'true_false', 'yes_no', 'fill_blank', 'short_answer', 'essay', 'drag_drop', 'image_based', 'math_equation'],
                'default'    => 'mcq',
            ],
            'difficulty' => [
                'type'       => 'ENUM',
                'constraint' => ['easy', 'medium', 'hard'],
                'default'    => 'medium',
            ],
            'points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'time_limit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'hints' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'randomize_options' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('subject_id');
        $this->forge->createTable('questions');

        // Question Options Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'question_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'option_text' => [
                'type' => 'TEXT',
            ],
            'is_correct' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'order_index' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('question_id');
        $this->forge->createTable('question_options');

        // Exams Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'exam_type' => [
                'type'       => 'ENUM',
                'constraint' => ['practice', 'quiz', 'midterm', 'final', 'assignment'],
                'default'    => 'quiz',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'scheduled', 'active', 'completed', 'cancelled'],
                'default'    => 'draft',
            ],
            'duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 60,
            ],
            'total_points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'passing_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 60.00,
            ],
            'max_attempts' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'randomize_questions' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'randomize_options' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'show_results' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'show_correct_answers' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'allow_review' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'require_proctoring' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'browser_lockdown' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'prevent_copy_paste' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'disable_right_click' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'start_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'instructions' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'settings' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'allowed_ips' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('subject_id');
        $this->forge->addKey('class_id');
        $this->forge->createTable('exams');
    }

    public function down()
    {
        $this->forge->dropTable('exams');
        $this->forge->dropTable('question_options');
        $this->forge->dropTable('questions');
        $this->forge->dropTable('subjects');
        $this->forge->dropTable('classes');
    }
}
