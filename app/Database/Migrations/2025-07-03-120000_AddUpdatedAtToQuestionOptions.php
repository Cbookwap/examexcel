<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToQuestionOptions extends Migration
{
    public function up()
    {
        // Check if updated_at column already exists
        $fields = $this->db->getFieldData('question_options');
        $existingColumns = array_column($fields, 'name');
        
        if (!in_array('updated_at', $existingColumns)) {
            // Add updated_at column to question_options table
            $this->forge->addColumn('question_options', [
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'created_at'
                ]
            ]);
            
            echo "Added updated_at column to question_options table.\n";
        } else {
            echo "updated_at column already exists in question_options table.\n";
        }
    }

    public function down()
    {
        // Remove updated_at column
        $this->forge->dropColumn('question_options', 'updated_at');
    }
}
