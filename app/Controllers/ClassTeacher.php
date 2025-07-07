<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\ExamAttemptModel;
use App\Models\SubjectModel;
use App\Models\AcademicSessionModel;
use App\Models\AcademicTermModel;
use CodeIgniter\Controller;
use Exception;

class ClassTeacher extends Controller
{
    protected $userModel;
    protected $classModel;
    protected $examModel;
    protected $attemptModel;
    protected $subjectModel;
    protected $sessionModel;
    protected $termModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->classModel = new ClassModel();
        $this->examModel = new ExamModel();
        $this->attemptModel = new ExamAttemptModel();
        $this->subjectModel = new SubjectModel();
        $this->sessionModel = new AcademicSessionModel();
        $this->termModel = new AcademicTermModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);

        // Check if user is logged in and is a class teacher
        if (!$this->session->get('is_logged_in') || $this->session->get('role') !== 'class_teacher') {
            redirect()->to('/auth/login')->send();
            exit;
        }
    }

    /**
     * Class Teacher dashboard
     */
    public function dashboard()
    {
        try {
            // Get user info with fallback
            $classTeacherId = $this->session->get('user_id');
            if (!$classTeacherId) {
                // For testing, get any class teacher
                $classTeacher = $this->userModel->where('role', 'class_teacher')->first();
                $classTeacherId = $classTeacher['id'] ?? 1;
            } else {
                $classTeacher = $this->userModel->find($classTeacherId);
            }

            $classId = $classTeacher['class_id'] ?? 1;

            // Get class information with fallback
            $class = $this->classModel->find($classId);
            if (!$class) {
                $class = ['id' => $classId, 'name' => 'Test Class', 'section' => 'A'];
            }

            // Get current academic session and term with fallback
            $currentSession = null;
            $currentTerm = null;
            try {
                $currentSession = $this->sessionModel->getCurrentSession();
                $currentTerm = $this->termModel->getCurrentTerm();
            } catch (Exception $e) {
                // Fallback if no session/term data
                $currentSession = ['id' => 1, 'session_name' => '2024-2025'];
                $currentTerm = ['id' => 1, 'term_name' => 'First Term'];
            }

            // Get class students with fallback
            $students = [];
            try {
                $students = $this->classModel->getClassStudents($classId);
            } catch (Exception $e) {
                $students = [];
            }

            // Get class subjects with fallback
            $subjects = [];
            try {
                $subjects = $this->classModel->getClassSubjects($classId);
            } catch (Exception $e) {
                $subjects = [];
            }

            // Get recent exam results for the class with fallback
            $recentResults = [];
            try {
                $recentResults = $this->getClassRecentResults($classId, 10);
            } catch (Exception $e) {
                $recentResults = [];
            }

            // Get class statistics with fallback
            $classStats = [
                'total_students' => count($students),
                'total_attempts' => 0,
                'average_score' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'pass_rate' => 0
            ];
            try {
                $classStats = $this->getClassStatistics($classId, $currentSession['id'] ?? null, $currentTerm['id'] ?? null);
            } catch (Exception $e) {
                // Use fallback stats
            }

            $data = [
                'title' => 'Class Teacher Dashboard - ' . get_app_name(),
                'pageTitle' => 'Welcome, ' . ($class['name'] ?? 'Class') . ' Teacher',
                'pageSubtitle' => 'Manage your class results and performance',
                'classTeacher' => $classTeacher,
                'class' => $class,
                'students' => $students,
                'subjects' => $subjects,
                'recentResults' => $recentResults,
                'classStats' => $classStats,
                'currentSession' => $currentSession,
                'currentTerm' => $currentTerm
            ];

            return view('class_teacher/dashboard', $data);

        } catch (Exception $e) {
            // If everything fails, show error info
            $data = [
                'title' => 'Class Teacher Dashboard Error - ' . get_app_name(),
                'pageTitle' => 'Dashboard Error',
                'pageSubtitle' => 'Error loading dashboard',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
            return view('class_teacher/error_dashboard', $data);
        }
    }

    /**
     * Class marksheet view
     */
    public function marksheet()
    {
        $classTeacherId = $this->session->get('user_id');
        $classTeacher = $this->userModel->find($classTeacherId);
        $classId = $classTeacher['class_id'];

        // Get filters from request
        $sessionId = $this->request->getGet('session_id');
        $termId = $this->request->getGet('term_id');
        $examType = $this->request->getGet('exam_type');

        // Get available sessions and terms
        $sessions = $this->sessionModel->findAll();
        $terms = $this->termModel->findAll();

        // Use current session/term if not specified
        if (!$sessionId) {
            $currentSession = $this->sessionModel->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;
        }
        if (!$termId) {
            $currentTerm = $this->termModel->getCurrentTerm();
            $termId = $currentTerm['id'] ?? null;
        }

        // Get class information
        $class = $this->classModel->find($classId);
        
        // Get class students
        $students = $this->classModel->getClassStudents($classId);
        
        // Get class subjects
        $subjects = $this->classModel->getClassSubjects($classId);

        // Generate marksheet data
        $marksheetData = $this->generateMarksheetData($classId, $sessionId, $termId, $examType);

        $data = [
            'title' => 'Class Marksheet - ' . get_app_name(),
            'pageTitle' => $class['name'] . ' - Marksheet',
            'pageSubtitle' => 'Student performance overview',
            'class' => $class,
            'students' => $students,
            'subjects' => $subjects,
            'marksheetData' => $marksheetData,
            'sessions' => $sessions,
            'terms' => $terms,
            'selectedSession' => $sessionId,
            'selectedTerm' => $termId,
            'selectedExamType' => $examType
        ];

        return view('class_teacher/marksheet', $data);
    }

    /**
     * Get class recent results
     */
    private function getClassRecentResults($classId, $limit = 10)
    {
        return $this->attemptModel->select('exam_attempts.*, exams.title as exam_title, exams.total_marks, 
                                           subjects.name as subject_name, users.first_name, users.last_name, users.student_id')
                                 ->join('exams', 'exams.id = exam_attempts.exam_id')
                                 ->join('subjects', 'subjects.id = exams.subject_id')
                                 ->join('users', 'users.id = exam_attempts.student_id')
                                 ->where('users.class_id', $classId)
                                 ->whereIn('exam_attempts.status', ['submitted', 'auto_submitted'])
                                 ->orderBy('exam_attempts.submitted_at', 'DESC')
                                 ->limit($limit)
                                 ->findAll();
    }

    /**
     * Get class statistics
     */
    private function getClassStatistics($classId, $sessionId = null, $termId = null)
    {
        $builder = $this->attemptModel->select('exam_attempts.*, exams.total_marks')
                                     ->join('exams', 'exams.id = exam_attempts.exam_id')
                                     ->join('users', 'users.id = exam_attempts.student_id')
                                     ->where('users.class_id', $classId)
                                     ->whereIn('exam_attempts.status', ['submitted', 'auto_submitted']);

        if ($sessionId) {
            $builder->where('exam_attempts.session_id', $sessionId);
        }
        if ($termId) {
            $builder->where('exam_attempts.term_id', $termId);
        }

        $attempts = $builder->findAll();

        $stats = [
            'total_students' => count($this->classModel->getClassStudents($classId)),
            'total_attempts' => count($attempts),
            'average_score' => 0,
            'highest_score' => 0,
            'lowest_score' => 100,
            'pass_rate' => 0
        ];

        if (count($attempts) > 0) {
            $totalScore = 0;
            $passCount = 0;

            foreach ($attempts as $attempt) {
                $percentage = $attempt['percentage'];
                $totalScore += $percentage;

                if ($percentage > $stats['highest_score']) {
                    $stats['highest_score'] = $percentage;
                }
                if ($percentage < $stats['lowest_score']) {
                    $stats['lowest_score'] = $percentage;
                }

                // Assuming 40% is pass mark
                if ($percentage >= 40) {
                    $passCount++;
                }
            }

            $stats['average_score'] = round($totalScore / count($attempts), 1);
            $stats['pass_rate'] = round(($passCount / count($attempts)) * 100, 1);
        }

        return $stats;
    }

    /**
     * Generate marksheet data
     */
    private function generateMarksheetData($classId, $sessionId, $termId, $examType = null)
    {
        // Get class students
        $students = $this->classModel->getClassStudents($classId);
        
        // Get class subjects
        $subjects = $this->classModel->getClassSubjects($classId);

        $marksheetData = [];

        foreach ($students as $student) {
            $studentData = [
                'student' => $student,
                'subjects' => [],
                'total_marks' => 0,
                'total_possible' => 0,
                'percentage' => 0,
                'grade' => 'F',
                'position' => 0
            ];

            foreach ($subjects as $subject) {
                // Get student's best attempt for this subject in the specified session/term
                $builder = $this->attemptModel->select('exam_attempts.*, exams.total_marks, exams.title as exam_title')
                                             ->join('exams', 'exams.id = exam_attempts.exam_id')
                                             ->where('exam_attempts.student_id', $student['id'])
                                             ->where('exams.subject_id', $subject['id'])
                                             ->whereIn('exam_attempts.status', ['submitted', 'auto_submitted']);

                if ($sessionId) {
                    $builder->where('exam_attempts.session_id', $sessionId);
                }
                if ($termId) {
                    $builder->where('exam_attempts.term_id', $termId);
                }
                if ($examType) {
                    $builder->where('exams.exam_type', $examType);
                }

                $attempt = $builder->orderBy('exam_attempts.percentage', 'DESC')->first();

                $subjectData = [
                    'subject_name' => $subject['name'],
                    'marks_obtained' => $attempt['marks_obtained'] ?? 0,
                    'total_marks' => $attempt['total_marks'] ?? 0,
                    'percentage' => $attempt['percentage'] ?? 0,
                    'grade' => $this->calculateGrade($attempt['percentage'] ?? 0),
                    'exam_title' => $attempt['exam_title'] ?? 'No Exam'
                ];

                $studentData['subjects'][] = $subjectData;
                $studentData['total_marks'] += $subjectData['marks_obtained'];
                $studentData['total_possible'] += $subjectData['total_marks'];
            }

            // Calculate overall percentage and grade
            if ($studentData['total_possible'] > 0) {
                $studentData['percentage'] = round(($studentData['total_marks'] / $studentData['total_possible']) * 100, 2);
            }
            $studentData['grade'] = $this->calculateGrade($studentData['percentage']);

            $marksheetData[] = $studentData;
        }

        // Sort by percentage for position calculation
        usort($marksheetData, function($a, $b) {
            return $b['percentage'] - $a['percentage'];
        });

        // Assign positions
        foreach ($marksheetData as $index => &$studentData) {
            $studentData['position'] = $index + 1;
        }

        return $marksheetData;
    }

    /**
     * Calculate grade based on percentage
     */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    /**
     * Debug method to check session and data
     */
    public function debug()
    {
        $sessionData = [
            'user_id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'role' => $this->session->get('role'),
            'class_id' => $this->session->get('class_id'),
            'is_logged_in' => $this->session->get('is_logged_in')
        ];

        $classTeacherId = $this->session->get('user_id');
        $classTeacher = $this->userModel->find($classTeacherId);

        echo "<h2>Class Teacher Debug</h2>";
        echo "<h3>Session Data:</h3>";
        echo "<pre>" . print_r($sessionData, true) . "</pre>";
        echo "<h3>User Data from Database:</h3>";
        echo "<pre>" . print_r($classTeacher, true) . "</pre>";

        if ($classTeacher && $classTeacher['class_id']) {
            $class = $this->classModel->find($classTeacher['class_id']);
            echo "<h3>Class Data:</h3>";
            echo "<pre>" . print_r($class, true) . "</pre>";
        }

        echo "<p><a href='" . base_url('class-teacher/dashboard') . "'>Try Dashboard</a></p>";
        echo "<p><a href='" . base_url('class-teacher/simple') . "'>Try Simple Dashboard</a></p>";
        echo "<p><a href='" . base_url('auth/logout') . "'>Logout</a></p>";
    }

    /**
     * Simple test dashboard without complex data loading
     */
    public function simple()
    {
        $data = [
            'title' => 'Simple Class Teacher Dashboard - ' . get_app_name(),
            'pageTitle' => 'Class Teacher Dashboard',
            'pageSubtitle' => 'Simple test version'
        ];

        return view('class_teacher/simple_dashboard', $data);
    }

    /**
     * Debug login process
     */
    public function debugLogin()
    {
        echo "<h2>Login Debug Information</h2>";

        // Check if user is logged in
        $sessionData = [
            'user_id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'role' => $this->session->get('role'),
            'class_id' => $this->session->get('class_id'),
            'is_logged_in' => $this->session->get('is_logged_in')
        ];

        echo "<h3>Current Session Data:</h3>";
        echo "<pre>" . print_r($sessionData, true) . "</pre>";

        // Check SS-ONE user specifically
        $ssOneUser = $this->userModel->where('username', 'SS-ONE')->first();
        echo "<h3>SS-ONE User Data:</h3>";
        echo "<pre>" . print_r($ssOneUser, true) . "</pre>";

        // Check all class teachers
        $allClassTeachers = $this->userModel->where('role', 'class_teacher')->findAll();
        echo "<h3>All Class Teachers (" . count($allClassTeachers) . " found):</h3>";
        foreach ($allClassTeachers as $ct) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<strong>Username:</strong> " . $ct['username'] . "<br>";
            echo "<strong>Role:</strong> " . $ct['role'] . "<br>";
            echo "<strong>Class ID:</strong> " . $ct['class_id'] . "<br>";
            echo "<strong>Active:</strong> " . ($ct['is_active'] ? 'Yes' : 'No') . "<br>";
            echo "</div>";
        }

        // Debug: Check all users with any role containing 'teacher'
        $allTeacherRoles = $this->userModel->like('role', 'teacher')->findAll();
        echo "<h3>All Users with 'teacher' in role (" . count($allTeacherRoles) . " found):</h3>";
        foreach ($allTeacherRoles as $user) {
            echo "<div style='border: 1px solid #ddd; margin: 5px; padding: 5px; background: #f9f9f9;'>";
            echo "<strong>Username:</strong> " . $user['username'] . " | ";
            echo "<strong>Role:</strong> '" . $user['role'] . "' | ";
            echo "<strong>Length:</strong> " . strlen($user['role']) . " chars<br>";
            echo "</div>";
        }

        // Debug: Check exact role match
        echo "<h3>Debug Role Matching:</h3>";
        echo "<p>Looking for role exactly equal to 'class_teacher'</p>";
        echo "<p>SS-ONE role: '" . $ssOneUser['role'] . "' (length: " . strlen($ssOneUser['role']) . ")</p>";
        echo "<p>Comparison: " . ($ssOneUser['role'] === 'class_teacher' ? 'MATCH' : 'NO MATCH') . "</p>";

        // Try different queries
        $directQuery = $this->userModel->where('username', 'SS-ONE')->where('role', 'class_teacher')->first();
        echo "<p>Direct query for SS-ONE with class_teacher role: " . ($directQuery ? 'FOUND' : 'NOT FOUND') . "</p>";

        echo "<h3>Fix Actions:</h3>";
        echo "<a href='" . base_url('debug-login/fix-ss-one') . "' style='display: block; margin: 10px; padding: 10px; background: #dc3545; color: white; text-decoration: none;'>🔧 FIX SS-ONE Role (Change to class_teacher)</a>";

        echo "<h3>Test Login Links:</h3>";
        echo "<a href='" . base_url('auth/login') . "' style='display: block; margin: 10px; padding: 10px; background: #007bff; color: white; text-decoration: none;'>Go to Login Page</a>";
        echo "<a href='" . base_url('class-teacher/dashboard') . "' style='display: block; margin: 10px; padding: 10px; background: #28a745; color: white; text-decoration: none;'>Try Dashboard Direct</a>";
        echo "<a href='" . base_url('class-teacher/simple') . "' style='display: block; margin: 10px; padding: 10px; background: #ffc107; color: white; text-decoration: none;'>Try Simple Dashboard</a>";
    }

    /**
     * Fix SS-ONE role to class_teacher
     */
    public function fixSSOne()
    {
        echo "<h2>Fixing SS-ONE Role</h2>";

        // Update SS-ONE role to class_teacher
        $result = $this->userModel->where('username', 'SS-ONE')->set(['role' => 'class_teacher'])->update();

        if ($result) {
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 10px; border-radius: 5px;'>";
            echo "<h3>✅ SUCCESS!</h3>";
            echo "<p>SS-ONE role has been updated to 'class_teacher'</p>";
            echo "</div>";

            // Verify the change
            $updatedUser = $this->userModel->where('username', 'SS-ONE')->first();
            echo "<h3>Verification:</h3>";
            echo "<p><strong>Username:</strong> " . $updatedUser['username'] . "</p>";
            echo "<p><strong>New Role:</strong> '" . $updatedUser['role'] . "'</p>";
            echo "<p><strong>Length:</strong> " . strlen($updatedUser['role']) . " chars</p>";

        } else {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 10px; border-radius: 5px;'>";
            echo "<h3>❌ FAILED!</h3>";
            echo "<p>Could not update SS-ONE role</p>";
            echo "</div>";
        }

        echo "<h3>Next Steps:</h3>";
        echo "<a href='" . base_url('debug-login') . "' style='display: block; margin: 10px; padding: 10px; background: #007bff; color: white; text-decoration: none;'>🔍 Check Debug Again</a>";
        echo "<a href='" . base_url('auth/login') . "' style='display: block; margin: 10px; padding: 10px; background: #28a745; color: white; text-decoration: none;'>🚪 Try Login Now</a>";
    }

    /**
     * Class Teacher Profile Management
     */
    public function profile()
    {
        $session = \Config\Services::session();

        if (!$session->get('is_logged_in') || $session->get('role') !== 'class_teacher') {
            return redirect()->to('/auth/login');
        }

        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'My Profile - ' . get_app_name(),
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->updateProfile($userId);
        }

        return view('class_teacher/profile', $data);
    }

    /**
     * Update Class Teacher Profile
     */
    private function updateProfile($userId)
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
            'address' => 'permit_empty',
            'date_of_birth' => 'permit_empty|valid_date',
            'gender' => 'permit_empty|in_list[male,female,other]',
            'department' => 'permit_empty|max_length[100]',
            'qualification' => 'permit_empty|max_length[255]'
        ];

        // If password is being changed
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            $user = $this->userModel->find($userId);

            return view('class_teacher/profile', [
                'title' => 'My Profile - ' . get_app_name(),
                'user' => $user,
                'validation' => $this->validator
            ]);
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'department' => $this->request->getPost('department'),
            'qualification' => $this->request->getPost('qualification'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($userId, $updateData)) {
            // Update session data
            $session = \Config\Services::session();
            $session->set('full_name', $updateData['first_name'] . ' ' . $updateData['last_name']);

            session()->setFlashdata('success', 'Profile updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update profile. Please try again.');
        }

        return redirect()->back();
    }
}
