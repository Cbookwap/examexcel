<?php

namespace App\Models;

use CodeIgniter\Model;

class SecurityLogModel extends Model
{
    protected $table = 'security_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_attempt_id', 'event_type', 'event_data', 'severity',
        'ip_address', 'user_agent', 'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'event_data' => 'json'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false; // We handle created_at manually
    protected $dateFormat = 'datetime';

    // Validation
    protected $validationRules = [
        'event_type' => 'required|max_length[100]',
        'severity' => 'required|in_list[low,medium,high,critical]'
    ];

    protected $validationMessages = [
        'event_type' => [
            'required' => 'Event type is required',
            'max_length' => 'Event type cannot exceed 100 characters'
        ],
        'severity' => [
            'required' => 'Severity level is required',
            'in_list' => 'Severity must be one of: low, medium, high, critical'
        ]
    ];

    // Event types constants
    const EVENT_LOGIN_ATTEMPT = 'login_attempt';
    const EVENT_LOGIN_SUCCESS = 'login_success';
    const EVENT_LOGIN_FAILED = 'login_failed';
    const EVENT_LOGOUT = 'logout';
    const EVENT_EXAM_START = 'exam_start';
    const EVENT_EXAM_SUBMIT = 'exam_submit';
    const EVENT_TAB_SWITCH = 'tab_switch';
    const EVENT_WINDOW_BLUR = 'window_blur';
    const EVENT_COPY_PASTE = 'copy_paste_attempt';
    const EVENT_RIGHT_CLICK = 'right_click_attempt';
    const EVENT_FULLSCREEN_EXIT = 'fullscreen_exit';
    const EVENT_BROWSER_RESIZE = 'browser_resize';
    const EVENT_SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    const EVENT_VIOLATION = 'violation';
    const EVENT_UNAUTHORIZED_ACCESS = 'unauthorized_access';
    const EVENT_PASSWORD_CHANGE = 'password_change';
    const EVENT_ACCOUNT_LOCKED = 'account_locked';
    const EVENT_MULTIPLE_SESSIONS = 'multiple_sessions';

    // Severity levels
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    /**
     * Log a security event
     */
    public function logEvent($eventType, $severity = self::SEVERITY_MEDIUM, $eventData = null, $examAttemptId = null, $ipAddress = null, $userAgent = null)
    {
        try {
            // Check if table exists before attempting to insert
            if (!$this->db->tableExists($this->table)) {
                // Table doesn't exist, skip logging for now
                return true;
            }

            $request = \Config\Services::request();

            $data = [
                'event_type' => $eventType,
                'severity' => $severity,
                'event_data' => $eventData,
                'exam_attempt_id' => $examAttemptId,
                'ip_address' => $ipAddress ?: $request->getIPAddress(),
                'user_agent' => $userAgent ?: $request->getUserAgent(),
                'created_at' => date('Y-m-d H:i:s')
            ];

            return $this->insert($data);
        } catch (\Exception $e) {
            // Log the error but don't break the application
            log_message('error', 'SecurityLogModel::logEvent failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get security logs with filters
     */
    public function getSecurityLogs($filters = [])
    {
        $builder = $this->select('security_logs.*,
                                 exam_attempts.exam_id,
                                 exams.title as exam_title,
                                 users.first_name,
                                 users.last_name,
                                 COALESCE(users.student_id, users.username) as student_id')
                        ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id', 'left')
                        ->join('exams', 'exams.id = exam_attempts.exam_id', 'left')
                        ->join('users', 'users.id = exam_attempts.student_id', 'left');

        // Apply filters
        if (!empty($filters['severity'])) {
            $builder->where('security_logs.severity', $filters['severity']);
        }

        if (!empty($filters['event_type'])) {
            $builder->where('security_logs.event_type', $filters['event_type']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('security_logs.created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $builder->where('security_logs.created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['exam_id'])) {
            $builder->where('exam_attempts.exam_id', $filters['exam_id']);
        }

        return $builder->orderBy('security_logs.created_at', 'DESC')->findAll();
    }

    /**
     * Get security statistics
     */
    public function getSecurityStats($dateFrom = null, $dateTo = null)
    {
        $builder = $this->db->table($this->table);

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $stats = [
            'total_events' => $builder->countAllResults(false),
            'critical_events' => $builder->where('severity', 'critical')->countAllResults(false),
            'high_events' => $builder->where('severity', 'high')->countAllResults(false),
            'medium_events' => $builder->where('severity', 'medium')->countAllResults(false),
            'low_events' => $builder->where('severity', 'low')->countAllResults(false)
        ];

        // Get event type breakdown
        $eventTypes = $this->select('event_type, COUNT(*) as count')
                          ->groupBy('event_type')
                          ->orderBy('count', 'DESC')
                          ->findAll();

        $stats['event_types'] = $eventTypes;

        return $stats;
    }

    /**
     * Get recent violations
     */
    public function getRecentViolations($limit = 10)
    {
        return $this->select('security_logs.*,
                             exam_attempts.exam_id,
                             exams.title as exam_title,
                             users.first_name,
                             users.last_name,
                             COALESCE(users.student_id, users.username) as student_id')
                    ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id', 'left')
                    ->join('exams', 'exams.id = exam_attempts.exam_id', 'left')
                    ->join('users', 'users.id = exam_attempts.student_id', 'left')
                    ->whereIn('severity', ['high', 'critical'])
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get top violators
     */
    public function getTopViolators($limit = 10)
    {
        return $this->select('users.first_name, users.last_name,
                             COALESCE(users.student_id, users.username) as student_number,
                             COUNT(*) as violation_count,
                             MAX(security_logs.created_at) as last_violation')
                    ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id')
                    ->join('users', 'users.id = exam_attempts.student_id')
                    ->whereIn('severity', ['high', 'critical'])
                    ->groupBy('users.id')
                    ->orderBy('violation_count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Clean old logs (older than specified days)
     */
    public function cleanOldLogs($days = 90)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $cutoffDate)->delete();
    }

    /**
     * Get usage analytics data
     */
    public function getUsageAnalytics()
    {
        $stats = [];

        // Get total events
        $stats['total_events'] = $this->countAll();

        // Get events by type
        $eventTypes = $this->select('event_type, COUNT(*) as count')
                          ->groupBy('event_type')
                          ->orderBy('count', 'DESC')
                          ->findAll();
        $stats['event_types'] = $eventTypes;

        // Get events by severity
        $severityStats = $this->select('severity, COUNT(*) as count')
                             ->groupBy('severity')
                             ->findAll();
        $stats['severity_stats'] = $severityStats;

        // Get daily activity for last 30 days
        $dailyActivity = $this->select('DATE(created_at) as date, COUNT(*) as count')
                             ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                             ->groupBy('DATE(created_at)')
                             ->orderBy('date', 'ASC')
                             ->findAll();
        $stats['daily_activity'] = $dailyActivity;

        return $stats;
    }
}
