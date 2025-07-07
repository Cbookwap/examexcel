<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\SubjectCategoryModel;
use App\Models\ExamModel;
use App\Models\ExamAttemptModel;
use App\Models\SecurityLogModel;
use App\Models\AcademicSessionModel;
use App\Models\AcademicTermModel;
use App\Models\TeacherSubjectAssignmentModel;
use App\Models\SubjectClassAssignmentModel;
use App\Models\SettingsModel;
use App\Libraries\SessionManager;
use CodeIgniter\Controller;

class Admin extends Controller
{
    protected $userModel;
    protected $classModel;
    protected $subjectModel;
    protected $subjectCategoryModel;
    protected $examModel;
    protected $attemptModel;
    protected $securityLogModel;
    protected $sessionModel;
    protected $securitySettingsModel;
    protected $settingsModel;
    protected $termModel;
    protected $assignmentModel;
    protected $subjectClassAssignmentModel;
    protected $sessionManager;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->classModel = new ClassModel();
        $this->subjectModel = new SubjectModel();
        $this->subjectCategoryModel = new SubjectCategoryModel();
        $this->examModel = new ExamModel();
        $this->attemptModel = new ExamAttemptModel();
        $this->securityLogModel = new SecurityLogModel();
        $this->sessionModel = new AcademicSessionModel();
        $this->termModel = new AcademicTermModel();
        $this->assignmentModel = new TeacherSubjectAssignmentModel();
        $this->subjectClassAssignmentModel = new SubjectClassAssignmentModel();
        $this->securitySettingsModel = new \App\Models\SecuritySettingsModel();
        $this->settingsModel = new SettingsModel();
        $this->sessionManager = new SessionManager();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        helper(['form', 'url', 'student']);

        // Check if user is logged in and is admin
        if (!$this->session->get('is_logged_in') || $this->session->get('role') !== 'admin') {
            redirect()->to('/auth/login')->send();
            exit;
        }
    }

    public function dashboard()
    {
        // Load additional models
        $examModel = new \App\Models\ExamModel();
        $attemptModel = new \App\Models\ExamAttemptModel();
        $questionModel = new \App\Models\QuestionModel();
        $classModel = new \App\Models\ClassModel();
        $subjectModel = new \App\Models\SubjectModel();
        $sessionModel = new \App\Models\AcademicSessionModel();
        $termModel = new \App\Models\AcademicTermModel();

        // Get comprehensive statistics
        $userStats = $this->userModel->getUserStats();
        $examStats = $examModel->getExamStats();

        // Get recent data
        $recentUsers = $this->userModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
        $recentExams = $examModel->select('exams.*, subjects.name as subject_name, classes.name as class_name, users.first_name, users.last_name')
                                ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                                ->join('classes', 'classes.id = exams.class_id', 'left')
                                ->join('users', 'users.id = exams.created_by', 'left')
                                ->orderBy('exams.created_at', 'DESC')
                                ->limit(5)
                                ->findAll();

        // Get current academic period
        $currentSession = $sessionModel->getCurrentSession();
        $currentTerm = $termModel->getCurrentTerm();

        // Get exam attempts statistics
        $totalAttempts = $attemptModel->countAllResults();
        $todayAttempts = $attemptModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
        $thisWeekAttempts = $attemptModel->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults();

        // Get question bank statistics
        $totalQuestions = $questionModel->countAllResults();
        $activeQuestions = $questionModel->where('is_active', 1)->countAllResults();

        // Get class and subject counts
        $totalClasses = $classModel->where('is_active', 1)->countAllResults();
        $totalSubjects = $subjectModel->where('is_active', 1)->countAllResults();

        // Calculate growth percentages (mock data for now - can be enhanced with historical data)
        $userGrowth = $this->calculateGrowthPercentage('users');
        $examGrowth = $this->calculateGrowthPercentage('exams');
        $attemptGrowth = $this->calculateGrowthPercentage('exam_attempts');

        // Get class teacher statistics
        $classStats = $this->classModel->getClassStats();
        $classTeacherStats = $this->getClassTeacherStats();

        // Get comprehensive dashboard statistics
        $studentStatsByClass = $this->getStudentStatsByClass();
        $questionStatsBySubject = $this->getQuestionStatsBySubject();
        $subjectQuestionCounts = $this->getSubjectQuestionCounts();

        $data = [
            'title' => 'Admin Dashboard - ' . get_app_name(),
            'user_stats' => $userStats,
            'exam_stats' => $examStats,
            'recent_users' => $recentUsers,
            'recent_exams' => $recentExams,
            'current_session' => $currentSession,
            'current_term' => $currentTerm,
            'class_stats' => $classStats,
            'class_teacher_stats' => $classTeacherStats,
            'student_stats_by_class' => $studentStatsByClass,
            'question_stats_by_subject' => $questionStatsBySubject,
            'subject_question_counts' => $subjectQuestionCounts,
            'dashboard_stats' => [
                'total_users' => $userStats['total'],
                'total_students' => $userStats['students'],
                'total_teachers' => $userStats['teachers'],
                'total_admins' => $userStats['admins'],
                'total_class_teachers' => $userStats['class_teachers'],
                'total_classes' => $classStats['total'],
                'active_classes' => $classStats['active'],
                'total_exams' => $examStats['total'],
                'active_exams' => $examStats['active'],
                'upcoming_exams' => $examStats['upcoming'],
                'total_attempts' => $totalAttempts,
                'today_attempts' => $todayAttempts,
                'week_attempts' => $thisWeekAttempts,
                'total_questions' => $totalQuestions,
                'active_questions' => $activeQuestions,
                'total_classes' => $totalClasses,
                'total_subjects' => $totalSubjects,
                'user_growth' => $userGrowth,
                'exam_growth' => $examGrowth,
                'attempt_growth' => $attemptGrowth
            ]
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Calculate growth percentage for dashboard metrics
     */
    private function calculateGrowthPercentage($table)
    {
        $db = \Config\Database::connect();

        // Get current month count
        $currentMonth = $db->table($table)
                          ->where('MONTH(created_at)', date('n'))
                          ->where('YEAR(created_at)', date('Y'))
                          ->countAllResults();

        // Get last month count
        $lastMonth = $db->table($table)
                       ->where('MONTH(created_at)', date('n', strtotime('-1 month')))
                       ->where('YEAR(created_at)', date('Y', strtotime('-1 month')))
                       ->countAllResults();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    public function users()
    {
        $data = [
            'title' => 'User Management - ' . get_app_name(),
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/users', $data);
    }

    public function studentList()
    {
        // Get search and filter parameters
        $search = $this->request->getGet('search') ?? '';
        $classFilter = $this->request->getGet('class') ?? '';
        $genderFilter = $this->request->getGet('gender') ?? '';
        $page = $this->request->getGet('page') ?? 1;

        // Build query for students with class information
        $builder = $this->userModel->select('users.*, classes.name as class_name')
                                  ->join('classes', 'classes.id = users.class_id', 'left')
                                  ->where('users.role', 'student');

        // Apply search filter (Student ID or name)
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('users.student_id', $search)
                   ->orLike('users.first_name', $search)
                   ->orLike('users.last_name', $search)
                   ->orLike("CONCAT(users.first_name, ' ', users.last_name)", $search)
                   ->groupEnd();
        }

        // Apply class filter
        if (!empty($classFilter)) {
            $builder->where('users.class_id', $classFilter);
        }

        // Apply gender filter
        if (!empty($genderFilter)) {
            $builder->where('users.gender', $genderFilter);
        }

        // Order by student ID
        $builder->orderBy('users.student_id', 'ASC');

        // Get paginated results (30 per page)
        $students = $builder->paginate(30, 'default', $page);
        $pager = $this->userModel->pager;

        // Get all classes for filter dropdown
        $classes = $this->classModel->select('id, name, section')
                                   ->where('is_active', 1)
                                   ->orderBy('name', 'ASC')
                                   ->findAll();

        // Add display name with category for each class
        foreach ($classes as &$class) {
            $class['display_name'] = $this->getClassDisplayName($class);
        }

        // Get student statistics
        $totalStudents = $this->userModel->where('role', 'student')->countAllResults();
        $activeStudents = $this->userModel->where('role', 'student')->where('is_active', 1)->countAllResults();
        $maleStudents = $this->userModel->where('role', 'student')->where('gender', 'male')->countAllResults();
        $femaleStudents = $this->userModel->where('role', 'student')->where('gender', 'female')->countAllResults();

        $data = [
            'title' => 'Student List - ' . get_app_name(),
            'students' => $students,
            'pager' => $pager,
            'classes' => $classes,
            'filters' => [
                'search' => $search,
                'class' => $classFilter,
                'gender' => $genderFilter
            ],
            'stats' => [
                'total' => $totalStudents,
                'active' => $activeStudents,
                'male' => $maleStudents,
                'female' => $femaleStudents
            ]
        ];

        return view('admin/student_list', $data);
    }

    public function teacherList()
    {
        // Get search and filter parameters
        $search = $this->request->getGet('search') ?? '';
        $departmentFilter = $this->request->getGet('department') ?? '';
        $genderFilter = $this->request->getGet('gender') ?? '';
        $page = $this->request->getGet('page') ?? 1;

        // Build query for teachers
        $builder = $this->userModel->where('users.role', 'teacher');

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('users.first_name', $search)
                   ->orLike('users.last_name', $search)
                   ->orLike('users.email', $search)
                   ->orLike('users.employee_id', $search)
                   ->groupEnd();
        }

        // Apply department filter
        if (!empty($departmentFilter)) {
            $builder->where('users.department', $departmentFilter);
        }

        // Apply gender filter
        if (!empty($genderFilter)) {
            $builder->where('users.gender', $genderFilter);
        }

        // Order by employee ID, then by name
        $builder->orderBy('users.employee_id', 'ASC')
               ->orderBy('users.first_name', 'ASC');

        // Get paginated results (30 per page)
        $teachers = $builder->paginate(30, 'default', $page);
        $pager = $this->userModel->pager;

        // Get unique departments for filter dropdown
        $departmentBuilder = $this->userModel->select('department')
                                           ->where('role', 'teacher')
                                           ->where('department IS NOT NULL')
                                           ->where('department !=', '')
                                           ->distinct()
                                           ->orderBy('department', 'ASC');
        $departmentResults = $departmentBuilder->findAll();
        $departments = array_column($departmentResults, 'department');

        // Get teacher statistics
        $totalTeachers = $this->userModel->where('role', 'teacher')->countAllResults();
        $activeTeachers = $this->userModel->where('role', 'teacher')->where('is_active', 1)->countAllResults();
        $maleTeachers = $this->userModel->where('role', 'teacher')->where('gender', 'male')->countAllResults();
        $femaleTeachers = $this->userModel->where('role', 'teacher')->where('gender', 'female')->countAllResults();

        $data = [
            'title' => 'Teacher List - ' . get_app_name(),
            'teachers' => $teachers,
            'pager' => $pager,
            'departments' => $departments,
            'filters' => [
                'search' => $search,
                'department' => $departmentFilter,
                'gender' => $genderFilter
            ],
            'stats' => [
                'total' => $totalTeachers,
                'active' => $activeTeachers,
                'male' => $maleTeachers,
                'female' => $femaleTeachers
            ]
        ];

        return view('admin/teacher_list', $data);
    }

    public function createUser()
    {
        $data = [
            'title' => 'Create User - ExamExcel',
            'classes' => $this->classModel->getActiveClasses(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            log_message('info', 'Admin createUser: POST request received');
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

            // Base validation rules
            $rules = [
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'first_name' => 'required|min_length[2]|max_length[100]',
                'last_name' => 'required|min_length[2]|max_length[100]',
                'role' => 'required|in_list[admin,teacher,student,principal]'
            ];

            // Add conditional validation based on role
            if ($this->request->getPost('role') === 'student') {
                // For students: student_id is required, username is not needed
                $rules['class_id'] = 'required|integer';
                $rules['student_id'] = 'required|max_length[50]|is_unique[users.student_id]';
            } else {
                // For admin and teacher: username is required, student_id is not needed
                $rules['username'] = 'required|min_length[3]|max_length[100]|is_unique[users.username]';
            }

            // Add conditional validation for teacher role
            if ($this->request->getPost('role') === 'teacher') {
                $rules['employee_id'] = 'permit_empty|max_length[50]';
                $rules['department'] = 'permit_empty|max_length[100]';
                $rules['qualification'] = 'permit_empty|max_length[255]';
            }

            // Add conditional validation for principal role
            if ($this->request->getPost('role') === 'principal') {
                // For principal role, either title or custom_title is required
                $title = $this->request->getPost('title');
                $customTitle = $this->request->getPost('custom_title');
                if (empty($title) && empty($customTitle)) {
                    $rules['title'] = 'required';
                }
            }

            log_message('info', 'Admin createUser: Validating with rules: ' . json_encode($rules));

            if (!$this->validate($rules)) {
                log_message('error', 'Admin createUser: Validation failed: ' . json_encode($this->validator->getErrors()));
                return view('admin/create_user', [
                    'title' => 'Create User - SRMS CBT System',
                    'classes' => $this->classModel->getActiveClasses(),
                    'validation' => $this->validator
                ]);
            }

            log_message('info', 'Admin createUser: Validation passed');

            // Base user data
            $userData = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'role' => $this->request->getPost('role'),
                'phone' => $this->request->getPost('phone'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'gender' => $this->request->getPost('gender'),
                'address' => $this->request->getPost('address'),
                'employee_id' => $this->request->getPost('employee_id'),
                'class_id' => $this->request->getPost('class_id'),
                'department' => $this->request->getPost('department'),
                'qualification' => $this->request->getPost('qualification'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'is_verified' => $this->request->getPost('is_verified') ? 1 : 0
            ];

            // Handle title for principal role
            if ($this->request->getPost('role') === 'principal') {
                $title = $this->request->getPost('title');
                $customTitle = $this->request->getPost('custom_title');

                // Use custom title if provided, otherwise use selected title
                $userData['title'] = !empty($customTitle) ? trim($customTitle) : $title;
            }

            // Handle student ID generation and username for students
            if ($this->request->getPost('role') === 'student') {
                $studentId = $this->request->getPost('student_id');
                if (empty($studentId)) {
                    // Auto-generate student ID if not provided
                    try {
                        $studentId = generate_unique_student_id();
                    } catch (\Exception $e) {
                        log_message('error', 'Failed to generate student ID: ' . $e->getMessage());
                        // Fallback to simple generation
                        $randomDigits = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                        $studentId = 'STD-' . $randomDigits;
                    }
                } else {
                    // Use provided student ID
                    $studentId = strtoupper(trim($studentId));
                }

                // For students: username = student_id
                $userData['student_id'] = $studentId;
                $userData['username'] = $studentId;
            } else {
                // For non-students: use provided username and clear student_id
                $userData['username'] = $this->request->getPost('username');
                $userData['student_id'] = null;
            }

            log_message('info', 'Admin createUser: Attempting to insert user data: ' . json_encode($userData));

            try {
                // Temporarily disable model validation since we've already validated in the controller
                $this->userModel->skipValidation(true);

                if ($this->userModel->insert($userData)) {
                    log_message('info', 'Admin createUser: User created successfully');
                    session()->setFlashdata('success', 'User created successfully!');
                    return redirect()->to('/admin/users');
                } else {
                    $errors = $this->userModel->errors();
                    log_message('error', 'Admin createUser: UserModel insert failed: ' . json_encode($errors));
                    session()->setFlashdata('error', 'Failed to create user. Validation errors: ' . implode(', ', $errors));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                log_message('error', 'Admin createUser: Exception during insert: ' . $e->getMessage());
                log_message('error', 'Admin createUser: Exception trace: ' . $e->getTraceAsString());
                session()->setFlashdata('error', 'Failed to create user. Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            } finally {
                // Re-enable model validation for future operations
                $this->userModel->skipValidation(false);
            }
        }

        return view('admin/create_user', $data);
    }

    public function principals()
    {
        $principals = $this->userModel->findPrincipalsWithTitles();

        $data = [
            'title' => 'Principal Management - ' . get_app_name(),
            'principals' => $principals
        ];

        return view('admin/principals', $data);
    }

    public function editUser($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        $data = [
            'title' => 'Edit User - SRMS CBT System',
            'user' => $user,
            'classes' => $this->classModel->getActiveClasses(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            // Base validation rules
            $rules = [
                'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
                'first_name' => 'required|min_length[2]|max_length[100]',
                'last_name' => 'required|min_length[2]|max_length[100]',
                'role' => 'required|in_list[admin,teacher,student,principal]'
            ];

            // If password is provided, validate it
            if ($this->request->getPost('password')) {
                $rules['password'] = 'min_length[6]';
            }

            // Add conditional validation based on role
            if ($this->request->getPost('role') === 'student') {
                // For students: student_id is required, username is not needed
                $rules['class_id'] = 'required|integer';
                $rules['student_id'] = "required|max_length[50]|is_unique[users.student_id,id,{$id}]";
            } else {
                // For admin and teacher: username is required, student_id is not needed
                $rules['username'] = "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]";
            }

            // Add conditional validation for teacher role
            if ($this->request->getPost('role') === 'teacher') {
                $rules['employee_id'] = 'permit_empty|max_length[50]';
                $rules['department'] = 'permit_empty|max_length[100]';
                $rules['qualification'] = 'permit_empty|max_length[255]';
            }

            // Add conditional validation for principal role
            if ($this->request->getPost('role') === 'principal') {
                // For principal role, either title or custom_title is required
                $title = $this->request->getPost('title');
                $customTitle = $this->request->getPost('custom_title');
                if (empty($title) && empty($customTitle)) {
                    $rules['title'] = 'required';
                }
            }

            if (!$this->validate($rules)) {
                return view('admin/edit_user', [
                    'title' => 'Edit User - ExamExcel',
                    'user' => $user,
                    'classes' => $this->classModel->getActiveClasses(),
                    'validation' => $this->validator
                ]);
            }

            // Base update data
            $updateData = [
                'email' => $this->request->getPost('email'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'role' => $this->request->getPost('role'),
                'phone' => $this->request->getPost('phone'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'gender' => $this->request->getPost('gender'),
                'address' => $this->request->getPost('address'),
                'employee_id' => $this->request->getPost('employee_id'),
                'class_id' => $this->request->getPost('class_id'),
                'department' => $this->request->getPost('department'),
                'qualification' => $this->request->getPost('qualification'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'is_verified' => $this->request->getPost('is_verified') ? 1 : 0
            ];

            // Handle title for principal role
            if ($this->request->getPost('role') === 'principal') {
                $title = $this->request->getPost('title');
                $customTitle = $this->request->getPost('custom_title');

                // Use custom title if provided, otherwise use selected title
                $updateData['title'] = !empty($customTitle) ? trim($customTitle) : $title;
            } else {
                // Clear title for non-principal roles
                $updateData['title'] = null;
            }

            // Handle student ID and username for students
            if ($this->request->getPost('role') === 'student') {
                $studentId = $this->request->getPost('student_id');
                if (empty($user['student_id']) && empty($studentId)) {
                    // Generate new student ID if user doesn't have one and none provided
                    $studentId = generate_unique_student_id();
                } elseif (!empty($studentId) && empty($user['student_id'])) {
                    // Use provided student ID if user doesn't have one yet
                    $studentId = strtoupper(trim($studentId));
                } else {
                    // Keep existing student ID (don't allow changes)
                    $studentId = $user['student_id'];
                }

                // For students: username = student_id
                $updateData['student_id'] = $studentId;
                $updateData['username'] = $studentId;
            } else {
                // For non-students: use provided username and clear student_id
                $updateData['username'] = $this->request->getPost('username');
                $updateData['student_id'] = null;
            }

            // Add password if provided
            if ($this->request->getPost('password')) {
                $updateData['password'] = $this->request->getPost('password');
            }

            log_message('info', 'Admin editUser: Attempting to update user data: ' . json_encode($updateData));

            try {
                // Temporarily disable model validation since we've already validated in the controller
                $this->userModel->skipValidation(true);

                if ($this->userModel->update($id, $updateData)) {
                    log_message('info', 'Admin editUser: User updated successfully');
                    session()->setFlashdata('success', 'User updated successfully!');
                    return redirect()->to('/admin/users');
                } else {
                    $errors = $this->userModel->errors();
                    log_message('error', 'Admin editUser: UserModel update failed: ' . json_encode($errors));
                    session()->setFlashdata('error', 'Failed to update user. Validation errors: ' . implode(', ', $errors));
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                log_message('error', 'Admin editUser: Exception during update: ' . $e->getMessage());
                log_message('error', 'Admin editUser: Exception trace: ' . $e->getTraceAsString());
                session()->setFlashdata('error', 'Failed to update user. Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            } finally {
                // Re-enable model validation for future operations
                $this->userModel->skipValidation(false);
            }
        }

        return view('admin/edit_user', $data);
    }

    public function deleteUser($id)
    {
        // Prevent admin from deleting themselves
        if ($id == $this->session->get('user_id')) {
            session()->setFlashdata('error', 'You cannot delete your own account.');
            return redirect()->to('/admin/users');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        if ($this->userModel->delete($id)) {
            session()->setFlashdata('success', 'User deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete user.');
        }

        return redirect()->to('/admin/users');
    }

    public function toggleUserStatus($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        // Prevent admin from deactivating themselves
        if ($id == $this->session->get('user_id')) {
            session()->setFlashdata('error', 'You cannot deactivate your own account.');
            return redirect()->to('/admin/users');
        }

        $newStatus = $user['is_active'] ? 0 : 1;

        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'activated' : 'deactivated';
            session()->setFlashdata('success', "User {$statusText} successfully!");
        } else {
            session()->setFlashdata('error', 'Failed to update user status.');
        }

        return redirect()->to('/admin/users');
    }

    public function generateStudentId()
    {
        // Check if user is logged in and is admin (for security)
        if (!$this->session->get('is_logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        // Set JSON header
        $this->response->setContentType('application/json');

        // Load student helper
        helper('student');

        try {
            $studentId = generate_unique_student_id();

            return $this->response->setJSON([
                'success' => true,
                'student_id' => $studentId,
                'message' => 'Student ID generated successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Student ID generation failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to generate student ID: ' . $e->getMessage()
            ]);
        }
    }

    public function settings()
    {
        try {
            // Get current settings from database
            $settings = $this->getCurrentSettings();

            // Fetch AI API key for selected provider/model
            $aiApiKey = '';
            if (!empty($settings['ai_model_provider']) && !empty($settings['ai_model'])) {
                try {
                    $apiKeyModel = new \App\Models\AIAPIKeyModel();
                    $apiKeyRow = $apiKeyModel->getApiKey($settings['ai_model_provider'], $settings['ai_model']);
                    if ($apiKeyRow && !empty($apiKeyRow['api_key'])) {
                        $aiApiKey = $apiKeyRow['api_key'];
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Failed to load AI API key: ' . $e->getMessage());
                    // Continue without AI key
                }
            }

            $data = [
                'title' => 'System Settings - ExamExcel',
                'settings' => $settings,
                'ai_api_key' => $aiApiKey,
                'validation' => \Config\Services::validation()
            ];

            return view('admin/settings', $data);
        } catch (\Exception $e) {
            log_message('error', 'Settings page failed to load: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Return error message instead of blank page
            echo "<div style='padding: 20px; font-family: Arial;'>";
            echo "<h2>Settings Page Error</h2>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='" . base_url('admin/dashboard') . "'>Return to Dashboard</a></p>";
            echo "</div>";
            exit;
        }
    }

    /**
     * Exam Settings Page
     */
    public function examSettings()
    {
        // Get current settings
        $settings = $this->getCurrentSettings();

        $data = [
            'title' => 'Exam Settings - SRMS CBT System',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/exam_settings', $data);
    }

    /**
     * Theme Settings Page
     */
    public function themeSettings()
    {
        $themeConfig = new \App\Config\UITheme();
        $settingsModel = new \App\Models\SettingsModel();

        // Get current theme settings
        $currentTheme = $settingsModel->getSetting('current_theme', 'purple');
        $customTheme = $settingsModel->getSetting('custom_theme_settings', []);
        $fontFamily = $settingsModel->getSetting('theme_font_family', $themeConfig->fontFamily);
        $fontSize = $settingsModel->getSetting('theme_font_size', $themeConfig->fontSizeBase);

        $data = [
            'title' => 'Theme Settings - ExamExcel',
            'predefinedThemes' => $themeConfig->getPredefinedThemes(),
            'currentTheme' => $currentTheme,
            'customTheme' => $customTheme,
            'fontFamily' => $fontFamily,
            'fontSize' => $fontSize,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/theme_settings', $data);
    }

    /**
     * Update Theme Settings
     */
    public function updateThemeSettings()
    {
        $selectedTheme = $this->request->getPost('selected_theme');

        if (empty($selectedTheme)) {
            session()->setFlashdata('error', 'Please select a theme.');
            return redirect()->back()->withInput();
        }

        $themeConfig = new \App\Config\UITheme();

        try {
            if ($selectedTheme === 'custom') {
                // Handle custom theme
                $customSettings = [
                    'primaryColor' => $this->request->getPost('custom_primary_color_hex'),
                    'primaryDark' => $this->request->getPost('custom_primary_dark_hex'),
                    'primaryLight' => $this->request->getPost('custom_primary_light_hex'),
                    'bodyBg' => $this->request->getPost('custom_body_bg_hex'),
                    'cardBg' => $this->request->getPost('custom_card_bg_hex'),
                    'sidebarBg' => $this->request->getPost('custom_sidebar_bg')
                ];

                // Validate hex colors
                foreach (['primaryColor', 'primaryDark', 'primaryLight', 'bodyBg', 'cardBg'] as $colorField) {
                    if (!empty($customSettings[$colorField]) && !preg_match('/^#[0-9A-F]{6}$/i', $customSettings[$colorField])) {
                        session()->setFlashdata('error', "Invalid color format for {$colorField}. Please use hex format (#RRGGBB).");
                        return redirect()->back()->withInput();
                    }
                }

                $themeConfig->saveCustomTheme($customSettings);
            } else {
                // Handle predefined theme
                if (!$themeConfig->applyPredefinedTheme($selectedTheme)) {
                    session()->setFlashdata('error', 'Invalid theme selected.');
                    return redirect()->back()->withInput();
                }
            }

            // Save font settings
            $fontFamily = $this->request->getPost('font_family');
            $fontSize = $this->request->getPost('font_size');

            if (!empty($fontFamily) && !empty($fontSize)) {
                $themeConfig->saveFontSettings($fontFamily, $fontSize);
            }

            session()->setFlashdata('success', 'Theme settings updated successfully!');

        } catch (\Exception $e) {
            log_message('error', 'Theme settings update failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to update theme settings. Please try again.');
        }

        return redirect()->to('/admin/theme-settings');
    }

    /**
     * Reset Theme to Default
     */
    public function resetThemeSettings()
    {
        try {
            $themeConfig = new \App\Config\UITheme();
            $themeConfig->resetToDefault();

            session()->setFlashdata('success', 'Theme settings have been reset to default.');
        } catch (\Exception $e) {
            log_message('error', 'Theme reset failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to reset theme settings. Please try again.');
        }

        return redirect()->to('/admin/theme-settings');
    }

    /**
     * Update exam preferences
     */
    public function updateExamPreferences()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        $rules = [
            'default_exam_duration' => 'required|integer|greater_than[0]|less_than_equal_to[600]',
            'default_max_attempts' => 'required|integer|greater_than[0]|less_than_equal_to[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $settingsModel = new \App\Models\SettingsModel();

        $settings = [
            'default_exam_duration' => $this->request->getPost('default_exam_duration'),
            'default_max_attempts' => $this->request->getPost('default_max_attempts'),
            'auto_submit_on_time_up' => $this->request->getPost('auto_submit_on_time_up') ? 1 : 0,
            'calculator_enabled' => $this->request->getPost('calculator_enabled') ? 1 : 0,
            'exam_pause_enabled' => $this->request->getPost('exam_pause_enabled') ? 1 : 0
        ];

        try {
            foreach ($settings as $key => $value) {
                $settingsModel->setSetting($key, $value);
            }

            return redirect()->back()->with('success', 'Exam preferences updated successfully');
        } catch (\Exception $e) {
            log_message('error', 'Error updating exam preferences: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update exam preferences');
        }
    }



    /**
     * List exam types for AJAX
     */
    public function listExamTypes()
    {
        // Allow both AJAX and direct requests for debugging
        $examTypeModel = new \App\Models\ExamTypeModel();
        $examTypes = $examTypeModel->orderBy('name', 'ASC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'examTypes' => $examTypes
        ]);
    }

    /**
     * Add new exam type
     */
    public function addExamType()
    {
        // Log all request data for debugging
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'All POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Request headers: ' . json_encode($this->request->getHeaders()));

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $examTypeModel = new \App\Models\ExamTypeModel();

        // Debug: Log the received data
        $receivedData = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'default_total_marks' => $this->request->getPost('default_total_marks'),
            'assessment_category' => $this->request->getPost('assessment_category')
        ];
        log_message('debug', 'Received exam type data: ' . json_encode($receivedData));

        // Validate required fields
        if (empty($receivedData['name']) || empty($receivedData['code'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Name and code are required fields'
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
            'default_total_marks' => $this->request->getPost('default_total_marks') ?: 100,
            'is_test' => $this->request->getPost('assessment_category') === 'continuous_assessment' ? 1 : 0,
            'assessment_category' => $this->request->getPost('assessment_category') ?: 'continuous_assessment',
            'is_active' => 1,
            'created_by' => $this->session->get('user_id')
        ];

        log_message('debug', 'Processed exam type data: ' . json_encode($data));

        try {
            if ($examTypeModel->insert($data)) {
                log_message('debug', 'Exam type inserted successfully');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Exam type added successfully'
                ]);
            } else {
                $errors = $examTypeModel->errors();
                log_message('error', 'Exam type validation failed: ' . json_encode($errors));

                // Create a user-friendly error message
                $errorMessages = [];
                foreach ($errors as $field => $message) {
                    $errorMessages[] = $message;
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $errorMessages),
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error adding exam type: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get exam type details
     */
    public function getExamType($id)
    {
        // Allow both AJAX and direct requests

        $examTypeModel = new \App\Models\ExamTypeModel();
        $examType = $examTypeModel->find($id);

        if ($examType) {
            return $this->response->setJSON([
                'success' => true,
                'examType' => $examType
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Exam type not found'
            ]);
        }
    }

    /**
     * Update exam type
     */
    public function updateExamType($id)
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $examTypeModel = new \App\Models\ExamTypeModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'default_total_marks' => $this->request->getPost('default_total_marks') ?: 100,
            'is_test' => $this->request->getPost('assessment_category') === 'continuous_assessment' ? 1 : 0,
            'assessment_category' => $this->request->getPost('assessment_category') ?: 'continuous_assessment'
        ];

        if ($examTypeModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Exam type updated successfully'
            ]);
        } else {
            $errors = $examTypeModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }
    }

    /**
     * Toggle exam type status
     */
    public function toggleExamTypeStatus($id)
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $examTypeModel = new \App\Models\ExamTypeModel();

        if ($examTypeModel->toggleStatus($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Exam type status updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update exam type status'
            ]);
        }
    }

    /**
     * Delete exam type
     */
    public function deleteExamType($id)
    {
        // Allow both AJAX and direct requests

        // Check for DELETE method or _method parameter
        $method = $this->request->getMethod();
        $methodOverride = $this->request->getPost('_method');

        if ($method !== 'DELETE' && $methodOverride !== 'DELETE') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $examTypeModel = new \App\Models\ExamTypeModel();

        // Check if exam type is being used in any exams
        $examModel = new \App\Models\ExamModel();
        $examCount = $examModel->where('exam_type', $id)->countAllResults();

        if ($examCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete exam type. It is being used in ' . $examCount . ' exam(s).'
            ]);
        }

        if ($examTypeModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Exam type deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete exam type'
            ]);
        }
    }

    public function updateSettings()
    {
        try {
            $rules = [
                'default_exam_duration' => 'required|integer|greater_than[0]|less_than_equal_to[600]',
                'backup_frequency' => 'required|in_list[daily,weekly,monthly,disabled]',
                'backup_retention_days' => 'required|integer|greater_than[0]|less_than_equal_to[365]',
                'news_flash_content' => 'max_length[1000]',
                'student_id_prefix' => 'required|min_length[2]|max_length[5]|alpha'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            // Get form data
            $settingsData = [
                'institution_name' => 'ExamExcel', // Fixed value, not editable
                'default_exam_duration' => $this->request->getPost('default_exam_duration'),
                'auto_submit_on_time_up' => $this->request->getPost('auto_submit_on_time_up') ? 1 : 0,
                'backup_frequency' => $this->request->getPost('backup_frequency'),
                'backup_retention_days' => $this->request->getPost('backup_retention_days'),
                'app_locked' => $this->request->getPost('app_locked') ? 1 : 0,
                'locked_roles' => json_encode($this->request->getPost('locked_roles') ?? []),
                'news_flash_enabled' => $this->request->getPost('news_flash_enabled') ? 1 : 0,
                'news_flash_content' => $this->request->getPost('news_flash_content'),
                'calculator_enabled' => $this->request->getPost('calculator_enabled') ? 1 : 0,
                'exam_pause_enabled' => $this->request->getPost('exam_pause_enabled') ? 1 : 0,
                'student_id_prefix' => $this->request->getPost('student_id_prefix'),
                // AI Settings
                'ai_generation_enabled' => $this->request->getPost('ai_generation_enabled') ? 1 : 0,
                'ai_model_provider' => $this->request->getPost('ai_model_provider'),
                'ai_model' => $this->request->getPost('ai_model'),
            ];

            // Handle AI API Key saving using new table
            $aiProvider = $this->request->getPost('ai_model_provider');
            $aiModel = $this->request->getPost('ai_model');
            $aiApiKey = $this->request->getPost('ai_api_key');
            $username = session()->get('username');
            if ($aiProvider && $aiModel && $aiApiKey) {
                try {
                    $apiKeyModel = new \App\Models\AIAPIKeyModel();
                    $apiKeyModel->setApiKey($aiProvider, $aiModel, $aiApiKey, $username);
                } catch (\Exception $e) {
                    log_message('error', 'AI API Key save failed: ' . $e->getMessage());
                    // Continue with other settings even if AI key fails
                }
            }

            // Handle file uploads for logo and favicon
            $logo = $this->request->getFile('logo');
            $favicon = $this->request->getFile('favicon');

            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                $logoPath = $this->handleFileUpload($logo, 'logos');
                if ($logoPath) {
                    $settingsData['logo_path'] = $logoPath;
                }
            }

            if ($favicon && $favicon->isValid() && !$favicon->hasMoved()) {
                $faviconPath = $this->handleFileUpload($favicon, 'favicons');
                if ($faviconPath) {
                    $settingsData['favicon_path'] = $faviconPath;
                }
            }

            // Save settings to database
            // Handle locked_roles separately to ensure proper JSON type
            $lockedRoles = $settingsData['locked_roles'];
            unset($settingsData['locked_roles']);

            if ($this->settingsModel->updateSettings($settingsData)) {
                // Save locked_roles with explicit JSON type
                $lockedRolesArray = json_decode($lockedRoles, true) ?: [];
                $this->settingsModel->setSetting('locked_roles', $lockedRolesArray, 'json', 'Roles that are locked from logging in');

                // If app is locked and roles are specified, force logout immediately
                if ($settingsData['app_locked'] && !empty($lockedRolesArray)) {
                    $this->forceLogoutLockedRoles();
                }

                session()->setFlashdata('success', 'Settings updated successfully!');
            } else {
                session()->setFlashdata('error', 'Failed to update settings. Please try again.');
            }

            return redirect()->to('/admin/settings');

        } catch (\Exception $e) {
            log_message('error', 'Settings update failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            session()->setFlashdata('error', 'Failed to update settings. Please check the error logs for details.');
            return redirect()->back()->withInput();
        }
    }

    public function reports()
    {
        // Ultra-simple version to ensure it works
        $data = [
            'title' => 'Reports - ExamExcel',
            'stats' => [
                'total_exams' => 0,
                'active_students' => 0,
                'total_teachers' => 0,
                'average_pass_rate' => 0
            ],
            'recent_activity' => [],
            'top_performers' => []
        ];

        try {
            // Basic statistics only
            $examModel = new \App\Models\ExamModel();
            $data['stats']['total_exams'] = $examModel->countAllResults();

            $data['stats']['active_students'] = $this->userModel->where('role', 'student')->where('is_active', 1)->countAllResults();
            $data['stats']['total_teachers'] = $this->userModel->where('role', 'teacher')->where('is_active', 1)->countAllResults();

            // Try to add exam attempts functionality
            try {
                $attemptModel = new \App\Models\ExamAttemptModel();

                // Calculate pass rate
                $allAttempts = $attemptModel->where('status', 'completed')->findAll();
                $passedAttempts = 0;
                $totalAttempts = count($allAttempts);

                foreach ($allAttempts as $attempt) {
                    if (isset($attempt['percentage']) && $attempt['percentage'] >= 50) {
                        $passedAttempts++;
                    }
                }

                $data['stats']['average_pass_rate'] = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0;

                // Get recent activity - basic version without complex joins
                $recentAttempts = $attemptModel->orderBy('created_at', 'DESC')->limit(10)->findAll();
                $recentActivity = [];

                foreach ($recentAttempts as $attempt) {
                    // Get related data safely
                    $exam = null;
                    $user = null;

                    try {
                        $exam = $examModel->find($attempt['exam_id']);
                    } catch (\Exception $e) {
                        // Ignore exam lookup errors
                    }

                    try {
                        // Handle both user_id and student_id
                        $userId = $attempt['user_id'] ?? $attempt['student_id'] ?? null;
                        if ($userId) {
                            $user = $this->userModel->find($userId);
                        }
                    } catch (\Exception $e) {
                        // Ignore user lookup errors
                    }

                    $attempt['exam_title'] = $exam ? $exam['title'] : 'Unknown Exam';
                    $attempt['first_name'] = $user ? $user['first_name'] : 'Unknown';
                    $attempt['last_name'] = $user ? $user['last_name'] : 'User';

                    $recentActivity[] = $attempt;
                }

                $data['recent_activity'] = $recentActivity;

                // Get top performers
                $topAttempts = $attemptModel->where('percentage >=', 50)->orderBy('percentage', 'DESC')->limit(10)->findAll();
                $topPerformers = [];

                foreach ($topAttempts as $attempt) {
                    // Get related data safely
                    $exam = null;
                    $user = null;

                    try {
                        $exam = $examModel->find($attempt['exam_id']);
                    } catch (\Exception $e) {
                        // Ignore exam lookup errors
                    }

                    try {
                        // Handle both user_id and student_id
                        $userId = $attempt['user_id'] ?? $attempt['student_id'] ?? null;
                        if ($userId) {
                            $user = $this->userModel->find($userId);
                        }
                    } catch (\Exception $e) {
                        // Ignore user lookup errors
                    }

                    $attempt['exam_title'] = $exam ? $exam['title'] : 'Unknown Exam';
                    $attempt['first_name'] = $user ? $user['first_name'] : 'Unknown';
                    $attempt['last_name'] = $user ? $user['last_name'] : 'User';

                    $topPerformers[] = $attempt;
                }

                $data['top_performers'] = $topPerformers;

            } catch (\Exception $e) {
                // If anything fails with exam attempts, just log and continue
                log_message('error', 'Exam attempts processing failed: ' . $e->getMessage());
            }

            return view('admin/reports', $data);
        } catch (\Exception $e) {
            log_message('error', 'Reports page failed to load: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Provide default values if database operations fail
            $data = [
                'title' => 'Reports - ExamExcel',
                'stats' => [
                    'total_exams' => 0,
                    'active_students' => 0,
                    'total_teachers' => 0,
                    'average_pass_rate' => 0
                ],
                'recent_activity' => [],
                'top_performers' => [],
                'error_message' => 'Unable to load statistics: ' . $e->getMessage()
            ];

            return view('admin/reports', $data);
        }
    }

    public function results()
    {
        // Get filter parameters
        $classId = $this->request->getGet('class_id');
        $subjectId = $this->request->getGet('subject_id');
        $examId = $this->request->getGet('exam_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Pagination settings
        $perPage = $this->request->getGet('per_page') ?? 20; // Number of results per page
        $page = $this->request->getGet('page') ?? 1;

        // Get all exam attempts with filters for statistics
        $statsBuilder = $this->attemptModel->select('exam_attempts.*,
                                                     exams.title as exam_title,
                                                     exams.total_points,
                                                     exams.total_marks,
                                                     exams.passing_score,
                                                     exams.passing_marks,
                                                     exams.exam_mode,
                                                     users.first_name,
                                                     users.last_name,
                                                     users.student_id,
                                                     subjects.name as subject_name,
                                                     classes.name as class_name')
                                           ->join('exams', 'exams.id = exam_attempts.exam_id')
                                           ->join('users', 'users.id = exam_attempts.student_id')
                                           ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                                           ->join('classes', 'classes.id = exams.class_id')
                                           ->whereIn('exam_attempts.status', ['submitted', 'auto_submitted', 'completed']);

        // Apply filters to stats builder
        if ($classId) {
            $statsBuilder->where('exams.class_id', $classId);
        }
        if ($subjectId) {
            $statsBuilder->where('exams.subject_id', $subjectId);
        }
        if ($examId) {
            $statsBuilder->where('exam_attempts.exam_id', $examId);
        }
        if ($dateFrom) {
            $statsBuilder->where('exam_attempts.submitted_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $statsBuilder->where('exam_attempts.submitted_at <=', $dateTo . ' 23:59:59');
        }

        // Get all attempts for statistics
        $allAttempts = $statsBuilder->orderBy('exam_attempts.submitted_at', 'DESC')->findAll();

        // Get paginated attempts for display
        $builder = $this->attemptModel->select('exam_attempts.*,
                                               exams.title as exam_title,
                                               exams.total_points,
                                               exams.total_marks,
                                               exams.passing_score,
                                               exams.passing_marks,
                                               exams.exam_mode,
                                               users.first_name,
                                               users.last_name,
                                               users.student_id,
                                               subjects.name as subject_name,
                                               classes.name as class_name')
                                     ->join('exams', 'exams.id = exam_attempts.exam_id')
                                     ->join('users', 'users.id = exam_attempts.student_id')
                                     ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                                     ->join('classes', 'classes.id = exams.class_id')
                                     ->whereIn('exam_attempts.status', ['submitted', 'auto_submitted', 'completed']);

        // Apply filters to paginated builder
        if ($classId) {
            $builder->where('exams.class_id', $classId);
        }
        if ($subjectId) {
            $builder->where('exams.subject_id', $subjectId);
        }
        if ($examId) {
            $builder->where('exam_attempts.exam_id', $examId);
        }
        if ($dateFrom) {
            $builder->where('exam_attempts.submitted_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('exam_attempts.submitted_at <=', $dateTo . ' 23:59:59');
        }

        // Get paginated results
        $attempts = $builder->orderBy('exam_attempts.submitted_at', 'DESC')
                           ->paginate($perPage, 'default', $page);

        // Get pagination object
        $pager = $this->attemptModel->pager;

        // Calculate statistics using all attempts
        $totalAttempts = count($allAttempts);
        $totalStudents = count(array_unique(array_column($allAttempts, 'student_id')));
        $totalExams = count(array_unique(array_column($allAttempts, 'exam_id')));

        $passedAttempts = 0;
        $totalMarks = 0;
        $totalPercentage = 0;

        foreach ($allAttempts as $attempt) {
            // Use marks_obtained if available, otherwise fall back to score
            $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
            $passingThreshold = $attempt['passing_marks'] ?? $attempt['passing_score'] ?? 0;

            if ($studentScore >= $passingThreshold) {
                $passedAttempts++;
            }
            $totalMarks += $studentScore;
            $totalPercentage += $attempt['percentage'];
        }

        $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0;
        $averageMarks = $totalAttempts > 0 ? round($totalMarks / $totalAttempts, 2) : 0;
        $averagePercentage = $totalAttempts > 0 ? round($totalPercentage / $totalAttempts, 2) : 0;

        // Get recent exam attempts (last 10) from all attempts for analytics
        $recentAttempts = array_slice($allAttempts, 0, 10);

        // Get top performers (top 10 by percentage) from all attempts
        $topPerformers = $allAttempts;
        usort($topPerformers, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });
        $topPerformers = array_slice($topPerformers, 0, 10);

        // Get performance by subject using all attempts
        $subjectPerformance = [];
        foreach ($allAttempts as $attempt) {
            // Handle multi-subject exams
            $subject = $attempt['subject_name'] ?? ($attempt['exam_mode'] === 'multi_subject' ? 'Multi-Subject' : 'Unknown Subject');
            if (!isset($subjectPerformance[$subject])) {
                $subjectPerformance[$subject] = [
                    'total_attempts' => 0,
                    'total_marks' => 0,
                    'total_percentage' => 0,
                    'passed' => 0
                ];
            }
            $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
            $passingThreshold = $attempt['passing_marks'] ?? $attempt['passing_score'] ?? 0;

            $subjectPerformance[$subject]['total_attempts']++;
            $subjectPerformance[$subject]['total_marks'] += $studentScore;
            $subjectPerformance[$subject]['total_percentage'] += $attempt['percentage'];
            if ($studentScore >= $passingThreshold) {
                $subjectPerformance[$subject]['passed']++;
            }
        }

        // Calculate averages for each subject
        foreach ($subjectPerformance as $subject => &$data) {
            if ($data['total_attempts'] > 0) {
                $data['average_marks'] = round($data['total_marks'] / $data['total_attempts'], 2);
                $data['average_percentage'] = round($data['total_percentage'] / $data['total_attempts'], 2);
                $data['pass_rate'] = round(($data['passed'] / $data['total_attempts']) * 100, 2);
            } else {
                $data['average_marks'] = 0;
                $data['average_percentage'] = 0;
                $data['pass_rate'] = 0;
            }
        }

        $data = [
            'title' => 'Results & Analytics - ExamExcel',
            'pageTitle' => 'Results & Analytics',
            'pageSubtitle' => 'Comprehensive examination performance analysis',
            'attempts' => $attempts, // Paginated attempts for table display
            'allAttempts' => $allAttempts, // All attempts for analytics
            'recentAttempts' => $recentAttempts,
            'topPerformers' => $topPerformers,
            'subjectPerformance' => $subjectPerformance,
            'pager' => $pager,
            'currentPage' => $page,
            'perPage' => $perPage,
            'stats' => [
                'total_attempts' => $totalAttempts,
                'total_students' => $totalStudents,
                'total_exams' => $totalExams,
                'pass_rate' => $passRate,
                'average_marks' => $averageMarks,
                'average_percentage' => $averagePercentage,
                'passed_attempts' => $passedAttempts,
                'failed_attempts' => $totalAttempts - $passedAttempts
            ],
            'filters' => [
                'class_id' => $classId,
                'subject_id' => $subjectId,
                'exam_id' => $examId,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ],
            'classes' => $this->classModel->where('is_active', 1)->findAll(),
            'subjects' => $this->subjectModel->where('is_active', 1)->findAll(),
            'exams' => $this->examModel->where('is_active', 1)->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/results', $data);
    }

    /**
     * View individual exam attempt result
     */
    public function viewResult($attemptId)
    {
        $attempt = $this->attemptModel->getAttemptWithDetails($attemptId);

        if (!$attempt) {
            return redirect()->to('/admin/results')->with('error', 'Exam attempt not found');
        }

        // Get exam details
        $exam = $this->examModel->find($attempt['exam_id']);
        if (!$exam) {
            return redirect()->to('/admin/results')->with('error', 'Exam not found');
        }

        // Get student answers for this attempt from student_answers table
        $studentAnswerModel = new \App\Models\StudentAnswerModel();
        $studentAnswers = $studentAnswerModel->getAnswersGroupedByQuestion($attemptId);

        // Get questions for this exam
        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $questions = $examQuestionModel->getExamQuestionsGrouped($attempt['exam_id']);

        // Calculate detailed performance metrics
        $totalQuestions = count($questions);

        // Check if we have student answers data
        if (!empty($studentAnswers)) {
            // Recalculate from student_answers table
            $correctAnswers = 0;
            $wrongAnswers = 0;
            $unanswered = 0;

            foreach ($questions as $question) {
                $studentAnswer = $studentAnswers[$question['id']] ?? null;

                if (!$studentAnswer || (empty($studentAnswer['answer_text']) && empty($studentAnswer['selected_options']))) {
                    $unanswered++;
                } else {
                    // Use the is_correct field from the student_answers table
                    if ($studentAnswer['is_correct']) {
                        $correctAnswers++;
                    } else {
                        $wrongAnswers++;
                    }
                }
            }
        } else {
            // Fallback: Try to reconstruct answers from exam_attempts.answers JSON field
            $jsonAnswers = $attempt['answers'] ?? [];

            // Ensure it's an array
            if (!is_array($jsonAnswers)) {
                if (is_string($jsonAnswers)) {
                    $jsonAnswers = json_decode($jsonAnswers, true) ?? [];
                } elseif (is_object($jsonAnswers)) {
                    $jsonAnswers = (array) $jsonAnswers;
                } else {
                    $jsonAnswers = [];
                }
            }

            if (!empty($jsonAnswers)) {
                // Reconstruct student answers from JSON data for display
                $studentAnswers = [];
                foreach ($questions as $question) {
                    $questionId = $question['id'];
                    $studentAnswer = $jsonAnswers[$questionId] ?? null;

                    if ($studentAnswer !== null && $studentAnswer !== '') {
                        $answerText = '';
                        $selectedOptions = [];

                        // Handle different question types
                        if (in_array($question['question_type'], ['mcq', 'true_false', 'yes_no'])) {
                            // For MCQ questions, convert option ID to option letter
                            if (!empty($question['options'])) {
                                foreach ($question['options'] as $index => $option) {
                                    if ($option['id'] == $studentAnswer) {
                                        $optionLetter = chr(65 + $index); // A, B, C, D...
                                        $selectedOptions = [$optionLetter];
                                        $answerText = $option['option_text'];
                                        break;
                                    }
                                }
                            }
                        } else {
                            // For text-based questions
                            $answerText = is_array($studentAnswer) ? json_encode($studentAnswer) : $studentAnswer;
                            $selectedOptions = [];
                        }

                        // Determine if the answer is correct
                        $isCorrect = false;
                        if (in_array($question['question_type'], ['mcq', 'true_false', 'yes_no'])) {
                            // For MCQ questions, check if selected option is correct
                            if (!empty($question['options'])) {
                                foreach ($question['options'] as $option) {
                                    if ($option['id'] == $studentAnswer && $option['is_correct']) {
                                        $isCorrect = true;
                                        break;
                                    }
                                }
                            }
                        }

                        // Create a mock student answer record for display
                        $studentAnswers[$questionId] = [
                            'question_id' => $questionId,
                            'answer_text' => $answerText,
                            'selected_options' => json_encode($selectedOptions),
                            'is_correct' => $isCorrect,
                            'points_earned' => $isCorrect ? ($question['points'] ?? 1) : 0,
                            'answered_at' => $attempt['submitted_at'] ?? $attempt['completed_at']
                        ];
                    }
                }
                log_message('info', "Reconstructed " . count($studentAnswers) . " answers from JSON for attempt {$attemptId} with correctness evaluation");
            }

            // Recalculate from reconstructed answers if available
            if (!empty($studentAnswers)) {
                $correctAnswers = 0;
                $wrongAnswers = 0;
                $unanswered = 0;

                foreach ($questions as $question) {
                    $studentAnswer = $studentAnswers[$question['id']] ?? null;

                    if (!$studentAnswer || (empty($studentAnswer['answer_text']) && empty($studentAnswer['selected_options']))) {
                        $unanswered++;
                    } else {
                        if ($studentAnswer['is_correct']) {
                            $correctAnswers++;
                        } else {
                            $wrongAnswers++;
                        }
                    }
                }
            } else {
                // Use stored values from exam_attempts table as final fallback
                $correctAnswers = $attempt['correct_answers'] ?? 0;
                $wrongAnswers = $attempt['wrong_answers'] ?? 0;
                $answeredQuestions = $attempt['answered_questions'] ?? 0;
                $unanswered = $totalQuestions - $answeredQuestions;
            }

            // Log this fallback for debugging
            log_message('info', "Using stored performance data for attempt {$attemptId} - no student_answers found");
        }

        $data = [
            'title' => 'Exam Result Details - SRMS CBT System',
            'pageTitle' => 'Exam Result Details',
            'pageSubtitle' => $attempt['exam_title'] . ' - ' . $attempt['first_name'] . ' ' . $attempt['last_name'],
            'attempt' => $attempt,
            'exam' => $exam,
            'questions' => $questions,
            'studentAnswers' => $studentAnswers,
            'performance' => [
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'unanswered' => $unanswered,
                'accuracy' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0
            ]
        ];

        return view('admin/result_view', $data);
    }

    /**
     * Download individual exam result report
     */
    public function downloadResult($attemptId)
    {
        $attempt = $this->attemptModel->getAttemptWithDetails($attemptId);

        if (!$attempt) {
            return redirect()->to('/admin/results')->with('error', 'Exam attempt not found');
        }

        // Generate PDF report (you can implement this based on your needs)
        // For now, we'll create a simple text-based report

        $filename = 'exam_report_' . $attemptId . '_' . date('Y-m-d') . '.txt';

        $content = "EXAM RESULT REPORT\n";
        $content .= "==================\n\n";
        $content .= "Student: " . $attempt['first_name'] . ' ' . $attempt['last_name'] . "\n";
        $content .= "Student ID: " . $attempt['student_id_number'] . "\n";
        $content .= "Exam: " . $attempt['exam_title'] . "\n";
        $content .= "Subject: " . $attempt['subject_name'] . "\n";
        $content .= "Class: " . $attempt['class_name'] . "\n";
        $content .= "Date: " . date('M j, Y g:i A', strtotime($attempt['submitted_at'])) . "\n\n";
        $content .= "RESULTS:\n";
        $content .= "--------\n";
        $content .= "Score: " . ($attempt['marks_obtained'] ?? $attempt['score'] ?? 0) . " / " . $attempt['total_marks'] . "\n";
        $content .= "Percentage: " . $attempt['percentage'] . "%\n";
        $content .= "Status: " . (($attempt['marks_obtained'] ?? $attempt['score'] ?? 0) >= ($attempt['passing_marks'] ?? 0) ? 'PASS' : 'FAIL') . "\n";
        $content .= "Duration: " . $attempt['time_taken'] . " minutes\n";

        // Set headers for download
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($content));

        echo $content;
        exit;
    }

    /**
     * Delete exam result
     */
    public function deleteResult($attemptId)
    {
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        // Validate attempt ID
        if (!$attemptId || !is_numeric($attemptId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid attempt ID'
            ]);
        }

        try {
            // Check if attempt exists
            $attempt = $this->attemptModel->find($attemptId);
            if (!$attempt) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Exam attempt not found'
                ]);
            }

            // Get attempt details for logging
            $attemptDetails = $this->attemptModel->getAttemptWithDetails($attemptId);

            // Start transaction
            $this->db->transStart();

            // Delete related student answers first
            $studentAnswerModel = new \App\Models\StudentAnswerModel();
            $studentAnswerModel->where('exam_attempt_id', $attemptId)->delete();

            // Delete the exam attempt
            $deleted = $this->attemptModel->delete($attemptId);

            if (!$deleted) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete exam result'
                ]);
            }

            // Commit transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database transaction failed'
                ]);
            }

            // Log the deletion activity
            if ($attemptDetails) {
                log_message('info', 'Admin deleted exam result - Student: ' . $attemptDetails['first_name'] . ' ' . $attemptDetails['last_name'] .
                    ', Exam: ' . $attemptDetails['exam_title'] . ', Score: ' . ($attemptDetails['marks_obtained'] ?? $attemptDetails['score'] ?? 0));
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Exam result deleted successfully'
            ]);

        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->db->transRollback();

            log_message('error', 'Error deleting exam result: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the result: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete exam results
     */
    public function bulkDeleteResults()
    {
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        // Get JSON input
        $input = $this->request->getJSON(true);
        $attemptIds = $input['attempt_ids'] ?? [];

        // Validate input
        if (empty($attemptIds) || !is_array($attemptIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No results selected for deletion'
            ]);
        }

        // Validate all IDs are numeric
        foreach ($attemptIds as $id) {
            if (!is_numeric($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid attempt ID provided'
                ]);
            }
        }

        try {
            // Start transaction
            $this->db->transStart();

            $deletedCount = 0;
            $studentAnswerModel = new \App\Models\StudentAnswerModel();

            foreach ($attemptIds as $attemptId) {
                // Check if attempt exists
                $attempt = $this->attemptModel->find($attemptId);
                if (!$attempt) {
                    continue; // Skip non-existent attempts
                }

                // Delete related student answers first
                $studentAnswerModel->where('exam_attempt_id', $attemptId)->delete();

                // Delete the exam attempt
                if ($this->attemptModel->delete($attemptId)) {
                    $deletedCount++;
                }
            }

            // Commit transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database transaction failed'
                ]);
            }

            // Log the bulk deletion activity
            log_message('info', 'Admin bulk deleted ' . $deletedCount . ' exam results out of ' . count($attemptIds) . ' requested');

            return $this->response->setJSON([
                'success' => true,
                'message' => $deletedCount . ' exam results deleted successfully',
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->db->transRollback();

            log_message('error', 'Error bulk deleting exam results: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the results: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export results to CSV
     */
    public function exportResults()
    {
        // Get filter parameters
        $classId = $this->request->getGet('class_id');
        $subjectId = $this->request->getGet('subject_id');
        $examId = $this->request->getGet('exam_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Get all exam attempts with filters (same logic as results method)
        $builder = $this->attemptModel->select('exam_attempts.*,
                                               exams.title as exam_title,
                                               exams.total_points,
                                               exams.total_marks,
                                               exams.passing_score,
                                               exams.passing_marks,
                                               users.first_name,
                                               users.last_name,
                                               users.student_id,
                                               subjects.name as subject_name,
                                               classes.name as class_name')
                                     ->join('exams', 'exams.id = exam_attempts.exam_id')
                                     ->join('users', 'users.id = exam_attempts.student_id')
                                     ->join('subjects', 'subjects.id = exams.subject_id')
                                     ->join('classes', 'classes.id = exams.class_id')
                                     ->whereIn('exam_attempts.status', ['submitted', 'auto_submitted', 'completed']);

        // Apply filters
        if ($classId) {
            $builder->where('exams.class_id', $classId);
        }
        if ($subjectId) {
            $builder->where('exams.subject_id', $subjectId);
        }
        if ($examId) {
            $builder->where('exam_attempts.exam_id', $examId);
        }
        if ($dateFrom) {
            $builder->where('exam_attempts.submitted_at >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('exam_attempts.submitted_at <=', $dateTo);
        }

        $attempts = $builder->orderBy('exam_attempts.submitted_at', 'DESC')->findAll();

        // Generate CSV content
        $filename = 'exam_results_' . date('Y-m-d_H-i-s') . '.csv';

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, [
            'Student Name',
            'Student ID',
            'Exam Title',
            'Subject',
            'Class',
            'Score',
            'Total Marks',
            'Percentage',
            'Status',
            'Time Taken (minutes)',
            'Submitted At'
        ]);

        // CSV data
        foreach ($attempts as $attempt) {
            $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
            $passingThreshold = $attempt['passing_marks'] ?? $attempt['passing_score'] ?? 0;
            $status = $studentScore >= $passingThreshold ? 'PASS' : 'FAIL';

            fputcsv($output, [
                $attempt['first_name'] . ' ' . $attempt['last_name'],
                $attempt['student_id'],
                $attempt['exam_title'],
                $attempt['subject_name'],
                $attempt['class_name'],
                $studentScore,
                $attempt['total_marks'] ?? $attempt['total_points'] ?? 0,
                $attempt['percentage'] . '%',
                $status,
                $attempt['time_taken'],
                date('M j, Y g:i A', strtotime($attempt['submitted_at']))
            ]);
        }

        fclose($output);
        exit;
    }

    public function security()
    {
        // Get filter parameters
        $severity = $this->request->getGet('severity');
        $eventType = $this->request->getGet('event_type');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $examId = $this->request->getGet('exam_id');

        // Build filters array
        $filters = array_filter([
            'severity' => $severity,
            'event_type' => $eventType,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'exam_id' => $examId
        ]);

        try {
            // Get security logs with filters
            $securityLogs = $this->securityLogModel->getSecurityLogs($filters);

            // Get security statistics
            $stats = $this->securityLogModel->getSecurityStats($dateFrom, $dateTo);

            // Get recent violations
            $recentViolations = $this->securityLogModel->getRecentViolations(10);

            // Get top violators
            $topViolators = $this->securityLogModel->getTopViolators(10);

            // Get failed login attempts in last 24 hours
            $failedLogins = $this->getFailedLoginAttempts();
        } catch (\Exception $e) {
            // If database operations fail, provide default values
            log_message('error', 'Security dashboard data loading failed: ' . $e->getMessage());

            $securityLogs = [];
            $stats = [
                'total_events' => 0,
                'critical_events' => 0,
                'event_types' => []
            ];
            $recentViolations = [];
            $topViolators = [];
            $failedLogins = 0;
        }

        // Get system security settings
        $securitySettings = $this->getCurrentSecuritySettings();

        // Get active sessions count
        try {
            $activeSessions = $this->getActiveSessionsCount();
        } catch (\Exception $e) {
            $activeSessions = 0;
        }

        $data = [
            'title' => 'Security Management - ExamExcel',
            'pageTitle' => 'Security Management',
            'pageSubtitle' => 'Monitor system security, violations, and access controls',
            'securityLogs' => array_slice($securityLogs, 0, 50), // Limit to 50 for performance
            'recentViolations' => $recentViolations,
            'topViolators' => $topViolators,
            'stats' => $stats,
            'securitySettings' => $securitySettings,
            'activeSessions' => $activeSessions,
            'failedLogins' => $failedLogins,
            'filters' => [
                'severity' => $severity,
                'event_type' => $eventType,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'exam_id' => $examId
            ],
            'exams' => $this->getExamsForFilter(),
            'eventTypes' => [
                'login_attempt' => 'Login Attempts',
                'login_failed' => 'Failed Logins',
                'exam_start' => 'Exam Started',
                'tab_switch' => 'Tab Switching',
                'window_blur' => 'Window Focus Lost',
                'copy_paste_attempt' => 'Copy/Paste Attempts',
                'right_click_attempt' => 'Right Click Attempts',
                'fullscreen_exit' => 'Fullscreen Exit',
                'suspicious_activity' => 'Suspicious Activity',
                'violation' => 'Security Violations',
                'unauthorized_access' => 'Unauthorized Access'
            ],
            'severityLevels' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'critical' => 'Critical'
            ]
        ];

        return view('admin/security', $data);
    }

    /**
     * View individual security log details
     */
    public function viewSecurityLog($logId)
    {
        $securityLogModel = new \App\Models\SecurityLogModel();

        // Get the security log with related data
        $log = $securityLogModel->select('
                security_logs.*,
                users.first_name,
                users.last_name,
                users.student_id,
                users.email,
                exams.title as exam_title,
                subjects.name as subject_name,
                exam_attempts.started_at,
                exam_attempts.submitted_at
            ')
            ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id', 'left')
            ->join('users', 'users.id = exam_attempts.student_id', 'left')
            ->join('exams', 'exams.id = exam_attempts.exam_id', 'left')
            ->join('subjects', 'subjects.id = exams.subject_id', 'left')
            ->where('security_logs.id', $logId)
            ->first();

        if (!$log) {
            return redirect()->to('/admin/security')->with('error', 'Security log not found');
        }

        // Parse event data if it exists
        $eventData = $this->parseEventData($log['event_data']);

        $data = [
            'title' => 'Security Log Details - SRMS CBT System',
            'pageTitle' => 'Security Log Details',
            'pageSubtitle' => 'Detailed view of security event #' . $logId,
            'log' => $log,
            'eventData' => $eventData
        ];

        return view('admin/security_log_details', $data);
    }

    /**
     * Get security event details via AJAX
     */
    public function getSecurityEventDetails($logId)
    {
        $securityLogModel = new \App\Models\SecurityLogModel();

        $log = $securityLogModel->find($logId);

        if (!$log) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Security log not found'
            ]);
        }

        $eventData = $this->parseEventData($log['event_data']);

        return $this->response->setJSON([
            'success' => true,
            'event_data' => $eventData,
            'log' => $log
        ]);
    }

    /**
     * Investigate security event
     */
    public function investigateSecurityEvent($logId)
    {
        $securityLogModel = new \App\Models\SecurityLogModel();

        // Get the security log with related data
        $log = $securityLogModel->select('
                security_logs.*,
                users.first_name,
                users.last_name,
                users.student_id,
                users.email,
                exams.title as exam_title,
                subjects.name as subject_name,
                exam_attempts.student_id as attempt_student_id
            ')
            ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id', 'left')
            ->join('users', 'users.id = exam_attempts.student_id', 'left')
            ->join('exams', 'exams.id = exam_attempts.exam_id', 'left')
            ->join('subjects', 'subjects.id = exams.subject_id', 'left')
            ->where('security_logs.id', $logId)
            ->first();

        if (!$log) {
            return redirect()->to('/admin/security')->with('error', 'Security log not found');
        }

        // Get related security events for this student
        $relatedEvents = [];
        if ($log['attempt_student_id']) {
            $relatedEvents = $securityLogModel->select('
                    security_logs.*,
                    exams.title as exam_title
                ')
                ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id', 'left')
                ->join('exams', 'exams.id = exam_attempts.exam_id', 'left')
                ->where('exam_attempts.student_id', $log['attempt_student_id'])
                ->where('security_logs.id !=', $logId)
                ->orderBy('security_logs.created_at', 'DESC')
                ->limit(20)
                ->findAll();
        }

        $data = [
            'title' => 'Security Investigation - SRMS CBT System',
            'pageTitle' => 'Security Investigation',
            'pageSubtitle' => 'Investigating security event #' . $logId,
            'log' => $log,
            'relatedEvents' => $relatedEvents,
            'eventData' => $this->parseEventData($log['event_data'])
        ];

        return view('admin/security_investigation', $data);
    }

    /**
     * Safely parse event data JSON
     */
    private function parseEventData($eventData)
    {
        if (!$eventData) {
            return null;
        }

        // If it's already an array (due to JSON casting), return it
        if (is_array($eventData)) {
            return $eventData;
        }

        // If it's a string, try to decode it
        if (is_string($eventData)) {
            $decoded = json_decode($eventData, true);

            // If JSON decode fails, return null
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            return $decoded;
        }

        // For any other type, return null
        return null;
    }

    private function getSecuritySettings()
    {
        // Get current security configuration
        return [
            'csrf_protection' => config('Security')->csrfProtection !== '',
            'session_timeout' => ini_get('session.gc_maxlifetime') / 60, // in minutes
            'max_login_attempts' => 5, // This could be configurable
            'lockout_duration' => 30, // minutes
            'require_https' => $this->request->isSecure(),
            'password_min_length' => 6, // This could be configurable
            'two_factor_enabled' => false, // Placeholder for future feature
            'ip_whitelist_enabled' => false, // Placeholder for future feature
            'browser_lockdown' => true,
            'proctoring_enabled' => true
        ];
    }

    private function getActiveSessionsCount()
    {
        // Count active user sessions (this is a simplified approach)
        // In a real implementation, you might track sessions in a database table
        return $this->userModel->where('is_active', 1)->countAllResults();
    }

    private function getFailedLoginAttempts()
    {
        // Get failed login attempts in the last 24 hours
        $yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));

        return $this->securityLogModel
                    ->where('event_type', 'login_failed')
                    ->where('created_at >=', $yesterday)
                    ->countAllResults();
    }

    public function securitySettings()
    {
        // Get current security settings
        $settings = $this->getCurrentSecuritySettings();

        $data = [
            'title' => 'Security Settings - ExamExcel',
            'pageTitle' => 'Security Settings',
            'pageSubtitle' => 'Configure system security parameters and policies',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/security_settings', $data);
    }

    public function updateSecuritySettings()
    {
        $rules = [
            'session_timeout' => 'required|integer|greater_than[0]|less_than_equal_to[1440]',
            'max_login_attempts' => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'lockout_duration' => 'required|integer|greater_than[0]|less_than_equal_to[1440]',
            'password_min_length' => 'required|integer|greater_than[3]|less_than_equal_to[50]',
            'auto_logout_idle' => 'required|integer|greater_than[0]|less_than_equal_to[120]',
            'max_tab_switches' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[50]',
            'max_window_focus_loss' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[50]',
            'max_monitor_warnings' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[50]',
            'max_security_violations' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Get form data
        $settingsData = [
            'session_timeout' => $this->request->getPost('session_timeout'),
            'max_login_attempts' => $this->request->getPost('max_login_attempts'),
            'lockout_duration' => $this->request->getPost('lockout_duration'),
            'password_min_length' => $this->request->getPost('password_min_length'),
            'auto_logout_idle' => $this->request->getPost('auto_logout_idle'),
            'require_https' => (bool) $this->request->getPost('require_https'),
            'csrf_protection' => (bool) $this->request->getPost('csrf_protection'),
            'browser_lockdown' => (bool) $this->request->getPost('browser_lockdown'),
            'proctoring_enabled' => (bool) $this->request->getPost('proctoring_enabled'),
            'require_proctoring' => (bool) $this->request->getPost('require_proctoring'),
            'prevent_copy_paste' => (bool) $this->request->getPost('prevent_copy_paste'),
            'disable_right_click' => (bool) $this->request->getPost('disable_right_click'),
            'fullscreen_mode' => (bool) $this->request->getPost('fullscreen_mode'),
            'tab_switching_detection' => (bool) $this->request->getPost('tab_switching_detection'),
            'ip_whitelist_enabled' => (bool) $this->request->getPost('ip_whitelist_enabled'),
            'two_factor_enabled' => (bool) $this->request->getPost('two_factor_enabled'),
            // Advanced Security Features
            'window_resize_detection' => (bool) $this->request->getPost('window_resize_detection'),
            'mouse_tracking_enabled' => (bool) $this->request->getPost('mouse_tracking_enabled'),
            'keyboard_pattern_analysis' => (bool) $this->request->getPost('keyboard_pattern_analysis'),
            'prevent_screen_capture' => (bool) $this->request->getPost('prevent_screen_capture'),
            'enhanced_devtools_detection' => (bool) $this->request->getPost('enhanced_devtools_detection'),
            'browser_extension_detection' => (bool) $this->request->getPost('browser_extension_detection'),
            'virtual_machine_detection' => (bool) $this->request->getPost('virtual_machine_detection'),
            'clipboard_monitoring' => (bool) $this->request->getPost('clipboard_monitoring'),
            // Security violation settings
            'strict_security_mode' => (bool) $this->request->getPost('strict_security_mode'),
            'auto_submit_on_violation' => (bool) $this->request->getPost('auto_submit_on_violation'),
            'max_tab_switches' => $this->request->getPost('max_tab_switches') ?? 5,
            'max_window_focus_loss' => $this->request->getPost('max_window_focus_loss') ?? 3,
            'max_monitor_warnings' => $this->request->getPost('max_monitor_warnings') ?? 2,
            'max_security_violations' => $this->request->getPost('max_security_violations') ?? 10
        ];

        // Save settings to database or config file
        if ($this->saveSecuritySettings($settingsData)) {
            // Try to log the security settings change (don't fail if logging fails)
            try {
                $this->securityLogModel->logEvent(
                    'security_settings_updated',
                    'medium',
                    $settingsData,
                    null,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
            } catch (\Exception $e) {
                // Log the error but don't break the settings save
                log_message('error', 'Failed to log security settings change: ' . $e->getMessage());
            }

            session()->setFlashdata('success', 'Security settings updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update security settings. Please try again.');
        }

        return redirect()->to('/admin/security/settings');
    }

    private function getCurrentSecuritySettings()
    {
        try {
            // Load settings from database
            $settings = $this->securitySettingsModel->getAllSettings();

            // If no settings found, return defaults
            if (empty($settings)) {
                return $this->getDefaultSecuritySettings();
            }

            // Merge with defaults to ensure all keys exist
            return array_merge($this->getDefaultSecuritySettings(), $settings);
        } catch (\Exception $e) {
            log_message('error', 'Failed to load security settings: ' . $e->getMessage());
            return $this->getDefaultSecuritySettings();
        }
    }

    private function getDefaultSecuritySettings()
    {
        return [
            'session_timeout' => 30, // minutes
            'max_login_attempts' => 5,
            'lockout_duration' => 30, // minutes
            'password_min_length' => 6,
            'auto_logout_idle' => 15, // minutes
            'require_https' => $this->request->isSecure(),
            'csrf_protection' => config('Security')->csrfProtection !== '',
            'browser_lockdown' => true,
            'proctoring_enabled' => true,
            'require_proctoring' => true, // Global default for exam-specific proctoring
            'prevent_copy_paste' => true,
            'disable_right_click' => true,
            'fullscreen_mode' => true,
            'tab_switching_detection' => true,
            'ip_whitelist_enabled' => false,
            'two_factor_enabled' => false,
            // Advanced Security Features
            'prevent_screen_capture' => false,
            'enhanced_devtools_detection' => false,
            'browser_extension_detection' => false,
            'virtual_machine_detection' => false,
            'mouse_tracking_enabled' => false,
            'keyboard_pattern_analysis' => false,
            'window_resize_detection' => false,
            'clipboard_monitoring' => false,
            // Security violation settings
            'strict_security_mode' => false, // Default to OFF for better user experience
            'auto_submit_on_violation' => true,
            'max_tab_switches' => 5,
            'max_window_focus_loss' => 3,
            'max_monitor_warnings' => 2,
            'max_security_violations' => 10
        ];
    }

    private function saveSecuritySettings($settings)
    {
        try {
            // Save settings to database using SecuritySettingsModel
            return $this->securitySettingsModel->updateSettings($settings);
        } catch (\Exception $e) {
            log_message('error', 'Failed to save security settings: ' . $e->getMessage());
            return false;
        }
    }

    private function getExamsForFilter()
    {
        try {
            return $this->examModel->where('is_active', 1)->orderBy('created_at', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Failed to load exams for filter: ' . $e->getMessage());
            return [];
        }
    }

    // Classes Management
    public function classes()
    {
        // Get classes with student counts
        $classes = $this->classModel->select('classes.*, COUNT(users.id) as student_count')
                                   ->join('users', 'users.class_id = classes.id AND users.role = "student" AND users.is_active = 1', 'left')
                                   ->groupBy('classes.id')
                                   ->orderBy('classes.created_at', 'DESC')
                                   ->findAll();

        $data = [
            'title' => 'Class Management - SRMS CBT System',
            'classes' => $classes
        ];

        return view('admin/classes', $data);
    }

    public function createClass()
    {
        $data = [
            'title' => 'Create Class - SRMS CBT System',
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[100]',
                'academic_year' => 'required|min_length[4]|max_length[20]',
                'max_students' => 'required|integer|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/create_class', $data);
            }

            $classData = [
                'name' => $this->request->getPost('name'),
                'section' => $this->request->getPost('section'),
                'academic_year' => $this->request->getPost('academic_year'),
                'description' => $this->request->getPost('description'),
                'max_students' => $this->request->getPost('max_students'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($classId = $this->classModel->insert($classData)) {
                session()->setFlashdata('success', 'Class created successfully! Class teacher account has been automatically created.');
                return redirect()->to('/admin/classes');
            } else {
                session()->setFlashdata('error', 'Failed to create class. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_class', $data);
    }

    /**
     * Manage class teacher credentials
     */
    public function manageClassTeacher($classId)
    {
        $class = $this->classModel->getClassWithClassTeacher($classId);

        if (!$class) {
            session()->setFlashdata('error', 'Class not found.');
            return redirect()->to('/admin/classes');
        }

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            if (empty($username)) {
                session()->setFlashdata('error', 'Username is required.');
                return redirect()->back();
            }

            // Check if username is unique (excluding current class teacher)
            $existingUser = $this->userModel->findByUsername($username);
            if ($existingUser && (!$class['class_teacher'] || $existingUser['id'] != $class['class_teacher']['id'])) {
                session()->setFlashdata('error', 'Username already exists. Please choose a different username.');
                return redirect()->back();
            }

            // If no class teacher exists, create one
            if (!$class['class_teacher']) {
                $result = $this->userModel->createClassTeacher($classId, $class['name'], $username, $password);
                if ($result) {
                    session()->setFlashdata('success', 'Class teacher account created successfully!');
                } else {
                    session()->setFlashdata('error', 'Failed to create class teacher account. Please try again.');
                }
            } else {
                // Update existing class teacher
                if ($this->classModel->updateClassTeacherCredentials($classId, $username, $password)) {
                    session()->setFlashdata('success', 'Class teacher credentials updated successfully!');
                } else {
                    session()->setFlashdata('error', 'Failed to update credentials. Please try again.');
                }
            }

            return redirect()->back();
        }

        $data = [
            'title' => 'Manage Class Teacher - ' . get_app_name(),
            'pageTitle' => 'Manage Class Teacher',
            'pageSubtitle' => 'Update class teacher login credentials',
            'class' => $class
        ];

        return view('admin/manage_class_teacher', $data);
    }

    /**
     * Debug and fix class teacher accounts
     */
    public function fixClassTeachers()
    {
        $classes = $this->classModel->findAll();
        $fixed = 0;
        $errors = [];

        foreach ($classes as $class) {
            // Check if class teacher exists
            $classTeacher = $this->userModel->findClassTeacher($class['id']);

            if (!$classTeacher) {
                // Create class teacher
                try {
                    $result = $this->userModel->createClassTeacher($class['id'], $class['name']);
                    if ($result) {
                        $fixed++;
                        log_message('info', "Created class teacher for class: {$class['name']} (ID: {$class['id']})");
                    } else {
                        $errors[] = "Failed to create class teacher for class: {$class['name']}";
                    }
                } catch (Exception $e) {
                    $errors[] = "Error creating class teacher for {$class['name']}: " . $e->getMessage();
                }
            }
        }

        $message = "Fixed {$fixed} class teacher accounts.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
            session()->setFlashdata('error', $message);
        } else {
            session()->setFlashdata('success', $message);
        }

        return redirect()->to('/admin/classes');
    }

    /**
     * Debug class teachers - show all class teacher accounts
     */
    public function debugClassTeachers()
    {
        $classTeachers = $this->userModel->where('role', 'class_teacher')->findAll();
        $classes = $this->classModel->findAll();

        $data = [
            'title' => 'Debug Class Teachers - ' . get_app_name(),
            'classTeachers' => $classTeachers,
            'classes' => $classes
        ];

        return view('admin/debug_class_teachers', $data);
    }

    /**
     * Check database for class teacher accounts - raw database query
     */
    public function checkDatabase()
    {
        $db = \Config\Database::connect();

        // Get all users with class_teacher role
        $classTeachers = $db->query("SELECT * FROM users WHERE role = 'class_teacher' ORDER BY id")->getResultArray();

        // Get all classes
        $classes = $db->query("SELECT * FROM classes ORDER BY id")->getResultArray();

        // Get all users for comparison
        $allUsers = $db->query("SELECT id, username, email, role, class_id, is_active, created_at FROM users ORDER BY role, id")->getResultArray();

        $data = [
            'title' => 'Database Check - ' . get_app_name(),
            'classTeachers' => $classTeachers,
            'classes' => $classes,
            'allUsers' => $allUsers
        ];

        return view('admin/check_database', $data);
    }

    /**
     * Manually create a class teacher for testing
     */
    public function createTestClassTeacher()
    {
        // Find a class without a class teacher
        $classes = $this->classModel->findAll();
        $testClass = null;

        foreach ($classes as $class) {
            $existingTeacher = $this->userModel->findClassTeacher($class['id']);
            if (!$existingTeacher) {
                $testClass = $class;
                break;
            }
        }

        if (!$testClass) {
            session()->setFlashdata('error', 'All classes already have class teachers. Create a new class first.');
            return redirect()->to('/admin/classes/debug-teachers');
        }

        // Manually create class teacher
        $username = 'TEST-' . strtoupper(str_replace(' ', '-', $testClass['name']));
        $password = 'test123';

        $userData = [
            'username' => $username,
            'email' => $username . '@test.local',
            'password' => password_hash($password, PASSWORD_DEFAULT), // Hash manually
            'first_name' => $testClass['name'],
            'last_name' => 'Test Teacher',
            'role' => 'class_teacher',
            'class_id' => $testClass['id'],
            'is_active' => 1,
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $result = $db->table('users')->insert($userData);

        if ($result) {
            session()->setFlashdata('success', "Test class teacher created! Username: {$username}, Password: {$password}");
        } else {
            session()->setFlashdata('error', 'Failed to create test class teacher.');
        }

        return redirect()->to('/admin/classes/debug-teachers');
    }

    /**
     * Test class teacher login and session
     */
    public function testClassTeacherLogin()
    {
        // Get a class teacher account
        $classTeacher = $this->userModel->where('role', 'class_teacher')->first();

        if (!$classTeacher) {
            session()->setFlashdata('error', 'No class teacher account found. Create one first.');
            return redirect()->to('/admin/classes/debug-teachers');
        }

        // Manually set session data (for testing)
        $sessionData = [
            'user_id' => $classTeacher['id'],
            'username' => $classTeacher['username'],
            'email' => $classTeacher['email'],
            'full_name' => $classTeacher['first_name'] . ' ' . $classTeacher['last_name'],
            'role' => $classTeacher['role'],
            'class_id' => $classTeacher['class_id'],
            'is_logged_in' => true
        ];

        $this->session->set($sessionData);

        // Try to redirect to class teacher dashboard
        return redirect()->to('/class-teacher/dashboard');
    }

    /**
     * Get class teacher statistics
     */
    private function getClassTeacherStats()
    {
        $stats = [
            'total_class_teachers' => $this->userModel->where('role', 'class_teacher')->countAllResults(),
            'active_class_teachers' => $this->userModel->where('role', 'class_teacher')->where('is_active', 1)->countAllResults(),
            'classes_with_teachers' => $this->classModel->where('class_teacher_id IS NOT NULL')->where('is_active', 1)->countAllResults(),
            'classes_without_teachers' => $this->classModel->where('class_teacher_id IS NULL')->where('is_active', 1)->countAllResults()
        ];

        return $stats;
    }

    /**
     * Get student statistics by class
     */
    private function getStudentStatsByClass()
    {
        $classModel = new \App\Models\ClassModel();
        $classes = $classModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $stats = [];

        foreach ($classes as $class) {
            $classId = $class['id'];
            $className = $class['name'];

            // Total students in this class
            $totalStudents = $this->userModel
                ->where('role', 'student')
                ->where('class_id', $classId)
                ->countAllResults();

            // Active students (is_active = 1 AND is_verified = 1)
            $activeStudents = $this->userModel
                ->where('role', 'student')
                ->where('class_id', $classId)
                ->where('is_active', 1)
                ->where('is_verified', 1)
                ->countAllResults();

            // Pending students (is_verified = 0)
            $pendingStudents = $this->userModel
                ->where('role', 'student')
                ->where('class_id', $classId)
                ->where('is_verified', 0)
                ->countAllResults();

            // Suspended students (is_active = 0)
            $suspendedStudents = $this->userModel
                ->where('role', 'student')
                ->where('class_id', $classId)
                ->where('is_active', 0)
                ->countAllResults();

            $stats[] = [
                'class_name' => $className,
                'total_students' => $totalStudents,
                'active_students' => $activeStudents,
                'pending_students' => $pendingStudents,
                'suspended_students' => $suspendedStudents
            ];
        }

        // Add general/unassigned students
        $generalTotal = $this->userModel
            ->where('role', 'student')
            ->where('class_id IS NULL')
            ->countAllResults();

        $generalActive = $this->userModel
            ->where('role', 'student')
            ->where('class_id IS NULL')
            ->where('is_active', 1)
            ->where('is_verified', 1)
            ->countAllResults();

        $generalPending = $this->userModel
            ->where('role', 'student')
            ->where('class_id IS NULL')
            ->where('is_verified', 0)
            ->countAllResults();

        $generalSuspended = $this->userModel
            ->where('role', 'student')
            ->where('class_id IS NULL')
            ->where('is_active', 0)
            ->countAllResults();

        if ($generalTotal > 0) {
            $stats[] = [
                'class_name' => 'General',
                'total_students' => $generalTotal,
                'active_students' => $generalActive,
                'pending_students' => $generalPending,
                'suspended_students' => $generalSuspended
            ];
        }

        return $stats;
    }

    /**
     * Get question statistics by subject for pie chart
     */
    private function getQuestionStatsBySubject()
    {
        $questionModel = new \App\Models\QuestionModel();

        $query = $questionModel->select('subjects.name as subject_name, COUNT(questions.id) as question_count')
            ->join('subjects', 'subjects.id = questions.subject_id')
            ->where('questions.is_active', 1)
            ->where('subjects.is_active', 1)
            ->groupBy('questions.subject_id')
            ->orderBy('question_count', 'DESC')
            ->limit(10);

        $results = $query->findAll();

        // Prepare data for pie chart
        $chartData = [];
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
        ];

        foreach ($results as $index => $result) {
            $chartData[] = [
                'subject' => $result['subject_name'],
                'count' => (int)$result['question_count'],
                'color' => $colors[$index % count($colors)]
            ];
        }

        return $chartData;
    }

    /**
     * Get detailed question counts by subject and difficulty
     */
    private function getSubjectQuestionCounts()
    {
        $questionModel = new \App\Models\QuestionModel();
        $subjectModel = new \App\Models\SubjectModel();

        $subjects = $subjectModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $stats = [];

        foreach ($subjects as $subject) {
            $subjectId = $subject['id'];
            $subjectName = $subject['name'];

            // Total questions for this subject
            $totalQuestions = $questionModel
                ->where('subject_id', $subjectId)
                ->where('is_active', 1)
                ->countAllResults();

            // Easy questions
            $easyQuestions = $questionModel
                ->where('subject_id', $subjectId)
                ->where('difficulty', 'easy')
                ->where('is_active', 1)
                ->countAllResults();

            // Normal (medium) questions
            $normalQuestions = $questionModel
                ->where('subject_id', $subjectId)
                ->where('difficulty', 'medium')
                ->where('is_active', 1)
                ->countAllResults();

            // Difficult (hard) questions
            $difficultQuestions = $questionModel
                ->where('subject_id', $subjectId)
                ->where('difficulty', 'hard')
                ->where('is_active', 1)
                ->countAllResults();

            // Only include subjects that have questions
            if ($totalQuestions > 0) {
                $stats[] = [
                    'subject_name' => $subjectName,
                    'total_questions' => $totalQuestions,
                    'easy_questions' => $easyQuestions,
                    'normal_questions' => $normalQuestions,
                    'difficult_questions' => $difficultQuestions
                ];
            }
        }

        return $stats;
    }

    // Subjects Management
    public function subjects()
    {
        $data = [
            'title' => 'Subject Management - ExamExcel',
            'subjects' => $this->subjectModel->orderBy('created_at', 'DESC')->findAll(),
            'categories' => $this->subjectModel->getAvailableCategories()
        ];

        return view('admin/subjects', $data);
    }

    public function createSubject()
    {
        $data = [
            'title' => 'Create Subject - SRMS CBT System',
            'categories' => $this->subjectCategoryModel->getActiveCategories(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[255]',
                'code' => 'required|min_length[2]|max_length[20]|is_unique[subjects.code]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/create_subject', $data);
            }

            $subjectData = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'description' => $this->request->getPost('description'),
                'category' => $this->request->getPost('category'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->subjectModel->insert($subjectData)) {
                session()->setFlashdata('success', 'Subject created successfully!');
                return redirect()->to('/admin/subjects');
            } else {
                session()->setFlashdata('error', 'Failed to create subject. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_subject', $data);
    }

    // Exams Management
    public function exams()
    {
        try {
            $exams = $this->examModel->getExamsWithSubjects([
                'exams.is_active' => 1
            ]);

            // Add creator information
            foreach ($exams as &$exam) {
                $creator = $this->userModel->find($exam['created_by']);
                $exam['first_name'] = $creator['first_name'] ?? '';
                $exam['last_name'] = $creator['last_name'] ?? '';

                // For display purposes, get the first subject name for single subject exams
                if ($exam['exam_mode'] === 'single_subject' && !empty($exam['subject_names']) && is_array($exam['subject_names'])) {
                    $exam['subject_name'] = $exam['subject_names'][0];
                } else if ($exam['exam_mode'] === 'multi_subject' && is_array($exam['subject_names'])) {
                    $exam['subject_name'] = implode(', ', array_slice($exam['subject_names'], 0, 2)) .
                                          (count($exam['subject_names']) > 2 ? ' +' . (count($exam['subject_names']) - 2) . ' more' : '');
                } else {
                    // Fallback for any data issues
                    $exam['subject_name'] = 'Unknown Subject';
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error loading exams: ' . $e->getMessage());
            $exams = [];
        }

        $data = [
            'title' => 'Exam Management - ' . get_app_name(),
            'exams' => $exams
        ];

        return view('admin/exams', $data);
    }

    /**
     * Create new exam
     */
    public function createExam()
    {
        $examTypeModel = new \App\Models\ExamTypeModel();

        $data = [
            'title' => 'Create Exam - ExamExcel',
            'pageTitle' => 'Create New Exam',
            'pageSubtitle' => 'Set up a new examination',
            'subjects' => $this->subjectModel->where('is_active', 1)->findAll(),
            'classes' => $this->classModel->where('is_active', 1)->findAll(),
            'examTypes' => $examTypeModel->getActiveExamTypes(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processCreateExam();
        }

        return view('admin/exam_create', $data);
    }

    /**
     * Process exam creation
     */
    private function processCreateExam()
    {
        // Get valid exam type IDs for validation
        $examTypeModel = new \App\Models\ExamTypeModel();
        $validExamTypes = array_column($examTypeModel->getActiveExamTypes(), 'id');
        $examTypeList = implode(',', $validExamTypes);

        $examMode = $this->request->getPost('exam_mode');

        $rules = [
            'title' => 'required|min_length[3]|max_length[200]',
            'description' => 'permit_empty|max_length[1000]',
            'exam_mode' => 'required|in_list[single_subject,multi_subject]',
            'class_id' => 'required|integer',
            'exam_type' => "required|in_list[{$examTypeList}]",
            'duration_minutes' => 'required|integer|greater_than[0]',
            'total_marks' => 'required|integer|greater_than[0]',
            'passing_marks' => 'required|decimal|greater_than[0]',
            'max_attempts' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[100]',
            'start_time' => 'required|valid_date',
            'end_time' => 'required|valid_date'
        ];

        // Add subject_id validation only for single subject mode
        if ($examMode === 'single_subject') {
            $rules['subject_id'] = 'required|integer';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $examData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'exam_mode' => $examMode,
            'subject_id' => $examMode === 'single_subject' ? $this->request->getPost('subject_id') : null,
            'class_id' => $this->request->getPost('class_id'),
            'session_id' => 1, // Default to first session
            'term_id' => 3, // Default to third term
            'exam_type' => $this->request->getPost('exam_type'),
            'status' => 'draft', // Start as draft until questions are configured
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'total_marks' => $this->request->getPost('total_marks'),
            'passing_marks' => $this->request->getPost('passing_marks'),
            'question_count' => 0, // Will be updated when questions are configured
            'total_questions' => 0, // Will be updated when questions are configured
            'questions_configured' => 0,
            'negative_marking' => $this->request->getPost('negative_marking') ? 1 : 0,
            'negative_marks_per_question' => $this->request->getPost('negative_marks_per_question') ?? 0,
            'randomize_questions' => $this->request->getPost('randomize_questions') ? 1 : 0,
            'randomize_options' => $this->request->getPost('randomize_options') ? 1 : 0,
            'show_result_immediately' => $this->request->getPost('show_result_immediately') ? 1 : 0,
            'allow_review' => $this->request->getPost('allow_review') ? 1 : 0,
            'require_proctoring' => $this->request->getPost('require_proctoring') ? 1 : 0,
            'browser_lockdown' => $this->request->getPost('browser_lockdown') ? 1 : 0,
            'prevent_copy_paste' => $this->request->getPost('prevent_copy_paste') ? 1 : 0,
            'disable_right_click' => $this->request->getPost('disable_right_click') ? 1 : 0,
            'calculator_enabled' => $this->request->getPost('calculator_enabled') ? 1 : 0,
            'exam_pause_enabled' => $this->request->getPost('exam_pause_enabled') ? 1 : 0,
            'max_attempts' => $this->request->getPost('max_attempts') ?? 5,
            'attempt_delay_minutes' => $this->request->getPost('attempt_delay_minutes') ?? 0,
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'instructions' => json_encode(['general' => 'Read all questions carefully before answering.']),
            'settings' => json_encode(['auto_submit' => true, 'show_timer' => true]),
            'allowed_ips' => json_encode([]),
            'is_active' => 1,
            'created_by' => $this->session->get('user_id')
        ];

        $examId = $this->examModel->insert($examData);
        if ($examId) {
            // Redirect to question management page
            return redirect()->to("/admin/exam/{$examId}/questions")->with('success', 'Exam created successfully! Now configure the questions.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create exam. Please try again.');
        }
    }

    /**
     * View exam details
     */
    public function viewExam($id)
    {
        $exam = $this->examModel->getExamWithDetails($id);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }

        $attemptModel = new \App\Models\ExamAttemptModel();
        $attempts = $attemptModel->getExamAttempts($id);

        // Validate exam configuration
        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $validationErrors = $examQuestionModel->validateQuestionCountConsistency($id);

        $data = [
            'title' => $exam['title'] . ' - SRMS CBT System',
            'pageTitle' => $exam['title'],
            'pageSubtitle' => 'Exam Details and Management',
            'exam' => $exam,
            'attempts' => $attempts,
            'status' => $this->examModel->getExamStatus($exam),
            'role' => 'admin',
            'validationErrors' => $validationErrors
        ];

        return view('admin/exam_view', $data);
    }

    /**
     * Edit exam
     */
    public function editExam($id)
    {
        $exam = $this->examModel->find($id);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }

        $examTypeModel = new \App\Models\ExamTypeModel();
        $examSubjectModel = new \App\Models\ExamSubjectModel();

        // Get exam subjects if it's a multi-subject exam
        $examSubjects = [];
        if ($exam['exam_mode'] === 'multi_subject') {
            $examSubjects = $examSubjectModel->getExamSubjects($id);
        }

        $data = [
            'title' => 'Edit Exam - ExamExcel',
            'pageTitle' => 'Edit Exam',
            'pageSubtitle' => 'Modify exam settings and details',
            'exam' => $exam,
            'examSubjects' => $examSubjects,
            'subjects' => $this->subjectModel->where('is_active', 1)->findAll(),
            'classes' => $this->classModel->where('is_active', 1)->findAll(),
            'examTypes' => $examTypeModel->getActiveExamTypes(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processEditExam($id);
        }

        return view('admin/exam_edit', $data);
    }

    /**
     * Process exam editing
     */
    private function processEditExam($id)
    {
        // Get the current exam to check its mode
        $currentExam = $this->examModel->find($id);
        if (!$currentExam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }

        // Get valid exam type IDs for validation
        $examTypeModel = new \App\Models\ExamTypeModel();
        $validExamTypes = array_column($examTypeModel->getActiveExamTypes(), 'id');
        $examTypeList = implode(',', $validExamTypes);

        // Different validation rules based on exam mode
        $rules = [
            'title' => 'required|min_length[3]|max_length[200]',
            'description' => 'permit_empty|max_length[1000]',
            'class_id' => 'required|integer',
            'exam_type' => "required|in_list[{$examTypeList}]",
            'duration_minutes' => 'required|integer|greater_than[0]',
            'total_marks' => 'required|integer|greater_than[0]',
            'passing_marks' => 'required|decimal|greater_than[0]',
            'max_attempts' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[100]',
            'start_time' => 'required|valid_date',
            'end_time' => 'required|valid_date'
        ];

        // Only require subject_id for single-subject exams
        if ($currentExam['exam_mode'] === 'single_subject') {
            $rules['subject_id'] = 'required|integer';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $examData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'class_id' => $this->request->getPost('class_id'),
            'exam_type' => $this->request->getPost('exam_type'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'total_marks' => $this->request->getPost('total_marks'),
            'passing_marks' => $this->request->getPost('passing_marks'),
            'negative_marking' => $this->request->getPost('negative_marking') ? 1 : 0,
            'negative_marks_per_question' => $this->request->getPost('negative_marks_per_question') ?? 0,
            'randomize_questions' => $this->request->getPost('randomize_questions') ? 1 : 0,
            'randomize_options' => $this->request->getPost('randomize_options') ? 1 : 0,
            'show_result_immediately' => $this->request->getPost('show_result_immediately') ? 1 : 0,
            'allow_review' => $this->request->getPost('allow_review') ? 1 : 0,
            'require_proctoring' => $this->request->getPost('require_proctoring') ? 1 : 0,
            'browser_lockdown' => $this->request->getPost('browser_lockdown') ? 1 : 0,
            'prevent_copy_paste' => $this->request->getPost('prevent_copy_paste') ? 1 : 0,
            'disable_right_click' => $this->request->getPost('disable_right_click') ? 1 : 0,
            'calculator_enabled' => $this->request->getPost('calculator_enabled') ? 1 : 0,
            'exam_pause_enabled' => $this->request->getPost('exam_pause_enabled') ? 1 : 0,
            'max_attempts' => $this->request->getPost('max_attempts') ?? 5,
            'attempt_delay_minutes' => $this->request->getPost('attempt_delay_minutes') ?? 0,
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time')
        ];

        // Only set subject_id for single-subject exams
        if ($currentExam['exam_mode'] === 'single_subject') {
            $examData['subject_id'] = $this->request->getPost('subject_id');
        }

        if ($this->examModel->update($id, $examData)) {
            return redirect()->to('/admin/exam/view/' . $id)->with('success', 'Exam updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update exam. Please try again.');
        }
    }

    /**
     * Delete exam
     */
    public function deleteExam($id)
    {
        $exam = $this->examModel->find($id);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }

        // Check if exam has attempts
        $attemptModel = new \App\Models\ExamAttemptModel();
        $attempts = $attemptModel->where('exam_id', $id)->countAllResults();

        if ($attempts > 0) {
            return redirect()->to('/admin/exams')->with('error', 'Cannot delete exam with existing attempts');
        }

        if ($this->examModel->delete($id)) {
            return redirect()->to('/admin/exams')->with('success', 'Exam deleted successfully!');
        } else {
            return redirect()->to('/admin/exams')->with('error', 'Failed to delete exam');
        }
    }

    /**
     * Manage exam questions
     */
    public function manageExamQuestions($examId)
    {
        try {
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return redirect()->to('/admin/exams')->with('error', 'Exam not found');
            }

            $data = [
                'title' => 'Manage Questions - ' . $exam['title'],
                'pageTitle' => 'Manage Exam Questions',
                'pageSubtitle' => $exam['title'],
                'exam' => $exam
            ];

            if ($exam['exam_mode'] === 'single_subject') {
                return $this->manageSingleSubjectQuestions($examId, $data);
            } else {
                return $this->manageMultiSubjectQuestions($examId, $data);
            }
        } catch (\Exception $e) {
            log_message('error', 'Manage exam questions failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Return error message instead of blank page
            echo "<div style='padding: 20px; font-family: Arial;'>";
            echo "<h2>Exam Questions Management Error</h2>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='" . base_url('admin/exams') . "'>Return to Exams</a></p>";
            echo "</div>";
            exit;
        }
    }

    /**
     * Manage single subject exam questions
     */
    private function manageSingleSubjectQuestions($examId, $data)
    {
        try {
            // Load text helper for character_limiter function
            helper('text');

            $exam = $data['exam'];
            $questionModel = new \App\Models\QuestionModel();
            $examQuestionModel = new \App\Models\ExamQuestionModel();

            // Handle POST request for question assignment
            if ($this->request->getMethod() === 'POST') {
                return $this->processSingleSubjectQuestions($examId);
            }

            // Get available questions for this subject and class
            // Note: Not filtering by exam_type to allow flexibility in question selection
            $availableQuestions = $questionModel->getQuestionsBySubjectAndClass(
                $exam['subject_id'],
                $exam['class_id']
            );

            // Process available questions to ensure proper ID handling
            $processedAvailableQuestions = [];
            foreach ($availableQuestions as $question) {
                if (isset($question['question_id'])) {
                    $question['id'] = $question['question_id']; // Ensure 'id' field exists for view compatibility
                }
                $processedAvailableQuestions[] = $question;
            }
            $availableQuestions = $processedAvailableQuestions;

            // Get currently selected questions
            $selectedQuestions = $examQuestionModel->getExamQuestions($examId);

            // Process selected questions to ensure proper ID handling
            $processedSelectedQuestions = [];
            foreach ($selectedQuestions as $question) {
                if (isset($question['question_id'])) {
                    $question['id'] = $question['question_id']; // Ensure 'id' field exists for view compatibility
                }
                $processedSelectedQuestions[] = $question;
            }
            $selectedQuestions = $processedSelectedQuestions;
            $selectedQuestionIds = array_column($selectedQuestions, 'question_id');

            $data['availableQuestions'] = $availableQuestions;
            $data['selectedQuestions'] = $selectedQuestions;
            $data['selectedQuestionIds'] = $selectedQuestionIds;
            $data['subjectModel'] = new \App\Models\SubjectModel();

            return view('admin/exam_questions_single', $data);
        } catch (\Exception $e) {
            log_message('error', 'Single subject questions management failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Get the last executed query for debugging
            $db = \Config\Database::connect();
            $lastQuery = $db->getLastQuery();
            log_message('error', 'Last executed query: ' . $lastQuery);

            // Return error message instead of blank page
            echo "<div style='padding: 20px; font-family: Arial;'>";
            echo "<h2>Single Subject Questions Error</h2>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>Exam ID:</strong> " . htmlspecialchars($examId) . "</p>";
            echo "<p><strong>Last Query:</strong> " . htmlspecialchars($lastQuery) . "</p>";
            echo "<p><a href='" . base_url('admin/exams') . "'>Return to Exams</a></p>";
            echo "</div>";
            exit;
        }
    }

    /**
     * Process single subject question assignment
     */
    private function processSingleSubjectQuestions($examId)
    {
        $selectedQuestions = $this->request->getPost('selected_questions');

        if (empty($selectedQuestions)) {
            return redirect()->back()->with('error', 'Please select at least one question for the exam.');
        }

        $examQuestionModel = new \App\Models\ExamQuestionModel();

        // Remove existing questions
        $examQuestionModel->where('exam_id', $examId)->delete();

        // Prepare question data
        $questionData = [];
        $order = 1;
        foreach ($selectedQuestions as $questionId) {
            $questionData[] = [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'order_index' => $order++,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        // Validate questions belong to correct class before assignment
        $validation = $examQuestionModel->validateQuestionClassMatch($examId, $selectedQuestions);
        if (!$validation['valid']) {
            $errorMessage = 'Class validation failed:<br>' . implode('<br>', $validation['errors']);
            return redirect()->back()->with('error', $errorMessage);
        }

        if ($examQuestionModel->insertBatch($questionData)) {
            // Update exam status and question count
            $this->examModel->update($examId, [
                'question_count' => count($selectedQuestions),
                'total_questions' => count($selectedQuestions),
                'questions_configured' => 1,
                'status' => 'scheduled'
            ]);

            return redirect()->to("/admin/exam/view/{$examId}")
                           ->with('success', 'Questions configured successfully! Exam is now ready.');
        } else {
            return redirect()->back()->with('error', 'Failed to configure questions. Please try again.');
        }
    }

    /**
     * Manage multi-subject exam questions
     */
    private function manageMultiSubjectQuestions($examId, $data)
    {
        try {
            $exam = $data['exam'];
            $examSubjectModel = new \App\Models\ExamSubjectModel();
            $subjectModel = new \App\Models\SubjectModel();

            // Get subjects assigned to this class
            $availableSubjects = $subjectModel->getSubjectsByClass($exam['class_id']);

            // Get configured exam subjects
            $examSubjects = $examSubjectModel->getExamSubjectsWithQuestions($examId);

            $data['availableSubjects'] = $availableSubjects;
            $data['examSubjects'] = $examSubjects;

            if ($this->request->getMethod() === 'POST') {
                return $this->processMultiSubjectConfiguration($examId);
            }

            return view('admin/exam_questions_multi', $data);
        } catch (\Exception $e) {
            log_message('error', 'Multi subject questions management failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Return error message instead of blank page
            echo "<div style='padding: 20px; font-family: Arial;'>";
            echo "<h2>Multi Subject Questions Error</h2>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>Exam ID:</strong> " . htmlspecialchars($examId) . "</p>";
            echo "<p><a href='" . base_url('admin/exams') . "'>Return to Exams</a></p>";
            echo "</div>";
            exit;
        }
    }

    /**
     * Process multi-subject configuration
     */
    private function processMultiSubjectConfiguration($examId)
    {
        $subjects = $this->request->getPost('subjects');
        $subjectConfig = $this->request->getPost('subject_config');

        if (empty($subjects)) {
            return redirect()->back()->with('error', 'Please select at least one subject for the exam.');
        }

        $examSubjectModel = new \App\Models\ExamSubjectModel();

        // Remove existing subject configurations
        $examSubjectModel->removeSubjectsFromExam($examId);

        // Add new subject configurations
        $subjectData = [];
        $order = 1;
        $totalMarks = 0;
        $totalQuestions = 0;
        $totalTime = 0;

        foreach ($subjects as $subjectId) {
            $config = $subjectConfig[$subjectId] ?? [];

            $questionCount = (int)($config['question_count'] ?? 0);
            $marks = (int)($config['total_marks'] ?? 0);
            $time = (int)($config['time_allocation'] ?? 0);

            if ($questionCount <= 0 || $marks <= 0) {
                return redirect()->back()->with('error', 'Please provide valid question count and marks for all selected subjects.');
            }

            $subjectData[] = [
                'exam_id' => $examId,
                'subject_id' => $subjectId,
                'question_count' => $questionCount,
                'total_marks' => $marks,
                'time_allocation' => $time,
                'subject_order' => $order++
            ];

            $totalMarks += $marks;
            $totalQuestions += $questionCount;
            $totalTime += $time;
        }

        if ($examSubjectModel->addSubjectsToExam($examId, $subjectData)) {
            // Update exam totals
            $this->examModel->update($examId, [
                'total_marks' => $totalMarks,
                'total_questions' => $totalQuestions,
                'duration_minutes' => $totalTime > 0 ? $totalTime : $this->examModel->find($examId)['duration_minutes'],
                'questions_configured' => 0, // Still need to configure individual questions
                'status' => 'draft'
            ]);

            return redirect()->back()->with('success', 'Subject configuration saved successfully! Now configure questions for each subject.');
        } else {
            return redirect()->back()->with('error', 'Failed to save subject configuration. Please try again.');
        }
    }

    /**
     * Reset exam subjects configuration
     */
    public function resetExamSubjects($examId)
    {
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        try {
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Exam not found'
                ]);
            }

            if ($exam['exam_mode'] !== 'multi_subject') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This operation is only available for multi-subject exams'
                ]);
            }

            $examSubjectModel = new \App\Models\ExamSubjectModel();
            $examQuestionModel = new \App\Models\ExamQuestionModel();

            // Start transaction
            $this->db->transStart();

            // Remove all exam questions for this exam
            $examQuestionModel->where('exam_id', $examId)->delete();

            // Remove all exam subjects for this exam
            $examSubjectModel->removeSubjectsFromExam($examId);

            // Reset exam totals
            $this->examModel->update($examId, [
                'total_questions' => 0,
                'total_marks' => 0,
                'questions_configured' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to reset exam configuration due to database error'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Exam subjects configuration has been reset successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error resetting exam subjects: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while resetting the configuration: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Manage questions for a specific subject in a multi-subject exam
     */
    public function manageExamSubjectQuestions($examId, $subjectId)
    {
        // Load text helper for character_limiter function
        helper('text');

        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/admin/exams')->with('error', 'Exam not found');
        }

        if ($exam['exam_mode'] !== 'multi_subject') {
            return redirect()->to("/admin/exam/{$examId}/questions")->with('error', 'This exam is not configured for multi-subject mode');
        }

        $subjectModel = new \App\Models\SubjectModel();
        $subject = $subjectModel->find($subjectId);
        if (!$subject) {
            return redirect()->back()->with('error', 'Subject not found');
        }

        $examSubjectModel = new \App\Models\ExamSubjectModel();
        $examSubject = $examSubjectModel->getExamSubject($examId, $subjectId);
        if (!$examSubject) {
            return redirect()->back()->with('error', 'This subject is not configured for this exam');
        }

        $questionModel = new \App\Models\QuestionModel();
        $examQuestionModel = new \App\Models\ExamQuestionModel();

        // Handle POST request for question assignment
        if ($this->request->getMethod() === 'POST') {
            return $this->processExamSubjectQuestions($examId, $subjectId);
        }

        // Get available questions for this subject and class
        $availableQuestions = $questionModel->getQuestionsBySubjectAndClass(
            $subjectId,
            $exam['class_id']
        );

        // Get currently selected questions for this subject in this exam
        $selectedQuestions = $examQuestionModel->getExamSubjectQuestions($examId, $subjectId);
        $selectedQuestionIds = array_column($selectedQuestions, 'question_id');

        $data = [
            'title' => 'Manage Questions - ' . $exam['title'] . ' - ' . $subject['name'],
            'pageTitle' => 'Manage Subject Questions',
            'pageSubtitle' => $exam['title'] . ' - ' . $subject['name'],
            'exam' => $exam,
            'subject' => $subject,
            'examSubject' => $examSubject,
            'availableQuestions' => $availableQuestions,
            'selectedQuestions' => $selectedQuestions,
            'selectedQuestionIds' => $selectedQuestionIds,
            'subjectModel' => $subjectModel
        ];

        return view('admin/exam_subject_questions', $data);
    }

    /**
     * Process subject-specific question assignment for multi-subject exam
     */
    private function processExamSubjectQuestions($examId, $subjectId)
    {
        $selectedQuestions = $this->request->getPost('selected_questions');

        $examSubjectModel = new \App\Models\ExamSubjectModel();
        $examSubject = $examSubjectModel->getExamSubject($examId, $subjectId);

        if (!$examSubject) {
            return redirect()->back()->with('error', 'Subject configuration not found for this exam');
        }

        $requiredQuestions = $examSubject['question_count'];

        if (empty($selectedQuestions)) {
            return redirect()->back()->with('error', 'Please select at least one question for this subject.');
        }

        if (count($selectedQuestions) > $requiredQuestions) {
            return redirect()->back()->with('error', "You can only select up to {$requiredQuestions} questions for this subject.");
        }

        if (count($selectedQuestions) < $requiredQuestions) {
            return redirect()->back()->with('error', "You must select exactly {$requiredQuestions} questions for this subject. Currently selected: " . count($selectedQuestions));
        }

        $examQuestionModel = new \App\Models\ExamQuestionModel();

        // Remove existing questions for this subject in this exam
        $examQuestionModel->where('exam_id', $examId)
                         ->where('subject_id', $subjectId)
                         ->delete();

        // Prepare question data
        $questionData = [];
        $order = 1;
        foreach ($selectedQuestions as $questionId) {
            $questionData[] = [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'subject_id' => $subjectId,
                'order_index' => $order++,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        // Validate questions belong to correct class before assignment
        $validation = $examQuestionModel->validateQuestionClassMatch($examId, $selectedQuestions);
        if (!$validation['valid']) {
            $errorMessage = 'Class validation failed:<br>' . implode('<br>', $validation['errors']);
            return redirect()->back()->with('error', $errorMessage);
        }

        if ($examQuestionModel->insertBatch($questionData)) {
            // Check if all subjects have questions configured
            $allSubjects = $examSubjectModel->getExamSubjectsWithQuestions($examId);
            $allConfigured = true;
            $totalConfiguredQuestions = 0;

            foreach ($allSubjects as $subj) {
                if ($subj['configured_questions'] < $subj['question_count']) {
                    $allConfigured = false;
                }
                $totalConfiguredQuestions += $subj['configured_questions'];
            }

            // Update exam status if all subjects are configured
            if ($allConfigured) {
                // Validate question count consistency before activating
                $examQuestionModel = new \App\Models\ExamQuestionModel();
                $validationErrors = $examQuestionModel->validateQuestionCountConsistency($examId);

                if (empty($validationErrors)) {
                    $this->examModel->update($examId, [
                        'questions_configured' => 1,
                        'status' => 'scheduled',
                        'total_questions' => $totalConfiguredQuestions
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Question count validation failed: ' . implode(', ', $validationErrors));
                }
            }

            return redirect()->to("/admin/exam/{$examId}/questions")
                           ->with('success', 'Questions configured successfully for ' . $examSubject['subject_name'] . '!');
        } else {
            return redirect()->back()->with('error', 'Failed to configure questions. Please try again.');
        }
    }

    // Additional Class Management Methods
    public function editClass($id)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            session()->setFlashdata('error', 'Class not found.');
            return redirect()->to('/admin/classes');
        }

        $data = [
            'title' => 'Edit Class - ExamExcel',
            'class' => $class,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[100]',
                'academic_year' => 'required|min_length[4]|max_length[20]',
                'max_students' => 'required|integer|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/edit_class', $data);
            }

            $classData = [
                'name' => $this->request->getPost('name'),
                'section' => $this->request->getPost('section'),
                'academic_year' => $this->request->getPost('academic_year'),
                'description' => $this->request->getPost('description'),
                'max_students' => $this->request->getPost('max_students'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->classModel->update($id, $classData)) {
                session()->setFlashdata('success', 'Class updated successfully!');
                return redirect()->to('/admin/classes');
            } else {
                session()->setFlashdata('error', 'Failed to update class. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/edit_class', $data);
    }

    public function deleteClass($id)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            session()->setFlashdata('error', 'Class not found.');
            return redirect()->to('/admin/classes');
        }

        if ($this->classModel->delete($id)) {
            session()->setFlashdata('success', 'Class deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete class.');
        }

        return redirect()->to('/admin/classes');
    }

    public function toggleClassStatus($id)
    {
        $class = $this->classModel->find($id);
        if (!$class) {
            session()->setFlashdata('error', 'Class not found.');
            return redirect()->to('/admin/classes');
        }

        $newStatus = $class['is_active'] ? 0 : 1;
        if ($this->classModel->update($id, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'activated' : 'deactivated';
            session()->setFlashdata('success', "Class {$statusText} successfully!");
        } else {
            session()->setFlashdata('error', 'Failed to update class status.');
        }

        return redirect()->to('/admin/classes');
    }

    // Additional Subject Management Methods
    public function editSubject($id)
    {
        $subject = $this->subjectModel->find($id);
        if (!$subject) {
            session()->setFlashdata('error', 'Subject not found.');
            return redirect()->to('/admin/subjects');
        }

        $data = [
            'title' => 'Edit Subject - SRMS CBT System',
            'subject' => $subject,
            'categories' => $this->subjectCategoryModel->getActiveCategories(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[255]',
                'code' => "required|min_length[2]|max_length[20]|is_unique[subjects.code,id,{$id}]"
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/edit_subject', $data);
            }

            $subjectData = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'description' => $this->request->getPost('description'),
                'category' => $this->request->getPost('category'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->subjectModel->update($id, $subjectData)) {
                session()->setFlashdata('success', 'Subject updated successfully!');
                return redirect()->to('/admin/subjects');
            } else {
                session()->setFlashdata('error', 'Failed to update subject. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/edit_subject', $data);
    }

    public function deleteSubject($id)
    {
        $subject = $this->subjectModel->find($id);
        if (!$subject) {
            session()->setFlashdata('error', 'Subject not found.');
            return redirect()->to('/admin/subjects');
        }

        if ($this->subjectModel->delete($id)) {
            session()->setFlashdata('success', 'Subject deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete subject.');
        }

        return redirect()->to('/admin/subjects');
    }

    public function toggleSubjectStatus($id)
    {
        $subject = $this->subjectModel->find($id);
        if (!$subject) {
            session()->setFlashdata('error', 'Subject not found.');
            return redirect()->to('/admin/subjects');
        }

        $newStatus = $subject['is_active'] ? 0 : 1;
        if ($this->subjectModel->update($id, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'activated' : 'deactivated';
            session()->setFlashdata('success', "Subject {$statusText} successfully!");
        } else {
            session()->setFlashdata('error', 'Failed to update subject status.');
        }

        return redirect()->to('/admin/subjects');
    }

    public function bulkActionSubjects()
    {
        if ($this->request->getMethod() !== 'POST') {
            session()->setFlashdata('error', 'Invalid request method.');
            return redirect()->to('/admin/subjects');
        }

        $action = $this->request->getPost('action');
        $subjectIds = $this->request->getPost('subjects');

        if (empty($action) || empty($subjectIds)) {
            session()->setFlashdata('error', 'Invalid bulk action request.');
            return redirect()->to('/admin/subjects');
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($subjectIds as $subjectId) {
            $subject = $this->subjectModel->find($subjectId);
            if (!$subject) {
                $errorCount++;
                $errors[] = "Subject with ID {$subjectId} not found.";
                continue;
            }

            switch ($action) {
                case 'delete':
                    // Check if subject can be deleted
                    $canDelete = $this->subjectModel->canDelete($subjectId);
                    if (!$canDelete['can_delete']) {
                        $errorCount++;
                        $errors[] = "Cannot delete '{$subject['name']}' - {$canDelete['reason']}";
                        continue 2;
                    }

                    if ($this->subjectModel->delete($subjectId)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to delete '{$subject['name']}'.";
                    }
                    break;

                case 'activate':
                    if ($this->subjectModel->update($subjectId, ['is_active' => 1])) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to activate '{$subject['name']}'.";
                    }
                    break;

                case 'deactivate':
                    if ($this->subjectModel->update($subjectId, ['is_active' => 0])) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to deactivate '{$subject['name']}'.";
                    }
                    break;

                default:
                    $errorCount++;
                    $errors[] = "Invalid action: {$action}";
                    break;
            }
        }

        // Set flash messages based on results
        if ($successCount > 0) {
            $actionText = $action === 'delete' ? 'deleted' : ($action === 'activate' ? 'activated' : 'deactivated');
            session()->setFlashdata('success', "{$successCount} subjects {$actionText} successfully!");
        }

        if ($errorCount > 0) {
            $errorMessage = "Failed to process {$errorCount} subjects.";
            if (!empty($errors)) {
                $errorMessage .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $errorMessage .= " and " . (count($errors) - 3) . " more.";
                }
            }
            session()->setFlashdata('error', $errorMessage);
        }

        return redirect()->to('/admin/subjects');
    }

    // Subject Categories Management
    public function subjectCategories()
    {
        $data = [
            'title' => 'Subject Categories - SRMS CBT System',
            'categories' => $this->subjectCategoryModel->getCategoriesWithSubjectCounts(),
            'stats' => $this->subjectCategoryModel->getCategoryStats()
        ];

        return view('admin/subject_categories', $data);
    }

    public function createSubjectCategory()
    {
        $data = [
            'title' => 'Create Subject Category - SRMS CBT System',
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[100]|is_unique[subject_categories.name]',
                'color' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/create_subject_category', $data);
            }

            $categoryData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'color' => $this->request->getPost('color'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->subjectCategoryModel->insert($categoryData)) {
                session()->setFlashdata('success', 'Subject category created successfully!');
                return redirect()->to('/admin/subject-categories');
            } else {
                session()->setFlashdata('error', 'Failed to create category. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_subject_category', $data);
    }

    public function editSubjectCategory($id)
    {
        $category = $this->subjectCategoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Category not found.');
            return redirect()->to('/admin/subject-categories');
        }

        $data = [
            'title' => 'Edit Subject Category - SRMS CBT System',
            'category' => $category,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => "required|min_length[2]|max_length[100]|is_unique[subject_categories.name,id,{$id}]",
                'color' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/edit_subject_category', $data);
            }

            $categoryData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'color' => $this->request->getPost('color'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->subjectCategoryModel->update($id, $categoryData)) {
                session()->setFlashdata('success', 'Category updated successfully!');
                return redirect()->to('/admin/subject-categories');
            } else {
                session()->setFlashdata('error', 'Failed to update category. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/edit_subject_category', $data);
    }

    public function deleteSubjectCategory($id)
    {
        $category = $this->subjectCategoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Category not found.');
            return redirect()->to('/admin/subject-categories');
        }

        // Check if category can be deleted
        if (!$this->subjectCategoryModel->canDelete($id)) {
            session()->setFlashdata('error', 'Cannot delete category. It is being used by one or more subjects.');
            return redirect()->to('/admin/subject-categories');
        }

        if ($this->subjectCategoryModel->delete($id)) {
            session()->setFlashdata('success', 'Category deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete category.');
        }

        return redirect()->to('/admin/subject-categories');
    }

    public function toggleSubjectCategoryStatus($id)
    {
        $category = $this->subjectCategoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Category not found.');
            return redirect()->to('/admin/subject-categories');
        }

        if ($this->subjectCategoryModel->toggleStatus($id)) {
            $statusText = $category['is_active'] ? 'deactivated' : 'activated';
            session()->setFlashdata('success', "Category {$statusText} successfully!");
        } else {
            session()->setFlashdata('error', 'Failed to update category status.');
        }

        return redirect()->to('/admin/subject-categories');
    }

    public function bulkActionSubjectCategories()
    {
        if ($this->request->getMethod() !== 'POST') {
            session()->setFlashdata('error', 'Invalid request method.');
            return redirect()->to('/admin/subject-categories');
        }

        $action = $this->request->getPost('action');
        $categoryIds = $this->request->getPost('categories');

        if (empty($action) || empty($categoryIds)) {
            session()->setFlashdata('error', 'Invalid bulk action request.');
            return redirect()->to('/admin/subject-categories');
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($categoryIds as $categoryId) {
            $category = $this->subjectCategoryModel->find($categoryId);
            if (!$category) {
                $errorCount++;
                $errors[] = "Category with ID {$categoryId} not found.";
                continue;
            }

            switch ($action) {
                case 'delete':
                    if (!$this->subjectCategoryModel->canDelete($categoryId)) {
                        $errorCount++;
                        $errors[] = "Cannot delete '{$category['name']}' - it has subjects assigned.";
                        continue 2;
                    }

                    if ($this->subjectCategoryModel->delete($categoryId)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to delete '{$category['name']}'.";
                    }
                    break;

                case 'activate':
                    if ($this->subjectCategoryModel->update($categoryId, ['is_active' => 1])) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to activate '{$category['name']}'.";
                    }
                    break;

                case 'deactivate':
                    if ($this->subjectCategoryModel->update($categoryId, ['is_active' => 0])) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to deactivate '{$category['name']}'.";
                    }
                    break;

                default:
                    $errorCount++;
                    $errors[] = "Invalid action: {$action}";
                    break;
            }
        }

        // Set flash messages based on results
        if ($successCount > 0) {
            $actionText = $action === 'delete' ? 'deleted' : ($action === 'activate' ? 'activated' : 'deactivated');
            session()->setFlashdata('success', "{$successCount} categories {$actionText} successfully!");
        }

        if ($errorCount > 0) {
            $errorMessage = "Failed to process {$errorCount} categories.";
            if (!empty($errors)) {
                $errorMessage .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $errorMessage .= " and " . (count($errors) - 3) . " more.";
                }
            }
            session()->setFlashdata('error', $errorMessage);
        }

        return redirect()->to('/admin/subject-categories');
    }

    // Teacher Assignment Management
    public function assignments()
    {
        $currentSession = $this->sessionModel->getCurrentSession();
        $sessionId = $this->request->getGet('session') ?: ($currentSession['id'] ?? null);

        $data = [
            'title' => 'Teacher Assignments - ExamExcel',
            'assignments' => $this->assignmentModel->getAllAssignments($sessionId),
            'sessions' => $this->sessionModel->getActiveSessions(),
            'currentSession' => $currentSession,
            'selectedSession' => $sessionId,
            'stats' => $this->assignmentModel->getAssignmentStats($sessionId)
        ];

        return view('admin/assignments', $data);
    }

    public function createAssignment()
    {
        $currentSession = $this->sessionModel->getCurrentSession();

        $data = [
            'title' => 'Create Teacher Assignment - ExamExcel',
            'teachers' => $this->userModel->where('role', 'teacher')->where('is_active', 1)->findAll(),
            'subjects' => $this->subjectModel->getActiveSubjects(),
            'classes' => $this->classModel->getActiveClasses(),
            'sessions' => $this->sessionModel->getActiveSessions(),
            'currentSession' => $currentSession,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'teacher_id' => 'required|integer',
                'subject_id' => 'required|integer',
                'class_id' => 'required|integer',
                'session_id' => 'required|integer'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/create_assignment', $data);
            }

            $teacherId = $this->request->getPost('teacher_id');
            $subjectId = $this->request->getPost('subject_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $assignedBy = $this->session->get('user_id');

            // Check if assignment already exists
            if ($this->assignmentModel->isTeacherAssigned($teacherId, $subjectId, $classId, $sessionId)) {
                session()->setFlashdata('error', 'This teacher is already assigned to this subject-class combination for the selected session.');
                return redirect()->back()->withInput();
            }

            if ($this->assignmentModel->assignTeacher($teacherId, $subjectId, $classId, $sessionId, $assignedBy)) {
                session()->setFlashdata('success', 'Teacher assignment created successfully!');
                return redirect()->to('/admin/assignments');
            } else {
                session()->setFlashdata('error', 'Failed to create assignment. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_assignment', $data);
    }

    public function deleteAssignment($id)
    {
        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to('/admin/assignments');
        }

        if ($this->assignmentModel->delete($id)) {
            session()->setFlashdata('success', 'Assignment deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete assignment.');
        }

        return redirect()->to('/admin/assignments');
    }

    // Academic Session Management
    public function sessions()
    {
        // Get current session and current term
        $currentSession = $this->sessionModel->getCurrentSession();
        $currentTerm = null;

        if ($currentSession) {
            $currentTerm = $this->termModel->where('session_id', $currentSession['id'])
                                          ->where('is_current', 1)
                                          ->first();
        }

        $data = [
            'title' => 'Academic Sessions - ExamExcel',
            'sessions' => $this->sessionModel->select('academic_sessions.*, COUNT(academic_terms.id) as term_count')
                                           ->join('academic_terms', 'academic_terms.session_id = academic_sessions.id', 'left')
                                           ->groupBy('academic_sessions.id')
                                           ->orderBy('academic_sessions.session_name', 'DESC')
                                           ->findAll(),
            'stats' => $this->sessionModel->getSessionStats(),
            'currentSession' => $currentSession,
            'currentTerm' => $currentTerm
        ];

        return view('admin/sessions', $data);
    }

    public function createSession()
    {
        $data = [
            'title' => 'Create Academic Session - ExamExcel',
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'session_name' => 'required|min_length[7]|max_length[20]|is_unique[academic_sessions.session_name]',
                'start_date' => 'required|valid_date',
                'end_date' => 'required|valid_date'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/create_session', $data);
            }

            $sessionData = [
                'session_name' => $this->request->getPost('session_name'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'is_current' => $this->request->getPost('is_current') ? 1 : 0,
                'is_active' => 1
            ];

            // If this is set as current, unset others
            if ($sessionData['is_current']) {
                $this->sessionModel->where('is_current', 1)->set(['is_current' => 0])->update();
            }

            $sessionId = $this->sessionModel->createSessionWithTerms($sessionData);

            if ($sessionId) {
                session()->setFlashdata('success', 'Academic session created successfully with 3 terms!');
                return redirect()->to('/admin/sessions');
            } else {
                session()->setFlashdata('error', 'Failed to create session. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/create_session', $data);
    }

    public function viewSession($id)
    {
        $session = $this->sessionModel->find($id);
        if (!$session) {
            session()->setFlashdata('error', 'Session not found.');
            return redirect()->to('/admin/sessions');
        }

        // Get terms for this session
        $termModel = new \App\Models\AcademicTermModel();
        $terms = $termModel->where('session_id', $id)->orderBy('term_number', 'ASC')->findAll();

        $data = [
            'title' => 'View Session - SRMS CBT System',
            'session' => $session,
            'terms' => $terms
        ];

        return view('admin/view_session', $data);
    }

    public function editSession($id)
    {
        $session = $this->sessionModel->find($id);
        if (!$session) {
            session()->setFlashdata('error', 'Session not found.');
            return redirect()->to('/admin/sessions');
        }

        $data = [
            'title' => 'Edit Session - SRMS CBT System',
            'session' => $session,
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'session_name' => "required|min_length[7]|max_length[20]|is_unique[academic_sessions.session_name,id,{$id}]",
                'start_date' => 'required|valid_date',
                'end_date' => 'required|valid_date'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/edit_session', $data);
            }

            $sessionData = [
                'session_name' => $this->request->getPost('session_name'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'is_current' => $this->request->getPost('is_current') ? 1 : 0
            ];

            // If this is set as current, unset others
            if ($sessionData['is_current']) {
                $this->sessionModel->where('is_current', 1)->set(['is_current' => 0])->update();
            }

            if ($this->sessionModel->update($id, $sessionData)) {
                session()->setFlashdata('success', 'Session updated successfully!');
                return redirect()->to('/admin/sessions');
            } else {
                session()->setFlashdata('error', 'Failed to update session. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        return view('admin/edit_session', $data);
    }

    public function deleteSession($id)
    {
        $session = $this->sessionModel->find($id);
        if (!$session) {
            session()->setFlashdata('error', 'Session not found.');
            return redirect()->to('/admin/sessions');
        }

        // Check if this is the current session
        if ($session['is_current']) {
            session()->setFlashdata('error', 'Cannot delete the current active session.');
            return redirect()->to('/admin/sessions');
        }

        if ($this->sessionModel->delete($id)) {
            session()->setFlashdata('success', 'Session deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete session.');
        }

        return redirect()->to('/admin/sessions');
    }

    public function setCurrentSession($id)
    {
        if ($this->sessionModel->setCurrentSession($id)) {
            session()->setFlashdata('success', 'Current session updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update current session.');
        }

        return redirect()->to('/admin/sessions');
    }

    public function setCurrentTerm($id)
    {
        $termModel = new \App\Models\AcademicTermModel();
        $term = $termModel->find($id);

        if (!$term) {
            session()->setFlashdata('error', 'Term not found.');
            return redirect()->to('/admin/sessions');
        }

        // Set all terms to not current (add WHERE clause to avoid database error)
        $termModel->where('is_current', 1)->set(['is_current' => 0])->update();

        // Set this term as current
        if ($termModel->update($id, ['is_current' => 1])) {
            session()->setFlashdata('success', 'Current term updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update current term.');
        }

        return redirect()->to('/admin/sessions');
    }

    // Settings helper methods
    private function getCurrentSettings()
    {
        try {
            // Load settings from database
            $settings = $this->settingsModel->getAllSettings();

            // If no settings found, return defaults
            if (empty($settings)) {
                return $this->getDefaultSettings();
            }

            // Decrypt provider-specific API keys if present
            $apiKeyFields = ['openai_api_key', 'gemini_api_key', 'claude_api_key', 'groq_api_key', 'huggingface_api_key'];
            foreach ($apiKeyFields as $field) {
                if (!empty($settings[$field])) {
                    $settings[$field] = $this->decryptApiKey($settings[$field]);
                }
            }

            // For backward compatibility, also decrypt old ai_api_key if present
            if (!empty($settings['ai_api_key'])) {
                $settings['ai_api_key'] = $this->decryptApiKey($settings['ai_api_key']);
            }

            // Merge with defaults to ensure all keys exist
            return array_merge($this->getDefaultSettings(), $settings);
        } catch (\Exception $e) {
            log_message('error', 'Failed to load settings: ' . $e->getMessage());
            return $this->getDefaultSettings();
        }
    }

    private function getDefaultSettings()
    {
        return [
            'system_name' => 'ExamExcel',
            'system_version' => '1.0.0',
            'institution_name' => 'ExamExcel',
            'default_exam_duration' => 80,
            'default_max_attempts' => 5,
            'auto_submit_on_time_up' => true,
            'backup_frequency' => 'weekly',
            'backup_retention_days' => 30,
            'app_locked' => false,
            'locked_roles' => [],
            'news_flash_enabled' => false,
            'news_flash_content' => '',
            'logo_path' => '',
            'favicon_path' => '',
            'calculator_enabled' => true,
            'exam_pause_enabled' => false,
            'student_id_prefix' => 'STD',
            'browser_lockdown' => false,
            'prevent_copy_paste' => false,
            'disable_right_click' => false,
            'require_proctoring' => false,
            // AI Settings
            'ai_generation_enabled' => false,
            'ai_model_provider' => '',
            'ai_model' => '',
            'openai_api_key' => '',
            'gemini_api_key' => '',
            'claude_api_key' => '',
            'groq_api_key' => '',
            'huggingface_api_key' => '',
            'ai_model' => '',
            'ai_api_key' => ''
        ];
    }

    private function handleFileUpload($file, $folder)
    {
        try {
            $uploadPath = FCPATH . 'uploads/' . $folder;

            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $fileName = time() . '_' . $file->getRandomName();

            if ($file->move($uploadPath, $fileName)) {
                return 'uploads/' . $folder . '/' . $fileName;
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'File upload failed: ' . $e->getMessage());
            return false;
        }
    }

    public function createBackup()
    {
        try {
            $backupPath = WRITEPATH . 'backups';

            // Create backup directory if it doesn't exist
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $timestamp = date('Y-m-d_H-i-s');
            $backupFile = $backupPath . '/backup_' . $timestamp . '.sql';

            // Get database configuration
            $db = \Config\Database::connect();

            // Get database name from environment or config
            $database = env('database.default.database');
            if (empty($database)) {
                // Try to get from connection
                $database = $db->getDatabase();
                if (empty($database)) {
                    throw new \Exception('Database name not configured. Please check your .env file and ensure database.default.database is set.');
                }
            }

            // Test database connection first
            if (!$this->testDatabaseConnection($database)) {
                throw new \Exception('Cannot connect to database. Please check your database configuration.');
            }

            // Try different backup methods
            $backupCreated = false;
            $errorMessages = [];

            // Method 1: Try mysqldump if available
            if ($this->isMysqldumpAvailable()) {
                $password = $db->password ? "-p\"{$db->password}\"" : '';
                $command = "mysqldump -h \"{$db->hostname}\" -u \"{$db->username}\" {$password} \"{$database}\" > \"{$backupFile}\" 2>&1";

                exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($backupFile) && filesize($backupFile) > 0) {
                    $backupCreated = true;
                } else {
                    $errorMessages[] = 'mysqldump failed (return code: ' . $returnCode . '): ' . implode(' ', $output);
                }
            } else {
                $errorMessages[] = 'mysqldump command not available in system PATH';
            }

            // Method 2: PHP-based backup if mysqldump failed
            if (!$backupCreated) {
                if ($this->createPhpBackup($backupFile, $database)) {
                    $backupCreated = true;
                } else {
                    $errorMessages[] = 'PHP backup method failed';
                }
            }

            if ($backupCreated) {
                $fileSize = $this->formatFileSize(filesize($backupFile));
                session()->setFlashdata('success', "Backup created successfully! File: backup_{$timestamp}.sql ({$fileSize})");
                log_message('info', "Backup created successfully: {$backupFile} ({$fileSize})");
            } else {
                $errorMessage = 'Failed to create backup. Errors: ' . implode('; ', $errorMessages);
                $debugInfo = [
                    'database' => $database,
                    'hostname' => $db->hostname,
                    'username' => $db->username,
                    'mysqldump_available' => $this->isMysqldumpAvailable() ? 'Yes' : 'No',
                    'backup_path' => $backupPath,
                    'writable' => is_writable($backupPath) ? 'Yes' : 'No'
                ];
                log_message('error', $errorMessage . ' Debug info: ' . json_encode($debugInfo));
                session()->setFlashdata('error', $errorMessage . ' Please check the system logs for more details.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Backup creation failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to create backup: ' . $e->getMessage());
        }

        return redirect()->to('/admin/backup');
    }

    public function clearCache()
    {
        try {
            // Clear CodeIgniter cache
            $cache = \Config\Services::cache();
            $cache->clean();

            // Clear writable cache directories
            $cacheDirs = [
                WRITEPATH . 'cache',
                WRITEPATH . 'session',
                WRITEPATH . 'logs'
            ];

            foreach ($cacheDirs as $dir) {
                if (is_dir($dir)) {
                    $this->clearDirectory($dir);
                }
            }

            session()->setFlashdata('success', 'System cache cleared successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Cache clearing failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to clear cache: ' . $e->getMessage());
        }

        return redirect()->to('/admin/settings');
    }

    private function clearDirectory($dir)
    {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                $this->clearDirectory($file);
                rmdir($file);
            }
        }
    }

    public function toggleAppLock()
    {
        try {
            $currentStatus = $this->settingsModel->getSetting('app_locked', false);
            $newStatus = !$currentStatus;

            if ($this->settingsModel->setSetting('app_locked', $newStatus, 'boolean')) {
                $statusText = $newStatus ? 'locked' : 'unlocked';
                session()->setFlashdata('success', "Application {$statusText} successfully!");

                // If locking the app, force logout of specified roles
                if ($newStatus) {
                    $this->forceLogoutLockedRoles();
                }
            } else {
                session()->setFlashdata('error', 'Failed to update app lock status.');
            }
        } catch (\Exception $e) {
            log_message('error', 'App lock toggle failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to toggle app lock: ' . $e->getMessage());
        }

        return redirect()->to('/admin/settings');
    }

    public function backup()
    {
        try {
            $backupPath = WRITEPATH . 'backups';

            // Create backup directory if it doesn't exist
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            // Get list of existing backup files
            $backupFiles = [];
            if (is_dir($backupPath)) {
                $files = scandir($backupPath);
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                        $filePath = $backupPath . '/' . $file;
                        $backupFiles[] = [
                            'name' => $file,
                            'size' => $this->formatFileSize(filesize($filePath)),
                            'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                            'timestamp' => filemtime($filePath)
                        ];
                    }
                }
            }

            // Sort by timestamp (newest first)
            usort($backupFiles, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });

            // Get backup settings
            $settings = $this->settingsModel->getAllSettings();

            $data = [
                'title' => 'Backup & Restore - ExamExcel',
                'pageTitle' => 'Backup & Restore',
                'pageSubtitle' => 'Manage database backups and system restoration',
                'backupFiles' => $backupFiles,
                'settings' => $settings,
                'backupPath' => $backupPath
            ];

            return view('admin/backup', $data);
        } catch (\Exception $e) {
            log_message('error', 'Backup page failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load backup page: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        try {
            $backupPath = WRITEPATH . 'backups/' . $filename;

            if (!file_exists($backupPath) || pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
                session()->setFlashdata('error', 'Backup file not found.');
                return redirect()->to('/admin/backup');
            }

            return $this->response->download($backupPath, null);
        } catch (\Exception $e) {
            log_message('error', 'Backup download failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to download backup: ' . $e->getMessage());
            return redirect()->to('/admin/backup');
        }
    }

    public function deleteBackup($filename)
    {
        try {
            $backupPath = WRITEPATH . 'backups/' . $filename;

            if (!file_exists($backupPath) || pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
                session()->setFlashdata('error', 'Backup file not found.');
                return redirect()->to('/admin/backup');
            }

            if (unlink($backupPath)) {
                session()->setFlashdata('success', 'Backup file deleted successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to delete backup file.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Backup deletion failed: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to delete backup: ' . $e->getMessage());
        }

        return redirect()->to('/admin/backup');
    }

    /**
     * Test database connection
     */
    private function testDatabaseConnection($database)
    {
        try {
            $db = \Config\Database::connect();
            $result = $db->query("SELECT 1");
            return $result !== false;
        } catch (\Exception $e) {
            log_message('error', 'Database connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if mysqldump is available
     */
    private function isMysqldumpAvailable()
    {
        $command = 'mysqldump --version 2>&1';
        exec($command, $output, $returnCode);
        return $returnCode === 0;
    }

    /**
     * Create backup using PHP (fallback method)
     */
    private function createPhpBackup($backupFile, $database)
    {
        try {
            $db = \Config\Database::connect();

            // Get all tables
            $tables = $db->listTables();

            if (empty($tables)) {
                return false;
            }

            $backup = "-- ExamExcel Database Backup\n";
            $backup .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
            $backup .= "-- Database: {$database}\n\n";
            $backup .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                // Get table structure
                $createTable = $db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
                if ($createTable) {
                    $backup .= "-- Table structure for table `{$table}`\n";
                    $backup .= "DROP TABLE IF EXISTS `{$table}`;\n";
                    $backup .= $createTable['Create Table'] . ";\n\n";
                }

                // Get table data
                $rows = $db->query("SELECT * FROM `{$table}`")->getResultArray();
                if (!empty($rows)) {
                    $backup .= "-- Dumping data for table `{$table}`\n";

                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . $db->escapeString($value) . "'";
                            }
                        }
                        $backup .= "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backup .= "\n";
                }
            }

            $backup .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Write backup to file
            return file_put_contents($backupFile, $backup) !== false;
        } catch (\Exception $e) {
            log_message('error', 'PHP backup failed: ' . $e->getMessage());
            return false;
        }
    }

    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    private function forceLogoutLockedRoles()
    {
        try {
            $lockedRoles = $this->settingsModel->getSetting('locked_roles', []);

            if (!empty($lockedRoles)) {
                log_message('info', 'App locked - forcing logout for roles: ' . implode(', ', $lockedRoles));

                // Force logout users with locked roles
                $result = $this->sessionManager->forceLogoutByRole($lockedRoles);

                if ($result) {
                    log_message('info', 'Force logout completed: ' . json_encode($result));
                } else {
                    log_message('error', 'Force logout failed');
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Force logout failed: ' . $e->getMessage());
        }
    }

    // Subject-Class Assignments Management
    public function subjectAssignments()
    {
        $assignments = $this->subjectClassAssignmentModel->getAssignmentsWithDetails();
        $stats = $this->subjectClassAssignmentModel->getAssignmentStats();
        $groupedByLevel = $this->subjectClassAssignmentModel->getSubjectsByClassLevel();

        $data = [
            'title' => 'Subject-Class Assignments - SRMS CBT System',
            'pageTitle' => 'Subject-Class Assignments',
            'pageSubtitle' => 'Assign subjects to classes based on academic level',
            'assignments' => $assignments,
            'stats' => $stats,
            'groupedByLevel' => $groupedByLevel
        ];

        return view('admin/subject_assignments', $data);
    }

    public function createSubjectAssignment()
    {
        $data = [
            'title' => 'Create Subject Assignment - SRMS CBT System',
            'subjects' => $this->subjectModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll(),
            'classes' => $this->classModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll(),
            'existingAssignments' => $this->subjectClassAssignmentModel->getExistingAssignmentsMap(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            $subjectIds = $this->request->getPost('subject_ids');
            $classIds = $this->request->getPost('class_ids');

            $rules = [
                'subject_ids' => 'required',
                'class_ids' => 'required'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('admin/create_subject_assignment', $data);
            }

            if (!is_array($subjectIds)) {
                $subjectIds = [$subjectIds];
            }

            if (!is_array($classIds)) {
                $classIds = [$classIds];
            }

            $successCount = 0;
            $duplicateCount = 0;
            $totalAssignments = count($subjectIds) * count($classIds);

            // Loop through each subject and assign to each class
            foreach ($subjectIds as $subjectId) {
                foreach ($classIds as $classId) {
                    if ($this->subjectClassAssignmentModel->isAssigned($subjectId, $classId)) {
                        $duplicateCount++;
                    } else {
                        if ($this->subjectClassAssignmentModel->assignSubjectToClass($subjectId, $classId)) {
                            $successCount++;
                        }
                    }
                }
            }

            if ($successCount > 0) {
                $subjectCount = count($subjectIds);
                $classCount = count($classIds);
                $message = "Successfully created {$successCount} assignment(s) for {$subjectCount} subject(s) across {$classCount} class(es).";
                if ($duplicateCount > 0) {
                    $message .= " {$duplicateCount} assignment(s) already existed.";
                }
                session()->setFlashdata('success', $message);
            } else {
                session()->setFlashdata('error', 'No new assignments were created. All selected assignments already exist.');
            }

            return redirect()->to('/admin/subject-assignments');
        }

        return view('admin/create_subject_assignment', $data);
    }

    public function deleteSubjectAssignment($id)
    {
        $assignment = $this->subjectClassAssignmentModel->find($id);
        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to('/admin/subject-assignments');
        }

        if ($this->subjectClassAssignmentModel->delete($id)) {
            session()->setFlashdata('success', 'Subject assignment removed successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to remove assignment. Please try again.');
        }

        return redirect()->to('/admin/subject-assignments');
    }

    public function bulkAssignSubjects()
    {
        $subjectIds = $this->request->getPost('subject_ids');
        $classId = $this->request->getPost('class_id');

        if (empty($subjectIds) || empty($classId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select subjects and a class.'
            ]);
        }

        $successCount = 0;
        $duplicateCount = 0;

        foreach ($subjectIds as $subjectId) {
            if ($this->subjectClassAssignmentModel->isAssigned($subjectId, $classId)) {
                $duplicateCount++;
            } else {
                if ($this->subjectClassAssignmentModel->assignSubjectToClass($subjectId, $classId)) {
                    $successCount++;
                }
            }
        }

        $message = "Successfully assigned {$successCount} subject(s) to class.";
        if ($duplicateCount > 0) {
            $message .= " {$duplicateCount} assignment(s) already existed.";
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Remove individual subject from exam configuration
     */
    public function removeExamSubject($examId, $subjectId)
    {
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        try {
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Exam not found'
                ]);
            }

            if ($exam['exam_mode'] !== 'multi_subject') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This operation is only available for multi-subject exams'
                ]);
            }

            $examSubjectModel = new \App\Models\ExamSubjectModel();
            $examQuestionModel = new \App\Models\ExamQuestionModel();

            // Get subject details before removal
            $subjectModel = new \App\Models\SubjectModel();
            $subject = $subjectModel->find($subjectId);
            $subjectName = $subject ? $subject['name'] : 'Unknown Subject';

            // Remove the specific subject from exam
            $removed = $examSubjectModel->removeSubjectsFromExam($examId, [$subjectId]);

            if ($removed) {
                // Also remove any questions assigned to this subject for this exam
                $examQuestionModel->where('exam_id', $examId)
                                 ->where('subject_id', $subjectId)
                                 ->delete();

                // Recalculate exam totals
                $this->recalculateExamTotals($examId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Subject '{$subjectName}' has been removed from the exam configuration."
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove subject from exam'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error removing exam subject: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while removing the subject. Please try again.'
            ]);
        }
    }

    /**
     * Recalculate exam totals after subject changes
     */
    private function recalculateExamTotals($examId)
    {
        $examSubjectModel = new \App\Models\ExamSubjectModel();

        $totalMarks = $examSubjectModel->getExamTotalMarks($examId);
        $totalQuestions = $examSubjectModel->getExamTotalQuestions($examId);
        $totalTime = $examSubjectModel->getExamTotalTime($examId);

        // Update exam with new totals
        $this->examModel->update($examId, [
            'total_marks' => $totalMarks,
            'total_questions' => $totalQuestions,
            'duration_minutes' => $totalTime > 0 ? $totalTime : $this->examModel->find($examId)['duration_minutes']
        ]);
    }

    public function removeSubjectAssignment()
    {
        $subjectId = $this->request->getPost('subject_id');
        $classId = $this->request->getPost('class_id');

        if (empty($subjectId) || empty($classId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid assignment data.'
            ]);
        }

        if ($this->subjectClassAssignmentModel->removeAssignment($subjectId, $classId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Assignment removed successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to remove assignment.'
            ]);
        }
    }

    /**
     * Activity Log page
     */
    public function activityLog()
    {
        // Get filter parameters
        $userRole = $this->request->getGet('user_role');
        $actionType = $this->request->getGet('action_type');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $userId = $this->request->getGet('user_id');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 20;

        // Build filters array
        $filters = array_filter([
            'user_role' => $userRole,
            'action_type' => $actionType,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'user_id' => $userId
        ]);

        // Get paginated activity data
        $activityData = $this->getSystemActivitiesPaginated($filters, $page, $perPage);

        // Get activity statistics
        $stats = $this->getActivityStats($dateFrom, $dateTo);

        // Get users for filter dropdown
        $users = $this->userModel->select('id, first_name, last_name, email, role')
                                ->where('is_active', 1)
                                ->orderBy('first_name', 'ASC')
                                ->findAll();

        $data = [
            'title' => 'Activity Log - ExamExcel',
            'pageTitle' => 'System Activity Log',
            'pageSubtitle' => 'Monitor user activities and system events',
            'activities' => $activityData['activities'],
            'stats' => $stats,
            'users' => $users,
            'filters' => [
                'user_role' => $userRole,
                'action_type' => $actionType,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'user_id' => $userId
            ],
            'pager' => $activityData['pager'],
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalActivities' => $activityData['total']
        ];

        return view('admin/activity_log', $data);
    }

    /**
     * Get system activities from various sources
     */
    private function getSystemActivities($filters = [])
    {
        $activities = [];

        // Get user registrations
        $userBuilder = $this->userModel->select('id, first_name, last_name, email, role, created_at, "user_registration" as activity_type')
                                     ->orderBy('created_at', 'DESC');

        if (!empty($filters['user_role'])) {
            $userBuilder->where('role', $filters['user_role']);
        }

        if (!empty($filters['date_from'])) {
            $userBuilder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $userBuilder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['user_id'])) {
            $userBuilder->where('id', $filters['user_id']);
        }

        $userActivities = $userBuilder->limit(20)->findAll();

        foreach ($userActivities as $user) {
            $activities[] = [
                'id' => 'user_' . $user['id'],
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'activity_type' => 'User Registration',
                'description' => 'New ' . ucfirst($user['role']) . ' account created',
                'created_at' => $user['created_at'],
                'status' => 'success',
                'ip_address' => 'N/A'
            ];
        }

        // Get exam attempts
        $attemptModel = new \App\Models\ExamAttemptModel();
        $attemptBuilder = $attemptModel->select('exam_attempts.*, users.first_name, users.last_name, users.email, users.role, exams.title as exam_title')
                                     ->join('users', 'users.id = exam_attempts.student_id')
                                     ->join('exams', 'exams.id = exam_attempts.exam_id')
                                     ->orderBy('exam_attempts.created_at', 'DESC');

        if (!empty($filters['user_role']) && $filters['user_role'] === 'student') {
            // Only include if filtering for students
        } elseif (!empty($filters['user_role']) && $filters['user_role'] !== 'student') {
            // Skip exam attempts if filtering for non-students
            $attemptBuilder->where('1', '0'); // This will return no results
        }

        if (!empty($filters['date_from'])) {
            $attemptBuilder->where('exam_attempts.created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $attemptBuilder->where('exam_attempts.created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['user_id'])) {
            $attemptBuilder->where('exam_attempts.student_id', $filters['user_id']);
        }

        $examAttempts = $attemptBuilder->limit(15)->findAll();

        foreach ($examAttempts as $attempt) {
            $activities[] = [
                'id' => 'attempt_' . $attempt['id'],
                'user_id' => $attempt['student_id'],
                'user_name' => $attempt['first_name'] . ' ' . $attempt['last_name'],
                'user_email' => $attempt['email'],
                'user_role' => $attempt['role'],
                'activity_type' => 'Exam Attempt',
                'description' => 'Started exam: ' . $attempt['exam_title'],
                'created_at' => $attempt['created_at'],
                'status' => in_array($attempt['status'], ['submitted', 'auto_submitted', 'completed']) ? 'success' : 'in_progress',
                'ip_address' => $attempt['ip_address'] ?? 'N/A'
            ];
        }

        // Sort all activities by date
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Apply action type filter
        if (!empty($filters['action_type'])) {
            $activities = array_filter($activities, function($activity) use ($filters) {
                return stripos($activity['activity_type'], $filters['action_type']) !== false;
            });
        }

        return array_slice($activities, 0, 50); // Limit to 50 most recent activities
    }

    /**
     * Get paginated system activities from various sources
     */
    private function getSystemActivitiesPaginated($filters = [], $page = 1, $perPage = 20)
    {
        $activities = [];
        $offset = ($page - 1) * $perPage;

        // Get user registrations
        $userBuilder = $this->userModel->select('id, first_name, last_name, email, role, created_at, "user_registration" as activity_type')
                                     ->orderBy('created_at', 'DESC');

        if (!empty($filters['user_role'])) {
            $userBuilder->where('role', $filters['user_role']);
        }

        if (!empty($filters['date_from'])) {
            $userBuilder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $userBuilder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['user_id'])) {
            $userBuilder->where('id', $filters['user_id']);
        }

        $userActivities = $userBuilder->findAll();

        foreach ($userActivities as $user) {
            $activities[] = [
                'id' => 'user_' . $user['id'],
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'activity_type' => 'User Registration',
                'description' => 'New ' . ucfirst($user['role']) . ' account created',
                'created_at' => $user['created_at'],
                'status' => 'success',
                'ip_address' => 'N/A',
                'sort_date' => strtotime($user['created_at'])
            ];
        }

        // Get exam attempts
        $attemptBuilder = $this->db->table('exam_attempts ea')
                                  ->select('ea.id, ea.student_id, ea.exam_id, ea.status, ea.created_at, ea.ip_address,
                                           u.first_name, u.last_name, u.email, u.role,
                                           e.title as exam_title')
                                  ->join('users u', 'u.id = ea.student_id')
                                  ->join('exams e', 'e.id = ea.exam_id')
                                  ->orderBy('ea.created_at', 'DESC');

        if (!empty($filters['user_role'])) {
            $attemptBuilder->where('u.role', $filters['user_role']);
        }

        if (!empty($filters['date_from'])) {
            $attemptBuilder->where('ea.created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $attemptBuilder->where('ea.created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['user_id'])) {
            $attemptBuilder->where('ea.student_id', $filters['user_id']);
        }

        $examAttempts = $attemptBuilder->get()->getResultArray();

        foreach ($examAttempts as $attempt) {
            $activities[] = [
                'id' => 'attempt_' . $attempt['id'],
                'user_id' => $attempt['student_id'],
                'user_name' => $attempt['first_name'] . ' ' . $attempt['last_name'],
                'user_email' => $attempt['email'],
                'user_role' => $attempt['role'],
                'activity_type' => 'Exam Attempt',
                'description' => 'Started exam: ' . $attempt['exam_title'],
                'created_at' => $attempt['created_at'],
                'status' => in_array($attempt['status'], ['submitted', 'auto_submitted', 'completed']) ? 'success' : 'in_progress',
                'ip_address' => $attempt['ip_address'] ?? 'N/A',
                'sort_date' => strtotime($attempt['created_at'])
            ];
        }

        // Sort all activities by date
        usort($activities, function($a, $b) {
            return $b['sort_date'] - $a['sort_date'];
        });

        // Apply action type filter
        if (!empty($filters['action_type'])) {
            $activities = array_filter($activities, function($activity) use ($filters) {
                return stripos($activity['activity_type'], $filters['action_type']) !== false;
            });
        }

        // Get total count
        $total = count($activities);

        // Apply pagination
        $paginatedActivities = array_slice($activities, $offset, $perPage);

        // Create pagination object
        $pager = \Config\Services::pager();
        $pager->store('default', $page, $perPage, $total);

        return [
            'activities' => $paginatedActivities,
            'total' => $total,
            'pager' => $pager
        ];
    }

    /**
     * Get activity statistics
     */
    private function getActivityStats($dateFrom = null, $dateTo = null)
    {
        $stats = [
            'total_activities' => 0,
            'user_registrations' => 0,
            'exam_attempts' => 0,
            'today_activities' => 0,
            'active_users' => 0
        ];

        // Build date conditions
        $dateConditions = [];
        if ($dateFrom) {
            $dateConditions[] = "created_at >= '{$dateFrom} 00:00:00'";
        }
        if ($dateTo) {
            $dateConditions[] = "created_at <= '{$dateTo} 23:59:59'";
        }
        $dateWhere = !empty($dateConditions) ? 'WHERE ' . implode(' AND ', $dateConditions) : '';

        // Get user registration stats
        $userQuery = "SELECT COUNT(*) as count FROM users";
        if ($dateWhere) {
            $userQuery .= " {$dateWhere}";
        }
        $userResult = $this->db->query($userQuery)->getRow();
        $stats['user_registrations'] = $userResult->count ?? 0;

        // Get exam attempt stats
        $attemptQuery = "SELECT COUNT(*) as count FROM exam_attempts";
        if ($dateWhere) {
            $attemptQuery .= " {$dateWhere}";
        }
        $attemptResult = $this->db->query($attemptQuery)->getRow();
        $stats['exam_attempts'] = $attemptResult->count ?? 0;

        // Total activities
        $stats['total_activities'] = $stats['user_registrations'] + $stats['exam_attempts'];

        // Today's activities
        $today = date('Y-m-d');
        $todayUserQuery = "SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = '{$today}'";
        $todayAttemptQuery = "SELECT COUNT(*) as count FROM exam_attempts WHERE DATE(created_at) = '{$today}'";

        $todayUsers = $this->db->query($todayUserQuery)->getRow();
        $todayAttempts = $this->db->query($todayAttemptQuery)->getRow();

        $stats['today_activities'] = ($todayUsers->count ?? 0) + ($todayAttempts->count ?? 0);

        // Active users (logged in within last 24 hours)
        $stats['active_users'] = $this->userModel->where('last_login >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                                                ->where('is_active', 1)
                                                ->countAllResults();

        return $stats;
    }

    /**
     * System Information page
     */
    public function systemInfo()
    {
        // Get system information
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'codeigniter_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => date_default_timezone_get(),
            'current_time' => date('Y-m-d H:i:s'),
            'disk_space' => $this->getDiskSpaceInfo(),
            'database_size' => $this->getDatabaseSize()
        ];

        // Get performance metrics
        $performanceMetrics = [
            'total_users' => $this->userModel->countAllResults(),
            'total_exams' => $this->db->table('exams')->countAllResults(),
            'total_questions' => $this->db->table('questions')->countAllResults(),
            'total_attempts' => $this->db->table('exam_attempts')->countAllResults(),
            'database_tables' => $this->getDatabaseTables(),
            'cache_status' => $this->getCacheStatus()
        ];

        $data = [
            'title' => 'System Information - ExamExcel',
            'pageTitle' => 'System Information',
            'pageSubtitle' => 'Detailed system metrics and configuration',
            'systemInfo' => $systemInfo,
            'performanceMetrics' => $performanceMetrics
        ];

        return view('admin/system_info', $data);
    }

    /**
     * Get database version
     */
    private function getDatabaseVersion()
    {
        try {
            $result = $this->db->query('SELECT VERSION() as version')->getRow();
            return $result->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Get disk space information
     */
    private function getDiskSpaceInfo()
    {
        try {
            $bytes = disk_free_space(FCPATH);
            $totalBytes = disk_total_space(FCPATH);

            return [
                'free' => $this->formatBytes($bytes),
                'total' => $this->formatBytes($totalBytes),
                'used_percentage' => round((($totalBytes - $bytes) / $totalBytes) * 100, 2)
            ];
        } catch (\Exception $e) {
            return [
                'free' => 'Unknown',
                'total' => 'Unknown',
                'used_percentage' => 0
            ];
        }
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $database = $this->db->getDatabase();
            $result = $this->db->query("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = '{$database}'
            ")->getRow();

            return $result->size_mb . ' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Get database tables count
     */
    private function getDatabaseTables()
    {
        try {
            $database = $this->db->getDatabase();
            $result = $this->db->query("
                SELECT COUNT(*) as table_count
                FROM information_schema.tables
                WHERE table_schema = '{$database}'
            ")->getRow();

            return $result->table_count ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get cache status
     */
    private function getCacheStatus()
    {
        try {
            $cache = \Config\Services::cache();
            return $cache->isSupported() ? 'Enabled' : 'Disabled';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Practice Questions Management
     */
    public function practiceQuestions()
    {
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();

        // Get all practice questions with pagination
        $perPage = 20;
        $page = $this->request->getGet('page') ?? 1;
        $category = $this->request->getGet('category');

        $practiceQuestions = $practiceQuestionModel->getPaginatedQuestions($perPage, $page, $category);
        $pager = $practiceQuestionModel->pager;

        // Get statistics
        $stats = $practiceQuestionModel->getPracticeStatistics();

        $data = [
            'title' => 'Practice Questions Management - ' . get_app_name(),
            'pageTitle' => 'Practice Questions',
            'pageSubtitle' => 'Manage general practice questions for students',
            'practiceQuestions' => $practiceQuestions,
            'pager' => $pager,
            'stats' => $stats,
            'currentCategory' => $category
        ];

        return view('admin/practice_questions', $data);
    }

    /**
     * Test endpoint for practice questions
     */
    public function testPracticeQuestions()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Test endpoint working!',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Create practice question form
     */
    public function createPracticeQuestion()
    {
        $data = [
            'title' => 'Create Practice Question - ' . get_app_name(),
            'pageTitle' => 'Create Practice Question',
            'pageSubtitle' => 'Add a new practice question for students'
        ];

        return view('admin/create_practice_question', $data);
    }

    /**
     * Store practice question
     */
    public function storePracticeQuestion()
    {
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();

        $data = [
            'category' => $this->request->getPost('category'),
            'question_text' => $this->request->getPost('question_text'),
            'option_a' => $this->request->getPost('option_a'),
            'option_b' => $this->request->getPost('option_b'),
            'option_c' => $this->request->getPost('option_c'),
            'option_d' => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'explanation' => $this->request->getPost('explanation'),
            'difficulty' => $this->request->getPost('difficulty'),
            'points' => $this->request->getPost('points') ?: 1,
            'created_by' => $this->session->get('user_id')
        ];

        if ($practiceQuestionModel->insert($data)) {
            $this->session->setFlashdata('success', 'Practice question created successfully!');
            return redirect()->to('/admin/practice-questions');
        } else {
            $this->session->setFlashdata('error', 'Failed to create practice question. Please check your input.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Edit practice question
     */
    public function editPracticeQuestion($id)
    {
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();

        $question = $practiceQuestionModel->find($id);
        if (!$question) {
            $this->session->setFlashdata('error', 'Practice question not found.');
            return redirect()->to('/admin/practice-questions');
        }

        $data = [
            'title' => 'Edit Practice Question - ' . get_app_name(),
            'pageTitle' => 'Edit Practice Question',
            'pageSubtitle' => 'Update practice question details',
            'question' => $question
        ];

        return view('admin/edit_practice_question', $data);
    }

    /**
     * Update practice question
     */
    public function updatePracticeQuestion($id)
    {
        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();

        $question = $practiceQuestionModel->find($id);
        if (!$question) {
            $this->session->setFlashdata('error', 'Practice question not found.');
            return redirect()->to('/admin/practice-questions');
        }

        $data = [
            'category' => $this->request->getPost('category'),
            'question_text' => $this->request->getPost('question_text'),
            'option_a' => $this->request->getPost('option_a'),
            'option_b' => $this->request->getPost('option_b'),
            'option_c' => $this->request->getPost('option_c'),
            'option_d' => $this->request->getPost('option_d'),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'explanation' => $this->request->getPost('explanation'),
            'difficulty' => $this->request->getPost('difficulty'),
            'points' => $this->request->getPost('points') ?: 1
        ];

        if ($practiceQuestionModel->update($id, $data)) {
            $this->session->setFlashdata('success', 'Practice question updated successfully!');
            return redirect()->to('/admin/practice-questions');
        } else {
            $this->session->setFlashdata('error', 'Failed to update practice question. Please check your input.');
            return redirect()->back()->withInput();
        }
    }

    // generateSamplePracticeQuestions method removed - questions are pre-loaded via migration

    /**
     * Delete practice question
     */
    public function deletePracticeQuestion($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/practice-questions');
        }

        $practiceQuestionModel = new \App\Models\PracticeQuestionModel();

        $question = $practiceQuestionModel->find($id);
        if (!$question) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Practice question not found.'
            ]);
        }

        try {
            if ($practiceQuestionModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Practice question deleted successfully!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete practice question.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to delete practice question: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the question.'
            ]);
        }
    }

    // getSampleQuestionsData method removed - questions are pre-loaded via migration

    /**
     * Violation Management Dashboard
     */
    public function violations()
    {
        $violationModel = new \App\Models\ViolationModel();
        $securityLogModel = new \App\Models\SecurityLogModel();
        $userModel = new UserModel();

        // Get filters
        $studentId = $this->request->getGet('student_id');
        $punishmentType = $this->request->getGet('punishment_type');
        $severity = $this->request->getGet('severity');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Get security events (all violations)
        $securityEvents = $securityLogModel->getSecurityLogs([
            'severity' => $severity,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ]);

        // Get applied punishments
        $violations = $violationModel->getStudentViolations($studentId, 100);

        // Get violation statistics from security logs
        $violationStats = $this->getSecurityViolationStats($dateFrom, $dateTo);

        // Get top violators from security logs
        $topViolators = $securityLogModel->getTopViolators(10);

        // Get recent security events as violations
        $recentViolations = $this->getRecentSecurityEvents(15);

        // Get students for filter dropdown
        $students = $userModel->where('role', 'student')
                             ->where('is_active', 1)
                             ->orderBy('first_name', 'ASC')
                             ->findAll();

        // Get suspended/banned students
        $suspendedStudents = $userModel->where('role', 'student')
                                     ->where('is_active', 1)
                                     ->groupStart()
                                         ->where('exam_banned', 1)
                                         ->orWhere('exam_suspended_until >', date('Y-m-d H:i:s'))
                                     ->groupEnd()
                                     ->findAll();

        $data = [
            'title' => 'Violation Management - ExamExcel',
            'pageTitle' => 'Security Violations',
            'pageSubtitle' => 'Monitor and manage student security violations',
            'violations' => $violations,
            'securityEvents' => $securityEvents,
            'violationStats' => $violationStats,
            'topViolators' => $topViolators,
            'recentViolations' => $recentViolations,
            'students' => $students,
            'suspendedStudents' => $suspendedStudents,
            'filters' => [
                'student_id' => $studentId,
                'punishment_type' => $punishmentType,
                'severity' => $severity,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ],
            'punishmentTypes' => [
                'warning' => 'Warning',
                'temporary_suspension' => 'Temporary Suspension',
                'permanent_ban' => 'Permanent Ban'
            ],
            'severityLevels' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'critical' => 'Critical'
            ]
        ];

        return view('admin/violations', $data);
    }

    /**
     * Get security violation statistics
     */
    private function getSecurityViolationStats($dateFrom = null, $dateTo = null)
    {
        $securityLogModel = new \App\Models\SecurityLogModel();

        $builder = $securityLogModel->select('
            COUNT(*) as total_violations,
            SUM(CASE WHEN severity = "low" THEN 1 ELSE 0 END) as low_violations,
            SUM(CASE WHEN severity = "medium" THEN 1 ELSE 0 END) as medium_violations,
            SUM(CASE WHEN severity = "high" THEN 1 ELSE 0 END) as high_violations,
            SUM(CASE WHEN severity = "critical" THEN 1 ELSE 0 END) as critical_violations
        ');

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $stats = $builder->first();

        // Add punishment stats from violation model
        $violationModel = new \App\Models\ViolationModel();
        $punishmentStats = $violationModel->getViolationStats($dateFrom, $dateTo);

        return array_merge($stats ?: [], $punishmentStats ?: []);
    }

    /**
     * Get recent security events formatted as violations
     */
    private function getRecentSecurityEvents($limit = 15)
    {
        $securityLogModel = new \App\Models\SecurityLogModel();

        return $securityLogModel->select('
                security_logs.*,
                users.id as student_id,
                users.first_name,
                users.last_name,
                COALESCE(users.student_id, users.username) as student_number,
                exams.title as exam_title,
                "security_event" as punishment_type
            ')
            ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id')
            ->join('users', 'users.id = exam_attempts.student_id')
            ->join('exams', 'exams.id = exam_attempts.exam_id')
            ->whereIn('security_logs.severity', ['high', 'critical'])
            ->orderBy('security_logs.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * View student violation details
     */
    public function studentViolations($studentId)
    {
        $violationModel = new \App\Models\ViolationModel();
        $userModel = new UserModel();
        $securityLogModel = new \App\Models\SecurityLogModel();

        // Try to find student by ID first (numeric), then by student_id (string)
        if (is_numeric($studentId)) {
            $student = $userModel->find($studentId);
        } else {
            $student = $userModel->where('student_id', $studentId)
                                ->where('role', 'student')
                                ->first();
        }

        if (!$student || $student['role'] !== 'student') {
            return redirect()->to('/admin/violations')->with('error', 'Student not found');
        }

        // Use the actual user ID for database queries
        $actualStudentId = $student['id'];

        // Get student's violation history
        $violations = $violationModel->getStudentViolationHistory($actualStudentId);

        // Get student's security events
        $securityEvents = $securityLogModel
            ->join('exam_attempts', 'exam_attempts.id = security_logs.exam_attempt_id')
            ->join('exams', 'exams.id = exam_attempts.exam_id')
            ->where('exam_attempts.student_id', $actualStudentId)
            ->select('security_logs.*, exams.title as exam_title')
            ->orderBy('security_logs.created_at', 'DESC')
            ->limit(50)
            ->findAll();

        // Get suspension details
        $suspensionDetails = $userModel->getSuspensionDetails($actualStudentId);

        $data = [
            'title' => 'Student Violations - ' . $student['first_name'] . ' ' . $student['last_name'],
            'pageTitle' => 'Student Violation Details',
            'pageSubtitle' => $student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['student_id'] . ')',
            'student' => $student,
            'violations' => $violations,
            'securityEvents' => $securityEvents,
            'suspensionDetails' => $suspensionDetails
        ];

        return view('admin/student_violations', $data);
    }

    /**
     * Clear student violations
     */
    public function clearViolations()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        $studentId = $this->request->getPost('student_id');
        $reason = $this->request->getPost('reason');
        $adminId = $this->session->get('user_id');

        if (!$studentId) {
            return redirect()->back()->with('error', 'Student ID is required');
        }

        $violationModel = new \App\Models\ViolationModel();
        $success = $violationModel->clearStudentViolations($studentId, $adminId, $reason);

        if ($success) {
            return redirect()->back()->with('success', 'Student violations cleared successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to clear violations');
        }
    }

    /**
     * Clear all incorrect bans (for students banned when strict mode was off)
     */
    public function clearIncorrectBans()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        $userModel = new UserModel();
        $violationModel = new \App\Models\ViolationModel();
        $adminId = $this->session->get('user_id');

        // Get all banned/suspended students
        $bannedStudents = $userModel->where('role', 'student')
                                   ->groupStart()
                                       ->where('exam_banned', 1)
                                       ->orWhere('exam_suspended_until IS NOT NULL')
                                   ->groupEnd()
                                   ->findAll();

        $clearedCount = 0;
        $reason = "Cleared due to incorrect ban when strict security mode was disabled";

        foreach ($bannedStudents as $student) {
            // Clear the ban/suspension
            $success = $userModel->update($student['id'], [
                'exam_banned' => 0,
                'exam_suspended_until' => null,
                'ban_reason' => null,
                'suspension_reason' => null
            ]);

            if ($success) {
                // Log the action
                $violationModel->insert([
                    'student_id' => $student['id'],
                    'violation_count' => 0,
                    'punishment_type' => 'warning',
                    'severity' => 'low',
                    'notes' => $reason . " by admin ID {$adminId}",
                    'admin_id' => $adminId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $clearedCount++;
            }
        }

        if ($clearedCount > 0) {
            return redirect()->back()->with('success', "Successfully cleared {$clearedCount} incorrect bans/suspensions");
        } else {
            return redirect()->back()->with('info', 'No banned/suspended students found to clear');
        }
    }

    /**
     * Apply manual punishment
     */
    public function applyPunishment()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        $studentId = $this->request->getPost('student_id');
        $punishmentType = $this->request->getPost('punishment_type');
        $duration = $this->request->getPost('duration');
        $reason = $this->request->getPost('reason');
        $adminId = $this->session->get('user_id');

        if (!$studentId || !$punishmentType) {
            return redirect()->back()->with('error', 'Student ID and punishment type are required');
        }

        $userModel = new UserModel();
        $violationModel = new \App\Models\ViolationModel();

        // Apply punishment
        $updateData = [];
        switch ($punishmentType) {
            case 'permanent_ban':
                $updateData = [
                    'exam_banned' => 1,
                    'ban_reason' => $reason ?: 'Manual admin action'
                ];
                break;
            case 'temporary_suspension':
                if (!$duration) {
                    return redirect()->back()->with('error', 'Duration is required for temporary suspension');
                }
                $suspendUntil = date('Y-m-d H:i:s', strtotime("+{$duration} days"));
                $updateData = [
                    'exam_suspended_until' => $suspendUntil,
                    'suspension_reason' => $reason ?: 'Manual admin action'
                ];
                break;
            case 'clear':
                $updateData = [
                    'exam_banned' => 0,
                    'exam_suspended_until' => null,
                    'ban_reason' => null,
                    'suspension_reason' => null
                ];
                break;
        }

        $success = $userModel->update($studentId, $updateData);

        // Log the manual punishment
        if ($success && $punishmentType !== 'clear') {
            $violationModel->insert([
                'student_id' => $studentId,
                'violation_count' => 0, // Manual punishment
                'punishment_type' => $punishmentType,
                'punishment_duration' => $duration,
                'severity' => 'high',
                'notes' => "Manual punishment applied by admin ID {$adminId}. Reason: " . ($reason ?: 'No reason provided'),
                'admin_id' => $adminId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        if ($success) {
            $action = $punishmentType === 'clear' ? 'cleared' : 'applied';
            return redirect()->back()->with('success', "Punishment {$action} successfully");
        } else {
            return redirect()->back()->with('error', 'Failed to apply punishment');
        }
    }

    /**
     * Bulk lift bans/suspensions for multiple students
     */
    public function bulkLiftBans()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method');
        }

        $studentIds = $this->request->getPost('student_ids');
        $reason = $this->request->getPost('reason') ?: 'Bulk action by admin';

        if (empty($studentIds) || !is_array($studentIds)) {
            return redirect()->back()->with('error', 'No students selected');
        }

        $userModel = new UserModel();
        $violationModel = new \App\Models\ViolationModel();
        $successCount = 0;
        $errorCount = 0;

        foreach ($studentIds as $studentId) {
            try {
                // Clear user suspension/ban status
                $success = $userModel->update($studentId, [
                    'exam_banned' => 0,
                    'exam_suspended_until' => null,
                    'ban_reason' => null,
                    'suspension_reason' => null
                ]);

                if ($success) {
                    // Log the action in violation history
                    $violationModel->insert([
                        'student_id' => $studentId,
                        'violation_count' => 0,
                        'punishment_type' => 'warning',
                        'severity' => 'low',
                        'notes' => "Ban/suspension lifted via bulk action. Reason: {$reason}",
                        'admin_id' => $this->session->get('user_id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                log_message('error', 'Error lifting ban for student ID ' . $studentId . ': ' . $e->getMessage());
                $errorCount++;
            }
        }

        if ($successCount > 0 && $errorCount === 0) {
            return redirect()->back()->with('success', "Successfully lifted bans/suspensions for {$successCount} students");
        } elseif ($successCount > 0 && $errorCount > 0) {
            return redirect()->back()->with('warning', "Lifted bans for {$successCount} students, but {$errorCount} failed");
        } else {
            return redirect()->back()->with('error', 'Failed to lift any bans/suspensions');
        }
    }

    /**
     * Test AI Connection
     */
    public function testAiConnection()
    {
        // Check if user is admin
        if (!$this->session->get('is_logged_in') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        try {
            // Get request data
            $input = $this->request->getJSON(true);
            $provider = $input['provider'] ?? '';
            $model = $input['model'] ?? '';
            $apiKey = $input['api_key'] ?? '';

            if (empty($provider) || empty($model) || empty($apiKey)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
            }

            // Initialize AI generator
            $aiGenerator = new \App\Libraries\AIQuestionGenerator($provider, $model, $apiKey);

            // Test connection
            $result = $aiGenerator->testConnection();

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'AI Connection Test Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Encrypt API key for secure storage
     */
    private function encryptApiKey($apiKey)
    {
        try {
            $encrypter = \Config\Services::encrypter();
            return $encrypter->encrypt($apiKey);
        } catch (\Exception $e) {
            log_message('error', 'API Key encryption failed: ' . $e->getMessage());
            // Fallback to base64 encoding if encryption fails
            return base64_encode($apiKey);
        }
    }

    /**
     * Decrypt API key for use
     */
    private function decryptApiKey($encryptedApiKey)
    {
        try {
            $encrypter = \Config\Services::encrypter();
            return $encrypter->decrypt($encryptedApiKey);
        } catch (\Exception $e) {
            log_message('error', 'API Key decryption failed: ' . $e->getMessage());
            // Fallback to base64 decoding if decryption fails
            return base64_decode($encryptedApiKey);
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
     * Admin Profile Management
     */
    public function profile()
    {
        $session = \Config\Services::session();

        if (!$session->get('is_logged_in') || $session->get('role') !== 'admin') {
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

        return view('admin/profile', $data);
    }

    /**
     * Update Admin Profile
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

            return view('admin/profile', [
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
