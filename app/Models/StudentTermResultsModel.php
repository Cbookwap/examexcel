<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentTermResultsModel extends Model
{
    protected $table = 'student_term_results';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id', 'session_id', 'term_id', 'class_id', 'total_subjects',
        'subjects_passed', 'subjects_failed', 'total_marks_obtained',
        'total_marks_possible', 'overall_percentage', 'grade', 'position_in_class',
        'total_students', 'attendance_percentage', 'conduct_grade',
        'teacher_remarks', 'principal_remarks', 'next_term_begins',
        'is_promoted', 'promotion_status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Promotion status constants
    const PROMOTION_PROMOTED = 'promoted';
    const PROMOTION_REPEATED = 'repeated';
    const PROMOTION_CONDITIONAL = 'conditional';
    const PROMOTION_PENDING = 'pending';

    /**
     * Calculate and save term results for a student
     */
    public function calculateTermResults($studentId, $sessionId, $termId, $classId)
    {
        $attemptModel = new ExamAttemptModel();

        // Get all exam attempts for the student in this term
        $attempts = $attemptModel->select('exam_attempts.*, exams.total_marks, exams.passing_marks, subjects.name as subject_name')
                                ->join('exams', 'exams.id = exam_attempts.exam_id')
                                ->join('subjects', 'subjects.id = exams.subject_id')
                                ->where('exam_attempts.student_id', $studentId)
                                ->where('exam_attempts.session_id', $sessionId)
                                ->where('exam_attempts.term_id', $termId)
                                ->whereIn('exam_attempts.status', [ExamAttemptModel::STATUS_SUBMITTED, ExamAttemptModel::STATUS_AUTO_SUBMITTED])
                                ->findAll();

        if (empty($attempts)) {
            return false;
        }

        // Calculate statistics
        $totalSubjects = count(array_unique(array_column($attempts, 'subject_name')));
        $totalMarksObtained = array_sum(array_column($attempts, 'marks_obtained'));
        $totalMarksPossible = array_sum(array_column($attempts, 'total_marks'));
        $overallPercentage = $totalMarksPossible > 0 ? ($totalMarksObtained / $totalMarksPossible) * 100 : 0;

        $subjectsPassed = 0;
        $subjectsFailed = 0;

        foreach ($attempts as $attempt) {
            if ($attempt['marks_obtained'] >= $attempt['passing_marks']) {
                $subjectsPassed++;
            } else {
                $subjectsFailed++;
            }
        }

        // Calculate grade
        $grade = $this->calculateGrade($overallPercentage);

        // Calculate position in class
        $position = $this->calculateClassPosition($studentId, $sessionId, $termId, $classId, $overallPercentage);

        // Get total students in class
        $historyModel = new StudentAcademicHistoryModel();
        $classStudents = $historyModel->getClassStudents($classId, $sessionId, $termId);
        $totalStudents = count($classStudents);

        // Determine promotion status
        $promotionStatus = $this->determinePromotionStatus($overallPercentage, $subjectsPassed, $totalSubjects);

        $data = [
            'student_id' => $studentId,
            'session_id' => $sessionId,
            'term_id' => $termId,
            'class_id' => $classId,
            'total_subjects' => $totalSubjects,
            'subjects_passed' => $subjectsPassed,
            'subjects_failed' => $subjectsFailed,
            'total_marks_obtained' => $totalMarksObtained,
            'total_marks_possible' => $totalMarksPossible,
            'overall_percentage' => round($overallPercentage, 2),
            'grade' => $grade,
            'position_in_class' => $position['position'],
            'total_students' => $totalStudents,
            'is_promoted' => $promotionStatus === self::PROMOTION_PROMOTED,
            'promotion_status' => $promotionStatus
        ];

        // Check if record exists
        $existing = $this->where('student_id', $studentId)
                        ->where('session_id', $sessionId)
                        ->where('term_id', $termId)
                        ->first();

        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Get student's term results with details
     */
    public function getStudentTermResults($studentId, $sessionId = null, $termId = null)
    {
        $builder = $this->select('student_term_results.*,
                                 academic_sessions.session_name,
                                 academic_terms.term_name, academic_terms.term_number,
                                 classes.name as class_name, classes.section as class_section')
                       ->join('academic_sessions', 'academic_sessions.id = student_term_results.session_id')
                       ->join('academic_terms', 'academic_terms.id = student_term_results.term_id')
                       ->join('classes', 'classes.id = student_term_results.class_id')
                       ->where('student_term_results.student_id', $studentId);

        if ($sessionId) {
            $builder->where('student_term_results.session_id', $sessionId);
        }

        if ($termId) {
            $builder->where('student_term_results.term_id', $termId);
        }

        return $builder->orderBy('academic_sessions.start_date', 'DESC')
                      ->orderBy('academic_terms.term_number', 'DESC')
                      ->findAll();
    }

    /**
     * Get class results for a term
     */
    public function getClassTermResults($classId, $sessionId, $termId)
    {
        $results = $this->select('student_term_results.*,
                                 users.first_name, users.last_name, users.student_id as student_number')
                       ->join('users', 'users.id = student_term_results.student_id')
                       ->where('student_term_results.class_id', $classId)
                       ->where('student_term_results.session_id', $sessionId)
                       ->where('student_term_results.term_id', $termId)
                       ->orderBy('student_term_results.position_in_class', 'ASC')
                       ->findAll();

        // If no term results calculated, fall back to showing students from users table
        if (empty($results)) {
            $userModel = new \App\Models\UserModel();
            $students = $userModel->select('users.id as student_id, users.first_name, users.last_name,
                                          COALESCE(users.student_id, users.username) as student_number, users.email,
                                          NULL as overall_percentage, NULL as grade, NULL as position_in_class,
                                          NULL as total_subjects, NULL as subjects_passed, NULL as subjects_failed')
                                 ->where('class_id', $classId)
                                 ->where('role', 'student')
                                 ->where('is_active', 1)
                                 ->orderBy('first_name', 'ASC')
                                 ->findAll();
            return $students;
        }

        return $results;
    }

    /**
     * Calculate grade based on percentage
     */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 30) return 'D';
        return 'F';
    }

    /**
     * Calculate student's position in class
     */
    private function calculateClassPosition($studentId, $sessionId, $termId, $classId, $studentPercentage)
    {
        // Get all students' percentages in the class for this term
        $classResults = $this->select('student_id, overall_percentage')
                            ->where('session_id', $sessionId)
                            ->where('term_id', $termId)
                            ->where('class_id', $classId)
                            ->where('student_id !=', $studentId)
                            ->findAll();

        $position = 1;
        foreach ($classResults as $result) {
            if ($result['overall_percentage'] > $studentPercentage) {
                $position++;
            }
        }

        return ['position' => $position, 'total' => count($classResults) + 1];
    }

    /**
     * Determine promotion status based on performance
     */
    private function determinePromotionStatus($percentage, $subjectsPassed, $totalSubjects)
    {
        // Basic promotion criteria (can be customized)
        if ($percentage >= 40 && $subjectsPassed >= ($totalSubjects * 0.6)) {
            return self::PROMOTION_PROMOTED;
        } elseif ($percentage >= 30 && $subjectsPassed >= ($totalSubjects * 0.4)) {
            return self::PROMOTION_CONDITIONAL;
        } else {
            return self::PROMOTION_REPEATED;
        }
    }

    /**
     * Get student's historical performance
     */
    public function getStudentPerformanceHistory($studentId)
    {
        return $this->select('student_term_results.*,
                             academic_sessions.session_name,
                             academic_terms.term_name, academic_terms.term_number,
                             classes.name as class_name')
                   ->join('academic_sessions', 'academic_sessions.id = student_term_results.session_id')
                   ->join('academic_terms', 'academic_terms.id = student_term_results.term_id')
                   ->join('classes', 'classes.id = student_term_results.class_id')
                   ->where('student_term_results.student_id', $studentId)
                   ->orderBy('academic_sessions.start_date', 'ASC')
                   ->orderBy('academic_terms.term_number', 'ASC')
                   ->findAll();
    }

    /**
     * Update teacher remarks
     */
    public function updateTeacherRemarks($studentId, $sessionId, $termId, $remarks)
    {
        return $this->where('student_id', $studentId)
                   ->where('session_id', $sessionId)
                   ->where('term_id', $termId)
                   ->set(['teacher_remarks' => $remarks])
                   ->update();
    }

    /**
     * Update principal remarks
     */
    public function updatePrincipalRemarks($studentId, $sessionId, $termId, $remarks)
    {
        return $this->where('student_id', $studentId)
                   ->where('session_id', $sessionId)
                   ->where('term_id', $termId)
                   ->set(['principal_remarks' => $remarks])
                   ->update();
    }

    /**
     * Get promotion statistics for a class
     */
    public function getClassPromotionStatistics($classId, $sessionId, $termId)
    {
        $stats = [];

        // First check if there are any term results calculated
        $termResultsTotal = $this->where('class_id', $classId)
                                 ->where('session_id', $sessionId)
                                 ->where('term_id', $termId)
                                 ->countAllResults();

        if ($termResultsTotal > 0) {
            // Use calculated term results if available
            $stats['total'] = $termResultsTotal;
            $stats['promoted'] = $this->where('class_id', $classId)
                                     ->where('session_id', $sessionId)
                                     ->where('term_id', $termId)
                                     ->where('promotion_status', self::PROMOTION_PROMOTED)
                                     ->countAllResults();

            $stats['repeated'] = $this->where('class_id', $classId)
                                     ->where('session_id', $sessionId)
                                     ->where('term_id', $termId)
                                     ->where('promotion_status', self::PROMOTION_REPEATED)
                                     ->countAllResults();

            $stats['conditional'] = $this->where('class_id', $classId)
                                        ->where('session_id', $sessionId)
                                        ->where('term_id', $termId)
                                        ->where('promotion_status', self::PROMOTION_CONDITIONAL)
                                        ->countAllResults();

            $stats['average_percentage'] = $this->selectAvg('overall_percentage')
                                               ->where('class_id', $classId)
                                               ->where('session_id', $sessionId)
                                               ->where('term_id', $termId)
                                               ->get()
                                               ->getRow()
                                               ->overall_percentage ?? 0;
        } else {
            // Fall back to actual student counts from users table if no term results calculated
            $userModel = new \App\Models\UserModel();
            $stats['total'] = $userModel->where('class_id', $classId)
                                       ->where('role', 'student')
                                       ->where('is_active', 1)
                                       ->countAllResults();

            $stats['promoted'] = 0;
            $stats['repeated'] = 0;
            $stats['conditional'] = 0;
            $stats['average_percentage'] = 0;
        }

        return $stats;
    }
}
