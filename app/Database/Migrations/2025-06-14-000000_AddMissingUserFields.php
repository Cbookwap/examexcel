<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingUserFields extends Migration
{
    public function up()
    {
        // Add missing columns to users table
        if (!$this->db->fieldExists('student_id', 'users')) {
            $this->forge->addColumn('users', [
                'student_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'admission_number',
                    'comment' => 'Student unique ID',
                ],
            ]);
        }
        if (!$this->db->fieldExists('title', 'users')) {
            $this->forge->addColumn('users', [
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'role',
                    'comment' => 'Principal/Staff title',
                ],
            ]);
        }
        if (!$this->db->fieldExists('department', 'users')) {
            $this->forge->addColumn('users', [
                'department' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'class_id',
                ],
            ]);
        }
        if (!$this->db->fieldExists('qualification', 'users')) {
            $this->forge->addColumn('users', [
                'qualification' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'department',
                ],
            ]);
        }
        if (!$this->db->fieldExists('is_verified', 'users')) {
            $this->forge->addColumn('users', [
                'is_verified' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'after' => 'is_active',
                ],
            ]);
        }
        if (!$this->db->fieldExists('profile_picture', 'users')) {
            $this->forge->addColumn('users', [
                'profile_picture' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'gender',
                ],
            ]);
        }
    }

    public function down()
    {
        // Remove the columns if rolling back
        $fields = ['student_id', 'title', 'department', 'qualification', 'is_verified', 'profile_picture'];
        foreach ($fields as $field) {
            if ($this->db->fieldExists($field, 'users')) {
                $this->forge->dropColumn('users', $field);
            }
        }
    }
} 