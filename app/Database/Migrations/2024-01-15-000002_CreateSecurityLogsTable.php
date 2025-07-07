<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSecurityLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'exam_attempt_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'event_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Type of security event (tab_switch_away, window_focus_lost, etc.)',
            ],
            'event_data' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Additional event data in JSON format',
            ],
            'severity' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('exam_attempt_id');
        $this->forge->addKey('event_type');
        $this->forge->addKey('severity');
        $this->forge->addKey('created_at');

        // Add foreign key constraint
        $this->forge->addForeignKey('exam_attempt_id', 'exam_attempts', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('security_logs');
    }

    public function down()
    {
        $this->forge->dropTable('security_logs');
    }
}
