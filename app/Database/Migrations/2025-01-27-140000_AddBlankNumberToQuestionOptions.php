<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBlankNumberToQuestionOptions extends Migration
{
    public function up()
    {
        // Add blank_number field to question_options table
        $fields = [
            'blank_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'is_correct'
            ]
        ];
        
        $this->forge->addColumn('question_options', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('question_options', 'blank_number');
    }
}
