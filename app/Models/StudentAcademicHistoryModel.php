<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAcademicHistoryModel extends Model
{
    protected $table = 'student_academic_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id', 'session_id', 'term_id', 'class_id', 'enrollment_date',
        'promotion_date', 'status', 'promotion_type', 'overall_percentage',
        'position_in_class', 'total_students', 'remarks'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_PROMOTED = 'promoted';
    const STATUS_REPEATED = 'repeated';
    const STATUS_GRADUATED = 'graduated';
    const STATUS_WITHDRAWN = 'withdrawn';

    // Promotion type constants
    const PROMOTION_AUTOMATIC = 'automatic';
    const PROMOTION_MANUAL = 'manual';
    const PROMOTION_CONDITIONAL = 'conditional';

    /**
     * Get student's complete academic history
     */
    public function getStudentHistory($studentId)
    {
        return $this->select('student_academic_history.*,
                             academic_sessions.session_name,
                             academic_terms.term_name, academic_terms.term_number,
                             classes.name as class_name, classes.section as class_section')
                   ->join('academic_sessions', 'academic_sessions.id = student_academic_history.session_id')
                   ->join('academic_terms', 'academic_terms.id = student_academic_history.term_id', 'left')
                   ->join('classes', 'classes.id = student_academic_history.class_id')
                   ->where('student_academic_history.student_id', $studentId)
                   ->orderBy('academic_sessions.start_date', 'DESC')
                   ->orderBy('academic_terms.term_number', 'DESC')
                   ->findAll();
    }

    /**
     * Get current academic record for student
     */
    public function getCurrentRecord($studentId, $sessionId = null, $termId = null)
    {
        $builder = $this->where('student_id', $studentId)
                       ->where('status', self::STATUS_ACTIVE);

        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        if ($termId) {
            $builder->where('term_id', $termId);
        }

        return $builder->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Enroll student in a class for a session/term
     */
    public function enrollStudent($studentId, $sessionId, $termId, $classId, $enrollmentDate = null)
    {
        $data = [
            'student_id' => $studentId,
            'session_id' => $sessionId,
            'term_id' => $termId,
            'class_id' => $classId,
            'enrollment_date' => $enrollmentDate ?: date('Y-m-d'),
            'status' => self::STATUS_ACTIVE
        ];

        return $this->insert($data);
    }

    /**
     * Promote student to next class
     */
    public function promoteStudent($studentId, $currentSessionId, $currentTermId, $newClassId, $promotionType = self::PROMOTION_AUTOMATIC, $remarks = null)
    {
        // For SRMS CBT system, we'll use a simpler promotion approach
        // Update the student's class in the users table directly
        $userModel = new \App\Models\UserModel();
        $result = $userModel->update($studentId, ['class_id' => $newClassId]);

        if ($result) {
            // Update current record if it exists in academic history
            $currentRecord = $this->getCurrentRecord($studentId, $currentSessionId, $currentTermId);
            if ($currentRecord) {
                $this->update($currentRecord['id'], [
                    'status' => self::STATUS_PROMOTED,
                    'promotion_date' => date('Y-m-d'),
                    'promotion_type' => $promotionType,
                    'remarks' => $remarks
                ]);
            } else {
                // Create a history record for this promotion
                $this->insert([
                    'student_id' => $studentId,
                    'session_id' => $currentSessionId,
                    'term_id' => $currentTermId,
                    'class_id' => $newClassId,
                    'enrollment_date' => date('Y-m-d'),
                    'promotion_date' => date('Y-m-d'),
                    'status' => self::STATUS_PROMOTED,
                    'promotion_type' => $promotionType,
                    'remarks' => $remarks
                ]);
            }
        }

        return $result;
    }

    /**
     * Repeat student in same class
     */
    public function repeatStudent($studentId, $currentSessionId, $currentTermId, $remarks = null)
    {
        $currentRecord = $this->getCurrentRecord($studentId, $currentSessionId, $currentTermId);
        if ($currentRecord) {
            return $this->update($currentRecord['id'], [
                'status' => self::STATUS_REPEATED,
                'remarks' => $remarks
            ]);
        }
        return false;
    }

    /**
     * Get students in a class for a session/term
     */
    public function getClassStudents($classId, $sessionId, $termId = null)
    {
        $builder = $this->select('student_academic_history.*,
                                 users.first_name, users.last_name, users.student_id as student_number,
                                 users.email, users.phone')
                       ->join('users', 'users.id = student_academic_history.student_id')
                       ->where('student_academic_history.class_id', $classId)
                       ->where('student_academic_history.session_id', $sessionId)
                       ->where('student_academic_history.status', self::STATUS_ACTIVE);

        if ($termId) {
            $builder->where('student_academic_history.term_id', $termId);
        }

        $students = $builder->orderBy('users.first_name', 'ASC')->findAll();

        // If no students found in academic history, fall back to users table
        if (empty($students)) {
            $userModel = new \App\Models\UserModel();
            $students = $userModel->select('users.id as student_id, users.first_name, users.last_name,
                                          COALESCE(users.student_id, users.username) as student_number, users.email, users.phone,
                                          users.id, NULL as enrollment_date, NULL as promotion_date,
                                          "active" as status')
                                 ->where('class_id', $classId)
                                 ->where('role', 'student')
                                 ->where('is_active', 1)
                                 ->orderBy('first_name', 'ASC')
                                 ->findAll();
        }

        return $students;
    }

    /**
     * Get promotion statistics for a class
     */
    public function getClassPromotionStats($classId, $sessionId)
    {
        $stats = [];

        // First check academic history table
        $historyTotal = $this->where('class_id', $classId)
                             ->where('session_id', $sessionId)
                             ->countAllResults();

        if ($historyTotal > 0) {
            // Use academic history data if available
            $stats['total'] = $historyTotal;
            $stats['promoted'] = $this->where('class_id', $classId)
                                     ->where('session_id', $sessionId)
                                     ->where('status', self::STATUS_PROMOTED)
                                     ->countAllResults();

            $stats['repeated'] = $this->where('class_id', $classId)
                                     ->where('session_id', $sessionId)
                                     ->where('status', self::STATUS_REPEATED)
                                     ->countAllResults();

            $stats['active'] = $this->where('class_id', $classId)
                                   ->where('session_id', $sessionId)
                                   ->where('status', self::STATUS_ACTIVE)
                                   ->countAllResults();
        } else {
            // Fall back to users table if no academic history exists
            $userModel = new \App\Models\UserModel();
            $stats['total'] = $userModel->where('class_id', $classId)
                                       ->where('role', 'student')
                                       ->where('is_active', 1)
                                       ->countAllResults();

            $stats['promoted'] = 0;
            $stats['repeated'] = 0;
            $stats['active'] = $stats['total']; // All students are considered active if no history
        }

        return $stats;
    }

    /**
     * Helper method to get next session
     */
    private function getNextSession($currentSessionId)
    {
        $sessionModel = new AcademicSessionModel();
        $currentSession = $sessionModel->find($currentSessionId);

        return $sessionModel->where('start_date >', $currentSession['end_date'])
                           ->orderBy('start_date', 'ASC')
                           ->first();
    }

    /**
     * Helper method to get first term of a session
     */
    private function getFirstTermOfSession($sessionId)
    {
        $termModel = new AcademicTermModel();
        return $termModel->where('session_id', $sessionId)
                        ->where('term_number', 1)
                        ->first();
    }

    /**
     * Get student's class progression
     */
    public function getStudentProgression($studentId)
    {
        return $this->select('student_academic_history.*,
                             academic_sessions.session_name, academic_sessions.start_date, academic_sessions.end_date,
                             academic_terms.term_name, academic_terms.term_number,
                             classes.name as class_name, classes.section as class_section')
                   ->join('academic_sessions', 'academic_sessions.id = student_academic_history.session_id')
                   ->join('academic_terms', 'academic_terms.id = student_academic_history.term_id', 'left')
                   ->join('classes', 'classes.id = student_academic_history.class_id')
                   ->where('student_academic_history.student_id', $studentId)
                   ->orderBy('academic_sessions.start_date', 'ASC')
                   ->orderBy('academic_terms.term_number', 'ASC')
                   ->findAll();
    }

    /**
     * Initialize academic history for existing students
     */
    public function initializeStudentHistory($studentId, $sessionId, $termId)
    {
        // Check if student already has a record for this session/term
        $existing = $this->where('student_id', $studentId)
                         ->where('session_id', $sessionId)
                         ->where('term_id', $termId)
                         ->first();

        if (!$existing) {
            // Get student's current class from users table
            $userModel = new \App\Models\UserModel();
            $student = $userModel->find($studentId);

            if ($student && $student['class_id']) {
                return $this->insert([
                    'student_id' => $studentId,
                    'session_id' => $sessionId,
                    'term_id' => $termId,
                    'class_id' => $student['class_id'],
                    'enrollment_date' => date('Y-m-d'),
                    'status' => self::STATUS_ACTIVE
                ]);
            }
        }

        return false;
    }
}
