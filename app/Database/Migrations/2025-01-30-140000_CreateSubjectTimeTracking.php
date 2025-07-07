<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubjectTimeTracking extends Migration
{
    public function up()
    {
        // Create subject_time_tracking table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'exam_attempt_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'start_time' => [
                'type' => 'DATETIME',
                'comment' => 'When student started working on this subject'
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When student finished/left this subject'
            ],
            'time_spent_seconds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Total time spent on this subject in seconds'
            ],
            'is_completed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Whether student completed this subject'
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
        $this->forge->addKey(['exam_attempt_id', 'subject_id']);
        $this->forge->addForeignKey('exam_attempt_id', 'exam_attempts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subject_time_tracking');
    }

    public function down()
    {
        $this->forge->dropTable('subject_time_tracking');
    }
}
