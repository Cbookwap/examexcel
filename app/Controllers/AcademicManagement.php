<?php

namespace App\Controllers;

use App\Models\StudentAcademicHistoryModel;
use App\Models\StudentTermResultsModel;

use App\Models\AcademicSessionModel;
use App\Models\AcademicTermModel;
use App\Models\ClassModel;
use App\Models\UserModel;

class AcademicManagement extends BaseController
{
    protected $historyModel;
    protected $termResultsModel;

    protected $sessionModel;
    protected $termModel;
    protected $classModel;
    protected $userModel;

    public function __construct()
    {
        $this->historyModel = new StudentAcademicHistoryModel();
        $this->termResultsModel = new StudentTermResultsModel();

        $this->sessionModel = new AcademicSessionModel();
        $this->termModel = new AcademicTermModel();
        $this->classModel = new ClassModel();
        $this->userModel = new UserModel();
    }

    /**
     * Academic management dashboard
     */
    public function index()
    {
        $currentSession = $this->sessionModel->getCurrentSession();
        $currentTerm = $this->termModel->getCurrentTerm();

        // Get real statistics
        $stats = $this->getAcademicStats();

        // Get recent academic activities
        $recentActivities = $this->getRecentAcademicActivities();

        $data = [
            'title' => 'Academic Management - ' . get_app_name(),
            'pageTitle' => 'Academic Management',
            'pageSubtitle' => 'Manage student promotions and academic records',
            'currentSession' => $currentSession,
            'currentTerm' => $currentTerm,
            'sessions' => $this->sessionModel->getActiveSessions(),
            'classes' => $this->classModel->getActiveClasses(),
            'stats' => $stats,
            'recentActivities' => $recentActivities
        ];

        return view('admin/academic_management/index', $data);
    }

    /**
     * Student academic history
     */
    public function studentHistory($studentId = null)
    {
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student ID is required');
        }

        $student = $this->userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return redirect()->back()->with('error', 'Student not found');
        }

        $history = $this->historyModel->getStudentHistory($studentId);
        $termResults = $this->termResultsModel->getStudentTermResults($studentId);
        $progression = $this->historyModel->getStudentProgression($studentId);

        $data = [
            'title' => 'Student Academic History - ' . get_app_name(),
            'pageTitle' => 'Academic History',
            'pageSubtitle' => $student['first_name'] . ' ' . $student['last_name'],
            'student' => $student,
            'history' => $history,
            'termResults' => $termResults,
            'progression' => $progression
        ];

        return view('admin/academic_management/student_history', $data);
    }

    /**
     * Class promotion management
     */
    public function classPromotion()
    {
        $currentSession = $this->sessionModel->getCurrentSession();
        $currentTerm = $this->termModel->getCurrentTerm();
        $classes = $this->classModel->getActiveClasses();

        // Add display name with category for each class
        foreach ($classes as &$class) {
            $class['display_name'] = $this->getClassDisplayName($class);
        }

        $promotionStats = [];
        foreach ($classes as $class) {
            $stats = $this->historyModel->getClassPromotionStats($class['id'], $currentSession['id'] ?? 0);
            $promotionStats[$class['id']] = $stats;
        }

        $data = [
            'title' => 'Class Promotion - ' . get_app_name(),
            'pageTitle' => 'Class Promotion Management',
            'pageSubtitle' => 'Manage student promotions by class',
            'currentSession' => $currentSession,
            'currentTerm' => $currentTerm,
            'classes' => $classes,
            'promotionStats' => $promotionStats
        ];

        return view('admin/academic_management/class_promotion', $data);
    }



    /**
     * Term results overview
     */
    public function termResults()
    {
        $currentSession = $this->sessionModel->getCurrentSession();
        $currentTerm = $this->termModel->getCurrentTerm();
        $classes = $this->classModel->getActiveClasses();

        $classResults = [];
        if ($currentSession && $currentTerm) {
            foreach ($classes as $class) {
                $results = $this->termResultsModel->getClassTermResults(
                    $class['id'],
                    $currentSession['id'],
                    $currentTerm['id']
                );
                $stats = $this->termResultsModel->getClassPromotionStatistics(
                    $class['id'],
                    $currentSession['id'],
                    $currentTerm['id']
                );
                $classResults[$class['id']] = [
                    'results' => $results,
                    'stats' => $stats
                ];
            }
        }

        $data = [
            'title' => 'Term Results - ' . get_app_name(),
            'pageTitle' => 'Term Results Overview',
            'pageSubtitle' => 'View and manage term results',
            'currentSession' => $currentSession,
            'currentTerm' => $currentTerm,
            'classes' => $classes,
            'classResults' => $classResults
        ];

        return view('admin/academic_management/term_results', $data);
    }

    /**
     * Calculate term results for all students
     */
    public function calculateTermResults()
    {
        $sessionId = $this->request->getPost('session_id');
        $termId = $this->request->getPost('term_id');
        $classId = $this->request->getPost('class_id');

        if (!$sessionId || !$termId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session and term are required'
            ]);
        }

        $classes = $classId ? [$this->classModel->find($classId)] : $this->classModel->getActiveClasses();
        $processed = 0;
        $errors = 0;
        $studentsFound = 0;
        $attemptsFound = 0;

        foreach ($classes as $class) {
            if (!$class) continue;

            $students = $this->historyModel->getClassStudents($class['id'], $sessionId, $termId);
            $studentsFound += count($students);

            foreach ($students as $student) {
                try {
                    // Check if student has exam attempts
                    $attemptModel = new \App\Models\ExamAttemptModel();
                    $attempts = $attemptModel->where('student_id', $student['student_id'])
                                           ->where('session_id', $sessionId)
                                           ->where('term_id', $termId)
                                           ->whereIn('status', ['submitted', 'auto_submitted'])
                                           ->findAll();

                    $attemptsFound += count($attempts);

                    $result = $this->termResultsModel->calculateTermResults(
                        $student['student_id'],
                        $sessionId,
                        $termId,
                        $class['id']
                    );

                    if ($result !== false) {
                        $processed++;
                    }
                } catch (\Exception $e) {
                    $errors++;
                    log_message('error', 'Failed to calculate results for student ' . $student['student_id'] . ': ' . $e->getMessage());
                }
            }
        }

        // Provide more detailed feedback
        if ($studentsFound === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No students found in the selected classes for the current session/term.'
            ]);
        }

        if ($attemptsFound === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Found {$studentsFound} students but no completed exam attempts. Students need to complete exams before results can be calculated."
            ]);
        }

        if ($processed === 0 && $errors === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Found {$studentsFound} students and {$attemptsFound} exam attempts, but no results were calculated. This may indicate the results have already been calculated."
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully processed {$processed} students out of {$studentsFound} found. {$attemptsFound} exam attempts were processed. {$errors} errors occurred.",
            'processed' => $processed,
            'errors' => $errors,
            'students_found' => $studentsFound,
            'attempts_found' => $attemptsFound
        ]);
    }

    /**
     * Bulk promote students
     */
    public function bulkPromoteStudents()
    {
        $classId = $this->request->getPost('class_id');
        $sessionId = $this->request->getPost('session_id');
        $termId = $this->request->getPost('term_id');
        $selectedStudents = $this->request->getPost('selected_students');
        $targetClassId = $this->request->getPost('target_class_id');

        if (!$classId || !$sessionId || !$termId || !$targetClassId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Class, session, term, and target class are required'
            ]);
        }

        try {
            $promoted = 0;
            $errors = 0;

            // Get all students in the class if none selected
            if (empty($selectedStudents)) {
                $students = $this->historyModel->getClassStudents($classId, $sessionId, $termId);
                $selectedStudents = array_column($students, 'student_id');
            }

            foreach ($selectedStudents as $studentId) {
                try {
                    $result = $this->historyModel->promoteStudent(
                        $studentId,
                        $sessionId,
                        $termId,
                        $targetClassId,
                        'bulk_manual',
                        'Bulk promotion by admin'
                    );

                    if ($result) {
                        $promoted++;
                    } else {
                        $errors++;
                    }
                } catch (\Exception $e) {
                    $errors++;
                    log_message('error', 'Failed to promote student ' . $studentId . ': ' . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Promoted {$promoted} students successfully. {$errors} errors occurred.",
                'promoted' => $promoted,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error during bulk promotion: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Promote individual student
     */
    public function promoteStudent()
    {
        $studentId = $this->request->getPost('student_id');
        $currentSessionId = $this->request->getPost('current_session_id');
        $currentTermId = $this->request->getPost('current_term_id');
        $newClassId = $this->request->getPost('new_class_id');
        $promotionType = $this->request->getPost('promotion_type') ?: 'manual';
        $remarks = $this->request->getPost('remarks');

        if (!$studentId || !$currentSessionId || !$currentTermId || !$newClassId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required'
            ]);
        }

        try {
            $result = $this->historyModel->promoteStudent(
                $studentId,
                $currentSessionId,
                $currentTermId,
                $newClassId,
                $promotionType,
                $remarks
            );

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student promoted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to promote student'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get students in a class for promotion selection
     */
    public function getClassStudents($classId)
    {
        if (!$classId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Class ID is required'
            ]);
        }

        try {
            $sessionId = $this->request->getGet('session_id') ?:
                        ($this->sessionModel->getCurrentSession()['id'] ?? 0);
            $termId = $this->request->getGet('term_id') ?:
                     ($this->termModel->getCurrentTerm()['id'] ?? 0);

            $students = $this->historyModel->getClassStudents($classId, $sessionId, $termId);

            return $this->response->setJSON([
                'success' => true,
                'students' => $students
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading students: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate display name for class with category
     */
    private function getClassDisplayName($class)
    {
        $name = $class['name'];
        $section = $class['section'] ?? '';

        // If section exists and it's a category (Science, Arts, Commercial), add it
        if (!empty($section) && in_array($section, ['Science', 'Arts', 'Commercial'])) {
            return $name . ' - ' . $section;
        }

        // If section exists but it's just a regular section (A, B, C), add it differently
        if (!empty($section) && !in_array($section, ['Science', 'Arts', 'Commercial'])) {
            return $name . ' (' . $section . ')';
        }

        // If no section, just return the name
        return $name;
    }

    /**
     * Get academic statistics for dashboard
     */
    private function getAcademicStats()
    {
        try {
            $stats = [];

            // Active Sessions
            $stats['active_sessions'] = $this->sessionModel->where('is_active', 1)->countAllResults();

            // Active Classes
            $stats['active_classes'] = $this->classModel->where('is_active', 1)->countAllResults();

            // Total Students
            $stats['total_students'] = $this->userModel->where('role', 'student')
                                                      ->where('is_active', 1)
                                                      ->countAllResults();

            // Pending Promotions (students who need to be promoted)
            $currentSession = $this->sessionModel->getCurrentSession();
            $currentTerm = $this->termModel->getCurrentTerm();

            if ($currentSession && $currentTerm) {
                // Count students who are active but haven't been promoted yet
                $stats['pending_promotions'] = $this->historyModel->where('session_id', $currentSession['id'])
                                                                 ->where('term_id', $currentTerm['id'])
                                                                 ->where('status', 'active')
                                                                 ->countAllResults();
            } else {
                $stats['pending_promotions'] = 0;
            }

            return $stats;
        } catch (\Exception $e) {
            log_message('error', 'getAcademicStats Error: ' . $e->getMessage());
            // Return default stats if there's an error
            return [
                'active_sessions' => 0,
                'active_classes' => 0,
                'total_students' => 0,
                'pending_promotions' => 0
            ];
        }
    }

    /**
     * Get recent academic activities
     */
    private function getRecentAcademicActivities()
    {
        try {
            $activities = [];

            // Get recent promotions
            $recentPromotions = $this->historyModel->select('student_academic_history.*, users.first_name, users.last_name, users.student_id, classes.name as class_name')
                                                  ->join('users', 'users.id = student_academic_history.student_id')
                                                  ->join('classes', 'classes.id = student_academic_history.class_id')
                                                  ->where('student_academic_history.status', 'promoted')
                                                  ->orderBy('student_academic_history.updated_at', 'DESC')
                                                  ->limit(5)
                                                  ->findAll();

            foreach ($recentPromotions as $promotion) {
                $activities[] = [
                    'type' => 'promotion',
                    'title' => 'Student Promoted',
                    'description' => $promotion['first_name'] . ' ' . $promotion['last_name'] . ' (' . $promotion['student_id'] . ') promoted to ' . $promotion['class_name'],
                    'timestamp' => $promotion['updated_at'],
                    'icon' => 'fas fa-arrow-up',
                    'color' => 'success'
                ];
            }

            // Get recent term results calculations
            $recentResults = $this->termResultsModel->select('student_term_results.*, users.first_name, users.last_name, users.student_id, classes.name as class_name')
                                                   ->join('users', 'users.id = student_term_results.student_id')
                                                   ->join('classes', 'classes.id = student_term_results.class_id')
                                                   ->orderBy('student_term_results.created_at', 'DESC')
                                                   ->limit(5)
                                                   ->findAll();

            foreach ($recentResults as $result) {
                $activities[] = [
                    'type' => 'result',
                    'title' => 'Term Result Calculated',
                    'description' => 'Term result calculated for ' . $result['first_name'] . ' ' . $result['last_name'] . ' (' . $result['student_id'] . ') - ' . $result['class_name'],
                    'timestamp' => $result['created_at'],
                    'icon' => 'fas fa-calculator',
                    'color' => 'info'
                ];
            }

            // Sort activities by timestamp (most recent first)
            usort($activities, function($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });

            // Return only the 10 most recent activities
            return array_slice($activities, 0, 10);
        } catch (\Exception $e) {
            log_message('error', 'getRecentAcademicActivities Error: ' . $e->getMessage());
            // Return empty array if there's an error
            return [];
        }
    }
}
