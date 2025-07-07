<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePracticeSessionsForPracticeQuestions extends Migration
{
    public function up()
    {
        // Add category field for practice questions
        $this->forge->addColumn('practice_sessions', [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'class_id'
            ]
        ]);

        // Drop foreign key constraint for subject_id
        $this->forge->dropForeignKey('practice_sessions', 'practice_sessions_subject_id_foreign');

        // Modify subject_id to be nullable
        $this->forge->modifyColumn('practice_sessions', [
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);

        // Add index for category
        $this->forge->addKey('category');
        $this->db->query('ALTER TABLE practice_sessions ADD INDEX idx_category (category)');
    }

    public function down()
    {
        // Remove category field
        $this->forge->dropColumn('practice_sessions', 'category');

        // Make subject_id not nullable again
        $this->forge->modifyColumn('practice_sessions', [
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ]
        ]);

        // Re-add foreign key constraint for subject_id
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
    }
}
