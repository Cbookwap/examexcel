<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClassTeacherRoleToUsers extends Migration
{
    public function up()
    {
        // Modify the role ENUM to include 'class_teacher' and 'principal'
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'student', 'class_teacher', 'principal') DEFAULT 'student'");

        // Add title field for principal roles
        $this->forge->addColumn('users', [
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'role'
            ]
        ]);

        // Update existing class teachers (users with class_id but no proper role)
        $this->db->query("UPDATE users SET role = 'class_teacher' WHERE class_id IS NOT NULL AND (role IS NULL OR role = '' OR role = 'student')");
    }

    public function down()
    {
        // First update any class_teacher and principal roles to teacher before removing the enum values
        $this->db->query("UPDATE users SET role = 'teacher' WHERE role IN ('class_teacher', 'principal')");

        // Drop the title column
        $this->forge->dropColumn('users', 'title');

        // Revert the role ENUM to original values
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'student') DEFAULT 'student'");
    }
}
