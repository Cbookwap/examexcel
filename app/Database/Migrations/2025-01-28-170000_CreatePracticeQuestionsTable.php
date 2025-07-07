<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePracticeQuestionsTable extends Migration
{
    public function up()
    {
        // Practice Questions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'question_text' => [
                'type' => 'TEXT',
            ],
            'option_a' => [
                'type' => 'TEXT',
            ],
            'option_b' => [
                'type' => 'TEXT',
            ],
            'option_c' => [
                'type' => 'TEXT',
            ],
            'option_d' => [
                'type' => 'TEXT',
            ],
            'correct_answer' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'C', 'D'],
            ],
            'explanation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'difficulty' => [
                'type'       => 'ENUM',
                'constraint' => ['easy', 'medium', 'hard'],
                'default'    => 'medium',
            ],
            'points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('category');
        $this->forge->addKey('difficulty');
        $this->forge->addKey('is_active');
        $this->forge->createTable('practice_questions');

        // Practice Question Options Table (for future extensibility)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'practice_question_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'option_text' => [
                'type' => 'TEXT',
            ],
            'is_correct' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'order_index' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('practice_question_id');
        $this->forge->addForeignKey('practice_question_id', 'practice_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('practice_question_options');
    }

    public function down()
    {
        $this->forge->dropTable('practice_question_options');
        $this->forge->dropTable('practice_questions');
    }
}
