<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ExamModel;
use App\Models\QuestionModel;
use App\Models\SubjectModel;
use App\Models\ExamAttemptModel;
use App\Models\TeacherSubjectAssignmentModel;
use App\Models\AcademicSessionModel;
use CodeIgniter\Controller;

class Teacher extends Controller
{
    protected $userModel;
    protected $examModel;
    protected $questionModel;
    protected $subjectModel;
    protected $attemptModel;
    protected $assignmentModel;
    protected $sessionModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->examModel = new ExamModel();
        $this->questionModel = new QuestionModel();
        $this->subjectModel = new SubjectModel();
        $this->attemptModel = new ExamAttemptModel();
        $this->assignmentModel = new TeacherSubjectAssignmentModel();
        $this->sessionModel = new AcademicSessionModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);

        // Check if user is logged in and is a teacher
        if (!$this->session->get('is_logged_in') || $this->session->get('role') !== 'teacher') {
            redirect()->to('/auth/login')->send();
            exit;
        }
    }

    /**
     * Teacher dashboard
     */
    public function dashboard()
    {
        $teacherId = $this->session->get('user_id');
        $teacher = $this->userModel->find($teacherId);

        // Get current academic session
        $currentSession = $this->sessionModel->getCurrentSession();
        $sessionId = $currentSession['id'] ?? null;

        // Get teacher's subjects using the proper assignment system
        $assignments = $this->assignmentModel->getTeacherAssignments($teacherId, $sessionId);
        $subjects = $this->getSubjectsFromAssignments($assignments);

        // Fallback: if no assignments found, try the old system for backward compatibility
        if (empty($subjects)) {
            $subjects = $this->subjectModel->getSubjectsByTeacher($teacherId);
        }

        // Get teacher's exams with status breakdown
        $allExams = $this->examModel->getExamsByTeacher($teacherId);
        $examStats = $this->getExamStatistics($allExams);

        // Get recent exam attempts for teacher's exams
        $recentAttempts = $this->attemptModel->getRecentAttemptsByTeacher($teacherId, 5);

        // Get teacher's questions count and breakdown
        $questionsCount = $this->questionModel->getQuestionCountByTeacher($teacherId);
        $questionStats = $this->getQuestionStatistics($teacherId);

        // Get student performance summary
        $performanceStats = $this->getStudentPerformanceStats($teacherId);

        // Get upcoming exams
        $upcomingExams = $this->getUpcomingExams($teacherId, 3);

        // Get total students taught
        $totalStudents = $this->getTotalStudentsTaught($teacherId);

        $data = [
            'title' => 'Teacher Dashboard - ' . get_app_name(),
            'pageTitle' => 'Welcome, ' . $teacher['first_name'],
            'pageSubtitle' => 'Manage your exams and assessments',
            'teacher' => $teacher,
            'subjects' => $subjects,
            'assignments' => $assignments,
            'exams' => $allExams,
            'recentAttempts' => $recentAttempts,
            'upcomingExams' => $upcomingExams,
            'questionsCount' => $questionsCount,
            'totalStudents' => $totalStudents,
            'currentSession' => $currentSession,
            'stats' => [
                'total_subjects' => count($subjects),
                'total_exams' => count($allExams),
                'active_exams' => $examStats['active'],
                'upcoming_exams' => $examStats['upcoming'],
                'completed_exams' => $examStats['completed'],
                'total_questions' => $questionsCount,
                'recent_attempts' => count($recentAttempts),
                'total_students' => $totalStudents,
                'average_score' => $performanceStats['average_score'],
                'total_attempts' => $performanceStats['total_attempts']
            ],
            'examStats' => $examStats,
            'questionStats' => $questionStats,
            'performanceStats' => $performanceStats
        ];

        return view('teacher/dashboard', $data);
    }



    /**
     * Teacher questions management
     */
    public function questions()
    {
        $teacherId = $this->session->get('user_id');
        $questions = $this->questionModel->getQuestionsByTeacher($teacherId);

        $data = [
            'title' => 'My Questions - ' . get_app_name(),
            'pageTitle' => 'My Questions',
            'pageSubtitle' => 'Manage your question bank',
            'questions' => $questions
        ];

        return view('teacher/questions', $data);
    }

    /**
     * Teacher results and analytics
     */
    public function results()
    {
        $teacherId = $this->session->get('user_id');
        $results = $this->attemptModel->getResultsByTeacher($teacherId);

        $data = [
            'title' => 'Exam Results - ' . get_app_name(),
            'pageTitle' => 'Exam Results',
            'pageSubtitle' => 'View student performance and analytics',
            'results' => $results
        ];

        return view('teacher/results', $data);
    }

    /**
     * Teacher reports and analytics
     */
    public function reports()
    {
        $teacherId = $this->session->get('user_id');

        // Get teacher's subjects and classes for filtering
        $subjects = $this->assignmentModel->getTeacherSubjects($teacherId);
        $classes = $this->assignmentModel->getTeacherClasses($teacherId);

        // Get exam statistics for teacher's exams
        $examStats = $this->getTeacherExamStatistics($teacherId);

        // Get student performance data
        $performanceData = $this->getTeacherPerformanceData($teacherId);

        $data = [
            'title' => 'Reports & Analytics - ' . get_app_name(),
            'pageTitle' => 'Reports & Analytics',
            'pageSubtitle' => 'Comprehensive performance reports and insights',
            'subjects' => $subjects,
            'classes' => $classes,
            'examStats' => $examStats,
            'performanceData' => $performanceData
        ];

        return view('teacher/reports', $data);
    }

    /**
     * Get exam statistics breakdown
     */
    private function getExamStatistics($exams)
    {
        $now = date('Y-m-d H:i:s');
        $stats = [
            'total' => count($exams),
            'active' => 0,
            'upcoming' => 0,
            'completed' => 0,
            'draft' => 0
        ];

        foreach ($exams as $exam) {
            if (!$exam['is_active']) {
                $stats['draft']++;
            } elseif ($exam['end_time'] < $now) {
                $stats['completed']++;
            } elseif ($exam['start_time'] <= $now && $exam['end_time'] >= $now) {
                $stats['active']++;
            } elseif ($exam['start_time'] > $now) {
                $stats['upcoming']++;
            }
        }

        return $stats;
    }

    /**
     * Get question statistics breakdown
     */
    private function getQuestionStatistics($teacherId)
    {
        $questions = $this->questionModel->getQuestionsByTeacher($teacherId);

        $stats = [
            'total' => count($questions),
            'by_type' => [],
            'by_difficulty' => [],
            'by_subject' => []
        ];

        foreach ($questions as $question) {
            // Count by type
            $type = $question['question_type'];
            $stats['by_type'][$type] = ($stats['by_type'][$type] ?? 0) + 1;

            // Count by difficulty
            $difficulty = $question['difficulty'];
            $stats['by_difficulty'][$difficulty] = ($stats['by_difficulty'][$difficulty] ?? 0) + 1;

            // Count by subject
            $subject = $question['subject_name'] ?? 'Unknown';
            $stats['by_subject'][$subject] = ($stats['by_subject'][$subject] ?? 0) + 1;
        }

        return $stats;
    }

    /**
     * Get student performance statistics
     */
    private function getStudentPerformanceStats($teacherId)
    {
        $attempts = $this->attemptModel->getResultsByTeacher($teacherId);

        $stats = [
            'total_attempts' => count($attempts),
            'average_score' => 0,
            'highest_score' => 0,
            'lowest_score' => 100,
            'pass_rate' => 0,
            'grade_distribution' => [
                'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
            ]
        ];

        if (count($attempts) > 0) {
            $totalScore = 0;
            $passCount = 0;

            foreach ($attempts as $attempt) {
                $percentage = $attempt['percentage'];
                $totalScore += $percentage;

                // Track highest and lowest scores
                if ($percentage > $stats['highest_score']) {
                    $stats['highest_score'] = $percentage;
                }
                if ($percentage < $stats['lowest_score']) {
                    $stats['lowest_score'] = $percentage;
                }

                // Count passes (assuming 40% is pass mark)
                if ($percentage >= 40) {
                    $passCount++;
                }

                // Grade distribution
                if ($percentage >= 80) $stats['grade_distribution']['A']++;
                elseif ($percentage >= 70) $stats['grade_distribution']['B']++;
                elseif ($percentage >= 60) $stats['grade_distribution']['C']++;
                elseif ($percentage >= 40) $stats['grade_distribution']['D']++;
                else $stats['grade_distribution']['F']++;
            }

            $stats['average_score'] = round($totalScore / count($attempts), 1);
            $stats['pass_rate'] = round(($passCount / count($attempts)) * 100, 1);
        }

        return $stats;
    }

    /**
     * Get upcoming exams for teacher
     */
    private function getUpcomingExams($teacherId, $limit = 5)
    {
        $now = date('Y-m-d H:i:s');

        return $this->examModel->select('exams.*, subjects.name as subject_name, classes.name as class_name')
                               ->join('subjects', 'subjects.id = exams.subject_id')
                               ->join('classes', 'classes.id = exams.class_id')
                               ->where('exams.created_by', $teacherId)
                               ->where('exams.is_active', 1)
                               ->where('exams.start_time >', $now)
                               ->orderBy('exams.start_time', 'ASC')
                               ->limit($limit)
                               ->findAll();
    }

    /**
     * Get total number of students taught by teacher
     */
    private function getTotalStudentsTaught($teacherId)
    {
        // Get all classes where teacher has subjects assigned
        $subjectIds = array_column($this->subjectModel->getSubjectsByTeacher($teacherId), 'id');

        if (empty($subjectIds)) {
            return 0;
        }

        // Get unique students who have taken exams in teacher's subjects
        $uniqueStudents = $this->attemptModel->select('DISTINCT student_id')
                                            ->join('exams', 'exams.id = exam_attempts.exam_id')
                                            ->whereIn('exams.subject_id', $subjectIds)
                                            ->where('exams.created_by', $teacherId)
                                            ->countAllResults();

        return $uniqueStudents;
    }

    /**
     * Convert teacher assignments to subjects format for dashboard display
     */
    private function getSubjectsFromAssignments($assignments)
    {
        $subjects = [];
        $subjectIds = [];

        foreach ($assignments as $assignment) {
            $subjectId = $assignment['subject_id'];

            // Avoid duplicates
            if (!in_array($subjectId, $subjectIds)) {
                $subjectIds[] = $subjectId;

                // Get question and exam counts for this subject
                $questionCount = $this->questionModel->where('subject_id', $subjectId)
                                                   ->where('is_active', 1)
                                                   ->countAllResults();

                $examCount = $this->examModel->where('subject_id', $subjectId)
                                            ->where('is_active', 1)
                                            ->countAllResults();

                $subjects[] = [
                    'id' => $subjectId,
                    'name' => $assignment['subject_name'],
                    'code' => $assignment['subject_code'],
                    'question_count' => $questionCount,
                    'exam_count' => $examCount,
                    'classes' => [] // Will be populated below
                ];
            }
        }

        // Add class information to subjects
        foreach ($subjects as &$subject) {
            $classes = [];
            foreach ($assignments as $assignment) {
                if ($assignment['subject_id'] == $subject['id']) {
                    $classes[] = $assignment['class_name'];
                }
            }
            $subject['classes'] = array_unique($classes);
        }

        return $subjects;
    }

    /**
     * Get exam statistics for teacher's reports
     */
    private function getTeacherExamStatistics($teacherId)
    {
        $exams = $this->examModel->getExamsByTeacher($teacherId);
        $attempts = $this->attemptModel->getResultsByTeacher($teacherId);

        $stats = [
            'total_exams' => count($exams),
            'total_attempts' => count($attempts),
            'average_score' => 0,
            'completion_rate' => 0,
            'monthly_trends' => [],
            'subject_performance' => []
        ];

        if (count($attempts) > 0) {
            $totalScore = array_sum(array_column($attempts, 'percentage'));
            $stats['average_score'] = round($totalScore / count($attempts), 1);

            // Calculate completion rate (attempts vs total possible attempts)
            $totalPossibleAttempts = count($exams) * $this->getTotalStudentsTaught($teacherId);
            if ($totalPossibleAttempts > 0) {
                $stats['completion_rate'] = round((count($attempts) / $totalPossibleAttempts) * 100, 1);
            }

            // Monthly trends for the last 6 months
            $stats['monthly_trends'] = $this->getMonthlyTrends($attempts);

            // Subject performance breakdown
            $stats['subject_performance'] = $this->getSubjectPerformance($attempts);
        }

        return $stats;
    }

    /**
     * Get performance data for teacher's reports
     */
    private function getTeacherPerformanceData($teacherId)
    {
        $attempts = $this->attemptModel->getResultsByTeacher($teacherId);

        $data = [
            'grade_distribution' => [
                'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
            ],
            'difficulty_analysis' => [],
            'top_performers' => [],
            'struggling_students' => []
        ];

        foreach ($attempts as $attempt) {
            $percentage = $attempt['percentage'];

            // Grade distribution
            if ($percentage >= 80) $data['grade_distribution']['A']++;
            elseif ($percentage >= 70) $data['grade_distribution']['B']++;
            elseif ($percentage >= 60) $data['grade_distribution']['C']++;
            elseif ($percentage >= 40) $data['grade_distribution']['D']++;
            else $data['grade_distribution']['F']++;
        }

        // Get top performers (top 5 students by average score)
        $data['top_performers'] = $this->getTopPerformers($teacherId, 5);

        // Get struggling students (bottom 5 students by average score)
        $data['struggling_students'] = $this->getStrugglingStudents($teacherId, 5);

        return $data;
    }

    /**
     * Get monthly trends for the last 6 months
     */
    private function getMonthlyTrends($attempts)
    {
        $trends = [];
        $monthlyData = [];

        // Group attempts by month
        foreach ($attempts as $attempt) {
            $month = date('Y-m', strtotime($attempt['submitted_at']));
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [];
            }
            $monthlyData[$month][] = $attempt['percentage'];
        }

        // Calculate averages for each month
        foreach ($monthlyData as $month => $scores) {
            $trends[] = [
                'month' => date('M Y', strtotime($month . '-01')),
                'average_score' => round(array_sum($scores) / count($scores), 1),
                'total_attempts' => count($scores)
            ];
        }

        // Sort by month and limit to last 6 months
        usort($trends, function($a, $b) {
            return strtotime($a['month']) - strtotime($b['month']);
        });

        return array_slice($trends, -6);
    }

    /**
     * Get subject performance breakdown
     */
    private function getSubjectPerformance($attempts)
    {
        $subjectData = [];

        foreach ($attempts as $attempt) {
            $subject = $attempt['subject_name'] ?? 'Unknown';
            if (!isset($subjectData[$subject])) {
                $subjectData[$subject] = [];
            }
            $subjectData[$subject][] = $attempt['percentage'];
        }

        $performance = [];
        foreach ($subjectData as $subject => $scores) {
            $performance[] = [
                'subject' => $subject,
                'average_score' => round(array_sum($scores) / count($scores), 1),
                'total_attempts' => count($scores),
                'highest_score' => max($scores),
                'lowest_score' => min($scores)
            ];
        }

        // Sort by average score descending
        usort($performance, function($a, $b) {
            return $b['average_score'] - $a['average_score'];
        });

        return $performance;
    }

    /**
     * Get top performing students
     */
    private function getTopPerformers($teacherId, $limit = 5)
    {
        return $this->attemptModel->select('users.first_name, users.last_name, users.student_id, AVG(exam_attempts.percentage) as average_score, COUNT(exam_attempts.id) as total_attempts')
                                 ->join('users', 'users.id = exam_attempts.student_id')
                                 ->join('exams', 'exams.id = exam_attempts.exam_id')
                                 ->where('exams.created_by', $teacherId)
                                 ->where('exam_attempts.status', 'completed')
                                 ->groupBy('exam_attempts.student_id')
                                 ->orderBy('average_score', 'DESC')
                                 ->limit($limit)
                                 ->findAll();
    }

    /**
     * Get struggling students
     */
    private function getStrugglingStudents($teacherId, $limit = 5)
    {
        return $this->attemptModel->select('users.first_name, users.last_name, users.student_id, AVG(exam_attempts.percentage) as average_score, COUNT(exam_attempts.id) as total_attempts')
                                 ->join('users', 'users.id = exam_attempts.student_id')
                                 ->join('exams', 'exams.id = exam_attempts.exam_id')
                                 ->where('exams.created_by', $teacherId)
                                 ->where('exam_attempts.status', 'completed')
                                 ->groupBy('exam_attempts.student_id')
                                 ->orderBy('average_score', 'ASC')
                                 ->limit($limit)
                                 ->findAll();
    }

    /**
     * Teacher Profile Management
     */
    public function profile()
    {
        $session = \Config\Services::session();

        if (!$session->get('is_logged_in') || $session->get('role') !== 'teacher') {
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

        return view('teacher/profile', $data);
    }

    /**
     * Update Teacher Profile
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

            return view('teacher/profile', [
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
