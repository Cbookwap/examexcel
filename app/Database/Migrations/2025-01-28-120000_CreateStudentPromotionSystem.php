<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentPromotionSystem extends Migration
{
    public function up()
    {
        // Student Academic History Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'enrollment_date' => [
                'type' => 'DATE',
            ],
            'promotion_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'promoted', 'repeated', 'graduated', 'withdrawn'],
                'default'    => 'active',
            ],
            'promotion_type' => [
                'type'       => 'ENUM',
                'constraint' => ['automatic', 'manual', 'conditional'],
                'null'       => true,
            ],
            'overall_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'position_in_class' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'total_students' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'remarks' => [
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
        $this->forge->addKey(['student_id', 'session_id', 'term_id']);
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('term_id', 'academic_terms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('student_academic_history');

        // Student Term Results Summary Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'total_subjects' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'subjects_passed' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'subjects_failed' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'total_marks_obtained' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0.00,
            ],
            'total_marks_possible' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0.00,
            ],
            'overall_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'grade' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
            ],
            'position_in_class' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'total_students' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'attendance_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'conduct_grade' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'teacher_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'principal_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'next_term_begins' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'is_promoted' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'promotion_status' => [
                'type'       => 'ENUM',
                'constraint' => ['promoted', 'repeated', 'conditional', 'pending'],
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
        $this->forge->addUniqueKey(['student_id', 'session_id', 'term_id']);
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('term_id', 'academic_terms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('student_term_results');

        // Class Promotion Rules Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'from_class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'to_class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'minimum_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 40.00,
            ],
            'minimum_subjects_passed' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
            ],
            'is_automatic' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'requires_approval' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addForeignKey('from_class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('to_class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_promotion_rules');

        // Add session_id and term_id to exam_attempts if not exists
        if (!$this->db->fieldExists('session_id', 'exam_attempts')) {
            $this->forge->addColumn('exam_attempts', [
                'session_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'student_id'
                ],
                'term_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'session_id'
                ]
            ]);
            
            $this->forge->addForeignKey('exam_attempts', 'session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('exam_attempts', 'term_id', 'academic_terms', 'id', 'CASCADE', 'CASCADE');
        }
    }

    public function down()
    {
        $this->forge->dropTable('class_promotion_rules');
        $this->forge->dropTable('student_term_results');
        $this->forge->dropTable('student_academic_history');
        
        if ($this->db->fieldExists('session_id', 'exam_attempts')) {
            $this->forge->dropForeignKey('exam_attempts', 'exam_attempts_session_id_foreign');
            $this->forge->dropForeignKey('exam_attempts', 'exam_attempts_term_id_foreign');
            $this->forge->dropColumn('exam_attempts', ['session_id', 'term_id']);
        }
    }
}
