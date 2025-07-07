<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username', 'email', 'password', 'first_name', 'last_name', 'role', 'title',
        'phone', 'date_of_birth', 'gender', 'address', 'profile_picture',
        'student_id', 'employee_id', 'class_id', 'department', 'qualification',
        'is_active', 'is_verified', 'last_login', 'exam_banned', 'ban_reason',
        'exam_suspended_until', 'suspension_reason'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'role' => 'required|in_list[admin,teacher,student,class_teacher,principal]',
        'title' => 'permit_empty|max_length[100]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters',
            'is_unique' => 'Username already exists'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already exists'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // Custom methods
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function findByStudentId($studentId)
    {
        return $this->where('student_id', $studentId)->first();
    }

    public function findByRole($role)
    {
        return $this->where('role', $role)->where('is_active', 1)->findAll();
    }

    public function findStudentsByClass($classId)
    {
        return $this->where('role', 'student')
                    ->where('class_id', $classId)
                    ->where('is_active', 1)
                    ->findAll();
    }

    public function findTeachers()
    {
        return $this->where('role', 'teacher')->where('is_active', 1)->findAll();
    }

    public function findStudents()
    {
        return $this->where('role', 'student')->where('is_active', 1)->findAll();
    }

    public function findPrincipals()
    {
        return $this->where('role', 'principal')->where('is_active', 1)->findAll();
    }

    public function findPrincipalsWithTitles()
    {
        return $this->select('id, username, email, first_name, last_name, title, phone, is_active, created_at')
                    ->where('role', 'principal')
                    ->where('is_active', 1)
                    ->findAll();
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function getActiveUsers()
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function getUserStats()
    {
        $stats = [];
        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        $stats['admins'] = $this->where('role', 'admin')->countAllResults();
        $stats['teachers'] = $this->where('role', 'teacher')->countAllResults();
        $stats['students'] = $this->where('role', 'student')->countAllResults();
        $stats['class_teachers'] = $this->where('role', 'class_teacher')->countAllResults();
        $stats['principals'] = $this->where('role', 'principal')->countAllResults();

        return $stats;
    }

    /**
     * Find class teacher by class ID
     */
    public function findClassTeacher($classId)
    {
        return $this->where('role', 'class_teacher')
                    ->where('class_id', $classId)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Create class teacher for a class
     */
    public function createClassTeacher($classId, $className, $username = null, $password = null)
    {
        // Generate username if not provided
        if (!$username) {
            $username = $this->generateClassTeacherUsername($className);
        }

        // Generate password if not provided
        if (!$password) {
            $password = 'class123'; // Default password
        }

        $data = [
            'username' => $username,
            'email' => $username . '@classteacher.local',
            'password' => $password, // Will be hashed by beforeInsert callback
            'first_name' => $className,
            'last_name' => 'Class Teacher',
            'role' => 'class_teacher',
            'class_id' => $classId,
            'is_active' => 1,
            'is_verified' => 1
        ];

        return $this->insert($data);
    }

    /**
     * Generate username for class teacher
     */
    private function generateClassTeacherUsername($className)
    {
        // Convert class name to username format (e.g., "JSS 1" -> "JSS-ONE")
        $username = strtoupper($className);
        $username = str_replace(' ', '-', $username);
        $username = preg_replace('/[^A-Z0-9\-]/', '', $username);

        // Convert numbers to words for better readability
        $numberWords = [
            '1' => 'ONE', '2' => 'TWO', '3' => 'THREE', '4' => 'FOUR', '5' => 'FIVE',
            '6' => 'SIX', '7' => 'SEVEN', '8' => 'EIGHT', '9' => 'NINE', '10' => 'TEN',
            '11' => 'ELEVEN', '12' => 'TWELVE'
        ];

        foreach ($numberWords as $number => $word) {
            $username = str_replace($number, $word, $username);
        }

        // Ensure uniqueness
        $originalUsername = $username;
        $counter = 1;
        while ($this->findByUsername($username)) {
            $username = $originalUsername . '-' . $counter;
            $counter++;
        }

        return $username;
    }

    public function searchUsers($keyword)
    {
        return $this->groupStart()
                    ->like('username', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('first_name', $keyword)
                    ->orLike('last_name', $keyword)
                    ->groupEnd()
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Get students with performance data
     */
    public function getStudentsWithPerformance()
    {
        return $this->select('users.*, classes.name as class_name,
                             AVG(exam_attempts.percentage) as average_percentage,
                             COUNT(exam_attempts.id) as total_attempts')
                   ->join('classes', 'classes.id = users.class_id', 'left')
                   ->join('exam_attempts', 'exam_attempts.student_id = users.id', 'left')
                   ->where('users.role', 'student')
                   ->where('users.is_active', 1)
                   ->groupBy('users.id')
                   ->orderBy('average_percentage', 'DESC')
                   ->findAll();
    }

    /**
     * Check if user is banned from exams
     */
    public function isExamBanned($userId)
    {
        $user = $this->find($userId);
        return $user && ($user['exam_banned'] ?? false);
    }

    /**
     * Check if user is temporarily suspended
     */
    public function isExamSuspended($userId)
    {
        $user = $this->find($userId);
        if (!$user || !isset($user['exam_suspended_until'])) {
            return false;
        }

        $suspendedUntil = strtotime($user['exam_suspended_until']);
        return $suspendedUntil > time();
    }

    /**
     * Get suspension details
     */
    public function getSuspensionDetails($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        $details = [
            'is_banned' => $user['exam_banned'] ?? false,
            'ban_reason' => $user['ban_reason'] ?? null,
            'is_suspended' => false,
            'suspended_until' => null,
            'suspension_reason' => null
        ];

        if (isset($user['exam_suspended_until']) && $user['exam_suspended_until']) {
            $suspendedUntil = strtotime($user['exam_suspended_until']);
            if ($suspendedUntil > time()) {
                $details['is_suspended'] = true;
                $details['suspended_until'] = $user['exam_suspended_until'];
                $details['suspension_reason'] = $user['suspension_reason'] ?? null;
            }
        }

        return $details;
    }

    /**
     * Clear user suspension/ban
     */
    public function clearSuspension($userId)
    {
        return $this->update($userId, [
            'exam_banned' => 0,
            'exam_suspended_until' => null,
            'ban_reason' => null,
            'suspension_reason' => null
        ]);
    }
}
