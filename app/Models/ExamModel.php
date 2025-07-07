<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title', 'description', 'subject_id', 'class_id', 'exam_mode', 'session_id', 'term_id',
        'exam_type', 'status', 'duration_minutes', 'total_marks', 'passing_marks',
        'question_count', 'total_questions', 'questions_configured', 'negative_marking', 'negative_marks_per_question',
        'randomize_questions', 'randomize_options', 'show_result_immediately',
        'allow_review', 'require_proctoring', 'browser_lockdown', 'prevent_copy_paste',
        'disable_right_click', 'calculator_enabled', 'exam_pause_enabled', 'max_attempts',
        'attempt_delay_minutes', 'start_time',
        'end_time', 'instructions', 'settings', 'allowed_ips', 'is_active', 'created_by',
        'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        // Disabled JSON casting due to compatibility issues
        // Will handle JSON parsing manually where needed
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'subject_id' => 'permit_empty|integer',
        'class_id' => 'required|integer',
        'duration_minutes' => 'required|integer|greater_than[0]',
        'total_marks' => 'required|integer|greater_than[0]',
        'question_count' => 'permit_empty|integer|greater_than_equal_to[0]'
    ];

    // Exam statuses
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get exam with related data
     */
    public function getExamWithDetails($id)
    {
        return $this->select('exams.id, exams.title, exams.description, exams.subject_id, exams.class_id, exams.exam_mode,
                             exams.session_id, exams.term_id, exams.exam_type, exams.status, exams.duration_minutes,
                             exams.total_marks, exams.passing_marks, exams.question_count, exams.total_questions,
                             exams.questions_configured, exams.negative_marking, exams.negative_marks_per_question,
                             exams.randomize_questions, exams.randomize_options, exams.show_result_immediately,
                             exams.allow_review, exams.require_proctoring, exams.browser_lockdown, exams.prevent_copy_paste,
                             exams.disable_right_click, exams.calculator_enabled, exams.exam_pause_enabled,
                             exams.max_attempts, exams.attempt_delay_minutes, exams.start_time, exams.end_time,
                             exams.is_active, exams.created_by, exams.created_at, exams.updated_at,
                             subjects.name as subject_name, classes.name as class_name,
                             users.first_name, users.last_name')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->join('users', 'users.id = exams.created_by', 'left')
                   ->where('exams.id', $id)
                   ->first();
    }

    /**
     * Get exams for a specific class
     */
    public function getExamsForClass($classId, $limit = null)
    {
        $builder = $this->select('exams.*, subjects.name as subject_name')
                       ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                       ->where('exams.class_id', $classId)
                       ->where('exams.is_active', 1)
                       ->orderBy('exams.start_time', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get active exams
     */
    public function getActiveExams()
    {
        $now = date('Y-m-d H:i:s');
        return $this->select('exams.*, subjects.name as subject_name, classes.name as class_name')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->where('exams.start_time <=', $now)
                   ->where('exams.end_time >=', $now)
                   ->where('exams.is_active', 1)
                   ->orderBy('exams.start_time', 'ASC')
                   ->findAll();
    }

    /**
     * Get upcoming exams
     */
    public function getUpcomingExams($limit = 10)
    {
        $now = date('Y-m-d H:i:s');
        return $this->select('exams.*, subjects.name as subject_name, classes.name as class_name')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->where('exams.start_time >', $now)
                   ->where('exams.is_active', 1)
                   ->orderBy('exams.start_time', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get exam status
     */
    public function getExamStatus($exam)
    {
        // Use application timezone for consistent time comparison
        $timezone = new \DateTimeZone(config('App')->appTimezone);
        $now = new \DateTime('now', $timezone);
        $nowString = $now->format('Y-m-d H:i:s');

        if (!$exam['is_active']) {
            return self::STATUS_CANCELLED;
        }

        if ($exam['end_time'] < $nowString) {
            return self::STATUS_COMPLETED;
        }

        if ($exam['start_time'] <= $nowString && $exam['end_time'] >= $nowString) {
            return self::STATUS_ACTIVE;
        }

        if ($exam['start_time'] > $nowString) {
            return self::STATUS_SCHEDULED;
        }

        return self::STATUS_DRAFT;
    }

    /**
     * Get exam statistics
     */
    public function getExamStats()
    {
        $total = $this->countAllResults();
        $active = $this->getActiveExams();
        $upcoming = $this->getUpcomingExams();

        return [
            'total' => $total,
            'active' => count($active),
            'upcoming' => count($upcoming),
            'completed' => $total - count($active) - count($upcoming)
        ];
    }

    /**
     * Get exam schedules for reports
     */
    public function getExamSchedules()
    {
        return $this->select('exams.*, subjects.name as subject_name, classes.name as class_name,
                             users.first_name, users.last_name')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->join('users', 'users.id = exams.created_by')
                   ->where('exams.is_active', 1)
                   ->orderBy('exams.start_time', 'ASC')
                   ->findAll();
    }

    /**
     * Get exams by teacher
     */
    public function getExamsByTeacher($teacherId)
    {
        return $this->select('exams.*, subjects.name as subject_name, classes.name as class_name')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->where('exams.created_by', $teacherId)
                   ->where('exams.is_active', 1)
                   ->orderBy('exams.created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Check if student can take exam
     */
    public function canStudentTakeExam($examId, $studentId)
    {
        $exam = $this->find($examId);
        if (!$exam) return false;

        // Use application timezone for consistent time comparison
        $timezone = new \DateTimeZone(config('App')->appTimezone);
        $now = new \DateTime('now', $timezone);
        $nowString = $now->format('Y-m-d H:i:s');

        // Check if exam is active and within time window
        if (!$exam['is_active'] || $exam['start_time'] > $nowString || $exam['end_time'] < $nowString) {
            return false;
        }

        // Check attempt limits
        $attemptModel = new ExamAttemptModel();
        $attempts = $attemptModel->where('exam_id', $examId)
                                ->where('student_id', $studentId)
                                ->findAll();

        $maxAttempts = $exam['max_attempts'] ?? 1;

        // Check if student has exceeded maximum attempts
        if (count($attempts) >= $maxAttempts) {
            return false;
        }

        // Check if there's a delay requirement between attempts
        if (!empty($attempts) && $exam['attempt_delay_minutes'] > 0) {
            $lastAttempt = end($attempts);
            $lastAttemptTime = new \DateTime($lastAttempt['created_at'], $timezone);
            $delayUntil = $lastAttemptTime->add(new \DateInterval('PT' . $exam['attempt_delay_minutes'] . 'M'));

            if ($now < $delayUntil) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get student's attempt information for an exam
     */
    public function getStudentAttemptInfo($examId, $studentId)
    {
        $exam = $this->find($examId);
        if (!$exam) return null;

        $attemptModel = new ExamAttemptModel();
        $attempts = $attemptModel->where('exam_id', $examId)
                                ->where('student_id', $studentId)
                                ->orderBy('created_at', 'DESC')
                                ->findAll();

        $maxAttempts = $exam['max_attempts'] ?? 1;
        $attemptsUsed = count($attempts);
        $attemptsRemaining = max(0, $maxAttempts - $attemptsUsed);

        $canRetake = $this->canStudentTakeExam($examId, $studentId);

        $nextAttemptTime = null;
        if (!$canRetake && !empty($attempts) && $exam['attempt_delay_minutes'] > 0) {
            $lastAttempt = end($attempts);
            $timezone = new \DateTimeZone(config('App')->appTimezone);
            $lastAttemptTime = new \DateTime($lastAttempt['created_at'], $timezone);
            $nextAttemptTime = $lastAttemptTime->add(new \DateInterval('PT' . $exam['attempt_delay_minutes'] . 'M'));
        }

        return [
            'max_attempts' => $maxAttempts,
            'attempts_used' => $attemptsUsed,
            'attempts_remaining' => $attemptsRemaining,
            'can_retake' => $canRetake,
            'next_attempt_time' => $nextAttemptTime ? $nextAttemptTime->format('Y-m-d H:i:s') : null,
            'attempts' => $attempts
        ];
    }

    /**
     * Check if exam is multi-subject
     */
    public function isMultiSubject($examId)
    {
        $exam = $this->find($examId);
        return $exam && $exam['exam_mode'] === 'multi_subject';
    }

    /**
     * Get exam with subjects (for multi-subject exams)
     */
    public function getExamWithSubjects($examId)
    {
        $exam = $this->find($examId);
        if (!$exam) return null;

        if ($exam['exam_mode'] === 'multi_subject') {
            $examSubjectModel = new \App\Models\ExamSubjectModel();
            $exam['subjects'] = $examSubjectModel->getExamSubjects($examId);
        } else {
            // For single subject exams, get the subject details
            $subjectModel = new \App\Models\SubjectModel();
            $subject = $subjectModel->find($exam['subject_id']);
            $exam['subject'] = $subject;
        }

        return $exam;
    }

    /**
     * Get exams with proper subject information
     */
    public function getExamsWithSubjects($conditions = [])
    {
        $builder = $this->select('exams.*, classes.name as class_name');
        $builder->join('classes', 'classes.id = exams.class_id', 'left');

        foreach ($conditions as $field => $value) {
            $builder->where($field, $value);
        }

        $exams = $builder->orderBy('exams.created_at', 'DESC')->findAll();

        foreach ($exams as &$exam) {
            if ($exam['exam_mode'] === 'multi_subject') {
                $examSubjectModel = new \App\Models\ExamSubjectModel();
                $exam['subjects'] = $examSubjectModel->getExamSubjects($exam['id']);
                $exam['subject_names'] = array_column($exam['subjects'], 'subject_name');

                // Ensure subject_names is always an array
                if (!is_array($exam['subject_names'])) {
                    $exam['subject_names'] = [];
                }
            } else {
                $subjectModel = new \App\Models\SubjectModel();
                $subject = $subjectModel->find($exam['subject_id']);
                $exam['subject_name'] = $subject['name'] ?? 'Unknown Subject';
                $exam['subject_names'] = [$exam['subject_name']];

                // Ensure subject_names is always an array
                if (!is_array($exam['subject_names'])) {
                    $exam['subject_names'] = ['Unknown Subject'];
                }
            }
        }

        return $exams;
    }

    /**
     * Mark exam questions as configured
     */
    public function markQuestionsConfigured($examId, $configured = true)
    {
        return $this->update($examId, ['questions_configured' => $configured ? 1 : 0]);
    }

    /**
     * Update exam totals from subjects
     */
    public function updateExamTotalsFromSubjects($examId)
    {
        $examSubjectModel = new \App\Models\ExamSubjectModel();

        $totalMarks = $examSubjectModel->getExamTotalMarks($examId);
        $totalQuestions = $examSubjectModel->getExamTotalQuestions($examId);
        $totalTime = $examSubjectModel->getExamTotalTime($examId);

        return $this->update($examId, [
            'total_marks' => $totalMarks,
            'total_questions' => $totalQuestions,
            'duration_minutes' => $totalTime > 0 ? $totalTime : null
        ]);
    }

    /**
     * Update exam total marks from actual question points
     */
    public function updateExamTotalMarksFromQuestions($examId)
    {
        $questionModel = new \App\Models\QuestionModel();
        $questions = $questionModel->getExamQuestions($examId);
        $actualTotalMarks = array_sum(array_column($questions, 'points'));

        if ($actualTotalMarks > 0) {
            return $this->update($examId, [
                'total_marks' => $actualTotalMarks,
                'total_questions' => count($questions)
            ]);
        }

        return false;
    }

    /**
     * Get all exams with related details (for principal/admin views)
     */
    public function getExamsWithDetails()
    {
        return $this->select('exams.id, exams.title, exams.description, exams.subject_id, exams.class_id, exams.exam_mode,
                             exams.exam_type, exams.status, exams.duration_minutes, exams.total_marks,
                             exams.passing_marks, exams.max_attempts, exams.start_time, exams.end_time, exams.is_active,
                             exams.total_questions, exams.question_count, exams.created_by, exams.created_at, exams.updated_at,
                             subjects.name as subject_name, classes.name as class_name,
                             users.first_name, users.last_name')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->join('classes', 'classes.id = exams.class_id', 'left')
                   ->join('users', 'users.id = exams.created_by', 'left')
                   ->orderBy('exams.created_at', 'DESC')
                   ->findAll();
    }
}
