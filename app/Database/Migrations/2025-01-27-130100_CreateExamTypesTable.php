<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamTypesTable extends Migration
{
    public function up()
    {
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
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'default_total_marks' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
                'comment'    => 'Default total marks for this exam type'
            ],
            'is_test' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1 for tests/CAs, 0 for main exams'
            ],
            'assessment_category' => [
                'type'       => 'ENUM',
                'constraint' => ['continuous_assessment', 'main_examination', 'practice'],
                'default'    => 'continuous_assessment',
                'comment'    => 'Category of assessment'
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
        $this->forge->addUniqueKey('code');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('exam_types');

        // Insert default exam types
        $data = [
            [
                'name' => 'First Continuous Assessment',
                'code' => 'FIRST_CA',
                'description' => 'First continuous assessment test',
                'default_total_marks' => 30,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Second Continuous Assessment',
                'code' => 'SECOND_CA',
                'description' => 'Second continuous assessment test',
                'default_total_marks' => 30,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Third Continuous Assessment',
                'code' => 'THIRD_CA',
                'description' => 'Third continuous assessment test',
                'default_total_marks' => 30,
                'is_test' => 1,
                'assessment_category' => 'continuous_assessment',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Terminal Examination',
                'code' => 'EXAM',
                'description' => 'End of term examination',
                'default_total_marks' => 70,
                'is_test' => 0,
                'assessment_category' => 'main_examination',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('exam_types')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('exam_types');
    }
}
