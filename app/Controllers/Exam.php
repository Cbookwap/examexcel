<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamAttemptModel;
use App\Models\QuestionModel;
use App\Models\SubjectModel;
use App\Models\ClassModel;
use CodeIgniter\Controller;

class Exam extends Controller
{
    protected $examModel;
    protected $attemptModel;
    protected $questionModel;
    protected $subjectModel;
    protected $classModel;
    protected $session;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->attemptModel = new ExamAttemptModel();
        $this->questionModel = new QuestionModel();
        $this->subjectModel = new SubjectModel();
        $this->classModel = new ClassModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);

        // Check if user is logged in
        if (!$this->session->get('is_logged_in')) {
            redirect()->to('/auth/login')->send();
            exit;
        }
    }

    /**
     * Exam listing page
     */
    public function index()
    {
        $role = $this->session->get('role');
        $userId = $this->session->get('user_id');

        try {
            if (in_array($role, ['admin', 'principal'])) {
                $exams = $this->examModel->select('exams.id, exams.title, exams.description, exams.subject_id, exams.class_id,
                                                  exams.exam_type, exams.status, exams.duration_minutes, exams.total_marks,
                                                  exams.passing_marks, exams.start_time, exams.end_time, exams.is_active,
                                                  exams.created_by, exams.created_at, exams.updated_at,
                                                  subjects.name as subject_name, classes.name as class_name')
                                       ->join('subjects', 'subjects.id = exams.subject_id')
                                       ->join('classes', 'classes.id = exams.class_id')
                                       ->orderBy('exams.created_at', 'DESC')
                                       ->findAll();
            } else {
                // Student - show exams for their class
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);
                $exams = $this->examModel->getExamsForClass($user['class_id']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error loading exams: ' . $e->getMessage());
            $exams = [];
        }

        $data = [
            'title' => 'Exams - SRMS CBT System',
            'pageTitle' => 'Exam Management',
            'pageSubtitle' => 'Manage and monitor examinations',
            'exams' => $exams,
            'role' => $role
        ];

        return view('exams/index', $data);
    }

    /**
     * Create new exam
     */
    public function create()
    {
        // Only admin and principal can create exams
        if (!in_array($this->session->get('role'), ['admin', 'principal'])) {
            return redirect()->to('/exam')->with('error', 'Access denied');
        }

        $data = [
            'title' => 'Create Exam - ExamExcel',
            'pageTitle' => 'Create New Exam',
            'pageSubtitle' => 'Set up a new examination',
            'subjects' => $this->subjectModel->getActiveSubjects(),
            'classes' => $this->classModel->getActiveClasses(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processCreateExam();
        }

        return view('exams/create', $data);
    }

    /**
     * Process exam creation
     */
    private function processCreateExam()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'subject_id' => 'required|integer',
            'class_id' => 'required|integer',
            'duration_minutes' => 'required|integer|greater_than[0]',
            'total_marks' => 'required|integer|greater_than[0]',
            'passing_marks' => 'required|decimal|greater_than[0]',
            'question_count' => 'required|integer|greater_than[0]',
            'start_time' => 'required',
            'end_time' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $examData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'subject_id' => $this->request->getPost('subject_id'),
            'class_id' => $this->request->getPost('class_id'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'total_marks' => $this->request->getPost('total_marks'),
            'passing_marks' => $this->request->getPost('passing_marks'),
            'question_count' => $this->request->getPost('question_count'),
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
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'instructions' => [
                'general' => $this->request->getPost('instructions_general'),
                'technical' => $this->request->getPost('instructions_technical'),
                'rules' => $this->request->getPost('instructions_rules')
            ],
            'calculator_enabled' => $this->request->getPost('calculator_enabled') ? 1 : 0,
            'exam_pause_enabled' => $this->request->getPost('exam_pause_enabled') ? 1 : 0,
            'settings' => [
                'auto_submit' => $this->request->getPost('auto_submit') ? 1 : 0,
                'show_timer' => $this->request->getPost('show_timer') ? 1 : 0,
                'allow_navigation' => $this->request->getPost('allow_navigation') ? 1 : 0
            ],
            'is_active' => 1,
            'created_by' => $this->session->get('user_id')
        ];

        if ($this->examModel->insert($examData)) {
            return redirect()->to('/exam')->with('success', 'Exam created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create exam. Please try again.');
        }
    }

    /**
     * View exam details
     */
    public function view($id)
    {
        $exam = $this->examModel->getExamWithDetails($id);
        if (!$exam) {
            return redirect()->to('/exam')->with('error', 'Exam not found');
        }

        $role = $this->session->get('role');
        $attempts = [];

        if (in_array($role, ['admin', 'principal'])) {
            $attempts = $this->attemptModel->getExamAttempts($id);
        }

        $data = [
            'title' => $exam['title'] . ' - ExamExcel',
            'pageTitle' => $exam['title'],
            'pageSubtitle' => 'Exam Details and Management',
            'exam' => $exam,
            'attempts' => $attempts,
            'status' => $this->examModel->getExamStatus($exam),
            'role' => $role
        ];

        return view('exams/view', $data);
    }

    /**
     * Edit exam
     */
    public function edit($id)
    {
        // Only admin and principal can edit exams
        if (!in_array($this->session->get('role'), ['admin', 'principal'])) {
            return redirect()->to('/exam')->with('error', 'Access denied');
        }

        $exam = $this->examModel->find($id);
        if (!$exam) {
            return redirect()->to('/exam')->with('error', 'Exam not found');
        }

        $data = [
            'title' => 'Edit Exam - ExamExcel',
            'pageTitle' => 'Edit Exam',
            'pageSubtitle' => 'Modify exam settings and details',
            'exam' => $exam,
            'subjects' => $this->subjectModel->getActiveSubjects(),
            'classes' => $this->classModel->getActiveClasses(),
            'validation' => \Config\Services::validation()
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processEditExam($id);
        }

        return view('exams/edit', $data);
    }

    /**
     * Process exam editing
     */
    private function processEditExam($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'subject_id' => 'required|integer',
            'class_id' => 'required|integer',
            'duration_minutes' => 'required|integer|greater_than[0]',
            'total_marks' => 'required|integer|greater_than[0]',
            'passing_marks' => 'required|decimal|greater_than[0]',
            'question_count' => 'required|integer|greater_than[0]',
            'start_time' => 'required',
            'end_time' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $examData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'subject_id' => $this->request->getPost('subject_id'),
            'class_id' => $this->request->getPost('class_id'),
            'duration_minutes' => $this->request->getPost('duration_minutes'),
            'total_marks' => $this->request->getPost('total_marks'),
            'passing_marks' => $this->request->getPost('passing_marks'),
            'question_count' => $this->request->getPost('question_count'),
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
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'instructions' => [
                'general' => $this->request->getPost('instructions_general'),
                'technical' => $this->request->getPost('instructions_technical'),
                'rules' => $this->request->getPost('instructions_rules')
            ],
            'settings' => [
                'auto_submit' => $this->request->getPost('auto_submit') ? 1 : 0,
                'show_timer' => $this->request->getPost('show_timer') ? 1 : 0,
                'allow_navigation' => $this->request->getPost('allow_navigation') ? 1 : 0
            ]
        ];

        if ($this->examModel->update($id, $examData)) {
            return redirect()->to('/exam/view/' . $id)->with('success', 'Exam updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update exam. Please try again.');
        }
    }

    /**
     * Delete exam
     */
    public function delete($id)
    {
        // Only admin and principal can delete exams
        if (!in_array($this->session->get('role'), ['admin', 'principal'])) {
            return redirect()->to('/exam')->with('error', 'Access denied');
        }

        $exam = $this->examModel->find($id);
        if (!$exam) {
            return redirect()->to('/exam')->with('error', 'Exam not found');
        }

        // Check if exam has attempts
        $attempts = $this->attemptModel->where('exam_id', $id)->countAllResults();
        if ($attempts > 0) {
            return redirect()->to('/exam')->with('error', 'Cannot delete exam with existing attempts');
        }

        if ($this->examModel->delete($id)) {
            return redirect()->to('/exam')->with('success', 'Exam deleted successfully!');
        } else {
            return redirect()->to('/exam')->with('error', 'Failed to delete exam');
        }
    }

    /**
     * Assign questions to exam
     */
    public function assignQuestions($examId)
    {
        // Only admin and principal can assign questions
        if (!in_array($this->session->get('role'), ['admin', 'principal'])) {
            return redirect()->to('/exam')->with('error', 'Access denied');
        }

        $exam = $this->examModel->getExamWithDetails($examId);
        if (!$exam) {
            return redirect()->to('/exam')->with('error', 'Exam not found');
        }

        // Get available questions for the subject AND class
        $availableQuestions = $this->questionModel->where('subject_id', $exam['subject_id'])
                                                 ->where('class_id', $exam['class_id'])
                                                 ->where('is_active', 1)
                                                 ->findAll();

        // Get currently assigned questions
        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $assignedQuestions = $examQuestionModel->getExamQuestions($examId);

        $data = [
            'title' => 'Assign Questions - ' . $exam['title'],
            'pageTitle' => 'Assign Questions',
            'pageSubtitle' => $exam['title'],
            'exam' => $exam,
            'availableQuestions' => $availableQuestions,
            'assignedQuestions' => $assignedQuestions
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processAssignQuestions($examId);
        }

        return view('exams/assign_questions', $data);
    }

    /**
     * Process question assignment
     */
    private function processAssignQuestions($examId)
    {
        $selectedQuestions = $this->request->getPost('questions') ?? [];

        if (empty($selectedQuestions)) {
            return redirect()->back()->with('error', 'Please select at least one question');
        }

        $examQuestionModel = new \App\Models\ExamQuestionModel();

        // Validate questions belong to correct class before assignment
        $validation = $examQuestionModel->validateQuestionClassMatch($examId, $selectedQuestions);
        if (!$validation['valid']) {
            $errorMessage = 'Class validation failed: ' . implode(', ', $validation['errors']);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Remove existing assignments
        $examQuestionModel->where('exam_id', $examId)->delete();

        // Prepare question data
        $questionData = [];
        foreach ($selectedQuestions as $questionId) {
            $questionData[] = [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'order_index' => array_search($questionId, $selectedQuestions) + 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        // Add new assignments
        if ($examQuestionModel->insertBatch($questionData)) {
            // Update exam question count
            $this->examModel->update($examId, ['question_count' => count($selectedQuestions)]);
            return redirect()->to('/exam/view/' . $examId)->with('success', 'Questions assigned successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to assign questions. Please try again.');
        }
    }
}
