<?php

namespace App\Models;

use CodeIgniter\Model;

class ViolationModel extends Model
{
    protected $table = 'student_violations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id', 'violation_count', 'punishment_type', 'punishment_duration',
        'severity', 'notes', 'admin_id', 'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false; // We handle timestamps manually
    protected $dateFormat = 'datetime';

    // Validation
    protected $validationRules = [
        'student_id' => 'required|integer',
        'violation_count' => 'required|integer|greater_than[0]',
        'punishment_type' => 'required|in_list[warning,temporary_suspension,permanent_ban]',
        'severity' => 'required|in_list[low,medium,high,critical]'
    ];

    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student ID is required',
            'integer' => 'Student ID must be a valid integer'
        ],
        'violation_count' => [
            'required' => 'Violation count is required',
            'integer' => 'Violation count must be a valid integer',
            'greater_than' => 'Violation count must be greater than 0'
        ],
        'punishment_type' => [
            'required' => 'Punishment type is required',
            'in_list' => 'Punishment type must be one of: warning, temporary_suspension, permanent_ban'
        ],
        'severity' => [
            'required' => 'Severity level is required',
            'in_list' => 'Severity must be one of: low, medium, high, critical'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get student violations with user details
     */
    public function getStudentViolations($studentId = null, $limit = 50)
    {
        $builder = $this->select('student_violations.*, users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_number, users.email')
                        ->join('users', 'users.id = student_violations.student_id')
                        ->orderBy('student_violations.created_at', 'DESC');

        if ($studentId) {
            $builder->where('student_violations.student_id', $studentId);
        }

        return $builder->limit($limit)->findAll();
    }

    /**
     * Get violations with user details for admin/principal view
     */
    public function getViolationsWithUserDetails($limit = 100)
    {
        return $this->select('
            student_violations.*,
            users.first_name,
            users.last_name,
            users.student_id,
            users.username,
            users.email,
            users.exam_banned,
            users.exam_suspended_until,
            CASE
                WHEN users.exam_banned = 1 THEN 1
                WHEN users.exam_suspended_until IS NOT NULL AND users.exam_suspended_until > NOW() THEN 1
                ELSE 0
            END as is_banned,
            "security_violation" as violation_type,
            "Exam security violation" as description
        ')
        ->join('users', 'users.id = student_violations.student_id')
        ->orderBy('student_violations.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    /**
     * Get violation statistics
     */
    public function getViolationStats($dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('
            COUNT(*) as total_violations,
            SUM(CASE WHEN punishment_type = "warning" THEN 1 ELSE 0 END) as warnings,
            SUM(CASE WHEN punishment_type = "temporary_suspension" THEN 1 ELSE 0 END) as suspensions,
            SUM(CASE WHEN punishment_type = "permanent_ban" THEN 1 ELSE 0 END) as bans,
            SUM(CASE WHEN severity = "critical" THEN 1 ELSE 0 END) as critical_violations,
            SUM(CASE WHEN severity = "high" THEN 1 ELSE 0 END) as high_violations
        ');

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo);
        }

        return $builder->first();
    }

    /**
     * Get top violators
     */
    public function getTopViolators($limit = 10)
    {
        return $this->select('users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_number,
                             MAX(student_violations.violation_count) as max_violations,
                             MAX(student_violations.created_at) as last_violation,
                             student_violations.punishment_type as current_punishment')
                    ->join('users', 'users.id = student_violations.student_id')
                    ->groupBy('student_violations.student_id')
                    ->orderBy('max_violations', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get recent violations
     */
    public function getRecentViolations($limit = 10)
    {
        return $this->select('student_violations.*, users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_number')
                    ->join('users', 'users.id = student_violations.student_id')
                    ->orderBy('student_violations.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Check if student is currently suspended
     */
    public function isStudentSuspended($studentId)
    {
        $userModel = new UserModel();
        $user = $userModel->find($studentId);
        
        if (!$user) {
            return false;
        }

        // Check for permanent ban
        if ($user['exam_banned'] ?? false) {
            return [
                'suspended' => true,
                'type' => 'permanent',
                'reason' => $user['ban_reason'] ?? 'Security violations'
            ];
        }

        // Check for temporary suspension
        if (isset($user['exam_suspended_until']) && $user['exam_suspended_until']) {
            $suspendedUntil = strtotime($user['exam_suspended_until']);
            if ($suspendedUntil > time()) {
                return [
                    'suspended' => true,
                    'type' => 'temporary',
                    'until' => $user['exam_suspended_until'],
                    'reason' => $user['suspension_reason'] ?? 'Security violations'
                ];
            }
        }

        return ['suspended' => false];
    }

    /**
     * Get student's violation history
     */
    public function getStudentViolationHistory($studentId)
    {
        return $this->where('student_id', $studentId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Clear student violations (admin action)
     */
    public function clearStudentViolations($studentId, $adminId, $reason = null)
    {
        // Archive current violations
        $violations = $this->where('student_id', $studentId)->findAll();
        
        foreach ($violations as $violation) {
            $this->update($violation['id'], [
                'notes' => ($violation['notes'] ?? '') . "\nCleared by admin ID {$adminId}. Reason: " . ($reason ?? 'No reason provided'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Clear user suspension/ban status
        $userModel = new UserModel();
        $userModel->update($studentId, [
            'exam_banned' => 0,
            'exam_suspended_until' => null,
            'ban_reason' => null,
            'suspension_reason' => null
        ]);

        return true;
    }
}
