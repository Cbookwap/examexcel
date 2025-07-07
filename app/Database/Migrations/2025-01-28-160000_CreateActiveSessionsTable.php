<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActiveSessionsTable extends Migration
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
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'user_role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'teacher', 'student'],
                'null' => false,
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
            'last_activity' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('session_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('user_role');
        $this->forge->addKey('last_activity');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('active_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('active_sessions');
    }
}
