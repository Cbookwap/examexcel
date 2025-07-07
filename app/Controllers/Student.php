<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamAttemptModel;
use App\Models\QuestionModel;
use App\Models\ExamQuestionModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Student extends Controller
{
    protected $examModel;
    protected $attemptModel;
    protected $questionModel;
    protected $examQuestionModel;
    protected $userModel;
    protected $historyModel;
    protected $termResultsModel;
    protected $studentAnswerModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->attemptModel = new ExamAttemptModel();
        $this->questionModel = new QuestionModel();
        $this->examQuestionModel = new ExamQuestionModel();
        $this->userModel = new UserModel();
        $this->historyModel = new \App\Models\StudentAcademicHistoryModel();
        $this->termResultsModel = new \App\Models\StudentTermResultsModel();
        $this->studentAnswerModel = new \App\Models\StudentAnswerModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);

        // Check if user is logged in and is a student
        if (!$this->session->get('is_logged_in') || $this->session->get('role') !== 'student') {
            redirect()->to('/auth/login')->send();
            exit;
        }
    }

    /**
     * Student dashboard
     */
    public function dashboard()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        // Get available exams for student's class
        $availableExams = [];
        if (!empty($student['class_id'])) {
            $availableExams = $this->examModel->getExamsForClass($student['class_id']);
        }

        // Get student's recent attempts
        $recentAttempts = $this->attemptModel->getStudentAttempts($studentId, 5);

        // Get upcoming exams
        $upcomingExams = $this->examModel->getUpcomingExams(5);

        // Get active exam attempts (in progress)
        $activeAttempts = $this->attemptModel->getActiveAttempts($studentId);

        $data = [
            'title' => 'Student Dashboard - ' . get_app_name(),
            'pageTitle' => 'Welcome, ' . $student['first_name'],
            'pageSubtitle' => 'Your examination portal',
            'student' => $student,
            'availableExams' => $availableExams,
            'recentAttempts' => $recentAttempts,
            'upcomingExams' => $upcomingExams,
            'activeAttempts' => $activeAttempts
        ];

        return view('student/dashboard', $data);
    }

    /**
     * Student profile management
     */
    public function profile()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        if (!$student) {
            return redirect()->to('/auth/login');
        }

        // Get class information
        $classInfo = null;
        if (!empty($student['class_id'])) {
            $classModel = new \App\Models\ClassModel();
            $classInfo = $classModel->find($student['class_id']);
        }

        $data = [
            'title' => 'My Profile - ' . get_app_name(),
            'pageTitle' => 'My Profile',
            'pageSubtitle' => 'Manage your personal information',
            'student' => $student,
            'classInfo' => $classInfo,
            'validation' => \Config\Services::validation()
        ];

        return view('student/profile', $data);
    }

    /**
     * Update student profile
     */
    public function updateProfile()
    {
        $studentId = $this->session->get('user_id');

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
            'date_of_birth' => 'permit_empty|valid_date',
            'gender' => 'permit_empty|in_list[male,female,other]',
            'address' => 'permit_empty|max_length[500]',
            'password' => 'permit_empty|min_length[6]',
            'confirm_password' => 'permit_empty|matches[password]'
        ];

        if (!$this->validate($rules)) {
            // Get student with class information
            $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                      ->join('classes', 'classes.id = users.class_id', 'left')
                                      ->where('users.id', $studentId)
                                      ->first();

            // Get class information
            $classInfo = null;
            if (!empty($student['class_id'])) {
                $classModel = new \App\Models\ClassModel();
                $classInfo = $classModel->find($student['class_id']);
            }

            return view('student/profile', [
                'title' => 'My Profile - ' . get_app_name(),
                'pageTitle' => 'My Profile',
                'pageSubtitle' => 'Manage your personal information',
                'student' => $student,
                'classInfo' => $classInfo,
                'validation' => $this->validator
            ]);
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address')
        ];

        // Only update password if provided
        if (!empty($this->request->getPost('password'))) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($studentId, $updateData)) {
            // Update session data
            $this->session->set('full_name', $updateData['first_name'] . ' ' . $updateData['last_name']);

            session()->setFlashdata('success', 'Profile updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update profile. Please try again.');
        }

        return redirect()->back();
    }

    /**
     * View available exams
     */
    public function exams()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        $exams = [];
        if (!empty($student['class_id'])) {
            $exams = $this->examModel->getExamsForClass($student['class_id']);
        }

        // Add attempt status and subject information for each exam
        foreach ($exams as &$exam) {
            $attempts = $this->attemptModel->select('exam_attempts.*, exams.total_marks')
                                          ->join('exams', 'exams.id = exam_attempts.exam_id')
                                          ->where('exam_attempts.exam_id', $exam['id'])
                                          ->where('exam_attempts.student_id', $studentId)
                                          ->orderBy('exam_attempts.created_at', 'DESC')
                                          ->findAll();
            $exam['attempt'] = !empty($attempts) ? $attempts[0] : null; // Latest attempt
            $exam['attempts'] = $attempts;
            $exam['status'] = $this->examModel->getExamStatus($exam);
            $exam['can_take'] = $this->examModel->canStudentTakeExam($exam['id'], $studentId);
            $exam['attempt_info'] = $this->examModel->getStudentAttemptInfo($exam['id'], $studentId);

            // Get exam with subject information
            $examWithSubjects = $this->examModel->getExamWithSubjects($exam['id']);
            if ($examWithSubjects) {
                $exam['exam_mode'] = $examWithSubjects['exam_mode'] ?? 'single_subject';
                $exam['subjects'] = $examWithSubjects['subjects'] ?? [];
                if (isset($examWithSubjects['subject'])) {
                    $exam['subject'] = $examWithSubjects['subject'];
                }
            } else {
                $exam['exam_mode'] = 'single_subject';
                $exam['subjects'] = [];
            }
        }

        $data = [
            'title' => 'My Exams - ' . get_app_name(),
            'pageTitle' => 'Available Exams',
            'pageSubtitle' => 'Examinations for your class',
            'student' => $student,
            'exams' => $exams
        ];

        return view('student/exams', $data);
    }

    /**
     * View exam schedule
     */
    public function schedule()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        if (!$student) {
            return redirect()->to('/auth/login');
        }

        $schedules = [];
        if (!empty($student['class_id'])) {
            // Get all exams for student's class (past, present, and future)
            $schedules = $this->examModel->select('exams.*, subjects.name as subject_name')
                                       ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                                       ->where('exams.class_id', $student['class_id'])
                                       ->where('exams.is_active', 1)
                                       ->orderBy('exams.start_time', 'ASC')
                                       ->findAll();

            // Add status and attempt information for each exam
            foreach ($schedules as &$schedule) {
                $now = date('Y-m-d H:i:s');

                // Determine exam status
                if ($schedule['start_time'] > $now) {
                    $schedule['status'] = 'upcoming';
                    $schedule['status_class'] = 'primary';
                } elseif ($schedule['end_time'] < $now) {
                    $schedule['status'] = 'completed';
                    $schedule['status_class'] = 'secondary';
                } else {
                    $schedule['status'] = 'active';
                    $schedule['status_class'] = 'success';
                }

                // Get student's attempts for this exam
                $attempts = $this->attemptModel->where('exam_id', $schedule['id'])
                                             ->where('student_id', $studentId)
                                             ->orderBy('created_at', 'DESC')
                                             ->findAll();

                $schedule['attempts'] = $attempts;
                $schedule['attempt_count'] = count($attempts);
                $schedule['best_score'] = 0;
                $schedule['latest_attempt'] = null;

                if (!empty($attempts)) {
                    $schedule['latest_attempt'] = $attempts[0];
                    $schedule['best_score'] = max(array_column($attempts, 'marks_obtained'));
                }

                // Calculate duration
                $startTime = new \DateTime($schedule['start_time']);
                $endTime = new \DateTime($schedule['end_time']);
                $schedule['duration_minutes'] = $endTime->diff($startTime)->h * 60 + $endTime->diff($startTime)->i;

                // Check if student can take the exam
                $schedule['can_take'] = $this->examModel->canStudentTakeExam($schedule['id'], $studentId);
            }
        }

        $data = [
            'title' => 'Exam Schedule - ' . get_app_name(),
            'pageTitle' => 'Exam Schedule',
            'pageSubtitle' => 'Your examination timetable',
            'student' => $student,
            'schedules' => $schedules ?? []
        ];

        return view('student/schedule', $data);
    }

    /**
     * Start exam
     */
    public function startExam($examId)
    {
        $studentId = $this->session->get('user_id');

        // Check if student is suspended or banned
        $userModel = new UserModel();
        $suspensionDetails = $userModel->getSuspensionDetails($studentId);

        if ($suspensionDetails['is_banned']) {
            return redirect()->to('/student/exams')->with('error', 'You are permanently banned from taking exams. Reason: ' . ($suspensionDetails['ban_reason'] ?: 'Security violations'));
        }

        if ($suspensionDetails['is_suspended']) {
            $suspendedUntil = date('M j, Y g:i A', strtotime($suspensionDetails['suspended_until']));
            return redirect()->to('/student/exams')->with('error', 'You are suspended from taking exams until ' . $suspendedUntil . '. Reason: ' . ($suspensionDetails['suspension_reason'] ?: 'Security violations'));
        }

        // Check if student can take the exam
        if (!$this->examModel->canStudentTakeExam($examId, $studentId)) {
            return redirect()->to('/student/exams')->with('error', 'You cannot take this exam at this time');
        }

        $exam = $this->examModel->getExamWithDetails($examId);
        if (!$exam) {
            return redirect()->to('/student/exams')->with('error', 'Exam not found');
        }

        // Validate exam configuration before allowing students to start
        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $validationErrors = $examQuestionModel->validateQuestionCountConsistency($examId);
        if (!empty($validationErrors)) {
            return redirect()->to('/student/exams')->with('error', 'This exam has configuration issues and cannot be taken at this time. Please contact your administrator.');
        }

        // Start the attempt
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();

        $attemptId = $this->attemptModel->startAttempt($examId, $studentId, $ipAddress, $userAgent);

        if ($attemptId) {
            // Initialize time tracking for multi-subject exams
            if ($exam['exam_mode'] === 'multi_subject') {
                $timeTrackingModel = new \App\Models\SubjectTimeTrackingModel();
                $timeTrackingModel->initializeExamTimeTracking($attemptId, $examId);
            }

            return redirect()->to('/student/takeExam/' . $attemptId);
        } else {
            return redirect()->to('/student/exams')->with('error', 'Failed to start exam');
        }
    }

    /**
     * Take exam interface
     */
    public function takeExam($attemptId)
    {
        // Disable debug toolbar for exam interface
        if (defined('CI_DEBUG')) {
            $GLOBALS['CI_DEBUG'] = false;
        }

        $studentId = $this->session->get('user_id');

        // Check if student is suspended or banned
        $userModel = new UserModel();
        $suspensionDetails = $userModel->getSuspensionDetails($studentId);

        if ($suspensionDetails['is_banned']) {
            return redirect()->to('/student/exams')->with('error', 'You are permanently banned from taking exams. Reason: ' . ($suspensionDetails['ban_reason'] ?: 'Security violations'));
        }

        if ($suspensionDetails['is_suspended']) {
            $suspendedUntil = date('M j, Y g:i A', strtotime($suspensionDetails['suspended_until']));
            return redirect()->to('/student/exams')->with('error', 'You are suspended from taking exams until ' . $suspendedUntil . '. Reason: ' . ($suspensionDetails['suspension_reason'] ?: 'Security violations'));
        }

        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->where('status', ExamAttemptModel::STATUS_IN_PROGRESS)
                                     ->first();

        if (!$attempt) {
            return redirect()->to('/student/exams')->with('error', 'Invalid exam attempt');
        }

        $exam = $this->examModel->getExamWithDetails($attempt['exam_id']);

        // Convert exam object to array if needed (CodeIgniter 4 sometimes returns objects)
        if (is_object($exam)) {
            $exam = (array) $exam;
        }

        // Generate consistent seed for randomization based on attempt ID
        $randomizationSeed = $attempt['id'] * 12345; // Consistent seed per attempt

        // Get questions with improved randomization for multi-subject exams
        // Check if exam_mode exists (for backward compatibility)
        $examMode = $exam['exam_mode'] ?? 'single_subject';

        if ($examMode === 'multi_subject') {
            // For multi-subject exams, get questions grouped by subject with subject-specific randomization
            $questionsBySubject = $this->examQuestionModel->getExamQuestionsGroupedBySubject(
                $attempt['exam_id'],
                (bool)$exam['randomize_questions'],
                (bool)$exam['randomize_options'],
                $randomizationSeed
            );

            // Ensure questionsBySubject is an array
            if (!is_array($questionsBySubject)) {
                $questionsBySubject = [];
            }

            // Create flat questions array for backward compatibility
            $questions = [];
            foreach ($questionsBySubject as $subjectData) {
                if (isset($subjectData['questions']) && is_array($subjectData['questions'])) {
                    $questions = array_merge($questions, $subjectData['questions']);
                }
            }
        } else {
            // For single subject exams, use the existing method
            $questions = $this->examQuestionModel->getExamQuestionsGrouped(
                $attempt['exam_id'],
                (bool)$exam['randomize_questions'],
                (bool)$exam['randomize_options'],
                $randomizationSeed
            );

            // Ensure questions is an array
            if (!is_array($questions)) {
                $questions = [];
            }

            // For single subject, questionsBySubject is empty
            $questionsBySubject = [];
        }

        // Apply global security settings if not set at exam level
        $securitySettingsModel = new \App\Models\SecuritySettingsModel();
        $globalSecuritySettings = $securitySettingsModel->getAllSettings();

        // Merge global security settings with exam-specific settings
        $securityFields = ['prevent_copy_paste', 'disable_right_click', 'browser_lockdown', 'require_proctoring', 'calculator_enabled', 'exam_pause_enabled'];
        foreach ($securityFields as $field) {
            if (!isset($exam[$field]) || $exam[$field] === null) {
                $exam[$field] = $globalSecuritySettings[$field] ?? false;
            }
        }

        // Get student information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        // Convert student object to array if needed
        if (is_object($student)) {
            $student = (array) $student;
        }

        // Get exam with subject information for multi-subject support
        $examWithSubjects = $this->examModel->getExamWithSubjects($attempt['exam_id']);

        // Convert examWithSubjects object to array if needed
        if (is_object($examWithSubjects)) {
            $examWithSubjects = (array) $examWithSubjects;
        }

        if ($examWithSubjects) {
            $exam['exam_mode'] = $examWithSubjects['exam_mode'] ?? 'single_subject';
            $exam['subjects'] = $examWithSubjects['subjects'] ?? [];
            if (isset($examWithSubjects['subject'])) {
                $exam['subject'] = $examWithSubjects['subject'];
            }
        } else {
            $exam['exam_mode'] = 'single_subject';
            $exam['subjects'] = [];
        }

        // Questions are already processed above, just use them
        $flatQuestions = $questions;

        // Check if exam time has expired
        $timeRemaining = $this->calculateTimeRemaining($attempt, $exam);
        if ($timeRemaining <= 0) {
            // Auto-submit the exam
            $this->attemptModel->submitAttempt($attemptId, $attempt['answers'] ?? [], true);
            return redirect()->to('/student/examResult/' . $attemptId)->with('info', 'Exam time expired. Your answers have been auto-submitted.');
        }

        // Load security settings with defaults
        $securitySettingsModel = new \App\Models\SecuritySettingsModel();
        $securitySettings = $securitySettingsModel->getAllSettings();

        // Ensure security settings have default values
        $securityDefaults = [
            'max_tab_switches' => 5,
            'max_window_focus_loss' => 3,
            'max_monitor_warnings' => 2,
            'max_security_violations' => 10,
            'strict_security_mode' => false, // Default to OFF for better user experience
            'auto_submit_on_violation' => true,
            'browser_lockdown' => false,
            'prevent_copy_paste' => false,
            'disable_right_click' => false,
            'require_proctoring' => false
        ];

        $securitySettings = array_merge($securityDefaults, $securitySettings);

        $data = [
            'title' => $exam['title'] . ' - ' . get_app_name(),
            'exam' => $exam,
            'attempt' => $attempt,
            'student' => $student,
            'questions' => $flatQuestions, // Use flat questions for backward compatibility
            'questionsBySubject' => $questionsBySubject,
            'timeRemaining' => $timeRemaining,
            'currentAnswers' => $attempt['answers'] ?? [],
            'isMultiSubject' => $examMode === 'multi_subject',
            'settings' => $securitySettings
        ];

        // Disable toolbar service
        $response = \Config\Services::response();
        $response->setHeader('X-Debug-Toolbar', 'false');

        // Use different views based on exam type
        $viewName = $examMode === 'multi_subject' ? 'student/take_exam_multi_subject' : 'student/take_exam';

        return view($viewName, $data);
    }



    /**
     * Save answer (AJAX)
     */
    public function saveAnswer()
    {
        // Basic logging for monitoring
        log_message('info', "=== SAVE ANSWER REQUEST ===");

        // Allow both AJAX and regular POST requests for better compatibility
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            log_message('error', "Invalid request method: " . $this->request->getMethod());
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $attemptId = $this->request->getPost('attempt_id');
        $questionId = $this->request->getPost('question_id');
        $answer = $this->request->getPost('answer');

        $studentId = $this->session->get('user_id');

        // Validate required fields
        if (empty($attemptId) || empty($questionId) || $answer === null) {
            log_message('error', "Missing required fields - Attempt: {$attemptId}, Question: {$questionId}");
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields']);
        }

        if (empty($studentId)) {
            log_message('error', "No student ID in session");
            return $this->response->setJSON(['success' => false, 'message' => 'Session expired']);
        }

        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->where('status', ExamAttemptModel::STATUS_IN_PROGRESS)
                                     ->first();

        if (!$attempt) {
            log_message('error', "Invalid attempt for student {$studentId}, attempt {$attemptId}");
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attempt']);
        }

        // Convert object to array if needed (CodeIgniter 4 sometimes returns objects)
        if (is_object($attempt)) {
            $attempt = (array) $attempt;
        }

        // Ensure we have an array at this point
        if (!is_array($attempt)) {
            log_message('error', "Attempt data is not an array or object: " . gettype($attempt));
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attempt data']);
        }

        $answers = $attempt['answers'] ?? [];

        // Handle object case first before trying to JSON encode
        if (is_object($answers)) {
            $answers = (array) $answers;
        }

        // Ensure answers is an array (handle JSON string case)
        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        // Ensure it's an array
        if (!is_array($answers)) {
            $answers = [];
        }

        // Ensure answer is properly typed (convert to string for consistency)
        $answer = (string)$answer;
        $answers[$questionId] = $answer;



        try {
            // Verify the attempt exists before updating
            $currentAttempt = $this->attemptModel->find($attemptId);
            if (!$currentAttempt) {
                log_message('error', "❌ Attempt {$attemptId} not found in database");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Exam attempt not found'
                ]);
            }

            $updated = $this->attemptModel->update($attemptId, ['answers' => $answers]);

            if ($updated) {
                log_message('info', "✅ Answer saved successfully for question {$questionId}");

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Answer saved successfully'
                ]);
            } else {
                log_message('error', "❌ Failed to save answer for question {$questionId} - Update returned false");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', "❌ Exception while saving answer: " . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Log security events during exam
     */
    public function logSecurityEvent()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $input = $this->request->getJSON(true);

        if (!$input) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid JSON data']);
        }

        $attemptId = $input['attemptId'] ?? null;
        $eventType = $input['type'] ?? null;
        $timestamp = $input['timestamp'] ?? null;
        $eventData = $input['data'] ?? [];

        if (!$attemptId || !$eventType) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        // Verify the attempt belongs to the current student
        $studentId = $this->session->get('user_id');
        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->where('status', ExamAttemptModel::STATUS_IN_PROGRESS)
                                     ->first();

        if (!$attempt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attempt']);
        }

        // Check if strict security mode is enabled
        $securitySettingsModel = new \App\Models\SecuritySettingsModel();
        $strictMode = $securitySettingsModel->getSetting('strict_security_mode', false);

        // Determine severity based on event type
        $severity = $this->getEventSeverity($eventType);

        // Always log security events for audit purposes, but only apply punishments in strict mode
        $securityLogModel = new \App\Models\SecurityLogModel();
        $logData = [
            'exam_attempt_id' => $attemptId,
            'event_type' => $eventType,
            'event_data' => $eventData,
            'severity' => $severity,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => $timestamp ?: date('Y-m-d H:i:s')
        ];

        $securityLogModel->insert($logData);

        // Only check for violation patterns and apply punishments if strict mode is enabled
        if ($strictMode) {
            $this->checkViolationPatterns($studentId, $eventType, $severity);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Security event logged']);
    }

    /**
     * Determine event severity based on event type
     */
    private function getEventSeverity($eventType)
    {
        $severityMap = [
            // Critical violations
            'screen_recording_attempt' => 'critical',
            'multiple_monitors_detected' => 'critical',
            'suspicious_clicking_pattern' => 'critical',

            // High violations
            'tab_switch_away' => 'high',
            'window_focus_lost' => 'high',
            'blocked_key_combination' => 'high',
            'right_click_blocked' => 'high',

            // Medium violations
            'tab_switch_back' => 'medium',
            'window_focus_gained' => 'medium',
            'screen_configuration_changed' => 'medium',

            // Low violations
            'fullscreen_change' => 'low',
            'mouse_activity' => 'low'
        ];

        return $severityMap[$eventType] ?? 'medium';
    }

    /**
     * Check violation patterns and apply punishments
     */
    private function checkViolationPatterns($studentId, $eventType, $severity)
    {
        // First check if strict security mode is enabled
        $securitySettingsModel = new \App\Models\SecuritySettingsModel();
        $strictMode = $securitySettingsModel->getSetting('strict_security_mode', false);

        // If strict security mode is disabled, don't apply any punishments
        if (!$strictMode) {
            log_message('info', "Security violation logged for student {$studentId} but strict mode is disabled - no punishment applied");
            return;
        }

        $securityLogModel = new \App\Models\SecurityLogModel();

        // Count violations in the last 24 hours
        $recentViolations = $securityLogModel
            ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id')
            ->where('exam_attempts.student_id', $studentId)
            ->where('security_logs.created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->whereIn('security_logs.severity', ['high', 'critical'])
            ->countAllResults();

        // Count total violations for this student
        $totalViolations = $securityLogModel
            ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id')
            ->where('exam_attempts.student_id', $studentId)
            ->whereIn('security_logs.severity', ['high', 'critical'])
            ->countAllResults();

        // Apply punishments based on violation count
        $this->applyPunishment($studentId, $totalViolations, $recentViolations, $severity);
    }

    /**
     * Apply punishment based on violation count
     */
    private function applyPunishment($studentId, $totalViolations, $recentViolations, $severity)
    {
        $userModel = new UserModel();
        $violationModel = new \App\Models\ViolationModel();

        $punishment = null;
        $duration = null;

        // Determine punishment based on violation count
        if ($totalViolations >= 10 || $severity === 'critical') {
            // Permanent exam ban
            $punishment = 'permanent_ban';
            $userModel->update($studentId, ['exam_banned' => 1, 'ban_reason' => 'Multiple security violations']);
        } elseif ($totalViolations >= 7 || $recentViolations >= 5) {
            // 7-day exam suspension
            $punishment = 'temporary_suspension';
            $duration = 7;
            $banUntil = date('Y-m-d H:i:s', strtotime('+7 days'));
            $userModel->update($studentId, ['exam_suspended_until' => $banUntil, 'suspension_reason' => 'Repeated security violations']);
        } elseif ($totalViolations >= 4 || $recentViolations >= 3) {
            // 24-hour exam suspension
            $punishment = 'temporary_suspension';
            $duration = 1;
            $banUntil = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $userModel->update($studentId, ['exam_suspended_until' => $banUntil, 'suspension_reason' => 'Security violations detected']);
        } elseif ($totalViolations >= 1) {
            // Warning
            $punishment = 'warning';
        }

        // Log the punishment
        if ($punishment) {
            $violationModel->insert([
                'student_id' => $studentId,
                'violation_count' => $totalViolations,
                'punishment_type' => $punishment,
                'punishment_duration' => $duration,
                'severity' => $severity,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Send notification to admin
            $this->notifyAdminOfViolation($studentId, $totalViolations, $punishment);
        }
    }

    /**
     * Notify admin of security violation
     */
    private function notifyAdminOfViolation($studentId, $violationCount, $punishment)
    {
        // Log for admin notification
        log_message('critical', "Student ID {$studentId} has {$violationCount} violations. Punishment: {$punishment}");

        // In a real system, you might send email notifications or create admin alerts
        // For now, we'll just log it for the admin dashboard to pick up
    }

    /**
     * Submit exam
     */
    public function submitExam($attemptId)
    {
        $studentId = $this->session->get('user_id');

        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->where('status', ExamAttemptModel::STATUS_IN_PROGRESS)
                                     ->first();

        if (!$attempt) {
            return redirect()->to('/student/exams')->with('error', 'Invalid exam attempt');
        }

        // Get fresh answers from database to ensure we have the latest data
        $freshAttempt = $this->attemptModel->find($attemptId);
        if (!$freshAttempt) {
            return redirect()->to('/student/exams')->with('error', 'Exam attempt not found');
        }

        // Convert object to array if needed (CodeIgniter 4 sometimes returns objects)
        if (is_object($freshAttempt)) {
            $freshAttempt = (array) $freshAttempt;
        }

        $answers = $freshAttempt['answers'] ?? [];

        // Handle object case for answers
        if (is_object($answers)) {
            $answers = (array) $answers;
        }

        // Ensure answers is an array (handle JSON string case)
        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        // Ensure it's an array
        if (!is_array($answers)) {
            $answers = [];
        }

        log_message('info', "=== EXAM SUBMISSION DEBUG ===");
        log_message('info', "Attempt ID: {$attemptId}");
        log_message('info', "Fresh answers from DB: " . json_encode($answers));
        log_message('info', "Number of answers: " . count($answers));

        $submitted = $this->attemptModel->submitAttempt($attemptId, $answers);

        if ($submitted) {
            return redirect()->to('/student/examResult/' . $attemptId)->with('success', 'Exam submitted successfully!');
        } else {
            return redirect()->to('/student/takeExam/' . $attemptId)->with('error', 'Failed to submit exam');
        }
    }

    /**
     * Track subject time (AJAX)
     */
    public function trackSubjectTime()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $attemptId = $this->request->getPost('attempt_id');
        $subjectId = $this->request->getPost('subject_id');
        $action = $this->request->getPost('action'); // 'start' or 'end'
        $isCompleted = $this->request->getPost('is_completed', false);

        $studentId = $this->session->get('user_id');

        // Verify the attempt belongs to the student
        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->first();

        if (!$attempt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid exam attempt']);
        }

        $timeTrackingModel = new \App\Models\SubjectTimeTrackingModel();

        if ($action === 'start') {
            $result = $timeTrackingModel->startSubjectTime($attemptId, $subjectId);
            return $this->response->setJSON(['success' => $result !== false]);
        } elseif ($action === 'end') {
            $result = $timeTrackingModel->endSubjectTime($attemptId, $subjectId, $isCompleted);
            return $this->response->setJSON(['success' => $result !== false]);
        } elseif ($action === 'update') {
            $result = $timeTrackingModel->updateActiveSessionTime($attemptId, $subjectId);
            return $this->response->setJSON(['success' => $result !== false]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid action']);
    }

    /**
     * View exam result
     */
    public function examResult($attemptId)
    {
        $studentId = $this->session->get('user_id');

        $attempt = $this->attemptModel->getAttemptWithDetails($attemptId);

        if (!$attempt || $attempt['student_id'] != $studentId) {
            return redirect()->to('/student/exams')->with('error', 'Result not found');
        }

        // WORKAROUND: Get the raw attempt data to ensure JSON casting works
        $rawAttempt = $this->attemptModel->find($attemptId);
        if ($rawAttempt && isset($rawAttempt['answers'])) {
            $attempt['answers'] = $rawAttempt['answers']; // Use the properly cast answers
        }

        // EMERGENCY WORKAROUND: Direct database query to get answers
        if (empty($attempt['answers'])) {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT answers FROM exam_attempts WHERE id = ?", [$attemptId]);
            $result = $query->getRowArray();
            if ($result && !empty($result['answers'])) {
                $attempt['answers'] = json_decode($result['answers'], true) ?? [];
                log_message('info', "Retrieved answers via direct DB query: " . json_encode($attempt['answers']));
            }
        }

        // Check if results should be shown immediately
        $exam = $this->examModel->find($attempt['exam_id']);

        if (!$exam) {
            return redirect()->to('/student/exams')->with('error', 'Exam not found');
        }

        // Allow viewing results for any attempt that exists (for now, to handle existing records)
        // In the future, we can add more strict filtering
        // $completedStatuses = ['submitted', 'auto_submitted', 'completed'];
        // $hasScores = !is_null($attempt['marks_obtained']) || !is_null($attempt['percentage']);
        // $isProcessed = isset($attempt['percentage']) || isset($attempt['marks_obtained']);

        // if (!in_array($attempt['status'], $completedStatuses) && !$hasScores && !$isProcessed) {
        //     return redirect()->to('/student/exams')->with('error', 'Exam not completed yet');
        // }

        // For completed attempts, only check show_result_immediately if it's explicitly disabled
        if (!$exam['show_result_immediately'] && $this->session->get('role') !== 'admin') {
            return redirect()->to('/student/exams')->with('info', 'Results will be available after review');
        }

        // Get detailed question and answer data for review
        $questions = $this->examQuestionModel->getExamQuestionsGrouped($attempt['exam_id']);



        // Get student answers from the exam_attempts.answers JSON column
        // Note: CodeIgniter automatically casts JSON fields to arrays via $casts in ExamAttemptModel
        $savedAnswers = $attempt['answers'] ?? [];

        // Ensure it's an array (CodeIgniter should have already converted JSON to array)
        if (!is_array($savedAnswers)) {
            // Handle different data types
            if (is_string($savedAnswers)) {
                $savedAnswers = json_decode($savedAnswers, true) ?? [];
            } elseif (is_object($savedAnswers)) {
                // Convert object to array (this is the issue!)
                $savedAnswers = (array) $savedAnswers;
            } else {
                $savedAnswers = [];
            }
        }

        // FALLBACK: If no answers in JSON, try to get from student_answers table
        if (empty($savedAnswers)) {
            $studentAnswerModel = new \App\Models\StudentAnswerModel();
            $studentAnswerRecords = $studentAnswerModel->where('exam_attempt_id', $attemptId)->findAll();

            if (!empty($studentAnswerRecords)) {
                foreach ($studentAnswerRecords as $record) {
                    $savedAnswers[$record['question_id']] = $record['answer_text'];
                }
                log_message('info', "Retrieved answers from student_answers table for attempt {$attemptId}");
            }
        }





        // Calculate fresh performance metrics instead of using potentially corrupted database values
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $answeredQuestions = 0;

        // Calculate subject performance for display and overall performance metrics
        $subjectPerformance = [];
        foreach ($questions as $question) {
            $questionId = $question['id'];
            // Fix data type mismatch: ensure consistent types for array key lookup
            $studentAnswer = $savedAnswers[$questionId] ?? $savedAnswers[(int)$questionId] ?? $savedAnswers[(string)$questionId] ?? null;

            // Check if question was answered
            $isAnswered = ($studentAnswer !== null && $studentAnswer !== '' && $studentAnswer !== '0');

            // Track overall performance
            if ($isAnswered) {
                $answeredQuestions++;
            }

            // Track subject performance
            $subjectName = $question['subject_name'] ?? 'Unknown';
            if (!isset($subjectPerformance[$subjectName])) {
                $subjectPerformance[$subjectName] = [
                    'total' => 0,
                    'correct' => 0,
                    'wrong' => 0,
                    'unanswered' => 0
                ];
            }

            $subjectPerformance[$subjectName]['total']++;

            if (!$isAnswered) {
                $subjectPerformance[$subjectName]['unanswered']++;
            } else {
                // Get correct options from question options
                $correctOptions = [];
                if (isset($question['options']) && is_array($question['options'])) {
                    foreach ($question['options'] as $option) {
                        if ($option['is_correct']) {
                            $correctOptions[] = (string)$option['id'];
                        }
                    }
                }

                // Convert student answer to string for comparison
                $studentAnswerStr = (string)$studentAnswer;

                // Check if answer is correct for both subject and overall performance
                if (in_array($studentAnswerStr, $correctOptions)) {
                    $subjectPerformance[$subjectName]['correct']++;
                    $correctAnswers++; // Update overall correct count
                } else {
                    $subjectPerformance[$subjectName]['wrong']++;
                    $wrongAnswers++; // Update overall wrong count
                }
            }
        }

        // Calculate unanswered questions
        $unanswered = $totalQuestions - $answeredQuestions;

        // Get student's class_id from user record
        $student = $this->userModel->find($attempt['student_id']);
        $studentClassId = $student['class_id'] ?? null;

        // Get class performance comparison
        $classPerformance = $this->getClassPerformanceComparison($attempt['exam_id'], $studentClassId, $attemptId);

        // Get student's rank in class
        $classRank = $this->getStudentClassRank($attempt['exam_id'], $studentClassId, $attempt['marks_obtained']);

        // Get marks sheet data for multi-subject exams
        $marksSheetData = [];
        $isMultiSubject = ($exam['exam_mode'] ?? 'single_subject') === 'multi_subject';

        if ($isMultiSubject) {
            $marksSheetData = $this->calculateMarksSheetData($exam['id'], $questions, $savedAnswers, $subjectPerformance, $attemptId);
        }

        $data = [
            'title' => 'Exam Result - ' . get_app_name(),
            'pageTitle' => 'Exam Result',
            'pageSubtitle' => $attempt['exam_title'],
            'attempt' => $attempt,
            'exam' => $exam,
            'passed' => $attempt['marks_obtained'] >= $exam['passing_marks'],
            'questions' => $questions,
            'studentAnswers' => $savedAnswers, // Use the correct answer source
            'performance' => [
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'unanswered' => $unanswered,
                'accuracy' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0
            ],
            'subjectPerformance' => $subjectPerformance,
            'classPerformance' => $classPerformance,
            'classRank' => $classRank,
            'isMultiSubject' => $isMultiSubject,
            'marksSheetData' => $marksSheetData
        ];

        return view('student/exam_result_comprehensive', $data);
    }

    /**
     * Calculate marks sheet data for multi-subject exams
     */
    private function calculateMarksSheetData($examId, $questions, $studentAnswers, $subjectPerformance, $attemptId)
    {
        $examSubjectModel = new \App\Models\ExamSubjectModel();
        $examSubjects = $examSubjectModel->getExamSubjects($examId);

        if (empty($examSubjects)) {
            return [];
        }

        // Get real time tracking data
        $timeTrackingModel = new \App\Models\SubjectTimeTrackingModel();
        $subjectTimeData = $timeTrackingModel->getSubjectTimeSpent($attemptId);

        // Create a lookup array for subject time data
        $timeBySubject = [];
        foreach ($subjectTimeData as $timeData) {
            $timeBySubject[$timeData['subject_id']] = $timeData['time_spent_seconds'];
        }

        $marksSheetData = [];
        $totalExamMarks = 0;
        $totalExamQuestions = 0;
        $totalExamTime = 0;
        $totalTimeSpent = 0;

        // Calculate totals first
        foreach ($examSubjects as $examSubject) {
            $totalExamMarks += $examSubject['total_marks'];
            $totalExamQuestions += $examSubject['question_count'];
            $totalExamTime += $examSubject['time_allocation'];
            $totalTimeSpent += $timeBySubject[$examSubject['subject_id']] ?? 0;
        }

        foreach ($examSubjects as $examSubject) {
            $subjectName = $examSubject['subject_name'];
            $subjectId = $examSubject['subject_id'];

            // Get subject performance data
            $performance = $subjectPerformance[$subjectName] ?? [
                'total' => 0,
                'correct' => 0,
                'wrong' => 0,
                'unanswered' => 0
            ];

            // VALIDATION: Ensure performance data doesn't exceed configured question count
            // This prevents data inconsistencies that could lead to impossible scores
            if ($performance['correct'] > $examSubject['question_count']) {
                log_message('warning', "Data inconsistency detected for subject {$subjectName}: Correct answers ({$performance['correct']}) exceed configured questions ({$examSubject['question_count']})");
                $performance['correct'] = min($performance['correct'], $examSubject['question_count']);
            }

            // Calculate marks obtained for this subject
            $subjectMarksPerQuestion = $examSubject['total_marks'] / max(1, $examSubject['question_count']);
            $marksObtained = $performance['correct'] * $subjectMarksPerQuestion;

            // CRITICAL FIX: Ensure marks obtained never exceed total marks for the subject
            // This prevents the 15/10 scoring issue where calculated marks exceed total possible marks
            $marksObtained = min($marksObtained, $examSubject['total_marks']);

            // Log calculation details for debugging (can be removed in production)
            log_message('debug', "Subject: {$subjectName} | Configured Questions: {$examSubject['question_count']} | Total Marks: {$examSubject['total_marks']} | Correct Answers: {$performance['correct']} | Marks Per Question: {$subjectMarksPerQuestion} | Calculated Marks: " . ($performance['correct'] * $subjectMarksPerQuestion) . " | Final Marks: {$marksObtained}");

            // Calculate percentage for this subject
            $subjectPercentage = $examSubject['total_marks'] > 0 ?
                round(($marksObtained / $examSubject['total_marks']) * 100, 1) : 0;

            // Calculate subject proportion of total exam
            $subjectProportion = $totalExamMarks > 0 ?
                round(($examSubject['total_marks'] / $totalExamMarks) * 100, 1) : 0;

            // Get real time spent on this subject
            $actualTimeSpent = $timeBySubject[$subjectId] ?? 0;
            $timeSpentFormatted = $timeTrackingModel->formatTime($actualTimeSpent);

            $marksSheetData[] = [
                'subject_name' => $subjectName,
                'subject_proportion' => $subjectProportion . '%',
                'question_count' => $examSubject['question_count'],
                'total_marks' => $examSubject['total_marks'],
                'marks_obtained' => round($marksObtained, 2),
                'percentage' => $subjectPercentage . '%',
                'time_allocation' => $examSubject['time_allocation'],
                'actual_time_taken' => $timeSpentFormatted,
                'time_spent_seconds' => $actualTimeSpent,
                'answered_questions' => $performance['correct'] + $performance['wrong'],
                'correct_answers' => $performance['correct'],
                'wrong_answers' => $performance['wrong'],
                'unanswered' => $performance['unanswered']
            ];
        }

        // Add grand total row
        $totalMarksObtained = array_sum(array_column($marksSheetData, 'marks_obtained'));
        $totalAnswered = array_sum(array_column($marksSheetData, 'answered_questions'));
        $totalCorrect = array_sum(array_column($marksSheetData, 'correct_answers'));
        $totalWrong = array_sum(array_column($marksSheetData, 'wrong_answers'));
        $totalUnanswered = array_sum(array_column($marksSheetData, 'unanswered'));
        $overallPercentage = $totalExamMarks > 0 ?
            round(($totalMarksObtained / $totalExamMarks) * 100, 1) : 0;

        // Format total time spent
        $totalTimeSpentFormatted = $timeTrackingModel->formatTime($totalTimeSpent);

        $marksSheetData[] = [
            'subject_name' => 'Grand Total',
            'subject_proportion' => '100%',
            'question_count' => $totalExamQuestions,
            'total_marks' => $totalExamMarks,
            'marks_obtained' => round($totalMarksObtained, 2),
            'percentage' => $overallPercentage . '%',
            'time_allocation' => $totalExamTime,
            'actual_time_taken' => $totalTimeSpentFormatted,
            'time_spent_seconds' => $totalTimeSpent,
            'answered_questions' => $totalAnswered,
            'correct_answers' => $totalCorrect,
            'wrong_answers' => $totalWrong,
            'unanswered' => $totalUnanswered,
            'is_total_row' => true
        ];

        return $marksSheetData;
    }

    /**
     * Get class performance comparison data
     */
    private function getClassPerformanceComparison($examId, $classId, $currentAttemptId)
    {
        // Get exam details to calculate proper percentages
        $exam = $this->examModel->find($examId);

        // Get actual total marks from questions
        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $questions = $examQuestionModel->getExamQuestionsGrouped($examId);
        $actualTotalMarks = array_sum(array_column($questions, 'points'));

        // Use actual total marks if available, otherwise fall back to exam total_marks
        $totalMarks = $actualTotalMarks > 0 ? $actualTotalMarks : ($exam['total_marks'] ?? 1);

        // Get the best attempt for each student in the class for this exam
        $builder = $this->db->table('exam_attempts ea')
                           ->select('MAX(ea.marks_obtained) as marks_obtained,
                                    u.first_name, u.last_name, u.student_id, u.class_id')
                           ->join('users u', 'u.id = ea.student_id')
                           ->where('ea.exam_id', $examId)
                           ->where('u.class_id', $classId)
                           ->where('ea.status', 'submitted')
                           ->groupBy('ea.student_id')  // Group by student to get one record per student
                           ->orderBy('marks_obtained', 'DESC');

        $classResults = $builder->get()->getResultArray();

        if (empty($classResults)) {
            return [
                'total_students' => 0,
                'class_average' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'students_passed' => 0,
                'pass_rate' => 0,
                'top_performers' => []
            ];
        }

        $totalStudents = count($classResults);
        $totalMarksObtained = array_sum(array_column($classResults, 'marks_obtained'));
        $classAverage = round($totalMarksObtained / $totalStudents, 2);
        $highestScore = max(array_column($classResults, 'marks_obtained'));
        $lowestScore = min(array_column($classResults, 'marks_obtained'));

        // Get exam passing marks
        $exam = $this->examModel->find($examId);
        $passingMarks = $exam['passing_marks'] ?? 60;

        $studentsPassed = count(array_filter($classResults, function($result) use ($passingMarks) {
            return $result['marks_obtained'] >= $passingMarks;
        }));

        $passRate = round(($studentsPassed / $totalStudents) * 100, 2);

        // Calculate proper percentages for top performers
        $topPerformers = array_slice($classResults, 0, 5);
        foreach ($topPerformers as &$performer) {
            // Calculate the correct percentage based on marks obtained and exam total marks
            $performer['calculated_percentage'] = $totalMarks > 0 ?
                round(($performer['marks_obtained'] / $totalMarks) * 100, 2) : 0;
        }

        return [
            'total_students' => $totalStudents,
            'class_average' => $classAverage,
            'highest_score' => $highestScore,
            'lowest_score' => $lowestScore,
            'students_passed' => $studentsPassed,
            'pass_rate' => $passRate,
            'top_performers' => $topPerformers
        ];
    }

    /**
     * Get student's rank in class
     */
    private function getStudentClassRank($examId, $classId, $studentScore)
    {
        // Count students with better best scores than the current student
        $builder = $this->db->table('exam_attempts ea')
                           ->select('MAX(ea.marks_obtained) as best_score')
                           ->join('users u', 'u.id = ea.student_id')
                           ->where('ea.exam_id', $examId)
                           ->where('u.class_id', $classId)
                           ->where('ea.status', 'submitted')
                           ->groupBy('ea.student_id')
                           ->having('best_score >', $studentScore);

        $betterScores = $builder->countAllResults();

        return $betterScores + 1; // Rank is number of better scores + 1
    }

    /**
     * View all results
     */
    public function results()
    {
        $studentId = $this->session->get('user_id');
        $attempts = $this->attemptModel->getStudentAttempts($studentId);

        // Fix corrupted marks for each attempt using the same logic as examResult
        foreach ($attempts as &$attempt) {
            $actualMarksObtained = $this->calculateActualMarks($attempt);
            $attempt['actual_marks_obtained'] = $actualMarksObtained;
            $attempt['actual_percentage'] = $attempt['total_marks'] > 0 ?
                round(($actualMarksObtained / $attempt['total_marks']) * 100, 2) : 0;
        }

        $data = [
            'title' => 'My Results - ' . get_app_name(),
            'pageTitle' => 'Exam Results',
            'pageSubtitle' => 'Your examination history and scores',
            'attempts' => $attempts
        ];

        return view('student/results', $data);
    }

    /**
     * Calculate actual marks for an attempt using the same logic as calculateMarksSheetData
     */
    private function calculateActualMarks($attempt)
    {
        // Get student answers
        $savedAnswers = $attempt['answers'] ?? [];
        if (!is_array($savedAnswers)) {
            if (is_string($savedAnswers)) {
                $savedAnswers = json_decode($savedAnswers, true) ?? [];
            } elseif (is_object($savedAnswers)) {
                $savedAnswers = (array) $savedAnswers;
            } else {
                $savedAnswers = [];
            }
        }

        // Get exam questions
        $questions = $this->examQuestionModel->getExamQuestionsGrouped($attempt['exam_id']);

        // Check if this is a multi-subject exam
        $exam = $this->examModel->find($attempt['exam_id']);
        $isMultiSubject = ($exam['exam_mode'] === 'multi_subject');

        if ($isMultiSubject) {
            // For multi-subject exams, use the same logic as calculateMarksSheetData
            $examSubjectModel = new \App\Models\ExamSubjectModel();
            $examSubjects = $examSubjectModel->getExamSubjects($attempt['exam_id']);

            // Calculate subject performance
            $subjectPerformance = [];
            foreach ($questions as $question) {
                $questionId = $question['id'];
                $studentAnswer = $savedAnswers[$questionId] ?? $savedAnswers[(int)$questionId] ?? $savedAnswers[(string)$questionId] ?? null;

                $isAnswered = ($studentAnswer !== null && $studentAnswer !== '' && $studentAnswer !== '0');
                $subjectName = $question['subject_name'] ?? 'Unknown';

                if (!isset($subjectPerformance[$subjectName])) {
                    $subjectPerformance[$subjectName] = ['correct' => 0];
                }

                if ($isAnswered) {
                    // Get correct options
                    $correctOptions = [];
                    if (isset($question['options']) && is_array($question['options'])) {
                        foreach ($question['options'] as $option) {
                            if ($option['is_correct']) {
                                $correctOptions[] = (string)$option['id'];
                            }
                        }
                    }

                    if (in_array((string)$studentAnswer, $correctOptions)) {
                        $subjectPerformance[$subjectName]['correct']++;
                    }
                }
            }

            // Calculate total marks using the same logic as calculateMarksSheetData
            $totalMarksObtained = 0;
            foreach ($examSubjects as $examSubject) {
                $subjectName = $examSubject['subject_name'];
                $performance = $subjectPerformance[$subjectName] ?? ['correct' => 0];

                // Use the exact same calculation as the working marksheet
                $subjectMarksPerQuestion = $examSubject['total_marks'] / max(1, $examSubject['question_count']);
                $marksObtained = $performance['correct'] * $subjectMarksPerQuestion;
                $marksObtained = min($marksObtained, $examSubject['total_marks']);

                $totalMarksObtained += $marksObtained;
            }

            return $totalMarksObtained;
        } else {
            // For single-subject exams, use simple calculation
            $correctAnswers = 0;
            $totalQuestions = count($questions);

            foreach ($questions as $question) {
                $questionId = $question['id'];
                $studentAnswer = $savedAnswers[$questionId] ?? $savedAnswers[(int)$questionId] ?? $savedAnswers[(string)$questionId] ?? null;

                $isAnswered = ($studentAnswer !== null && $studentAnswer !== '' && $studentAnswer !== '0');

                if ($isAnswered) {
                    $correctOptions = [];
                    if (isset($question['options']) && is_array($question['options'])) {
                        foreach ($question['options'] as $option) {
                            if ($option['is_correct']) {
                                $correctOptions[] = (string)$option['id'];
                            }
                        }
                    }

                    if (in_array((string)$studentAnswer, $correctOptions)) {
                        $correctAnswers++;
                    }
                }
            }

            // Calculate marks: (correct answers / total questions) * total marks
            $totalMarks = $attempt['total_marks'] ?? 1;
            $marksPerQuestion = $totalQuestions > 0 ? ($totalMarks / $totalQuestions) : 1;
            return $correctAnswers * $marksPerQuestion;
        }
    }

    /**
     * Student progress tracking page
     */
    public function progress()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        if (!$student) {
            return redirect()->to('/auth/login');
        }

        // Get exam statistics
        $examStats = $this->getExamStatistics($studentId);

        // Get practice statistics
        $practiceStats = $this->getPracticeStatistics($studentId);

        // Get recent performance
        $recentPerformance = $this->getRecentPerformance($studentId);

        // Get subject-wise performance
        $subjectPerformance = $this->getSubjectPerformance($studentId);

        // Get monthly progress
        $monthlyProgress = $this->getMonthlyProgress($studentId);

        $data = [
            'title' => 'Progress Tracking - ' . get_app_name(),
            'pageTitle' => 'Progress Tracking',
            'pageSubtitle' => 'Monitor your academic performance and improvement',
            'student' => $student,
            'examStats' => $examStats,
            'practiceStats' => $practiceStats,
            'recentPerformance' => $recentPerformance,
            'subjectPerformance' => $subjectPerformance,
            'monthlyProgress' => $monthlyProgress
        ];

        return view('student/progress', $data);
    }

    /**
     * Academic history and progression
     */
    public function academicHistory()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        // Initialize academic history if needed
        $this->initializeStudentAcademicData($studentId);

        // Get academic history
        $history = $this->historyModel->getStudentHistory($studentId);

        // Get term results
        $termResults = $this->termResultsModel->getStudentTermResults($studentId);

        // Get progression
        $progression = $this->historyModel->getStudentProgression($studentId);

        // Calculate statistics
        $stats = $this->calculateAcademicStats($student, $history, $termResults);

        $data = [
            'title' => 'Academic History - ' . get_app_name(),
            'pageTitle' => 'Academic History',
            'pageSubtitle' => 'Your complete academic journey and performance records',
            'student' => $student,
            'history' => $history,
            'termResults' => $termResults,
            'progression' => $progression,
            'stats' => $stats
        ];

        return view('student/academic_history', $data);
    }

    /**
     * Initialize academic data for student if not exists
     */
    private function initializeStudentAcademicData($studentId)
    {
        // Get current session and term
        $sessionModel = new \App\Models\AcademicSessionModel();
        $termModel = new \App\Models\AcademicTermModel();

        $currentSession = $sessionModel->getCurrentSession();
        $currentTerm = $termModel->getCurrentTerm();

        if ($currentSession && $currentTerm) {
            // Initialize academic history record if it doesn't exist
            $this->historyModel->initializeStudentHistory(
                $studentId,
                $currentSession['id'],
                $currentTerm['id']
            );
        }
    }

    /**
     * Calculate academic statistics for display
     */
    private function calculateAcademicStats($student, $history, $termResults)
    {
        $stats = [
            'current_class' => 'N/A',
            'terms_completed' => 0,
            'average_performance' => 0.0,
            'promotions' => 0,
            'best_performance' => null,
            'recent_performance' => null
        ];

        // Current class - prioritize from student record
        if ($student && !empty($student['class_name'])) {
            $stats['current_class'] = $student['class_name'];
            if (!empty($student['class_section'])) {
                $stats['current_class'] .= ' (' . $student['class_section'] . ')';
            }
        } elseif (!empty($history)) {
            // Fallback to most recent history record
            $stats['current_class'] = $history[0]['class_name'];
            if (!empty($history[0]['class_section'])) {
                $stats['current_class'] .= ' (' . $history[0]['class_section'] . ')';
            }
        }

        // Terms completed
        $stats['terms_completed'] = count($termResults);

        // Average performance
        if (!empty($termResults)) {
            $totalPercentage = array_sum(array_column($termResults, 'overall_percentage'));
            $stats['average_performance'] = round($totalPercentage / count($termResults), 1);

            // Best performance
            $stats['best_performance'] = max(array_column($termResults, 'overall_percentage'));

            // Recent performance (most recent term)
            $stats['recent_performance'] = $termResults[0]['overall_percentage'];
        }

        // Promotions
        if (!empty($history)) {
            $stats['promotions'] = count(array_filter($history, function($h) {
                return $h['status'] === 'promoted';
            }));
        }

        return $stats;
    }

    /**
     * Record violation (AJAX)
     */
    public function recordViolation()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $attemptId = $this->request->getPost('attempt_id');
        $violationType = $this->request->getPost('violation_type');
        $details = $this->request->getPost('details');
        $studentId = $this->session->get('user_id');

        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->first();

        if ($attempt) {
            $this->attemptModel->recordViolation($attemptId, $violationType, $details);
        }

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Record pause/resume events (AJAX)
     */
    public function recordPauseEvent()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $attemptId = $this->request->getPost('attempt_id');
        $action = $this->request->getPost('action'); // 'pause' or 'resume'
        $studentId = $this->session->get('user_id');

        // Verify the attempt belongs to the student
        $attempt = $this->attemptModel->where('id', $attemptId)
                                     ->where('student_id', $studentId)
                                     ->first();

        if (!$attempt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attempt']);
        }

        // Log the pause/resume event
        $logData = [
            'attempt_id' => $attemptId,
            'event_type' => 'exam_' . $action,
            'event_data' => json_encode([
                'action' => $action,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $this->request->getIPAddress()
            ]),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // You can add this to a security_logs table or exam_attempts table
        // For now, we'll just return success
        return $this->response->setJSON(['success' => true]);
    }

    /**
     * View practice tests
     */
    public function practice()
    {
        $studentId = $this->session->get('user_id');

        // Get student with class information
        $student = $this->userModel->select('users.*, classes.name as class_name, classes.section as class_section')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        // Get available practice categories with question counts
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();
        $categories = $practiceQuestionModel->select('category, COUNT(*) as question_count')
                                          ->where('is_active', 1)
                                          ->groupBy('category')
                                          ->orderBy('category', 'ASC')
                                          ->findAll();

        // Get recent practice sessions for this student
        $practiceModel = new \App\Models\PracticeSessionModel();
        $recentPractices = $practiceModel->getStudentPracticeSessions($studentId, 10);

        // Get practice statistics
        $practiceStats = [
            'total_sessions' => $practiceModel->where('student_id', $studentId)
                                             ->where('status', 'completed')
                                             ->countAllResults(),
            'average_score' => 0,
            'best_score' => 0,
            'sessions_this_week' => 0
        ];

        if ($practiceStats['total_sessions'] > 0) {
            // Calculate average score
            $avgResult = $practiceModel->selectAvg('percentage')
                                      ->where('student_id', $studentId)
                                      ->where('status', 'completed')
                                      ->first();
            $practiceStats['average_score'] = round($avgResult['percentage'] ?? 0, 1);

            // Get best score
            $bestResult = $practiceModel->selectMax('percentage')
                                       ->where('student_id', $studentId)
                                       ->where('status', 'completed')
                                       ->first();
            $practiceStats['best_score'] = round($bestResult['percentage'] ?? 0, 1);

            // Count sessions this week
            $weekStart = date('Y-m-d', strtotime('monday this week'));
            $practiceStats['sessions_this_week'] = $practiceModel->where('student_id', $studentId)
                                                                 ->where('status', 'completed')
                                                                 ->where('DATE(created_at) >=', $weekStart)
                                                                 ->countAllResults();
        }

        $data = [
            'title' => 'Practice Tests - ' . get_app_name(),
            'pageTitle' => 'Practice Tests',
            'pageSubtitle' => 'Improve your skills with practice questions',
            'student' => $student,
            'categories' => $categories,
            'recentPractices' => $recentPractices,
            'practiceStats' => $practiceStats
        ];

        return view('student/practice', $data);
    }

    /**
     * Start practice test
     */
    public function startPractice($category = null)
    {
        $studentId = $this->session->get('user_id');

        // Get category from URL parameter or POST data
        if (!$category) {
            $category = $this->request->getPost('category');
        }

        if (!$category) {
            return redirect()->to('/student/practice')->with('error', 'Practice category is required');
        }

        // Get student information
        $student = $this->userModel->find($studentId);

        // Get random practice questions for the category (limit to 10 questions)
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();
        $questions = $practiceQuestionModel->getQuestionsByCategory($category, 10);

        if (empty($questions)) {
            return redirect()->to('/student/practice')->with('error', 'No practice questions available for this category');
        }

        // Create practice session
        $practiceData = [
            'student_id' => $studentId,
            'subject_id' => null, // Not using subject for practice questions
            'class_id' => $student['class_id'] ?? null,
            'category' => $category,
            'questions' => json_encode(array_column($questions, 'id')),
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'in_progress',
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ];

        $practiceModel = new \App\Models\PracticeSessionModel();
        $practiceId = $practiceModel->insert($practiceData);

        if ($practiceId) {
            return redirect()->to('/student/takePractice/' . $practiceId);
        } else {
            return redirect()->to('/student/practice')->with('error', 'Failed to start practice session');
        }
    }

    /**
     * Take practice test interface
     */
    public function takePractice($practiceId)
    {
        $studentId = $this->session->get('user_id');

        $practiceModel = new \App\Models\PracticeSessionModel();
        $practice = $practiceModel->where('id', $practiceId)
                                 ->where('student_id', $studentId)
                                 ->where('status', 'in_progress')
                                 ->first();

        if (!$practice) {
            return redirect()->to('/student/practice')->with('error', 'Invalid practice session');
        }

        // Get practice questions for this session
        $questionIds = json_decode($practice['questions'], true);
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();
        $questions = $practiceQuestionModel->whereIn('id', $questionIds)->findAll();

        if (empty($questions)) {
            return redirect()->to('/student/practice')->with('error', 'No questions found for this practice session');
        }

        // Get category from first question
        $category = $questions[0]['category'] ?? 'Practice';

        // Shuffle questions to randomize order
        shuffle($questions);

        $data = [
            'title' => 'Practice: ' . $category . ' - ' . get_app_name(),
            'practice' => $practice,
            'category' => $category,
            'questions' => $questions,
            'currentAnswers' => json_decode($practice['answers'] ?? '{}', true)
        ];

        return view('student/take_practice', $data);
    }

    /**
     * Save practice answer (AJAX)
     */
    public function savePracticeAnswer()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $practiceId = $this->request->getPost('practice_id');
        $questionId = $this->request->getPost('question_id');
        $answer = $this->request->getPost('answer');
        $studentId = $this->session->get('user_id');

        $practiceModel = new \App\Models\PracticeSessionModel();
        $practice = $practiceModel->where('id', $practiceId)
                                 ->where('student_id', $studentId)
                                 ->where('status', 'in_progress')
                                 ->first();

        if (!$practice) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid practice session']);
        }

        // Update answers
        $answers = json_decode($practice['answers'] ?? '{}', true);
        $answers[$questionId] = $answer;

        $updated = $practiceModel->update($practiceId, [
            'answers' => json_encode($answers),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => $updated]);
    }

    /**
     * Submit practice test
     */
    public function submitPractice($practiceId)
    {
        $studentId = $this->session->get('user_id');

        $practiceModel = new \App\Models\PracticeSessionModel();
        $practice = $practiceModel->where('id', $practiceId)
                                 ->where('student_id', $studentId)
                                 ->where('status', 'in_progress')
                                 ->first();

        if (!$practice) {
            return redirect()->to('/student/practice')->with('error', 'Invalid practice session');
        }

        // Calculate score
        $questionIds = json_decode($practice['questions'], true);
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();
        $questions = $practiceQuestionModel->whereIn('id', $questionIds)->findAll();
        $answers = json_decode($practice['answers'] ?? '{}', true);

        $totalQuestions = count($questions);
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $studentAnswer = $answers[$question['id']] ?? '';
            if ($studentAnswer === $question['correct_answer']) {
                $correctAnswers++;
            }
        }

        $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        // Update practice session
        $updateData = [
            'status' => 'completed',
            'end_time' => date('Y-m-d H:i:s'),
            'score' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage
        ];

        $updated = $practiceModel->update($practiceId, $updateData);

        if ($updated) {
            return redirect()->to('/student/practiceResult/' . $practiceId)->with('success', 'Practice test completed!');
        } else {
            return redirect()->to('/student/takePractice/' . $practiceId)->with('error', 'Failed to submit practice test');
        }
    }

    /**
     * View practice test result
     */
    public function practiceResult($practiceId)
    {
        $studentId = $this->session->get('user_id');

        $practiceModel = new \App\Models\PracticeSessionModel();
        $practice = $practiceModel->where('id', $practiceId)
                                 ->where('student_id', $studentId)
                                 ->where('status', 'completed')
                                 ->first();

        if (!$practice) {
            return redirect()->to('/student/practice')->with('error', 'Practice result not found');
        }

        // Get practice questions and answers for detailed review
        $questionIds = json_decode($practice['questions'], true);
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();
        $questions = $practiceQuestionModel->whereIn('id', $questionIds)->findAll();
        $answers = json_decode($practice['answers'] ?? '{}', true);

        // Get category from first question
        $category = !empty($questions) ? $questions[0]['category'] : 'Practice';

        // Prepare detailed results
        $detailedResults = [];
        foreach ($questions as $question) {
            $studentAnswer = $answers[$question['id']] ?? '';
            $isCorrect = $studentAnswer === $question['correct_answer'];

            $detailedResults[] = [
                'question' => $question,
                'student_answer' => $studentAnswer,
                'correct_answer' => $question['correct_answer'],
                'is_correct' => $isCorrect
            ];
        }

        $data = [
            'title' => 'Practice Result - ' . get_app_name(),
            'pageTitle' => 'Practice Test Result',
            'pageSubtitle' => $category,
            'practice' => $practice,
            'category' => $category,
            'detailedResults' => $detailedResults,
            'passed' => $practice['percentage'] >= 60
        ];

        return view('student/practice_result', $data);
    }

    /**
     * View all practice results
     */
    public function practiceHistory()
    {
        $studentId = $this->session->get('user_id');

        // Get student information
        $student = $this->userModel->select('users.*, classes.name as class_name')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.id', $studentId)
                                  ->first();

        // Get all practice sessions for this student
        $practiceModel = new \App\Models\PracticeSessionModel();
        $allPractices = $practiceModel->getStudentPracticeSessions($studentId);

        // Get practice statistics
        $practiceStats = [
            'total_sessions' => $practiceModel->where('student_id', $studentId)
                                             ->where('status', 'completed')
                                             ->countAllResults(),
            'average_score' => 0,
            'best_score' => 0,
            'total_questions_attempted' => 0
        ];

        if ($practiceStats['total_sessions'] > 0) {
            // Calculate average score
            $avgResult = $practiceModel->selectAvg('percentage')
                                      ->where('student_id', $studentId)
                                      ->where('status', 'completed')
                                      ->first();
            $practiceStats['average_score'] = round($avgResult['percentage'] ?? 0, 1);

            // Get best score
            $bestResult = $practiceModel->selectMax('percentage')
                                       ->where('student_id', $studentId)
                                       ->where('status', 'completed')
                                       ->first();
            $practiceStats['best_score'] = round($bestResult['percentage'] ?? 0, 1);

            // Calculate total questions attempted
            $totalQuestionsResult = $practiceModel->selectSum('total_questions')
                                                 ->where('student_id', $studentId)
                                                 ->where('status', 'completed')
                                                 ->first();
            $practiceStats['total_questions_attempted'] = $totalQuestionsResult['total_questions'] ?? 0;
        }

        $data = [
            'title' => 'Practice History - ' . get_app_name(),
            'pageTitle' => 'Practice History',
            'pageSubtitle' => 'Your complete practice test history',
            'student' => $student,
            'allPractices' => $allPractices,
            'practiceStats' => $practiceStats
        ];

        return view('student/practice_history', $data);
    }

    /**
     * Calculate time remaining for exam
     */
    private function calculateTimeRemaining($attempt, $exam)
    {
        $startTime = new \DateTime($attempt['start_time']);
        $currentTime = new \DateTime();
        $examEndTime = new \DateTime($exam['end_time']);

        // Calculate time based on exam duration
        $durationEndTime = clone $startTime;
        $durationEndTime->add(new \DateInterval('PT' . $exam['duration_minutes'] . 'M'));

        // Use the earlier of exam end time or duration end time
        $effectiveEndTime = $examEndTime < $durationEndTime ? $examEndTime : $durationEndTime;

        $diff = $effectiveEndTime->diff($currentTime);

        if ($effectiveEndTime < $currentTime) {
            return 0; // Time expired
        }

        return ($diff->h * 60) + $diff->i; // Return minutes remaining
    }

    /**
     * Get exam statistics for progress tracking (using corrected marks)
     */
    private function getExamStatistics($studentId)
    {
        // Get all submitted attempts with exam total_marks
        $attempts = $this->attemptModel->select('exam_attempts.*, exams.total_marks')
                                      ->join('exams', 'exams.id = exam_attempts.exam_id')
                                      ->where('exam_attempts.student_id', $studentId)
                                      ->where('exam_attempts.status', 'submitted')
                                      ->findAll();

        $totalExams = count($attempts);

        if ($totalExams === 0) {
            return [
                'total_exams' => 0,
                'average_score' => 0,
                'best_score' => 0,
                'passed_exams' => 0,
                'pass_rate' => 0
            ];
        }

        // Calculate corrected percentages for each attempt
        $correctedPercentages = [];
        $passedExams = 0;

        foreach ($attempts as $attempt) {
            $actualMarksObtained = $this->calculateActualMarks($attempt);
            $actualPercentage = $attempt['total_marks'] > 0 ?
                round(($actualMarksObtained / $attempt['total_marks']) * 100, 2) : 0;

            $correctedPercentages[] = $actualPercentage;

            // Count passed exams (assuming 60% is passing)
            if ($actualPercentage >= 60) {
                $passedExams++;
            }
        }

        // Calculate statistics from corrected percentages
        $averageScore = count($correctedPercentages) > 0 ?
            round(array_sum($correctedPercentages) / count($correctedPercentages), 1) : 0;
        $bestScore = count($correctedPercentages) > 0 ?
            round(max($correctedPercentages), 1) : 0;
        $passRate = $totalExams > 0 ? round(($passedExams / $totalExams) * 100, 1) : 0;

        return [
            'total_exams' => $totalExams,
            'average_score' => $averageScore,
            'best_score' => $bestScore,
            'passed_exams' => $passedExams,
            'pass_rate' => $passRate
        ];
    }

    /**
     * Get practice statistics for progress tracking
     */
    private function getPracticeStatistics($studentId)
    {
        $practiceModel = new \App\Models\PracticeSessionModel();

        $totalPractices = $practiceModel->where('student_id', $studentId)
                                       ->where('status', 'completed')
                                       ->countAllResults();

        $avgResult = $practiceModel->selectAvg('percentage')
                                  ->where('student_id', $studentId)
                                  ->where('status', 'completed')
                                  ->first();

        $bestResult = $practiceModel->selectMax('percentage')
                                   ->where('student_id', $studentId)
                                   ->where('status', 'completed')
                                   ->first();

        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekPractices = $practiceModel->where('student_id', $studentId)
                                          ->where('status', 'completed')
                                          ->where('DATE(created_at) >=', $thisWeekStart)
                                          ->countAllResults();

        return [
            'total_practices' => $totalPractices,
            'average_score' => round($avgResult['percentage'] ?? 0, 1),
            'best_score' => round($bestResult['percentage'] ?? 0, 1),
            'this_week' => $thisWeekPractices
        ];
    }

    /**
     * Get recent performance data (with corrected percentages)
     */
    private function getRecentPerformance($studentId)
    {
        $recentExams = $this->attemptModel->select('exam_attempts.*, exams.title as exam_title, exams.total_marks')
                                         ->join('exams', 'exams.id = exam_attempts.exam_id')
                                         ->where('exam_attempts.student_id', $studentId)
                                         ->where('exam_attempts.status', 'submitted')
                                         ->orderBy('exam_attempts.created_at', 'DESC')
                                         ->limit(5)
                                         ->findAll();

        // Fix corrupted percentages for recent exams
        foreach ($recentExams as &$exam) {
            $actualMarksObtained = $this->calculateActualMarks($exam);
            $exam['actual_percentage'] = $exam['total_marks'] > 0 ?
                round(($actualMarksObtained / $exam['total_marks']) * 100, 2) : 0;
            // Use the corrected percentage for display
            $exam['percentage'] = $exam['actual_percentage'];
        }

        $practiceModel = new \App\Models\PracticeSessionModel();
        $recentPractices = $practiceModel->where('student_id', $studentId)
                                        ->where('status', 'completed')
                                        ->orderBy('created_at', 'DESC')
                                        ->limit(5)
                                        ->findAll();

        return [
            'recent_exams' => $recentExams,
            'recent_practices' => $recentPractices
        ];
    }

    /**
     * Get subject-wise performance (handles both single-subject and multi-subject exams)
     */
    private function getSubjectPerformance($studentId)
    {
        $subjectPerformance = [];

        // Get all completed exam attempts for this student
        $attempts = $this->attemptModel->select('exam_attempts.*, exams.exam_mode, exams.subject_id as exam_subject_id, exams.total_marks')
                                      ->join('exams', 'exams.id = exam_attempts.exam_id')
                                      ->where('exam_attempts.student_id', $studentId)
                                      ->where('exam_attempts.status', 'submitted')
                                      ->findAll();

        foreach ($attempts as $attempt) {
            if ($attempt['exam_mode'] === 'multi_subject') {
                // For multi-subject exams, calculate performance for each subject
                $subjectPerformances = $this->calculateMultiSubjectPerformance($attempt);

                foreach ($subjectPerformances as $subjectName => $performance) {
                    if (!isset($subjectPerformance[$subjectName])) {
                        $subjectPerformance[$subjectName] = [
                            'total_percentage' => 0,
                            'exam_count' => 0
                        ];
                    }

                    $subjectPerformance[$subjectName]['total_percentage'] += $performance['percentage'];
                    $subjectPerformance[$subjectName]['exam_count']++;
                }
            } else {
                // For single-subject exams, use the exam's subject
                if ($attempt['exam_subject_id']) {
                    $subjectModel = new \App\Models\SubjectModel();
                    $subject = $subjectModel->find($attempt['exam_subject_id']);

                    if ($subject) {
                        $subjectName = $subject['name'];

                        if (!isset($subjectPerformance[$subjectName])) {
                            $subjectPerformance[$subjectName] = [
                                'total_percentage' => 0,
                                'exam_count' => 0
                            ];
                        }

                        // Use the corrected percentage from our calculateActualMarks method
                        $actualMarksObtained = $this->calculateActualMarks($attempt);
                        $actualPercentage = $attempt['total_marks'] > 0 ?
                            round(($actualMarksObtained / $attempt['total_marks']) * 100, 2) : 0;

                        $subjectPerformance[$subjectName]['total_percentage'] += $actualPercentage;
                        $subjectPerformance[$subjectName]['exam_count']++;
                    }
                }
            }
        }

        // Calculate averages and format results
        $results = [];
        foreach ($subjectPerformance as $subjectName => $data) {
            $avgPercentage = $data['exam_count'] > 0 ?
                round($data['total_percentage'] / $data['exam_count'], 1) : 0;

            $results[] = [
                'subject_name' => $subjectName,
                'avg_percentage' => $avgPercentage,
                'exam_count' => $data['exam_count']
            ];
        }

        // Sort by average percentage descending
        usort($results, function($a, $b) {
            return $b['avg_percentage'] <=> $a['avg_percentage'];
        });

        return $results;
    }

    /**
     * Calculate performance for each subject in a multi-subject exam
     */
    private function calculateMultiSubjectPerformance($attempt)
    {
        // Get student answers
        $savedAnswers = $attempt['answers'] ?? [];
        if (!is_array($savedAnswers)) {
            if (is_string($savedAnswers)) {
                $savedAnswers = json_decode($savedAnswers, true) ?? [];
            } elseif (is_object($savedAnswers)) {
                $savedAnswers = (array) $savedAnswers;
            } else {
                $savedAnswers = [];
            }
        }

        // Get exam questions and subjects
        $questions = $this->examQuestionModel->getExamQuestionsGrouped($attempt['exam_id']);
        $examSubjectModel = new \App\Models\ExamSubjectModel();
        $examSubjects = $examSubjectModel->getExamSubjects($attempt['exam_id']);

        // Calculate subject performance using the same logic as calculateMarksSheetData
        $subjectPerformance = [];
        foreach ($questions as $question) {
            $questionId = $question['id'];
            $studentAnswer = $savedAnswers[$questionId] ?? $savedAnswers[(int)$questionId] ?? $savedAnswers[(string)$questionId] ?? null;

            $isAnswered = ($studentAnswer !== null && $studentAnswer !== '' && $studentAnswer !== '0');
            $subjectName = $question['subject_name'] ?? 'Unknown';

            if (!isset($subjectPerformance[$subjectName])) {
                $subjectPerformance[$subjectName] = ['correct' => 0];
            }

            if ($isAnswered) {
                // Get correct options
                $correctOptions = [];
                if (isset($question['options']) && is_array($question['options'])) {
                    foreach ($question['options'] as $option) {
                        if ($option['is_correct']) {
                            $correctOptions[] = (string)$option['id'];
                        }
                    }
                }

                if (in_array((string)$studentAnswer, $correctOptions)) {
                    $subjectPerformance[$subjectName]['correct']++;
                }
            }
        }

        // Calculate percentage for each subject
        $results = [];
        foreach ($examSubjects as $examSubject) {
            $subjectName = $examSubject['subject_name'];
            $performance = $subjectPerformance[$subjectName] ?? ['correct' => 0];

            // Use the exact same calculation as the working marksheet
            $subjectMarksPerQuestion = $examSubject['total_marks'] / max(1, $examSubject['question_count']);
            $marksObtained = $performance['correct'] * $subjectMarksPerQuestion;
            $marksObtained = min($marksObtained, $examSubject['total_marks']);

            $percentage = $examSubject['total_marks'] > 0 ?
                round(($marksObtained / $examSubject['total_marks']) * 100, 1) : 0;

            $results[$subjectName] = [
                'percentage' => $percentage,
                'marks_obtained' => $marksObtained,
                'total_marks' => $examSubject['total_marks']
            ];
        }

        return $results;
    }

    /**
     * Get monthly progress data (with corrected exam percentages)
     */
    private function getMonthlyProgress($studentId)
    {
        // Get all exam attempts from the last 6 months with exam total_marks
        $attempts = $this->attemptModel->select('exam_attempts.*, exams.total_marks')
                                      ->join('exams', 'exams.id = exam_attempts.exam_id')
                                      ->where('exam_attempts.student_id', $studentId)
                                      ->where('exam_attempts.status', 'submitted')
                                      ->where('exam_attempts.created_at >=', date('Y-m-d', strtotime('-6 months')))
                                      ->orderBy('exam_attempts.created_at', 'ASC')
                                      ->findAll();

        // Calculate corrected percentages and group by month
        $examProgressByMonth = [];
        foreach ($attempts as $attempt) {
            $month = date('Y-m', strtotime($attempt['created_at']));
            $actualMarksObtained = $this->calculateActualMarks($attempt);
            $actualPercentage = $attempt['total_marks'] > 0 ?
                round(($actualMarksObtained / $attempt['total_marks']) * 100, 2) : 0;

            if (!isset($examProgressByMonth[$month])) {
                $examProgressByMonth[$month] = [
                    'total_percentage' => 0,
                    'exam_count' => 0
                ];
            }

            $examProgressByMonth[$month]['total_percentage'] += $actualPercentage;
            $examProgressByMonth[$month]['exam_count']++;
        }

        // Convert to the expected format
        $examProgress = [];
        foreach ($examProgressByMonth as $month => $data) {
            $examProgress[] = [
                'month' => $month,
                'avg_score' => round($data['total_percentage'] / $data['exam_count'], 2),
                'exam_count' => $data['exam_count']
            ];
        }

        // Get practice progress by month (this data should be correct)
        $db = \Config\Database::connect();
        $practiceBuilder = $db->table('practice_sessions')
                             ->select("DATE_FORMAT(created_at, '%Y-%m') as month, AVG(percentage) as avg_score, COUNT(*) as practice_count")
                             ->where('student_id', $studentId)
                             ->where('status', 'completed')
                             ->where('created_at >=', date('Y-m-d', strtotime('-6 months')))
                             ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
                             ->orderBy('month', 'ASC');

        $practiceProgress = $practiceBuilder->get()->getResultArray();

        return [
            'exam_progress' => $examProgress,
            'practice_progress' => $practiceProgress
        ];
    }
}
