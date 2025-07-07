<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiveSessionModel extends Model
{
    protected $table = 'active_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'session_id',
        'user_id',
        'user_role',
        'ip_address',
        'user_agent',
        'last_activity',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'last_activity';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'session_id' => 'required|max_length[128]',
        'user_id' => 'required|integer',
        'user_role' => 'required|in_list[admin,teacher,student]',
        'ip_address' => 'permit_empty|max_length[45]',
        'user_agent' => 'permit_empty'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

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
     * Create or update active session
     */
    public function createSession($sessionId, $userId, $userRole, $ipAddress = null, $userAgent = null)
    {
        try {
            // First, deactivate any existing sessions for this user
            $this->where('user_id', $userId)->set(['is_active' => 0])->update();

            // Create new session record
            $data = [
                'session_id' => $sessionId,
                'user_id' => $userId,
                'user_role' => $userRole,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'last_activity' => date('Y-m-d H:i:s'),
                'is_active' => 1
            ];

            return $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Failed to create active session: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update session activity
     */
    public function updateActivity($sessionId)
    {
        try {
            return $this->where('session_id', $sessionId)
                       ->where('is_active', 1)
                       ->set(['last_activity' => date('Y-m-d H:i:s')])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Failed to update session activity: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate session
     */
    public function deactivateSession($sessionId)
    {
        try {
            return $this->where('session_id', $sessionId)
                       ->set(['is_active' => 0])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Failed to deactivate session: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate all sessions for a user
     */
    public function deactivateUserSessions($userId)
    {
        try {
            return $this->where('user_id', $userId)
                       ->set(['is_active' => 0])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Failed to deactivate user sessions: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate all sessions for specific roles
     */
    public function deactivateRoleSessions($roles)
    {
        try {
            if (!is_array($roles)) {
                $roles = [$roles];
            }

            return $this->whereIn('user_role', $roles)
                       ->where('is_active', 1)
                       ->set(['is_active' => 0])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Failed to deactivate role sessions: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active sessions by role
     */
    public function getActiveSessionsByRole($role)
    {
        try {
            return $this->select('active_sessions.*, users.first_name, users.last_name, users.email')
                       ->join('users', 'users.id = active_sessions.user_id')
                       ->where('active_sessions.user_role', $role)
                       ->where('active_sessions.is_active', 1)
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Failed to get active sessions by role: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all active sessions
     */
    public function getActiveSessions()
    {
        try {
            return $this->select('active_sessions.*, users.first_name, users.last_name, users.email')
                       ->join('users', 'users.id = active_sessions.user_id')
                       ->where('active_sessions.is_active', 1)
                       ->orderBy('active_sessions.last_activity', 'DESC')
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Failed to get active sessions: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions($timeoutMinutes = 120)
    {
        try {
            $expiredTime = date('Y-m-d H:i:s', strtotime("-{$timeoutMinutes} minutes"));
            
            return $this->where('last_activity <', $expiredTime)
                       ->set(['is_active' => 0])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Failed to cleanup expired sessions: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if session is active
     */
    public function isSessionActive($sessionId)
    {
        try {
            $session = $this->where('session_id', $sessionId)
                           ->where('is_active', 1)
                           ->first();
            
            return $session !== null;
        } catch (\Exception $e) {
            log_message('error', 'Failed to check session status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get session statistics
     */
    public function getSessionStats()
    {
        try {
            $stats = [
                'total_active' => $this->where('is_active', 1)->countAllResults(),
                'admin_sessions' => $this->where('user_role', 'admin')->where('is_active', 1)->countAllResults(),
                'teacher_sessions' => $this->where('user_role', 'teacher')->where('is_active', 1)->countAllResults(),
                'student_sessions' => $this->where('user_role', 'student')->where('is_active', 1)->countAllResults(),
            ];

            return $stats;
        } catch (\Exception $e) {
            log_message('error', 'Failed to get session stats: ' . $e->getMessage());
            return [
                'total_active' => 0,
                'admin_sessions' => 0,
                'teacher_sessions' => 0,
                'student_sessions' => 0
            ];
        }
    }
}
