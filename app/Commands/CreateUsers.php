<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateUsers extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'create:users';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Create demo users for the CBT system.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // Check if admin already exists from installation
        $existingAdmin = $db->table('users')->where('role', 'admin')->get()->getRow();
        if ($existingAdmin) {
            CLI::write('Admin user already exists from installation!', 'yellow');
            CLI::write('Use your installation credentials to login.', 'yellow');
            return;
        }

        // Create admin user (demo only)
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@srmscbt.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'first_name' => 'Demo',
            'last_name' => 'Administrator',
            'role' => 'admin',
            'phone' => '+1234567890',
            'gender' => 'male',
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $db->table('users')->insert($adminData);

        if ($result) {
            CLI::write('Admin user created successfully!', 'green');
            CLI::write('Username: admin', 'yellow');
            CLI::write('Password: admin123', 'yellow');
        } else {
            CLI::write('Failed to create admin user.', 'red');
        }

        // Create teacher user (demo only)
        $teacherData = [
            'username' => 'teacher',
            'email' => 'teacher@srmscbt.com',
            'password' => password_hash('teacher123', PASSWORD_DEFAULT),
            'first_name' => 'Demo',
            'last_name' => 'Teacher',
            'role' => 'teacher',
            'phone' => '+1234567891',
            'gender' => 'male',
            'employee_id' => 'EMP001',
            'department' => 'Computer Science',
            'qualification' => 'M.Sc Computer Science',
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result2 = $db->table('users')->insert($teacherData);

        if ($result2) {
            CLI::write('Teacher user created successfully!', 'green');
            CLI::write('Username: teacher', 'yellow');
            CLI::write('Password: teacher123', 'yellow');
        }

        // Create a sample class first
        $classData = [
            'name' => 'Computer Science - Year 1',
            'section' => 'A',
            'academic_year' => '2024-2025',
            'description' => 'First year computer science students',
            'max_students' => 50,
            'class_teacher_id' => 2, // Teacher ID
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $classResult = $db->table('classes')->insert($classData);

        if ($classResult) {
            CLI::write('Sample class created successfully!', 'green');
        }

        // Create student user (demo only)
        $studentData = [
            'username' => 'student',
            'email' => 'student@srmscbt.com',
            'password' => password_hash('student123', PASSWORD_DEFAULT),
            'first_name' => 'Demo',
            'last_name' => 'Student',
            'role' => 'student',
            'phone' => '+1234567892',
            'gender' => 'female',
            'student_id' => 'STU001',
            'class_id' => 1,
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result3 = $db->table('users')->insert($studentData);

        if ($result3) {
            CLI::write('Student user created successfully!', 'green');
            CLI::write('Username: student', 'yellow');
            CLI::write('Password: student123', 'yellow');
        }

        CLI::write('All demo users created! You can now login to the system.', 'green');
        CLI::write('Note: These are demo users for development only.', 'yellow');
    }
}
