<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToExamAttempts extends Migration
{
    public function up()
    {
        // Add missing columns to exam_attempts table that don't already exist
        $this->forge->addColumn('exam_attempts', [
            'start_time' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status'
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'start_time'
            ],
            'time_taken_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'end_time'
            ],
            'total_questions' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'time_taken_minutes'
            ],
            'answered_questions' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'total_questions'
            ],
            'correct_answers' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'answered_questions'
            ],
            'wrong_answers' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'correct_answers'
            ],
            'marks_obtained' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'default' => 0.00,
                'after' => 'wrong_answers'
            ],
            'violations' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'user_agent'
            ],
            'answers' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'proctoring_data'
            ]
        ]);

        // Add missing columns to exams table
        $this->forge->addColumn('exams', [
            'duration_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 60,
                'after' => 'exam_type'
            ],
            'total_marks' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'duration_minutes'
            ],
            'passing_marks' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 60.00,
                'after' => 'total_marks'
            ],
            'question_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'passing_marks'
            ],
            'negative_marking' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'question_count'
            ],
            'negative_marks_per_question' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'after' => 'negative_marking'
            ],
            'show_result_immediately' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'negative_marks_per_question'
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'allowed_ips'
            ]
        ]);
    }

    public function down()
    {
        // Remove columns from exam_attempts
        $this->forge->dropColumn('exam_attempts', [
            'start_time',
            'end_time', 
            'time_taken_minutes',
            'total_questions',
            'answered_questions',
            'correct_answers',
            'wrong_answers',
            'marks_obtained',
            'violations',
            'answers'
        ]);

        // Remove columns from exams
        $this->forge->dropColumn('exams', [
            'duration_minutes',
            'total_marks',
            'passing_marks',
            'question_count',
            'negative_marking',
            'negative_marks_per_question',
            'show_result_immediately',
            'is_active'
        ]);
    }
}
