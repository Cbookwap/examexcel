<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClassAndExamTypeToQuestions extends Migration
{
    public function up()
    {
        // Add class_id and exam_type_id to questions table
        $this->forge->addColumn('questions', [
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'subject_id'
            ],
            'exam_type_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'term_id'
            ],
            'instruction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'exam_type_id'
            ]
        ]);

        // Add foreign key constraints
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('exam_type_id', 'exam_types', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('instruction_id', 'question_instructions', 'id', 'SET NULL', 'CASCADE');

        // Add indexes for better performance
        $this->db->query('ALTER TABLE questions ADD INDEX idx_class_exam_type (class_id, exam_type_id)');
    }

    public function down()
    {
        // Remove foreign key constraints first
        $this->forge->dropForeignKey('questions', 'questions_class_id_foreign');
        $this->forge->dropForeignKey('questions', 'questions_exam_type_id_foreign');
        $this->forge->dropForeignKey('questions', 'questions_instruction_id_foreign');
        
        // Remove the columns
        $this->forge->dropColumn('questions', ['class_id', 'exam_type_id', 'instruction_id']);
    }
}
