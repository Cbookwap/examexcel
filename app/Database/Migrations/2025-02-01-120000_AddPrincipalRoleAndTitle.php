<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrincipalRoleAndTitle extends Migration
{
    public function up()
    {
        // Modify the role ENUM to include 'principal'
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'student', 'class_teacher', 'principal') DEFAULT 'student'");
        
        // Add title field for principal roles and other positions
        $this->forge->addColumn('users', [
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'role',
                'comment'    => 'Job title for principals (e.g., Principal, Vice Principal, HOD, etc.)'
            ]
        ]);
        
        // Set default title for existing principal account if it exists
        $this->db->query("UPDATE users SET title = 'Principal' WHERE role = 'principal' AND title IS NULL");
    }

    public function down()
    {
        // First update any principal roles to admin before removing the enum value
        $this->db->query("UPDATE users SET role = 'admin' WHERE role = 'principal'");
        
        // Drop the title column
        $this->forge->dropColumn('users', 'title');
        
        // Revert the role ENUM to exclude principal
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'student', 'class_teacher') DEFAULT 'student'");
    }
}
