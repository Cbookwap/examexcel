<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRubricAndMathSupport extends Migration
{
    public function up()
    {
        // Add rubric support to questions table
        $fields = [
            'enable_rubric' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'metadata'
            ],
            'rubric_data' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'enable_rubric'
            ],
            'model_answer' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'rubric_data'
            ],
            'decimal_places' => [
                'type' => 'INT',
                'constraint' => 2,
                'default' => 2,
                'after' => 'model_answer'
            ],
            'tolerance' => [
                'type' => 'DECIMAL',
                'constraint' => '10,4',
                'default' => 0.01,
                'after' => 'decimal_places'
            ]
        ];
        
        $this->forge->addColumn('questions', $fields);
        
        // Create essay_grading table for AI-assisted grading
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'exam_attempt_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'question_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'student_answer' => [
                'type' => 'TEXT'
            ],
            'ai_suggested_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true
            ],
            'ai_feedback' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'teacher_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true
            ],
            'teacher_feedback' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'ai_graded', 'teacher_reviewed', 'finalized'],
                'default' => 'pending'
            ],
            'graded_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'graded_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('exam_attempt_id');
        $this->forge->addKey('question_id');
        $this->forge->addKey('status');
        $this->forge->createTable('essay_grading');
    }

    public function down()
    {
        $this->forge->dropColumn('questions', ['enable_rubric', 'rubric_data', 'model_answer', 'decimal_places', 'tolerance']);
        $this->forge->dropTable('essay_grading');
    }
}
