<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixSubjectIdNullable extends Migration
{
    public function up()
    {
        // Modify subject_id column to allow NULL values for multi-subject exams
        $this->forge->modifyColumn('exams', [
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Subject ID for single-subject exams, NULL for multi-subject exams'
            ]
        ]);
    }

    public function down()
    {
        // Revert subject_id column to NOT NULL (this might fail if there are NULL values)
        $this->forge->modifyColumn('exams', [
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false
            ]
        ]);
    }
}
