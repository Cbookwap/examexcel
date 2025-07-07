<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\ExamModel;
use App\Models\ExamAttemptModel;
use App\Models\SettingsModel;
use App\Models\ExamTypeModel;
use App\Models\ExamSubjectModel;
use App\Models\ExamQuestionModel;
use App\Models\QuestionModel;
use App\Models\StudentAnswerModel;

class Principal extends BaseController
{
    protected $userModel;
    protected $classModel;
    protected $subjectModel;
    protected $examModel;
    protected $examAttemptModel;
    protected $settingsModel;
    protected $questionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->classModel = new ClassModel();
        $this->subjectModel = new SubjectModel();
        $this->examModel = new ExamModel();
        $this->examAttemptModel = new ExamAttemptModel();
        $this->settingsModel = new SettingsModel();
        $this->questionModel = new \App\Models\QuestionModel();

        // Load helpers
        helper(['form', 'url']);
    }

    public function dashboard()
    {
        $session = \Config\Services::session();

        $data = [
            'title' => 'Principal Dashboard',
            'stats' => $this->getDashboardStats(),
            'recentExams' => $this->getRecentExams(),
            'recentStudents' => $this->getRecentStudents(),
            'user_name' => $session->get('first_name') ?? 'Principal'
        ];

        return view('principal/dashboard', $data);
    }

    /**
     * Principal Profile Management
     */
    public function profile()
    {
        $session = \Config\Services::session();

        if (!$session->get('is_logged_in') || $session->get('role') !== 'principal') {
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

        return view('principal/profile', $data);
    }

    /**
     * Update Principal Profile
     */
    private function updateProfile($userId)
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
            'date_of_birth' => 'permit_empty|valid_date',
            'gender' => 'permit_empty|in_list[male,female,other]',
            'address' => 'permit_empty|max_length[500]',
            'title' => 'permit_empty|max_length[100]',
            'password' => 'permit_empty|min_length[6]',
            'confirm_password' => 'permit_empty|matches[password]'
        ];

        if (!$this->validate($rules)) {
            $user = $this->userModel->find($userId);
            return view('principal/profile', [
                'title' => 'My Profile - ' . get_app_name(),
                'user' => $user,
                'validation' => $this->validator
            ]);
        }

        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'title' => $this->request->getPost('title')
        ];

        // Only update password if provided
        if (!empty($this->request->getPost('password'))) {
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

    // Student Management
    public function students()
    {
        $request = $this->request;
        $perPage = 20;

        // Get filters
        $filters = [
            'search' => $request->getGet('search') ?? '',
            'class' => $request->getGet('class') ?? '',
            'gender' => $request->getGet('gender') ?? ''
        ];

        // Build query
        $query = $this->userModel->where('role', 'student');

        if (!empty($filters['search'])) {
            $query->groupStart()
                  ->like('first_name', $filters['search'])
                  ->orLike('last_name', $filters['search'])
                  ->orLike('student_id', $filters['search'])
                  ->orLike('email', $filters['search'])
                  ->groupEnd();
        }

        if (!empty($filters['class'])) {
            $query->where('class_id', $filters['class']);
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        $students = $query->select('users.*, classes.name as class_name')
                         ->join('classes', 'classes.id = users.class_id', 'left')
                         ->orderBy('users.created_at', 'DESC')
                         ->paginate($perPage);

        // Get all classes for filter dropdown
        $classes = $this->classModel->select('id, name, section')
                                   ->where('is_active', 1)
                                   ->orderBy('name', 'ASC')
                                   ->findAll();

        // Add display name with category for each class
        foreach ($classes as &$class) {
            $class['display_name'] = $this->getClassDisplayName($class);
        }

        $data = [
            'title' => 'Student Management',
            'students' => $students,
            'pager' => $this->userModel->pager,
            'filters' => $filters,
            'classes' => $classes,
            'stats' => $this->getStudentStats()
        ];

        return view('principal/students', $data);
    }

    public function createStudent()
    {
        if ($this->request->getMethod() === 'POST') {
            // Validation rules for student creation
            $rules = [
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'first_name' => 'required|min_length[2]|max_length[100]',
                'last_name' => 'required|min_length[2]|max_length[100]',
                'class_id' => 'permit_empty|integer',
                'phone' => 'permit_empty|max_length[20]',
                'gender' => 'permit_empty|in_list[male,female]',
                'address' => 'permit_empty|max_length[500]'
            ];

            // Validate student ID if provided
            if (!empty($this->request->getPost('student_id'))) {
                $rules['student_id'] = 'max_length[50]|is_unique[users.student_id]';
            }

            if (!$this->validate($rules)) {
                // Get classes with display names for validation error case
                $classes = $this->classModel->select('id, name, section')
                                           ->where('is_active', 1)
                                           ->orderBy('name', 'ASC')
                                           ->findAll();

                // Add display name with category for each class
                foreach ($classes as &$class) {
                    $class['display_name'] = $this->getClassDisplayName($class);
                }

                return view('principal/create_student', [
                    'title' => 'Create Student',
                    'classes' => $classes,
                    'validation' => $this->validator
                ]);
            }

            $data = $this->request->getPost();

            // Generate student ID if not provided
            if (empty($data['student_id'])) {
                $data['student_id'] = $this->generateStudentIdInternal();
            }

            // Set username to be the same as student_id for students
            $data['username'] = $data['student_id'];

            $data['role'] = 'student';
            $data['is_active'] = 1;
            $data['is_verified'] = 1;

            if ($this->userModel->insert($data)) {
                return redirect()->to('/principal/students')->with('success', 'Student created successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create student.');
            }
        }

        // Get classes with display names for form display
        $classes = $this->classModel->select('id, name, section')
                                   ->where('is_active', 1)
                                   ->orderBy('name', 'ASC')
                                   ->findAll();

        // Add display name with category for each class
        foreach ($classes as &$class) {
            $class['display_name'] = $this->getClassDisplayName($class);
        }

        $data = [
            'title' => 'Create Student',
            'classes' => $classes
        ];

        return view('principal/create_student', $data);
    }

    // Teacher Management
    public function teachers()
    {
        $request = $this->request;
        $perPage = 20;

        // Get filters
        $filters = [
            'search' => $request->getGet('search') ?? '',
            'department' => $request->getGet('department') ?? '',
            'gender' => $request->getGet('gender') ?? ''
        ];

        // Build query
        $query = $this->userModel->where('role', 'teacher');

        if (!empty($filters['search'])) {
            $query->groupStart()
                  ->like('first_name', $filters['search'])
                  ->orLike('last_name', $filters['search'])
                  ->orLike('employee_id', $filters['search'])
                  ->orLike('email', $filters['search'])
                  ->groupEnd();
        }

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        $teachers = $query->orderBy('created_at', 'DESC')
                         ->paginate($perPage);

        // Get unique departments for filter dropdown
        $departmentBuilder = $this->userModel->select('department')
                                           ->where('role', 'teacher')
                                           ->where('department IS NOT NULL')
                                           ->where('department !=', '')
                                           ->distinct()
                                           ->orderBy('department', 'ASC');
        $departmentResults = $departmentBuilder->findAll();
        $departments = array_column($departmentResults, 'department');

        $data = [
            'title' => 'Teacher Management',
            'teachers' => $teachers,
            'pager' => $this->userModel->pager,
            'filters' => $filters,
            'departments' => $departments,
            'stats' => $this->getTeacherStats()
        ];

        return view('principal/teachers', $data);
    }

    public function createTeacher()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            $data['role'] = 'teacher';
            $data['is_active'] = 1;
            $data['is_verified'] = 1;

            if ($this->userModel->insert($data)) {
                return redirect()->to('/principal/teachers')->with('success', 'Teacher created successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create teacher.');
            }
        }

        $data = [
            'title' => 'Create Teacher'
        ];

        return view('principal/create_teacher', $data);
    }

    // Class Management
    public function classes()
    {
        $classes = $this->classModel->select('classes.*, COUNT(users.id) as student_count')
                                  ->join('users', 'users.class_id = classes.id AND users.role = "student"', 'left')
                                  ->groupBy('classes.id')
                                  ->orderBy('classes.name', 'ASC')
                                  ->findAll();

        $data = [
            'title' => 'Class Management',
            'classes' => $classes,
            'stats' => $this->getClassStats()
        ];

        return view('principal/classes', $data);
    }

    public function createClass()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $data['is_active'] = 1;

            if ($this->classModel->insert($data)) {
                return redirect()->to('/principal/classes')->with('success', 'Class created successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create class.');
            }
        }

        $data = [
            'title' => 'Create Class'
        ];

        return view('principal/create_class', $data);
    }

    // Exam Management
    public function exams()
    {
        $exams = $this->examModel->getExamsWithDetails();

        $data = [
            'title' => 'Exam Management - ' . get_app_name(),
            'exams' => $exams
        ];

        return view('principal/exams', $data);
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

        return view('principal/exam_create', $data);
    }

    /**
     * Process exam creation
     */
    private function processCreateExam()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[500]',
            'class_id' => 'required|numeric',
            'exam_type' => 'required|max_length[50]',
            'duration_minutes' => 'required|numeric|greater_than[0]',
            'total_marks' => 'required|numeric|greater_than[0]',
            'passing_marks' => 'required|numeric|greater_than[0]',
            'start_time' => 'required|valid_date',
            'end_time' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $examMode = $this->request->getPost('exam_mode') ?: 'single_subject';

        if ($examMode === 'single_subject') {
            $rules['subject_id'] = 'required|numeric';
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('validation', $validation);
            }
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
            'total_questions' => 0,
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
            return redirect()->to("/principal/exams/{$examId}/questions")->with('success', 'Exam created successfully! Now configure the questions.');
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
            return redirect()->to('/principal/exams')->with('error', 'Exam not found');
        }

        // Ensure all required fields have default values - convert to proper types
        $exam['max_attempts'] = isset($exam['max_attempts']) ? (int)$exam['max_attempts'] : 1;
        $exam['attempt_delay_minutes'] = isset($exam['attempt_delay_minutes']) ? (int)$exam['attempt_delay_minutes'] : 0;
        $exam['total_questions'] = isset($exam['total_questions']) ? (int)$exam['total_questions'] : 0;
        $exam['questions_configured'] = isset($exam['questions_configured']) ? (int)$exam['questions_configured'] : 0;
        $exam['negative_marking'] = isset($exam['negative_marking']) ? (int)$exam['negative_marking'] : 0;
        $exam['negative_marks_per_question'] = isset($exam['negative_marks_per_question']) ? (float)$exam['negative_marks_per_question'] : 0;
        $exam['randomize_questions'] = isset($exam['randomize_questions']) ? (int)$exam['randomize_questions'] : 0;
        $exam['randomize_options'] = isset($exam['randomize_options']) ? (int)$exam['randomize_options'] : 0;
        $exam['show_result_immediately'] = isset($exam['show_result_immediately']) ? (int)$exam['show_result_immediately'] : 0;
        $exam['allow_review'] = isset($exam['allow_review']) ? (int)$exam['allow_review'] : 0;
        $exam['require_proctoring'] = isset($exam['require_proctoring']) ? (int)$exam['require_proctoring'] : 0;
        $exam['browser_lockdown'] = isset($exam['browser_lockdown']) ? (int)$exam['browser_lockdown'] : 0;
        $exam['prevent_copy_paste'] = isset($exam['prevent_copy_paste']) ? (int)$exam['prevent_copy_paste'] : 0;
        $exam['disable_right_click'] = isset($exam['disable_right_click']) ? (int)$exam['disable_right_click'] : 0;
        $exam['calculator_enabled'] = isset($exam['calculator_enabled']) ? (int)$exam['calculator_enabled'] : 0;
        $exam['exam_pause_enabled'] = isset($exam['exam_pause_enabled']) ? (int)$exam['exam_pause_enabled'] : 0;

        $attemptModel = new \App\Models\ExamAttemptModel();
        $attempts = $attemptModel->getExamAttempts($id);

        // Validate exam configuration
        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $validationErrors = [];
        try {
            $validationErrors = $examQuestionModel->validateQuestionCountConsistency($id);
        } catch (\Exception $e) {
            // If validation method doesn't exist, just continue
            $validationErrors = [];
        }

        // Determine exam status
        $status = 'draft';
        $now = new \DateTime();
        $startTime = $exam['start_time'] ? new \DateTime($exam['start_time']) : null;
        $endTime = $exam['end_time'] ? new \DateTime($exam['end_time']) : null;

        if ($exam['is_active'] && $startTime && $endTime) {
            if ($now < $startTime) {
                $status = 'scheduled';
            } elseif ($now >= $startTime && $now <= $endTime) {
                $status = 'active';
            } elseif ($now > $endTime) {
                $status = 'completed';
            }
        } elseif (!$exam['is_active']) {
            $status = 'inactive';
        }

        $data = [
            'title' => $exam['title'] . ' - ExamExcel',
            'pageTitle' => $exam['title'],
            'pageSubtitle' => 'Exam Details and Management',
            'exam' => $exam,
            'attempts' => $attempts,
            'status' => $status,
            'role' => 'principal',
            'validationErrors' => $validationErrors
        ];

        return view('principal/exam_view', $data);
    }

    /**
     * Edit exam
     */
    public function editExam($id)
    {
        $exam = $this->examModel->find($id);
        if (!$exam) {
            return redirect()->to('/principal/exams')->with('error', 'Exam not found');
        }

        $examTypeModel = new \App\Models\ExamTypeModel();
        $examSubjectModel = new \App\Models\ExamSubjectModel();
        $examSubjects = $examSubjectModel->getExamSubjects($id);

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

        return view('principal/exam_edit', $data);
    }

    /**
     * Process exam editing
     */
    private function processEditExam($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[500]',
            'class_id' => 'required|numeric',
            'exam_type' => 'required|max_length[50]',
            'duration_minutes' => 'required|numeric|greater_than[0]',
            'total_marks' => 'required|numeric|greater_than[0]',
            'passing_marks' => 'required|numeric|greater_than[0]',
            'start_time' => 'required|valid_date',
            'end_time' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
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

        if ($this->examModel->update($id, $examData)) {
            return redirect()->to("/principal/exams/view/{$id}")->with('success', 'Exam updated successfully!');
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
            return redirect()->to('/principal/exams')->with('error', 'Exam not found');
        }

        // Check if exam has attempts
        $attemptModel = new \App\Models\ExamAttemptModel();
        $attempts = $attemptModel->where('exam_id', $id)->countAllResults();

        if ($attempts > 0) {
            return redirect()->to('/principal/exams')->with('error', 'Cannot delete exam with existing attempts');
        }

        if ($this->examModel->delete($id)) {
            return redirect()->to('/principal/exams')->with('success', 'Exam deleted successfully!');
        } else {
            return redirect()->to('/principal/exams')->with('error', 'Failed to delete exam');
        }
    }

    /**
     * Manage exam questions
     */
    public function manageExamQuestions($examId)
    {
        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/principal/exams')->with('error', 'Exam not found');
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
    }

    /**
     * Manage single subject exam questions
     */
    private function manageSingleSubjectQuestions($examId, $data)
    {
        $exam = $data['exam'];
        $examQuestionModel = new ExamQuestionModel();
        $questionModel = new QuestionModel();

        if ($this->request->getMethod() === 'POST') {
            return $this->processSingleSubjectQuestions($examId);
        }

        // Get available questions for this subject
        $availableQuestions = $questionModel->getQuestionsBySubject($exam['subject_id']);

        // Get currently selected questions
        $selectedQuestions = $examQuestionModel->getExamQuestions($examId);

        $data['availableQuestions'] = $availableQuestions;
        $data['selectedQuestions'] = $selectedQuestions;
        $data['examMode'] = 'single_subject';

        return view('principal/exam_questions', $data);
    }

    /**
     * Manage multi-subject exam questions
     */
    private function manageMultiSubjectQuestions($examId, $data)
    {
        $exam = $data['exam'];
        $examSubjectModel = new ExamSubjectModel();

        if ($this->request->getMethod() === 'POST') {
            return $this->processMultiSubjectQuestions($examId);
        }

        // Get subjects assigned to this class
        $availableSubjects = $this->subjectModel->getSubjectsByClass($exam['class_id']);

        // Get configured exam subjects with question details
        $examSubjects = $examSubjectModel->getExamSubjectsWithQuestions($examId);

        $data['availableSubjects'] = $availableSubjects;
        $data['examSubjects'] = $examSubjects;
        $data['examMode'] = 'multi_subject';
        $data['subjects'] = $this->subjectModel->where('is_active', 1)->findAll();

        return view('principal/exam_questions', $data);
    }

    /**
     * Process single subject question selection
     */
    private function processSingleSubjectQuestions($examId)
    {
        $selectedQuestions = $this->request->getPost('questions') ?? [];
        $examQuestionModel = new ExamQuestionModel();

        // Clear existing questions
        $examQuestionModel->where('exam_id', $examId)->delete();

        // Add selected questions
        $order = 1;
        foreach ($selectedQuestions as $questionId) {
            $examQuestionModel->insert([
                'exam_id' => $examId,
                'question_id' => $questionId,
                'question_order' => $order++
            ]);
        }

        // Update exam question count
        $this->examModel->update($examId, [
            'question_count' => count($selectedQuestions),
            'total_questions' => count($selectedQuestions),
            'questions_configured' => 1
        ]);

        return redirect()->to("/principal/exams/view/{$examId}")->with('success', 'Questions configured successfully!');
    }

    /**
     * Process multi-subject question configuration
     */
    private function processMultiSubjectQuestions($examId)
    {
        $subjects = $this->request->getPost('subjects') ?? [];
        $examSubjectModel = new ExamSubjectModel();

        // Clear existing exam subjects
        $examSubjectModel->where('exam_id', $examId)->delete();

        $totalQuestions = 0;
        $totalMarks = 0;
        $totalTime = 0;

        foreach ($subjects as $subjectData) {
            if (!empty($subjectData['subject_id']) && !empty($subjectData['question_count'])) {
                $examSubjectModel->insert([
                    'exam_id' => $examId,
                    'subject_id' => $subjectData['subject_id'],
                    'question_count' => $subjectData['question_count'],
                    'marks_per_question' => $subjectData['marks_per_question'] ?? 1,
                    'time_per_question' => $subjectData['time_per_question'] ?? 1
                ]);

                $totalQuestions += $subjectData['question_count'];
                $totalMarks += $subjectData['question_count'] * ($subjectData['marks_per_question'] ?? 1);
                $totalTime += $subjectData['question_count'] * ($subjectData['time_per_question'] ?? 1);
            }
        }

        // Update exam totals
        $this->examModel->update($examId, [
            'total_questions' => $totalQuestions,
            'total_marks' => $totalMarks,
            'duration_minutes' => $totalTime,
            'questions_configured' => 1
        ]);

        return redirect()->to("/principal/exams/view/{$examId}")->with('success', 'Multi-subject exam configured successfully!');
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

    /**
     * Manage questions for a specific subject in a multi-subject exam
     */
    public function manageExamSubjectQuestions($examId, $subjectId)
    {
        // Load text helper for character_limiter function
        helper('text');

        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/principal/exams')->with('error', 'Exam not found');
        }

        if ($exam['exam_mode'] !== 'multi_subject') {
            return redirect()->to("/principal/exams/{$examId}/questions")->with('error', 'This exam is not configured for multi-subject mode');
        }

        $subject = $this->subjectModel->find($subjectId);
        if (!$subject) {
            return redirect()->back()->with('error', 'Subject not found');
        }

        $examSubjectModel = new ExamSubjectModel();
        $examSubject = $examSubjectModel->getExamSubject($examId, $subjectId);
        if (!$examSubject) {
            return redirect()->back()->with('error', 'This subject is not configured for this exam');
        }

        $questionModel = new QuestionModel();
        $examQuestionModel = new ExamQuestionModel();

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
            'selectedQuestionIds' => $selectedQuestionIds
        ];

        return view('principal/exam_subject_questions', $data);
    }

    /**
     * Process subject-specific question assignment for multi-subject exam
     */
    private function processExamSubjectQuestions($examId, $subjectId)
    {
        $selectedQuestions = $this->request->getPost('selected_questions');

        $examSubjectModel = new ExamSubjectModel();
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

        $examQuestionModel = new ExamQuestionModel();

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
                $this->examModel->update($examId, [
                    'questions_configured' => 1,
                    'status' => 'scheduled',
                    'total_questions' => $totalConfiguredQuestions
                ]);
            }

            return redirect()->to("/principal/exams/{$examId}/questions")
                           ->with('success', 'Questions configured successfully for ' . $examSubject['subject_name'] . '!');
        } else {
            return redirect()->back()->with('error', 'Failed to configure questions. Please try again.');
        }
    }

    // Violation Management
    public function violations()
    {
        $violationModel = new \App\Models\ViolationModel();
        $data = [
            'title' => 'Violation Management',
            'violations' => $violationModel->getViolationsWithUserDetails()
        ];

        return view('principal/violations', $data);
    }

    public function liftBan($userId)
    {
        $userModel = new \App\Models\UserModel();

        if ($userModel->update($userId, ['exam_banned' => 0, 'ban_reason' => null, 'exam_suspended_until' => null, 'suspension_reason' => null])) {
            session()->setFlashdata('success', 'Ban lifted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to lift ban.');
        }

        return redirect()->back();
    }

    public function deleteViolation($id)
    {
        $violationModel = new \App\Models\ViolationModel();

        if ($violationModel->delete($id)) {
            session()->setFlashdata('success', 'Violation record deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete violation record.');
        }

        return redirect()->back();
    }

    // Results Management
    public function results()
    {
        $classId = $this->request->getGet('class_id');
        $subjectId = $this->request->getGet('subject_id');
        $examId = $this->request->getGet('exam_id');

        $builder = $this->examAttemptModel->select('exam_attempts.*,
                                                   exams.title as exam_title,
                                                   exams.total_marks,
                                                   exams.passing_marks,
                                                   users.first_name,
                                                   users.last_name,
                                                   COALESCE(users.student_id, users.username) as student_id,
                                                   subjects.name as subject_name,
                                                   classes.name as class_name')
                                         ->join('exams', 'exams.id = exam_attempts.exam_id')
                                         ->join('users', 'users.id = exam_attempts.student_id')
                                         ->join('subjects', 'subjects.id = exams.subject_id', 'left')
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

        $attempts = $builder->orderBy('exam_attempts.submitted_at', 'DESC')->findAll();

        $data = [
            'title' => 'Exam Results - ExamExcel',
            'pageTitle' => 'Exam Results',
            'pageSubtitle' => 'View and manage student exam results',
            'attempts' => $attempts,
            'classes' => $this->classModel->where('is_active', 1)->findAll(),
            'subjects' => $this->subjectModel->where('is_active', 1)->findAll(),
            'exams' => $this->examModel->where('is_active', 1)->orderBy('created_at', 'DESC')->findAll(),
            'filters' => [
                'class_id' => $classId,
                'subject_id' => $subjectId,
                'exam_id' => $examId
            ]
        ];

        return view('principal/results', $data);
    }

    /**
     * View individual exam attempt result
     */
    public function viewResult($attemptId)
    {
        $attempt = $this->examAttemptModel->getAttemptWithDetails($attemptId);

        if (!$attempt) {
            return redirect()->to('/principal/results')->with('error', 'Exam attempt not found');
        }

        // Get exam details with all required fields
        $exam = $this->examModel->getExamWithDetails($attempt['exam_id']);
        if (!$exam) {
            return redirect()->to('/principal/results')->with('error', 'Exam not found');
        }

        // Ensure required fields have default values
        $exam['total_questions'] = $exam['total_questions'] ?? 0;
        $exam['passing_marks'] = $exam['passing_marks'] ?? 0;

        // Get student answers for this attempt from student_answers table
        $studentAnswerModel = new StudentAnswerModel();
        $studentAnswers = $studentAnswerModel->getAnswersGroupedByQuestion($attemptId);

        // Get questions for this exam
        $examQuestionModel = new ExamQuestionModel();
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
            'title' => 'Exam Result Details - ExamExcel',
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

        return view('principal/result_view', $data);
    }

    // Reports and Analytics
    public function reports()
    {
        $data = [
            'title' => 'Reports & Analytics',
            'examStats' => $this->getExamStats(),
            'performanceData' => $this->getPerformanceData(),
            'classPerformance' => $this->getClassPerformance()
        ];

        return view('principal/reports', $data);
    }

    // Settings (Limited)
    public function settings()
    {
        if ($this->request->getMethod() === 'POST') {
            $allowedSettings = [
                'school_address',
                'school_phone',
                'school_email',
                'academic_year'
            ];

            $data = $this->request->getPost();

            // Always set institution_name to ExamExcel (not editable)
            $this->settingsModel->setSetting('institution_name', 'ExamExcel', 'string', 'Fixed system name');

            foreach ($data as $key => $value) {
                if (in_array($key, $allowedSettings)) {
                    $this->settingsModel->setSetting($key, $value, 'string', 'Principal updated setting');
                }
            }

            return redirect()->back()->with('success', 'Settings updated successfully.');
        }

        $data = [
            'title' => 'Principal Settings',
            'settings' => $this->getPrincipalSettings()
        ];

        return view('principal/settings', $data);
    }

    // Helper Methods
    private function getDashboardStats()
    {
        // Get question bank statistics
        $questionStats = $this->questionModel->getQuestionStats();

        return [
            'total_students' => $this->userModel->where('role', 'student')->countAllResults(),
            'active_students' => $this->userModel->where('role', 'student')->where('is_active', 1)->countAllResults(),
            'total_teachers' => $this->userModel->where('role', 'teacher')->countAllResults(),
            'active_teachers' => $this->userModel->where('role', 'teacher')->where('is_active', 1)->countAllResults(),
            'total_classes' => $this->classModel->countAllResults(),
            'active_classes' => $this->classModel->where('is_active', 1)->countAllResults(),
            'total_questions' => $questionStats['total'],
            'active_questions' => $questionStats['active'],
            'mcq_questions' => $questionStats['mcq'] ?? 0,
            'recent_questions' => $this->questionModel->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults()
        ];
    }

    private function getRecentExams()
    {
        return $this->examModel->select('exams.*, subjects.name as subject_name')
                              ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                              ->orderBy('exams.created_at', 'DESC')
                              ->limit(5)
                              ->findAll();
    }

    private function getRecentStudents()
    {
        return $this->userModel->select('users.*, classes.name as class_name')
                              ->join('classes', 'classes.id = users.class_id', 'left')
                              ->where('users.role', 'student')
                              ->orderBy('users.created_at', 'DESC')
                              ->limit(5)
                              ->findAll();
    }



    private function getStudentStats()
    {
        $stats = $this->userModel->getUserStats();
        return [
            'total' => $stats['students'],
            'active' => $this->userModel->where('role', 'student')->where('is_active', 1)->countAllResults(),
            'male' => $this->userModel->where('role', 'student')->where('gender', 'male')->countAllResults(),
            'female' => $this->userModel->where('role', 'student')->where('gender', 'female')->countAllResults()
        ];
    }

    private function getTeacherStats()
    {
        return [
            'total' => $this->userModel->where('role', 'teacher')->countAllResults(),
            'active' => $this->userModel->where('role', 'teacher')->where('is_active', 1)->countAllResults(),
            'male' => $this->userModel->where('role', 'teacher')->where('gender', 'male')->countAllResults(),
            'female' => $this->userModel->where('role', 'teacher')->where('gender', 'female')->countAllResults()
        ];
    }

    private function getClassStats()
    {
        return [
            'total' => $this->classModel->countAllResults(),
            'active' => $this->classModel->where('is_active', 1)->countAllResults()
        ];
    }

    /**
     * Generate student ID via AJAX
     */
    public function generateStudentId()
    {
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

    private function generateStudentIdInternal()
    {
        $prefix = $this->settingsModel->getSetting('student_id_prefix', 'STD');
        $lastId = $this->userModel->selectMax('id')->first()['id'] ?? 0;
        return $prefix . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }

    private function getExamStats()
    {
        return [
            'total_exams' => $this->examModel->countAllResults(),
            'total_attempts' => $this->examAttemptModel->countAllResults(),
            'average_score' => $this->examAttemptModel->selectAvg('percentage')->first()['percentage'] ?? 0
        ];
    }

    private function getPerformanceData()
    {
        // Get performance data for charts
        return $this->examAttemptModel->select('DATE(created_at) as date, AVG(percentage) as avg_score')
                                     ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                                     ->groupBy('DATE(created_at)')
                                     ->orderBy('date', 'ASC')
                                     ->findAll();
    }

    private function getClassPerformance()
    {
        return $this->examAttemptModel->select('classes.name as class_name, AVG(exam_attempts.percentage) as avg_score, COUNT(exam_attempts.id) as total_attempts')
                                     ->join('users', 'users.id = exam_attempts.student_id')
                                     ->join('classes', 'classes.id = users.class_id')
                                     ->groupBy('classes.id')
                                     ->orderBy('avg_score', 'DESC')
                                     ->findAll();
    }

    private function getPrincipalSettings()
    {
        $allowedSettings = [
            'institution_name', // Always show ExamExcel, but not editable
            'school_address',
            'school_phone',
            'school_email',
            'academic_year'
        ];

        $settings = [];
        foreach ($allowedSettings as $setting) {
            if ($setting === 'institution_name') {
                $settings[$setting] = 'ExamExcel'; // Fixed value
            } else {
                $settings[$setting] = $this->settingsModel->getSetting($setting, '');
            }
        }

        return $settings;
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
}
