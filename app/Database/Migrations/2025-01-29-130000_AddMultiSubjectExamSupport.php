<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMultiSubjectExamSupport extends Migration
{
    public function up()
    {
        // Add exam_mode column to exams table
        $this->forge->addColumn('exams', [
            'exam_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['single_subject', 'multi_subject'],
                'default'    => 'single_subject',
                'comment'    => 'Exam mode: single subject or multiple subjects',
                'after'      => 'class_id'
            ],
            'total_questions' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Total number of questions across all subjects',
                'after'      => 'question_count'
            ],
            'questions_configured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Whether questions have been configured for this exam',
                'after'      => 'total_questions'
            ]
        ]);

        // Modify subject_id column to allow NULL for multi-subject exams
        $this->forge->modifyColumn('exams', [
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Subject ID for single-subject exams, NULL for multi-subject exams'
            ]
        ]);

        // Create exam_subjects junction table for multi-subject exams
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'exam_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'question_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Number of questions for this subject in the exam'
            ],
            'total_marks' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Total marks for this subject in the exam'
            ],
            'time_allocation' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Time allocated for this subject in minutes'
            ],
            'subject_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'Order of subjects in the exam'
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
        $this->forge->addKey(['exam_id', 'subject_id']);
        $this->forge->addForeignKey('exam_id', 'exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_subjects');

        // Update exam_questions table to include subject_id for multi-subject support
        $this->forge->addColumn('exam_questions', [
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Subject ID for multi-subject exams',
                'after'      => 'question_id'
            ],
            'subject_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'Order within subject for multi-subject exams',
                'after'      => 'order_index'
            ]
        ]);

        $this->forge->addForeignKey('exam_questions', 'subject_id', 'subjects', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        // Drop foreign key and columns from exam_questions
        $this->forge->dropForeignKey('exam_questions', 'exam_questions_subject_id_foreign');
        $this->forge->dropColumn('exam_questions', ['subject_id', 'subject_order']);

        // Drop exam_subjects table
        $this->forge->dropTable('exam_subjects');

        // Revert subject_id column to NOT NULL
        $this->forge->modifyColumn('exams', [
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false
            ]
        ]);

        // Drop columns from exams table
        $this->forge->dropColumn('exams', ['exam_mode', 'total_questions', 'questions_configured']);
    }
}
