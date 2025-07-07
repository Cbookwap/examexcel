<?php

namespace App\Models;

use CodeIgniter\Model;

class PracticeSessionModel extends Model
{
    protected $table = 'practice_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id',
        'subject_id',
        'class_id',
        'category',
        'questions',
        'answers',
        'start_time',
        'end_time',
        'status',
        'score',
        'total_questions',
        'percentage',
        'ip_address',
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'student_id' => 'required|integer',
        'subject_id' => 'permit_empty|integer',
        'class_id' => 'permit_empty|integer',
        'category' => 'permit_empty|string|max_length[100]',
        'questions' => 'required',
        'status' => 'required|in_list[in_progress,completed,abandoned]'
    ];

    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student ID is required',
            'integer' => 'Student ID must be a valid number'
        ],
        'subject_id' => [
            'required' => 'Subject ID is required',
            'integer' => 'Subject ID must be a valid number'
        ],
        'class_id' => [
            'required' => 'Class ID is required',
            'integer' => 'Class ID must be a valid number'
        ],
        'questions' => [
            'required' => 'Questions data is required'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: in_progress, completed, abandoned'
        ]
    ];

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

    // Practice session statuses
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ABANDONED = 'abandoned';

    /**
     * Get practice sessions for a student
     */
    public function getStudentPracticeSessions($studentId, $limit = null)
    {
        $builder = $this->select('practice_sessions.*,
                                 COALESCE(subjects.name, practice_sessions.category) as subject_name,
                                 classes.name as class_name')
                       ->join('subjects', 'subjects.id = practice_sessions.subject_id', 'left')
                       ->join('classes', 'classes.id = practice_sessions.class_id', 'left')
                       ->where('practice_sessions.student_id', $studentId)
                       ->orderBy('practice_sessions.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get completed practice sessions for a student
     */
    public function getCompletedPracticeSessions($studentId, $limit = null)
    {
        $builder = $this->select('practice_sessions.*, subjects.name as subject_name, classes.name as class_name')
                       ->join('subjects', 'subjects.id = practice_sessions.subject_id')
                       ->join('classes', 'classes.id = practice_sessions.class_id')
                       ->where('practice_sessions.student_id', $studentId)
                       ->where('practice_sessions.status', self::STATUS_COMPLETED)
                       ->orderBy('practice_sessions.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get practice session with details
     */
    public function getPracticeSessionWithDetails($id)
    {
        return $this->select('practice_sessions.*, subjects.name as subject_name, classes.name as class_name,
                             users.first_name, users.last_name, users.student_id')
                   ->join('subjects', 'subjects.id = practice_sessions.subject_id')
                   ->join('classes', 'classes.id = practice_sessions.class_id')
                   ->join('users', 'users.id = practice_sessions.student_id')
                   ->where('practice_sessions.id', $id)
                   ->first();
    }

    /**
     * Get practice statistics for a student
     */
    public function getStudentPracticeStats($studentId)
    {
        $total = $this->where('student_id', $studentId)->countAllResults();
        $completed = $this->where('student_id', $studentId)
                         ->where('status', self::STATUS_COMPLETED)
                         ->countAllResults();

        $avgScore = 0;
        if ($completed > 0) {
            $avgScoreResult = $this->select('AVG(percentage) as avg_percentage')
                                  ->where('student_id', $studentId)
                                  ->where('status', self::STATUS_COMPLETED)
                                  ->first();
            $avgScore = round($avgScoreResult['avg_percentage'] ?? 0, 1);
        }

        return [
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $total - $completed,
            'average_score' => $avgScore
        ];
    }

    /**
     * Get practice statistics by subject for a student
     */
    public function getStudentPracticeStatsBySubject($studentId)
    {
        return $this->select('subjects.name as subject_name,
                             COUNT(*) as total_sessions,
                             AVG(practice_sessions.percentage) as avg_percentage,
                             MAX(practice_sessions.percentage) as best_score')
                   ->join('subjects', 'subjects.id = practice_sessions.subject_id')
                   ->where('practice_sessions.student_id', $studentId)
                   ->where('practice_sessions.status', self::STATUS_COMPLETED)
                   ->groupBy('practice_sessions.subject_id')
                   ->orderBy('subjects.name', 'ASC')
                   ->findAll();
    }

    /**
     * Clean up abandoned practice sessions (older than 24 hours)
     */
    public function cleanupAbandonedSessions()
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-24 hours'));

        return $this->where('status', self::STATUS_IN_PROGRESS)
                   ->where('created_at <', $cutoffTime)
                   ->set('status', self::STATUS_ABANDONED)
                   ->update();
    }

    /**
     * Get recent practice activity for dashboard
     */
    public function getRecentPracticeActivity($limit = 10)
    {
        return $this->select('practice_sessions.*, subjects.name as subject_name,
                             users.first_name, users.last_name, users.student_id')
                   ->join('subjects', 'subjects.id = practice_sessions.subject_id')
                   ->join('users', 'users.id = practice_sessions.student_id')
                   ->where('practice_sessions.status', self::STATUS_COMPLETED)
                   ->orderBy('practice_sessions.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get practice performance trends
     */
    public function getPracticePerformanceTrends($studentId, $days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        return $this->select('DATE(created_at) as practice_date,
                             COUNT(*) as sessions_count,
                             AVG(percentage) as avg_score')
                   ->where('student_id', $studentId)
                   ->where('status', self::STATUS_COMPLETED)
                   ->where('DATE(created_at) >=', $startDate)
                   ->groupBy('DATE(created_at)')
                   ->orderBy('practice_date', 'ASC')
                   ->findAll();
    }
}
