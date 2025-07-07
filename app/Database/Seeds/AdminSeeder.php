<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Get admin email from environment or use default
        $adminEmail = env('app.admin_email', 'admin@localhost.com');

        // Check if admin already exists
        $existingAdmin = $this->db->table('users')->where('email', $adminEmail)->get()->getRow();

        if (!$existingAdmin) {
            // Create admin user with username
            $username = explode('@', $adminEmail)[0];
            $this->db->table('users')->insert([
                'username' => $username,
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'email' => $adminEmail,
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            echo "Admin user created successfully!\n";
            echo "Email: {$adminEmail}\n";
            echo "Password: admin123\n";
        } else {
            echo "Admin user already exists from installation!\n";
            echo "Use your installation credentials to login.\n";
        }

        // Create a few sample users if they don't exist (only if no admin exists from installation)
        if (!$existingAdmin) {
            $sampleUsers = [
                [
                    'username' => 'teacher',
                    'first_name' => 'John',
                    'last_name' => 'Teacher',
                    'email' => 'teacher@srmscbt.com',
                    'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                    'role' => 'teacher',
                    'employee_id' => 'T001',
                    'is_active' => 1,
                    'is_verified' => 1
                ],
                [
                    'username' => 'student',
                    'first_name' => 'Alice',
                    'last_name' => 'Student',
                    'email' => 'student@srmscbt.com',
                    'password' => password_hash('student123', PASSWORD_DEFAULT),
                    'role' => 'student',
                    'student_id' => 'S001',
                    'class_id' => 1,
                    'is_active' => 1,
                    'is_verified' => 1
                ]
            ];

            foreach ($sampleUsers as $user) {
                $existing = $this->db->table('users')->where('email', $user['email'])->get()->getRow();
                if (!$existing) {
                    $user['created_at'] = date('Y-m-d H:i:s');
                    $user['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->table('users')->insert($user);
                    echo "Created user: {$user['email']}\n";
                }
            }
        } else {
            echo "Skipping sample user creation - admin exists from installation.\n";
        }
    }
}
