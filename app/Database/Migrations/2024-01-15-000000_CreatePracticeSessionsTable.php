<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePracticeSessionsTable extends Migration
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
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'class_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'questions' => [
                'type' => 'TEXT',
                'comment' => 'JSON array of question IDs',
            ],
            'answers' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON object of question_id => answer pairs',
            ],
            'start_time' => [
                'type' => 'DATETIME',
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['in_progress', 'completed', 'abandoned'],
                'default' => 'in_progress',
            ],
            'score' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Number of correct answers',
            ],
            'total_questions' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Score percentage (0.00 to 100.00)',
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
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('student_id');
        $this->forge->addKey('subject_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');

        // Add foreign key constraints
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('practice_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('practice_sessions');
    }
}
