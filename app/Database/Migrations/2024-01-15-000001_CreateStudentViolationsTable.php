<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentViolationsTable extends Migration
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
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'violation_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'punishment_type' => [
                'type' => 'ENUM',
                'constraint' => ['warning', 'temporary_suspension', 'permanent_ban'],
                'default' => 'warning',
            ],
            'punishment_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Duration in days for temporary suspensions',
            ],
            'severity' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Admin who applied manual punishment',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('student_id');
        $this->forge->addKey('punishment_type');
        $this->forge->addKey('severity');
        $this->forge->addKey('created_at');

        // Add foreign key constraints
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('admin_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('student_violations');
    }

    public function down()
    {
        $this->forge->dropTable('student_violations');
    }
}
