<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToQuestions extends Migration
{
    public function up()
    {
        // Check if columns exist before adding them
        $fields = $this->db->getFieldData('questions');
        $existingColumns = array_column($fields, 'name');
        
        $columnsToAdd = [];
        
        // Add class_id if it doesn't exist
        if (!in_array('class_id', $existingColumns)) {
            $columnsToAdd['class_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'subject_id'
            ];
        }
        
        // Add exam_type_id if it doesn't exist
        if (!in_array('exam_type_id', $existingColumns)) {
            $columnsToAdd['exam_type_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'term_id'
            ];
        }
        
        // Add instruction_id if it doesn't exist
        if (!in_array('instruction_id', $existingColumns)) {
            $columnsToAdd['instruction_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'exam_type_id'
            ];
        }
        
        // Add columns if any need to be added
        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('questions', $columnsToAdd);
        }
        
        // Add foreign key constraints (only if columns were added)
        if (isset($columnsToAdd['class_id'])) {
            $this->forge->addForeignKey('class_id', 'classes', 'id', 'SET NULL', 'CASCADE');
        }
        if (isset($columnsToAdd['exam_type_id'])) {
            $this->forge->addForeignKey('exam_type_id', 'exam_types', 'id', 'SET NULL', 'CASCADE');
        }
        if (isset($columnsToAdd['instruction_id'])) {
            $this->forge->addForeignKey('instruction_id', 'question_instructions', 'id', 'SET NULL', 'CASCADE');
        }
        
        // Add indexes for better performance
        if (!empty($columnsToAdd)) {
            $this->db->query('ALTER TABLE questions ADD INDEX idx_class_exam_type (class_id, exam_type_id)');
        }
    }

    public function down()
    {
        // Check if foreign keys exist before dropping them
        try {
            $this->forge->dropForeignKey('questions', 'questions_class_id_foreign');
        } catch (Exception $e) {
            // Foreign key doesn't exist, continue
        }
        
        try {
            $this->forge->dropForeignKey('questions', 'questions_exam_type_id_foreign');
        } catch (Exception $e) {
            // Foreign key doesn't exist, continue
        }
        
        try {
            $this->forge->dropForeignKey('questions', 'questions_instruction_id_foreign');
        } catch (Exception $e) {
            // Foreign key doesn't exist, continue
        }
        
        // Remove the columns
        $this->forge->dropColumn('questions', ['class_id', 'exam_type_id', 'instruction_id']);
    }
}
