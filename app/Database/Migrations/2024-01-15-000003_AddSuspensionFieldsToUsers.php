<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuspensionFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'exam_banned' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether user is permanently banned from exams',
            ],
            'ban_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for permanent ban',
            ],
            'exam_suspended_until' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Date/time until which user is suspended from exams',
            ],
            'suspension_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for temporary suspension',
            ],
        ];

        $this->forge->addColumn('users', $fields);

        // Add indexes for better performance
        $this->forge->addKey(['exam_banned'], false, false, 'users');
        $this->forge->addKey(['exam_suspended_until'], false, false, 'users');
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['exam_banned', 'ban_reason', 'exam_suspended_until', 'suspension_reason']);
    }
}
