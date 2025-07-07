<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNigerianSchoolStructure extends Migration
{
    public function up()
    {
        // Academic Sessions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'session_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'e.g., 2024/2025'
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'is_current' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addUniqueKey('session_name');
        $this->forge->createTable('academic_sessions');

        // Terms Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'term_number' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'comment'    => '1=First Term, 2=Second Term, 3=Third Term'
            ],
            'term_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'e.g., First Term, Second Term, Third Term'
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'is_current' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('session_id');
        $this->forge->addUniqueKey(['session_id', 'term_number']);
        $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('academic_terms');

        // Teacher Subject Class Assignments Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'assigned_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Admin who made the assignment'
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
        $this->forge->addKey(['teacher_id', 'subject_id', 'class_id']);
        $this->forge->addKey('session_id');
        $this->forge->addUniqueKey(['teacher_id', 'subject_id', 'class_id', 'session_id']);
        $this->forge->addForeignKey('teacher_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('teacher_subject_assignments');

        // Update exams table to include term and session
        $this->forge->addColumn('exams', [
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'class_id'
            ],
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'session_id'
            ],
        ]);

        // Update exam_attempts table to include term and session
        $this->forge->addColumn('exam_attempts', [
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'exam_id'
            ],
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'session_id'
            ],
        ]);

        // Remove credits column from subjects table as it's not needed for primary/secondary schools
        $this->forge->dropColumn('subjects', 'credits');

        // Create Subject Categories Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#6c757d',
                'comment'    => 'Hex color code for category display'
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('subject_categories');

        // Insert default categories
        $categories = [
            ['name' => 'Core', 'description' => 'Core subjects required for all students', 'color' => '#dc3545'],
            ['name' => 'Science', 'description' => 'Science and technology subjects', 'color' => '#28a745'],
            ['name' => 'Arts', 'description' => 'Arts and humanities subjects', 'color' => '#ffc107'],
            ['name' => 'Social Science', 'description' => 'Social science subjects', 'color' => '#17a2b8'],
            ['name' => 'Language', 'description' => 'Language and literature subjects', 'color' => '#6f42c1'],
            ['name' => 'Vocational', 'description' => 'Vocational and technical subjects', 'color' => '#fd7e14'],
            ['name' => 'Non-Core', 'description' => 'Optional and elective subjects', 'color' => '#6c757d'],
        ];

        foreach ($categories as $category) {
            $category['is_active'] = 1;
            $category['created_at'] = date('Y-m-d H:i:s');
            $category['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('subject_categories')->insert($category);
        }
    }

    public function down()
    {
        // Add back credits column
        $this->forge->addColumn('subjects', [
            'credits' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'after'      => 'description'
            ]
        ]);

        // Remove columns from exam_attempts
        $this->forge->dropColumn('exam_attempts', ['session_id', 'term_id']);

        // Remove columns from exams
        $this->forge->dropColumn('exams', ['session_id', 'term_id']);

        // Drop tables
        $this->forge->dropTable('subject_categories');
        $this->forge->dropTable('teacher_subject_assignments');
        $this->forge->dropTable('academic_terms');
        $this->forge->dropTable('academic_sessions');
    }
}
