<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureExamColumnsExist extends Migration
{
    public function up()
    {
        // Get current columns in exams table
        $fields = $this->db->getFieldData('exams');
        $existingColumns = array_column($fields, 'name');
        
        $columnsToAdd = [];
        
        // Check and add missing columns that are used in demo data
        if (!in_array('start_time', $existingColumns)) {
            $columnsToAdd['start_time'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status'
            ];
        }
        
        if (!in_array('end_time', $existingColumns)) {
            $columnsToAdd['end_time'] = [
                'type' => 'DATETIME', 
                'null' => true,
                'after' => 'start_time'
            ];
        }
        
        if (!in_array('exam_mode', $existingColumns)) {
            $columnsToAdd['exam_mode'] = [
                'type' => 'ENUM',
                'constraint' => ['single_subject', 'multi_subject'],
                'default' => 'single_subject',
                'after' => 'class_id'
            ];
        }
        
        if (!in_array('session_id', $existingColumns)) {
            $columnsToAdd['session_id'] = [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'exam_mode'
            ];
        }
        
        if (!in_array('term_id', $existingColumns)) {
            $columnsToAdd['term_id'] = [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'session_id'
            ];
        }
        
        if (!in_array('total_questions', $existingColumns)) {
            $columnsToAdd['total_questions'] = [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'question_count'
            ];
        }
        
        if (!in_array('questions_configured', $existingColumns)) {
            $columnsToAdd['questions_configured'] = [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'total_questions'
            ];
        }
        
        if (!in_array('allow_review', $existingColumns)) {
            $columnsToAdd['allow_review'] = [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'show_result_immediately'
            ];
        }
        
        // Add columns if any are missing
        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('exams', $columnsToAdd);
            echo "Added missing columns to exams table: " . implode(', ', array_keys($columnsToAdd)) . "\n";
        } else {
            echo "All required columns already exist in exams table.\n";
        }
    }

    public function down()
    {
        // Remove the columns we added
        $columnsToRemove = ['start_time', 'end_time', 'exam_mode', 'session_id', 'term_id', 
                           'total_questions', 'questions_configured', 'allow_review'];
        
        foreach ($columnsToRemove as $column) {
            try {
                $this->forge->dropColumn('exams', $column);
            } catch (Exception $e) {
                // Column doesn't exist, continue
            }
        }
    }
}
