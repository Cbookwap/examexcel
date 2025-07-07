<?php

namespace App\Controllers;

use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use App\Models\SubjectModel;
use App\Models\UserModel;
use App\Models\AcademicSessionModel;
use App\Models\AcademicTermModel;
use App\Models\ClassModel;
use App\Models\ExamTypeModel;
use App\Models\QuestionInstructionModel;
use CodeIgniter\Controller;

class Questions extends Controller
{
    protected $questionModel;
    protected $optionModel;
    protected $subjectModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->questionModel = new QuestionModel();
        $this->optionModel = new QuestionOptionModel();
        $this->subjectModel = new SubjectModel();
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);

        // Check if user is logged in (skip for AJAX requests - handle in individual methods)
        $request = \Config\Services::request();
        if (!$this->session->get('is_logged_in') && !$request->isAJAX()) {
            redirect()->to('/auth/login')->send();
            exit;
        }
    }

    public function index()
    {
        try {
            $userRole = $this->session->get('role');

            // Check permissions
            if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
                return redirect()->to('/')->with('error', 'Access denied.');
            }

            // Get filters
            $filters = [
                'subject_id' => $this->request->getGet('subject'),
                'question_type' => $this->request->getGet('type'),
                'difficulty' => $this->request->getGet('difficulty'),
                'class_id' => $this->request->getGet('class'),
                'session_id' => $this->request->getGet('session'),
                'term_id' => $this->request->getGet('term'),
                'search' => $this->request->getGet('search')
            ];

            // Get pagination parameters
            $page = (int)($this->request->getGet('page') ?? 1);
            $perPage = (int)($this->request->getGet('per_page') ?? 15);

            // Validate per page limits
            if ($perPage < 5) $perPage = 5;
            if ($perPage > 100) $perPage = 100;

            // Build query based on role and filters with pagination
            $paginatedData = $this->getFilteredQuestionsWithPagination($filters, $page, $perPage);



            $classModel = new ClassModel();

            // Get settings for AI functionality
            $settingsModel = new \App\Models\SettingsModel();
            $settings = $settingsModel->getAllSettings();

            // Fetch AI API key for selected provider/model from new table
            $aiApiKey = '';
            if (!empty($settings['ai_model_provider']) && !empty($settings['ai_model'])) {
                try {
                    $apiKeyModel = new \App\Models\AIAPIKeyModel();
                    $apiKeyRow = $apiKeyModel->getApiKey($settings['ai_model_provider'], $settings['ai_model']);
                    if ($apiKeyRow && !empty($apiKeyRow['api_key'])) {
                        $aiApiKey = $apiKeyRow['api_key'];
                    }


                } catch (\Exception $e) {
                    log_message('error', 'Failed to load AI API key in Questions: ' . $e->getMessage());
                    // Continue without AI key
                }
            }

            $data = [
                'title' => 'Question Bank - ExamExcel',
                'pageTitle' => 'Question Bank',
                'pageSubtitle' => 'Manage questions for exams and assessments',
                'questions' => $paginatedData['questions'],
                'pager' => $paginatedData['pager'],
                'total_questions' => $paginatedData['total'],
                'current_page' => $page,
                'per_page' => $perPage,
                'subjects' => $this->getAvailableSubjects(),
                'classes' => $this->getAvailableClasses(),
                'question_types' => QuestionModel::TYPES,
                'difficulties' => QuestionModel::DIFFICULTIES,
                'filters' => $filters,
                'stats' => $this->getQuestionStatsForRole($userRole),
                'sessions' => $this->getAllSessions(),
                'terms' => $this->getAllTerms(),
                'current_session' => $this->getCurrentSession(),
                'current_term' => $this->getCurrentTerm(),
                'layout' => $this->getLayoutForRole($userRole),
                'route_prefix' => $this->getRoutePrefix($userRole),
                'settings' => $settings,
                'ai_api_key' => $aiApiKey
            ];

            // Add cache-busting headers to prevent browser caching
            $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
            $this->response->setHeader('Pragma', 'no-cache');
            $this->response->setHeader('Expires', '0');

            return view('questions/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Questions index page failed to load: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Return error message instead of blank page
            echo "<div style='padding: 20px; font-family: Arial;'>";
            echo "<h2>Question Bank Error</h2>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='" . base_url('admin/dashboard') . "'>Return to Dashboard</a></p>";
            echo "</div>";
            exit;
        }
    }

    public function create()
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $classModel = new ClassModel();
        $examTypeModel = new ExamTypeModel();
        $instructionModel = new QuestionInstructionModel();

        $data = [
            'title' => 'Create Question - ExamExcel',
            'pageTitle' => 'Create New Question',
            'pageSubtitle' => 'Add a new question to the question bank',
            'subjects' => $this->getAvailableSubjects(),
            'classes' => $this->getAvailableClasses(),
            'examTypes' => $examTypeModel->getActiveExamTypes(),
            'instructions' => $instructionModel->getActiveInstructions(),
            'question_types' => QuestionModel::TYPES,
            'difficulties' => QuestionModel::DIFFICULTIES,
            'validation' => \Config\Services::validation(),
            'layout' => $this->getLayoutForRole($userRole),
            'route_prefix' => $this->getRoutePrefix($userRole)
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processCreate();
        }

        // Add session and term data
        $data['current_session'] = $this->getCurrentSession();
        $data['current_term'] = $this->getCurrentTerm();

        return view('questions/create_enhanced', $data);
    }

    private function processCreate()
    {
        $rules = [
            'subject_id' => 'required|integer',
            'question_text' => 'required|min_length[6]',
            'question_type' => 'required|in_list[mcq,true_false,yes_no,fill_blank,short_answer,essay,drag_drop,image_based,math_equation]',
            'difficulty' => 'required|in_list[easy,medium,hard]',
            'points' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->create();
        }

        // Check if teacher has permission for this subject-class combination
        $userRole = $this->session->get('role');
        if ($userRole === 'teacher') {
            $userId = $this->session->get('user_id');
            $subjectId = $this->request->getPost('subject_id');
            $classId = $this->request->getPost('class_id');

            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            if (!$assignmentModel->isTeacherAssigned($userId, $subjectId, $classId, $sessionId)) {
                session()->setFlashdata('error', 'You are not assigned to this subject-class combination.');
                return redirect()->back()->withInput();
            }
        }

        $customInstruction = $this->request->getPost('custom_instruction');
        $instructionId = null;

        // If custom instruction is provided, save it as a new instruction template
        if (!empty($customInstruction)) {
            $instructionModel = new QuestionInstructionModel();
            $instructionData = [
                'title' => 'Auto-saved: ' . substr($customInstruction, 0, 50) . '...',
                'instruction_text' => $customInstruction,
                'is_active' => 1,
                'created_by' => $this->session->get('user_id')
            ];
            $instructionId = $instructionModel->insert($instructionData);
        }

        $questionData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'class_id' => $this->request->getPost('class_id') ?: null,
            'session_id' => $this->request->getPost('session_id'),
            'term_id' => $this->request->getPost('term_id'),
            'exam_type_id' => $this->request->getPost('exam_type_id') ?: null,
            'instruction_id' => $instructionId,
            'question_text' => $this->request->getPost('question_text'),
            'question_type' => $this->request->getPost('question_type'),
            'difficulty' => $this->request->getPost('difficulty'),
            'points' => $this->request->getPost('points'),
            'time_limit' => $this->request->getPost('time_limit') ?: null,
            'explanation' => $this->request->getPost('explanation'),
            'hints' => $this->request->getPost('hints'),
            'randomize_options' => $this->request->getPost('randomize_options') ? 1 : 0,
            'is_active' => 1,
            'created_by' => $this->session->get('user_id')
        ];

        // Auto-set session and term if not provided
        if (empty($questionData['session_id']) || empty($questionData['term_id'])) {
            $currentSession = $this->getCurrentSession();
            $currentTerm = $this->getCurrentTerm();

            if ($currentSession && empty($questionData['session_id'])) {
                $questionData['session_id'] = $currentSession['id'];
            }
            if ($currentTerm && empty($questionData['term_id'])) {
                $questionData['term_id'] = $currentTerm['id'];
            }
        }

        // Handle metadata for specific question types
        $metadata = [];

        if ($questionData['question_type'] === 'math_equation') {
            $metadata['equation_format'] = $this->request->getPost('equation_format');
            $metadata['allow_calculator'] = $this->request->getPost('allow_calculator') ? 1 : 0;
            $metadata['math_answers'] = $this->request->getPost('math_answers') ?: [];
            $questionData['decimal_places'] = $this->request->getPost('decimal_places') ?: 2;
            $questionData['tolerance'] = $this->request->getPost('tolerance') ?: 0.01;
        } elseif ($questionData['question_type'] === 'fill_blank') {
            $blankAnswers = $this->request->getPost('blank_answers') ?: [];
            $metadata['blank_answers'] = $blankAnswers;

            // Count the number of blanks for scoring calculation
            $questionText = $questionData['question_text'];
            $blankCount = substr_count($questionText, '[BLANK]');
            $metadata['blank_count'] = $blankCount;
        } elseif ($questionData['question_type'] === 'short_answer') {
            $metadata['short_answers'] = $this->request->getPost('short_answers') ?: [];
            $metadata['max_words'] = $this->request->getPost('max_words') ?: 50;
        } elseif ($questionData['question_type'] === 'essay') {
            $metadata['min_words'] = $this->request->getPost('min_words') ?: 100;
            $metadata['max_words_essay'] = $this->request->getPost('max_words_essay') ?: 1000;
            $metadata['grading_rubric'] = $this->request->getPost('grading_rubric') ?: '';

            // Handle AI-assisted grading rubric
            $questionData['enable_rubric'] = $this->request->getPost('enable_rubric') ? 1 : 0;
            if ($questionData['enable_rubric']) {
                $rubricData = [
                    'type' => $this->request->getPost('rubric_type'),
                    'max_score' => $this->request->getPost('rubric_max_score'),
                    'criteria' => $this->request->getPost('rubric_criteria') ?: []
                ];
                $questionData['rubric_data'] = json_encode($rubricData);
                $questionData['model_answer'] = $this->request->getPost('model_answer') ?: '';
            }
        } elseif ($questionData['question_type'] === 'image_based') {
            $metadata['image_question_type'] = $this->request->getPost('image_question_type') ?: 'clickable_areas';
        }

        if (!empty($metadata)) {
            $questionData['metadata'] = json_encode($metadata);
        }

        // Handle file upload for image-based questions
        if ($questionData['question_type'] === 'image_based') {
            $image = $this->request->getFile('question_image');
            if ($image && $image->isValid()) {
                $imageName = $image->getRandomName();
                $image->move(WRITEPATH . '../public/uploads/questions/', $imageName);
                $questionData['image_url'] = 'uploads/questions/' . $imageName;
            }
        }

        // Validate question data
        $options = $this->request->getPost('options') ?: [];
        $singleCorrectOption = $this->request->getPost('single_correct_option');

        $validationData = array_merge($questionData, ['options' => $options]);
        if ($singleCorrectOption !== null) {
            $validationData['single_correct_option'] = $singleCorrectOption;
        }

        $validationErrors = $this->questionModel->validateQuestionData($validationData);

        if (!empty($validationErrors)) {
            session()->setFlashdata('error', implode('<br>', $validationErrors));
            return redirect()->back()->withInput();
        }

        try {
            // Insert question
            $questionId = $this->questionModel->insert($questionData);

            if (!$questionId) {
                session()->setFlashdata('error', 'Failed to create question. Please try again.');
                return redirect()->back()->withInput();
            }

        // Save options if required
        if (in_array($questionData['question_type'], ['mcq', 'true_false', 'yes_no', 'drag_drop'])) {
            // Handle radio button format for True/False and Yes/No
            if (in_array($questionData['question_type'], ['true_false', 'yes_no']) && $singleCorrectOption !== null) {
                // Mark the selected option as correct
                foreach ($options as $index => &$option) {
                    $option['is_correct'] = ($index == $singleCorrectOption) ? 1 : 0;
                }
            }

            if (!$this->optionModel->saveQuestionOptions($questionId, $options)) {
                // Rollback question creation
                $this->questionModel->delete($questionId);
                session()->setFlashdata('error', 'Failed to save question options. Please try again.');
                return redirect()->back()->withInput();
            }
        } elseif (in_array($questionData['question_type'], ['fill_blank', 'short_answer', 'math_equation'])) {
            // Save answers as options for these question types
            $answers = [];

            if ($questionData['question_type'] === 'fill_blank') {
                $blankAnswers = $this->request->getPost('blank_answers') ?: [];

                // Handle the new structure: blank_answers[1][], blank_answers[2][], etc.
                foreach ($blankAnswers as $blankNumber => $answersForBlank) {
                    if (is_array($answersForBlank)) {
                        foreach ($answersForBlank as $answer) {
                            if (!empty(trim($answer))) {
                                $answers[] = [
                                    'option_text' => trim($answer),
                                    'is_correct' => 1,
                                    'blank_number' => $blankNumber // Store which blank this answer belongs to
                                ];
                            }
                        }
                    }
                }
            } elseif ($questionData['question_type'] === 'short_answer') {
                $shortAnswers = $this->request->getPost('short_answers') ?: [];
                foreach ($shortAnswers as $answer) {
                    if (!empty(trim($answer))) {
                        $answers[] = [
                            'option_text' => trim($answer),
                            'is_correct' => 1
                        ];
                    }
                }
            } elseif ($questionData['question_type'] === 'math_equation') {
                $mathAnswers = $this->request->getPost('math_answers') ?: [];
                foreach ($mathAnswers as $answer) {
                    if (!empty(trim($answer))) {
                        $answers[] = [
                            'option_text' => trim($answer),
                            'is_correct' => 1
                        ];
                    }
                }
            }

            if (!empty($answers) && !$this->optionModel->saveQuestionOptions($questionId, $answers)) {
                // Rollback question creation
                $this->questionModel->delete($questionId);
                session()->setFlashdata('error', 'Failed to save question answers. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'An error occurred while creating the question: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Question created successfully! You can continue adding more questions.');
        return redirect()->to('/questions/create');
    }

    public function edit($id)
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $question = $this->questionModel->getQuestionWithOptions($id);

        if (!$question) {
            return redirect()->to('/questions')->with('error', 'Question not found.');
        }

        // Check if teacher can edit this question
        if ($userRole === 'teacher') {
            $userId = $this->session->get('user_id');

            // Teachers can only edit questions they created
            if (!isset($question['created_by']) || $question['created_by'] != $userId) {
                return redirect()->to('/questions')->with('error', 'You can only edit questions you created.');
            }

            // Also verify teacher is assigned to this subject-class combination
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            if (!$assignmentModel->isTeacherAssigned($userId, $question['subject_id'], $question['class_id'], $sessionId)) {
                return redirect()->to('/questions')->with('error', 'You are not assigned to this subject-class combination.');
            }
        }

        $data = [
            'title' => 'Edit Question - ExamExcel',
            'pageTitle' => 'Edit Question',
            'pageSubtitle' => 'Modify question details and options',
            'question' => $question,
            'subjects' => $this->getAvailableSubjects(),
            'question_types' => QuestionModel::TYPES,
            'difficulties' => QuestionModel::DIFFICULTIES,
            'validation' => \Config\Services::validation(),
            'layout' => $this->getLayoutForRole($userRole),
            'route_prefix' => $this->getRoutePrefix($userRole)
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->processEdit($id);
        }

        return view('questions/edit', $data);
    }

    private function processEdit($id)
    {
        $rules = [
            'subject_id' => 'required|integer',
            'question_text' => 'required|min_length[6]',
            'question_type' => 'required|in_list[mcq,true_false,yes_no,fill_blank,short_answer,essay,drag_drop,image_based,math_equation]',
            'difficulty' => 'required|in_list[easy,medium,hard]',
            'points' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->edit($id);
        }

        $questionData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'question_text' => $this->request->getPost('question_text'),
            'question_type' => $this->request->getPost('question_type'),
            'difficulty' => $this->request->getPost('difficulty'),
            'points' => $this->request->getPost('points'),
            'time_limit' => $this->request->getPost('time_limit') ?: null,
            'explanation' => $this->request->getPost('explanation'),
            'hints' => $this->request->getPost('hints'),
            'randomize_options' => $this->request->getPost('randomize_options') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Handle metadata
        $metadata = [];
        if ($questionData['question_type'] === 'math_equation') {
            $metadata['equation_format'] = $this->request->getPost('equation_format');
            $metadata['allow_calculator'] = $this->request->getPost('allow_calculator') ? 1 : 0;
        }

        if (!empty($metadata)) {
            $questionData['metadata'] = json_encode($metadata);
        }

        // Handle image upload
        if ($questionData['question_type'] === 'image_based') {
            $image = $this->request->getFile('question_image');
            if ($image && $image->isValid()) {
                $imageName = $image->getRandomName();
                $image->move(WRITEPATH . '../public/uploads/questions/', $imageName);
                $questionData['image_url'] = 'uploads/questions/' . $imageName;
            }
        }

        // Update question
        if (!$this->questionModel->update($id, $questionData)) {
            session()->setFlashdata('error', 'Failed to update question. Please try again.');
            return redirect()->back()->withInput();
        }

        // Update options if required
        if (in_array($questionData['question_type'], ['mcq', 'true_false', 'yes_no', 'drag_drop'])) {
            $options = $this->request->getPost('options') ?: [];
            if (!$this->optionModel->saveQuestionOptions($id, $options)) {
                session()->setFlashdata('error', 'Failed to update question options. Please try again.');
                return redirect()->back()->withInput();
            }
        }

        session()->setFlashdata('success', 'Question updated successfully!');
        return redirect()->to('/questions');
    }

    public function delete($id)
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $question = $this->questionModel->find($id);

        if (!$question) {
            return redirect()->to('/questions')->with('error', 'Question not found.');
        }

        // Check if teacher can delete this question
        if ($userRole === 'teacher') {
            $userId = $this->session->get('user_id');

            // Teachers can only delete questions they created
            if (!isset($question['created_by']) || $question['created_by'] != $userId) {
                return redirect()->to('/questions')->with('error', 'You can only delete questions you created.');
            }

            // Also verify teacher is assigned to this subject-class combination
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            if (!$assignmentModel->isTeacherAssigned($userId, $question['subject_id'], $question['class_id'], $sessionId)) {
                return redirect()->to('/questions')->with('error', 'You are not assigned to this subject-class combination.');
            }
        }

        // Delete options first
        $this->optionModel->deleteQuestionOptions($id);

        // Delete question
        if ($this->questionModel->delete($id)) {
            session()->setFlashdata('success', 'Question deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete question.');
        }

        return redirect()->to('/questions');
    }

    public function duplicate($id)
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $question = $this->questionModel->getQuestionWithOptions($id);

        if (!$question) {
            return redirect()->to('/questions')->with('error', 'Question not found.');
        }

        // Duplicate question
        $newQuestionId = $this->questionModel->duplicateQuestion($id);

        if ($newQuestionId) {
            // Duplicate options if they exist
            if (!empty($question['options'])) {
                $this->optionModel->duplicateOptions($id, $newQuestionId);
            }

            session()->setFlashdata('success', 'Question duplicated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to duplicate question.');
        }

        return redirect()->to('/questions');
    }

    private function getFilteredQuestions($filters)
    {
        $userRole = $this->session->get('role');
        $userId = $this->session->get('user_id');

        if (!empty($filters['search'])) {
            $questions = $this->questionModel->searchQuestions($filters['search'], $filters);
        } else {
            $questions = $this->questionModel->getQuestionsWithSubjectAndClass($filters);
        }

        // Filter by user role
        if ($userRole === 'teacher') {
            // Get teacher's assigned subjects and classes from the assignment system
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            $assignments = $assignmentModel->getTeacherAssignments($userId, $sessionId);

            // Extract subject-class combinations that teacher is assigned to
            $allowedCombinations = [];
            foreach ($assignments as $assignment) {
                $allowedCombinations[] = [
                    'subject_id' => $assignment['subject_id'],
                    'class_id' => $assignment['class_id']
                ];
            }

            // Filter questions to only show:
            // 1. Questions from assigned subject-class combinations
            // 2. Questions created by this teacher
            $questions = array_filter($questions, function($question) use ($allowedCombinations, $userId) {
                // Check if teacher created this question
                if (isset($question['created_by']) && $question['created_by'] == $userId) {
                    return true;
                }

                // Check if question is from assigned subject-class combination
                foreach ($allowedCombinations as $combination) {
                    if ($question['subject_id'] == $combination['subject_id'] &&
                        $question['class_id'] == $combination['class_id']) {
                        // Only show if teacher created this question
                        return isset($question['created_by']) && $question['created_by'] == $userId;
                    }
                }

                return false;
            });
        }

        return $questions;
    }

    private function getFilteredQuestionsWithPagination($filters, $page = 1, $perPage = 15)
    {
        $userRole = $this->session->get('role');
        $userId = $this->session->get('user_id');

        // Build base query - simplified to avoid JOIN duplication issues
        $builder = $this->questionModel->builder();
        $builder->select('q.id, q.subject_id, q.class_id, q.session_id, q.term_id, q.question_text,
                         q.question_type, q.difficulty, q.points, q.explanation, q.hints, q.is_active,
                         q.created_by, q.created_at, q.updated_at');
        $builder->from('questions q');

        // Apply filters
        if (!empty($filters['subject_id'])) {
            $builder->where('q.subject_id', $filters['subject_id']);
        }
        if (!empty($filters['question_type'])) {
            $builder->where('q.question_type', $filters['question_type']);
        }
        if (!empty($filters['difficulty'])) {
            $builder->where('q.difficulty', $filters['difficulty']);
        }
        if (!empty($filters['class_id'])) {
            $builder->where('q.class_id', $filters['class_id']);
        }
        if (!empty($filters['session_id'])) {
            $builder->where('q.session_id', $filters['session_id']);
        }
        if (!empty($filters['term_id'])) {
            $builder->where('q.term_id', $filters['term_id']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('q.question_text', $filters['search'])
                    ->orLike('q.explanation', $filters['search'])
                    ->orLike('q.hints', $filters['search'])
                    ->groupEnd();
        }

        // Apply is_active filter - CRITICAL: Only show active questions
        $builder->where('q.is_active', 1);

        // Apply role-based filtering
        if ($userRole === 'teacher') {
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            $allowedCombinations = $assignmentModel->getTeacherAssignments($userId, $sessionId);

            if (!empty($allowedCombinations)) {
                $builder->groupStart();
                foreach ($allowedCombinations as $index => $combination) {
                    if ($index === 0) {
                        $builder->where('(q.subject_id = ' . $combination['subject_id'] . ' AND q.class_id = ' . $combination['class_id'] . ' AND q.created_by = ' . $userId . ')');
                    } else {
                        $builder->orWhere('(q.subject_id = ' . $combination['subject_id'] . ' AND q.class_id = ' . $combination['class_id'] . ' AND q.created_by = ' . $userId . ')');
                    }
                }
                $builder->groupEnd();
            } else {
                // No assignments, only show questions created by this teacher
                $builder->where('q.created_by', $userId);
            }
        }

        // Get total count for pagination using the same filters
        $countBuilder = clone $builder;
        $countBuilder->select('COUNT(DISTINCT q.id) as count');
        $total = $countBuilder->get()->getRow()->count;

        // Debug logging
        log_message('debug', 'Total questions found: ' . $total);
        log_message('debug', 'Page: ' . $page . ', Per page: ' . $perPage);
        log_message('debug', 'Offset: ' . (($page - 1) * $perPage));

        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $builder->orderBy('q.created_at', 'DESC');
        $builder->limit($perPage, $offset);

        $questions = $builder->get()->getResultArray();

        // Debug logging
        log_message('debug', 'Questions returned: ' . count($questions));
        if (!empty($questions)) {
            log_message('debug', 'First question ID: ' . $questions[0]['id']);
            log_message('debug', 'Last question ID: ' . $questions[count($questions)-1]['id']);
        }

        // Enrich questions with related data
        if (!empty($questions)) {
            $questions = $this->enrichQuestionsWithRelatedData($questions);
        }

        // Create pagination object
        $pager = \Config\Services::pager();
        $pager->store('default', $page, $perPage, $total);

        return [
            'questions' => $questions,
            'total' => $total,
            'pager' => $pager
        ];
    }

    /**
     * Enrich questions with related data to avoid JOIN duplication issues
     */
    private function enrichQuestionsWithRelatedData($questions)
    {
        if (empty($questions)) {
            return $questions;
        }

        // Get all unique IDs for batch fetching
        $subjectIds = array_unique(array_filter(array_column($questions, 'subject_id')));
        $classIds = array_unique(array_filter(array_column($questions, 'class_id')));
        $sessionIds = array_unique(array_filter(array_column($questions, 'session_id')));
        $termIds = array_unique(array_filter(array_column($questions, 'term_id')));
        $userIds = array_unique(array_filter(array_column($questions, 'created_by')));

        // Batch fetch related data
        $subjects = [];
        $classes = [];
        $sessions = [];
        $terms = [];
        $users = [];

        if (!empty($subjectIds)) {
            $subjectModel = new \App\Models\SubjectModel();
            $subjectResults = $subjectModel->whereIn('id', $subjectIds)->findAll();
            foreach ($subjectResults as $subject) {
                $subjects[$subject['id']] = $subject;
            }
        }

        if (!empty($classIds)) {
            $classModel = new \App\Models\ClassModel();
            $classResults = $classModel->whereIn('id', $classIds)->findAll();
            foreach ($classResults as $class) {
                $classes[$class['id']] = $class;
            }
        }

        if (!empty($sessionIds)) {
            $sessionModel = new \App\Models\AcademicSessionModel();
            $sessionResults = $sessionModel->whereIn('id', $sessionIds)->findAll();
            foreach ($sessionResults as $session) {
                $sessions[$session['id']] = $session;
            }
        }

        if (!empty($termIds)) {
            $termModel = new \App\Models\AcademicTermModel();
            $termResults = $termModel->whereIn('id', $termIds)->findAll();
            foreach ($termResults as $term) {
                $terms[$term['id']] = $term;
            }
        }

        if (!empty($userIds)) {
            $userModel = new \App\Models\UserModel();
            $userResults = $userModel->whereIn('id', $userIds)->findAll();
            foreach ($userResults as $user) {
                $users[$user['id']] = $user;
            }
        }

        // Enrich each question with related data
        foreach ($questions as &$question) {
            $question['subject_name'] = $subjects[$question['subject_id']]['name'] ?? 'N/A';
            $question['subject_code'] = $subjects[$question['subject_id']]['code'] ?? '';
            $question['class_name'] = $classes[$question['class_id']]['name'] ?? 'N/A';
            $question['session_name'] = $sessions[$question['session_id']]['session_name'] ?? 'N/A';
            $question['term_name'] = $terms[$question['term_id']]['term_name'] ?? 'N/A';
            $question['term_number'] = $terms[$question['term_id']]['term_number'] ?? '';
            $question['first_name'] = $users[$question['created_by']]['first_name'] ?? '';
            $question['last_name'] = $users[$question['created_by']]['last_name'] ?? '';
        }

        return $questions;
    }

    private function getAvailableSubjects()
    {
        $userRole = $this->session->get('role');

        if ($userRole === 'admin' || $userRole === 'principal') {
            return $this->subjectModel->getActiveSubjects();
        } else {
            // For teachers, get subjects from assignment system
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            return $assignmentModel->getTeacherSubjects($this->session->get('user_id'), $sessionId);
        }
    }

    private function getAvailableClasses($subjectId = null)
    {
        $userRole = $this->session->get('role');
        $classModel = new \App\Models\ClassModel();

        if ($userRole === 'admin' || $userRole === 'principal') {
            $classes = $classModel->where('is_active', 1)->findAll();
        } else {
            // For teachers, get classes from assignment system for specific subject
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;
            $userId = $this->session->get('user_id');

            $assignments = $assignmentModel->getTeacherAssignments($userId, $sessionId);

            // Filter by subject if provided
            if ($subjectId) {
                $assignments = array_filter($assignments, function($assignment) use ($subjectId) {
                    return $assignment['subject_id'] == $subjectId;
                });
            }

            // Extract unique classes
            $classIds = array_unique(array_column($assignments, 'class_id'));

            if (empty($classIds)) {
                return [];
            }

            $classes = $classModel->whereIn('id', $classIds)->where('is_active', 1)->findAll();
        }

        // Add display name with category for each class
        foreach ($classes as &$class) {
            $class['display_name'] = $this->getClassDisplayName($class);
        }

        return $classes;
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

    public function preview($id)
    {
        $userRole = $this->session->get('role');
        $question = $this->questionModel->getQuestionWithOptions($id);

        if (!$question) {
            return redirect()->to('/questions')->with('error', 'Question not found.');
        }

        // Check if teacher can preview this question
        if ($userRole === 'teacher') {
            $userId = $this->session->get('user_id');

            // Teachers can only preview questions they created
            if (!isset($question['created_by']) || $question['created_by'] != $userId) {
                return redirect()->to('/questions')->with('error', 'You can only preview questions you created.');
            }

            // Also verify teacher is assigned to this subject-class combination
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            if (!$assignmentModel->isTeacherAssigned($userId, $question['subject_id'], $question['class_id'], $sessionId)) {
                return redirect()->to('/questions')->with('error', 'You are not assigned to this subject-class combination.');
            }
        }

        $data = [
            'title' => 'Preview Question - SRMS CBT System',
            'question' => $question,
            'question_types' => QuestionModel::TYPES,
            'difficulties' => QuestionModel::DIFFICULTIES
        ];

        return view('questions/preview', $data);
    }

    public function bulkActions()
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $action = $this->request->getPost('action');
        $questionIdsString = $this->request->getPost('question_ids');

        if (empty($action) || empty($questionIdsString)) {
            return redirect()->back()->with('error', 'Please select questions and an action.');
        }

        $questionIds = explode(',', $questionIdsString);
        $count = 0;

        foreach ($questionIds as $id) {
            $id = trim($id);
            if (empty($id)) continue;

            // Check if teacher can modify this question
            if ($userRole === 'teacher') {
                $question = $this->questionModel->find($id);
                if ($question) {
                    $userId = $this->session->get('user_id');

                    // Teachers can only modify questions they created
                    if (!isset($question['created_by']) || $question['created_by'] != $userId) {
                        continue; // Skip questions not created by this teacher
                    }

                    // Also verify teacher is assigned to this subject-class combination
                    $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
                    $currentSession = $this->getCurrentSession();
                    $sessionId = $currentSession['id'] ?? null;

                    if (!$assignmentModel->isTeacherAssigned($userId, $question['subject_id'], $question['class_id'], $sessionId)) {
                        continue; // Skip questions from unassigned subject-class combinations
                    }
                }
            }

            switch ($action) {
                case 'delete':
                    $this->optionModel->deleteQuestionOptions($id);
                    if ($this->questionModel->delete($id)) {
                        $count++;
                    }
                    break;
                case 'change_difficulty':
                    $newDifficulty = $this->request->getPost('new_difficulty');
                    if (!empty($newDifficulty) && in_array($newDifficulty, array_keys(QuestionModel::DIFFICULTIES))) {
                        if ($this->questionModel->update($id, ['difficulty' => $newDifficulty])) {
                            $count++;
                        }
                    }
                    break;
                case 'change_subject':
                    $newSubject = $this->request->getPost('new_subject');
                    if (!empty($newSubject)) {
                        // Check if teacher has access to new subject
                        if ($userRole === 'teacher') {
                            $userId = $this->session->get('user_id');
                            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
                            $currentSession = $this->getCurrentSession();
                            $sessionId = $currentSession['id'] ?? null;

                            // Get current question to check class
                            $currentQuestion = $this->questionModel->find($id);
                            if (!$currentQuestion) {
                                continue 2;
                            }

                            // Check if teacher is assigned to new subject with same class
                            if (!$assignmentModel->isTeacherAssigned($userId, $newSubject, $currentQuestion['class_id'], $sessionId)) {
                                continue 2; // Skip this question and continue with the next one in the foreach loop
                            }
                        }
                        if ($this->questionModel->update($id, ['subject_id' => $newSubject])) {
                            $count++;
                        }
                    }
                    break;
            }
        }

        session()->setFlashdata('success', "{$count} questions processed successfully.");
        return redirect()->back();
    }

    public function saveInstructionTemplate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userRole = $this->session->get('role');
        if (!in_array($userRole, ['admin', 'teacher'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $input = json_decode($this->request->getBody(), true);

        if (empty($input['title']) || empty($input['instruction_text'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Title and instruction text are required']);
        }

        $instructionModel = new QuestionInstructionModel();

        $data = [
            'title' => trim($input['title']),
            'instruction_text' => trim($input['instruction_text']),
            'is_active' => 1,
            'created_by' => $this->session->get('user_id')
        ];

        try {
            $instructionModel->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Instruction template saved successfully']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error saving template: ' . $e->getMessage()]);
        }
    }

    public function bulkCreate()
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $classModel = new ClassModel();
        $examTypeModel = new ExamTypeModel();
        $instructionModel = new QuestionInstructionModel();

        $data = [
            'title' => 'Bulk Create Questions - ExamExcel',
            'pageTitle' => 'Bulk Create Questions',
            'pageSubtitle' => 'Create multiple questions at once',
            'subjects' => $this->getAvailableSubjects(),
            'classes' => $this->getAvailableClasses(),
            'examTypes' => $examTypeModel->getActiveExamTypes(),
            'instructions' => $instructionModel->getActiveInstructions(),
            'question_types' => QuestionModel::TYPES,
            'difficulties' => QuestionModel::DIFFICULTIES,
            'validation' => \Config\Services::validation(),
            'layout' => $this->getLayoutForRole($userRole),
            'route_prefix' => $this->getRoutePrefix($userRole)
        ];

        // Add session and term data
        $data['current_session'] = $this->getCurrentSession();
        $data['current_term'] = $this->getCurrentTerm();

        return view('questions/bulk_create', $data);
    }

    public function processBulkCreate()
    {
        $userRole = $this->session->get('role');

        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return redirect()->to('/questions')->with('error', 'Access denied.');
        }

        $questions = $this->request->getPost('questions');
        $subjectId = $this->request->getPost('subject_id');
        $classId = $this->request->getPost('class_id');
        $sessionId = $this->request->getPost('session_id');
        $termId = $this->request->getPost('term_id');
        $examTypeId = $this->request->getPost('exam_type_id');

        if (empty($questions) || empty($subjectId)) {
            return redirect()->back()->with('error', 'Please provide questions and select a subject.');
        }

        // Check if teacher has permission for this subject-class combination
        if ($userRole === 'teacher') {
            $userId = $this->session->get('user_id');
            $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
            $currentSession = $this->getCurrentSession();
            $sessionId = $currentSession['id'] ?? null;

            if (!$assignmentModel->isTeacherAssigned($userId, $subjectId, $classId, $sessionId)) {
                return redirect()->back()->with('error', 'You are not assigned to this subject-class combination.');
            }
        }

        // Auto-set session and term if not provided
        if (empty($sessionId) || empty($termId)) {
            $currentSession = $this->getCurrentSession();
            $currentTerm = $this->getCurrentTerm();

            if ($currentSession && empty($sessionId)) {
                $sessionId = $currentSession['id'];
            }
            if ($currentTerm && empty($termId)) {
                $termId = $currentTerm['id'];
            }
        }

        $createdCount = 0;
        $errors = [];

        foreach ($questions as $index => $questionData) {
            if (empty($questionData['question_text']) || empty($questionData['question_type'])) {
                continue; // Skip incomplete questions
            }

            // Handle individual custom instruction for each question
            $instructionId = null;
            if (!empty($questionData['custom_instruction'])) {
                $instructionModel = new QuestionInstructionModel();
                $instructionData = [
                    'title' => 'Auto-saved: ' . substr($questionData['custom_instruction'], 0, 50) . '...',
                    'instruction_text' => $questionData['custom_instruction'],
                    'is_active' => 1,
                    'created_by' => $this->session->get('user_id')
                ];
                $instructionId = $instructionModel->insert($instructionData);
            }

            $data = [
                'subject_id' => $subjectId,
                'class_id' => $classId ?: null,
                'session_id' => $sessionId,
                'term_id' => $termId,
                'exam_type_id' => $examTypeId ?: null,
                'instruction_id' => $instructionId ?: null,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'difficulty' => $questionData['difficulty'] ?? 'easy',
                'points' => $questionData['points'] ?? 1,
                'time_limit' => $questionData['time_limit'] ?: null,
                'explanation' => $questionData['explanation'] ?? null,
                'hints' => $questionData['hints'] ?? null,
                'randomize_options' => 0,
                'is_active' => 1,
                'created_by' => $this->session->get('user_id')
            ];

            // Handle metadata for specific question types
            $metadata = [];

            if ($data['question_type'] === 'math_equation') {
                $metadata['equation_format'] = $questionData['equation_format'] ?? 'text';
                $metadata['allow_calculator'] = isset($questionData['allow_calculator']) ? 1 : 0;
                $metadata['math_answers'] = $questionData['math_answers'] ?? [];
                $data['decimal_places'] = $questionData['decimal_places'] ?? 2;
                $data['tolerance'] = $questionData['tolerance'] ?? 0.01;
            } elseif ($data['question_type'] === 'fill_blank') {
                $blankAnswers = $questionData['blank_answers'] ?? [];
                $metadata['blank_answers'] = $blankAnswers;
                $blankCount = substr_count($data['question_text'], '[BLANK]');
                $metadata['blank_count'] = $blankCount;
            } elseif ($data['question_type'] === 'short_answer') {
                $metadata['short_answers'] = $questionData['short_answers'] ?? [];
                $metadata['max_words'] = $questionData['max_words'] ?? 50;
            } elseif ($data['question_type'] === 'essay') {
                $metadata['min_words'] = $questionData['min_words'] ?? 100;
                $metadata['max_words_essay'] = $questionData['max_words_essay'] ?? 1000;
                $metadata['grading_rubric'] = $questionData['grading_rubric'] ?? '';

                // Handle AI-assisted grading rubric
                $data['enable_rubric'] = isset($questionData['enable_rubric']) ? 1 : 0;
                if ($data['enable_rubric']) {
                    $rubricData = [
                        'type' => $questionData['rubric_type'] ?? 'content_only',
                        'max_score' => $questionData['rubric_max_score'] ?? 10,
                        'criteria' => $questionData['rubric_criteria'] ?? []
                    ];
                    $data['rubric_data'] = json_encode($rubricData);
                    $data['model_answer'] = $questionData['model_answer'] ?? '';
                }
            } elseif ($data['question_type'] === 'image_based') {
                $metadata['image_question_type'] = $questionData['image_question_type'] ?? 'clickable_areas';
            }

            if (!empty($metadata)) {
                $data['metadata'] = json_encode($metadata);
            }



            // Validate question data
            $validationData = $data;
            if (isset($questionData['options'])) {
                $validationData['options'] = $questionData['options'];
            }
            if (isset($questionData['correct_option'])) {
                $validationData['single_correct_option'] = $questionData['correct_option'];
            }

            $validationErrors = $this->questionModel->validateQuestionData($validationData);
            if (!empty($validationErrors)) {
                $errors[] = "Question " . ($index + 1) . ": " . implode(', ', $validationErrors);
                continue;
            }

            // Insert question
            $questionId = $this->questionModel->insert($data);

            if ($questionId) {
                // Handle options if provided
                if (isset($questionData['options']) && in_array($data['question_type'], ['mcq', 'true_false', 'yes_no', 'drag_drop'])) {
                    $options = $questionData['options'];

                    // Handle radio button format for True/False and Yes/No
                    if (in_array($data['question_type'], ['true_false', 'yes_no']) && isset($questionData['correct_option'])) {
                        // Mark the selected option as correct
                        foreach ($options as $optionIndex => &$option) {
                            $option['is_correct'] = ($optionIndex == $questionData['correct_option']) ? 1 : 0;
                        }
                    }

                    if (!$this->optionModel->saveQuestionOptions($questionId, $options)) {
                        $errors[] = "Question " . ($index + 1) . ": Failed to save options";
                        // Don't delete the question, just note the error
                    }
                } elseif (in_array($data['question_type'], ['fill_blank', 'short_answer', 'math_equation'])) {
                    // Save answers as options for these question types
                    $answers = [];

                    if ($data['question_type'] === 'fill_blank') {
                        $blankAnswers = $questionData['blank_answers'] ?? [];
                        foreach ($blankAnswers as $blankNumber => $answersForBlank) {
                            if (is_array($answersForBlank)) {
                                foreach ($answersForBlank as $answer) {
                                    if (!empty(trim($answer))) {
                                        $answers[] = [
                                            'option_text' => trim($answer),
                                            'is_correct' => 1,
                                            'blank_number' => $blankNumber
                                        ];
                                    }
                                }
                            }
                        }
                    } elseif ($data['question_type'] === 'short_answer') {
                        $shortAnswers = $questionData['short_answers'] ?? [];
                        foreach ($shortAnswers as $answer) {
                            if (!empty(trim($answer))) {
                                $answers[] = [
                                    'option_text' => trim($answer),
                                    'is_correct' => 1
                                ];
                            }
                        }
                    } elseif ($data['question_type'] === 'math_equation') {
                        $mathAnswers = $questionData['math_answers'] ?? [];
                        foreach ($mathAnswers as $answer) {
                            if (!empty(trim($answer))) {
                                $answers[] = [
                                    'option_text' => trim($answer),
                                    'is_correct' => 1
                                ];
                            }
                        }
                    }

                    if (!empty($answers) && !$this->optionModel->saveQuestionOptions($questionId, $answers)) {
                        $errors[] = "Question " . ($index + 1) . ": Failed to save answers";
                        // Don't delete the question, just note the error
                    }
                }
                $createdCount++;
            } else {
                $errors[] = "Question " . ($index + 1) . ": Failed to create";
            }
        }

        if ($createdCount > 0) {
            $message = "{$createdCount} questions created successfully.";
            if (!empty($errors)) {
                $message .= " However, there were some issues: " . implode('; ', $errors);
            }
            session()->setFlashdata('success', $message);
        } else {
            session()->setFlashdata('error', 'No questions were created. ' . implode('; ', $errors));
        }

        return redirect()->to('/questions');
    }

    private function getCurrentSession()
    {
        $sessionModel = new AcademicSessionModel();
        return $sessionModel->getCurrentSession();
    }

    private function getCurrentTerm()
    {
        $termModel = new AcademicTermModel();
        return $termModel->getCurrentTerm();
    }

    private function getAllSessions()
    {
        $sessionModel = new AcademicSessionModel();
        return $sessionModel->findAll();
    }

    private function getAllTerms()
    {
        $termModel = new AcademicTermModel();
        return $termModel->findAll();
    }

    /**
     * AJAX method to create question and return JSON response
     */
    public function createAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $userRole = $this->session->get('role');
        if (!in_array($userRole, ['admin', 'teacher'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Use the same validation and processing logic as regular create
        $rules = [
            'subject_id' => 'required|integer',
            'question_text' => 'required|min_length[6]',
            'question_type' => 'required|in_list[mcq,true_false,yes_no,fill_blank,short_answer,essay,drag_drop,image_based,math_equation]',
            'difficulty' => 'required|in_list[easy,medium,hard]',
            'points' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Process the question creation (reuse existing logic)
        $result = $this->processQuestionCreation();

        if ($result['success']) {
            // Get updated question count
            $count = $this->getQuestionCountForFilters(
                $this->request->getPost('subject_id'),
                $this->request->getPost('class_id'),
                $this->request->getPost('session_id'),
                $this->request->getPost('term_id')
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Question created successfully!',
                'question_id' => $result['question_id'],
                'question_count' => $count
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }

    /**
     * Check for duplicate questions
     */
    public function checkDuplicate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        // Check authentication for AJAX requests
        if (!$this->session->get('is_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Authentication required']);
        }

        $userRole = $this->session->get('role');
        if (!in_array($userRole, ['admin', 'teacher'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $questionText = $this->request->getPost('question_text');
        $questionType = $this->request->getPost('question_type');
        $subjectId = $this->request->getPost('subject_id');
        $termId = $this->request->getPost('term_id');
        $examTypeId = $this->request->getPost('exam_type_id');
        $excludeId = $this->request->getPost('exclude_id'); // For edit form to exclude current question
        $options = $this->request->getPost('options') ?: [];

        // If options is a JSON string, decode it
        if (is_string($options)) {
            $options = json_decode($options, true) ?: [];
        }

        if (empty($questionText) || empty($questionType)) {
            return $this->response->setJSON(['is_duplicate' => false, 'message' => 'Missing required fields']);
        }

        // If term_id or exam_type_id are not provided, use current session/term
        if (empty($termId)) {
            $currentTerm = $this->getCurrentTerm();
            $termId = $currentTerm['id'] ?? null;
        }

        // If no subject_id provided, return false (can't check duplicates without subject)
        if (empty($subjectId)) {
            return $this->response->setJSON(['is_duplicate' => false, 'message' => 'Subject ID required for duplicate checking']);
        }

        $isDuplicate = $this->questionModel->checkDuplicateQuestion(
            $questionText,
            $questionType,
            $subjectId,
            $options,
            $termId,
            $examTypeId,
            $excludeId
        );

        $message = '';
        if ($isDuplicate) {
            $message = 'A similar question already exists';
            if ($subjectId) {
                $subjectModel = new SubjectModel();
                $subject = $subjectModel->find($subjectId);
                $message .= ' in ' . ($subject['name'] ?? 'this subject');
            }
            if ($termId) {
                $termModel = new AcademicTermModel();
                $term = $termModel->find($termId);
                $message .= ' for ' . ($term['term_name'] ?? 'this term');
            }
            if ($examTypeId) {
                $examTypeModel = new ExamTypeModel();
                $examType = $examTypeModel->find($examTypeId);
                $message .= ' (' . ($examType['name'] ?? 'this exam type') . ')';
            }
            $message .= '.';
        }

        return $this->response->setJSON([
            'is_duplicate' => $isDuplicate,
            'message' => $message
        ]);
    }

    /**
     * Get question count for specific filters
     */
    public function getQuestionCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $subjectId = $this->request->getGet('subject_id');
        $classId = $this->request->getGet('class_id');
        $sessionId = $this->request->getGet('session_id');
        $termId = $this->request->getGet('term_id');

        $count = $this->getQuestionCountForFilters($subjectId, $classId, $sessionId, $termId);

        return $this->response->setJSON([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Helper method to get question count for specific filters
     */
    private function getQuestionCountForFilters($subjectId, $classId = null, $sessionId = null, $termId = null)
    {
        $builder = $this->questionModel->builder();

        if ($subjectId) {
            $builder->where('subject_id', $subjectId);
        }

        if ($classId) {
            $builder->where('class_id', $classId);
        }

        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        } else {
            // Use current session if not specified
            $currentSession = $this->getCurrentSession();
            if ($currentSession) {
                $builder->where('session_id', $currentSession['id']);
            }
        }

        if ($termId) {
            $builder->where('term_id', $termId);
        } else {
            // Use current term if not specified
            $currentTerm = $this->getCurrentTerm();
            if ($currentTerm) {
                $builder->where('term_id', $currentTerm['id']);
            }
        }

        // Only count active questions
        $builder->where('is_active', 1);

        return $builder->countAllResults();
    }

    /**
     * Extract question creation logic for reuse
     */
    private function processQuestionCreation()
    {
        // This contains the same logic as the existing processCreate method
        // but returns array instead of redirect

        $customInstruction = $this->request->getPost('custom_instruction');
        $instructionId = null;

        // If custom instruction is provided, save it as a new instruction template
        if (!empty($customInstruction)) {
            $instructionModel = new QuestionInstructionModel();
            $instructionData = [
                'title' => 'Auto-saved: ' . substr($customInstruction, 0, 50) . '...',
                'instruction_text' => $customInstruction,
                'is_active' => 1,
                'created_by' => $this->session->get('user_id')
            ];
            $instructionId = $instructionModel->insert($instructionData);
        }

        $questionData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'class_id' => $this->request->getPost('class_id') ?: null,
            'session_id' => $this->request->getPost('session_id'),
            'term_id' => $this->request->getPost('term_id'),
            'exam_type_id' => $this->request->getPost('exam_type_id') ?: null,
            'instruction_id' => $instructionId,
            'question_text' => $this->request->getPost('question_text'),
            'question_type' => $this->request->getPost('question_type'),
            'difficulty' => $this->request->getPost('difficulty'),
            'points' => $this->request->getPost('points'),
            'time_limit' => $this->request->getPost('time_limit') ?: null,
            'explanation' => $this->request->getPost('explanation'),
            'hints' => $this->request->getPost('hints'),
            'randomize_options' => $this->request->getPost('randomize_options') ? 1 : 0,
            'is_active' => 1,
            'created_by' => $this->session->get('user_id')
        ];

        // Auto-set session and term if not provided
        if (empty($questionData['session_id']) || empty($questionData['term_id'])) {
            $currentSession = $this->getCurrentSession();
            $currentTerm = $this->getCurrentTerm();

            if ($currentSession && empty($questionData['session_id'])) {
                $questionData['session_id'] = $currentSession['id'];
            }
            if ($currentTerm && empty($questionData['term_id'])) {
                $questionData['term_id'] = $currentTerm['id'];
            }
        }

        // Handle metadata for specific question types (same as existing logic)
        $metadata = [];

        if ($questionData['question_type'] === 'math_equation') {
            $metadata['equation_format'] = $this->request->getPost('equation_format');
            $metadata['allow_calculator'] = $this->request->getPost('allow_calculator') ? 1 : 0;
            $metadata['math_answers'] = $this->request->getPost('math_answers') ?: [];
            $questionData['decimal_places'] = $this->request->getPost('decimal_places') ?: 2;
            $questionData['tolerance'] = $this->request->getPost('tolerance') ?: 0.01;
        } elseif ($questionData['question_type'] === 'fill_blank') {
            $blankAnswers = $this->request->getPost('blank_answers') ?: [];
            $metadata['blank_answers'] = $blankAnswers;
            $questionText = $questionData['question_text'];
            $blankCount = substr_count($questionText, '[BLANK]');
            $metadata['blank_count'] = $blankCount;
        } elseif ($questionData['question_type'] === 'short_answer') {
            $metadata['short_answers'] = $this->request->getPost('short_answers') ?: [];
            $metadata['max_words'] = $this->request->getPost('max_words') ?: 50;
        } elseif ($questionData['question_type'] === 'essay') {
            $metadata['min_words'] = $this->request->getPost('min_words') ?: 100;
            $metadata['max_words_essay'] = $this->request->getPost('max_words_essay') ?: 1000;
            $metadata['grading_rubric'] = $this->request->getPost('grading_rubric') ?: '';

            // Handle AI-assisted grading rubric
            $questionData['enable_rubric'] = $this->request->getPost('enable_rubric') ? 1 : 0;
            if ($questionData['enable_rubric']) {
                $rubricData = [
                    'type' => $this->request->getPost('rubric_type'),
                    'max_score' => $this->request->getPost('rubric_max_score'),
                    'criteria' => $this->request->getPost('rubric_criteria') ?: []
                ];
                $questionData['rubric_data'] = json_encode($rubricData);
                $questionData['model_answer'] = $this->request->getPost('model_answer') ?: '';
            }
        } elseif ($questionData['question_type'] === 'image_based') {
            $metadata['image_question_type'] = $this->request->getPost('image_question_type') ?: 'clickable_areas';
        }

        if (!empty($metadata)) {
            $questionData['metadata'] = json_encode($metadata);
        }

        // Handle file upload for image-based questions
        if ($questionData['question_type'] === 'image_based') {
            $image = $this->request->getFile('question_image');
            if ($image && $image->isValid()) {
                $imageName = $image->getRandomName();
                $image->move(WRITEPATH . '../public/uploads/questions/', $imageName);
                $questionData['image_url'] = 'uploads/questions/' . $imageName;
            }
        }

        // Validate question data
        $options = $this->request->getPost('options') ?: [];
        $singleCorrectOption = $this->request->getPost('single_correct_option');

        $validationData = array_merge($questionData, ['options' => $options]);
        if ($singleCorrectOption !== null) {
            $validationData['single_correct_option'] = $singleCorrectOption;
        }

        $validationErrors = $this->questionModel->validateQuestionData($validationData);

        if (!empty($validationErrors)) {
            return ['success' => false, 'message' => implode('<br>', $validationErrors)];
        }

        // Insert question
        $questionId = $this->questionModel->insert($questionData);

        if (!$questionId) {
            return ['success' => false, 'message' => 'Failed to create question. Please try again.'];
        }

        // Save options if required (same logic as existing)
        if (in_array($questionData['question_type'], ['mcq', 'true_false', 'yes_no', 'drag_drop'])) {
            // Handle radio button format for True/False and Yes/No
            if (in_array($questionData['question_type'], ['true_false', 'yes_no']) && $singleCorrectOption !== null) {
                // Mark the selected option as correct
                foreach ($options as $index => &$option) {
                    $option['is_correct'] = ($index == $singleCorrectOption) ? 1 : 0;
                }
            }

            if (!$this->optionModel->saveQuestionOptions($questionId, $options)) {
                // Rollback question creation
                $this->questionModel->delete($questionId);
                return ['success' => false, 'message' => 'Failed to save question options. Please try again.'];
            }
        } elseif (in_array($questionData['question_type'], ['fill_blank', 'short_answer', 'math_equation'])) {
            // Save answers as options for these question types
            $answers = [];

            if ($questionData['question_type'] === 'fill_blank') {
                $blankAnswers = $this->request->getPost('blank_answers') ?: [];

                // Handle the new structure: blank_answers[1][], blank_answers[2][], etc.
                foreach ($blankAnswers as $blankNumber => $answersForBlank) {
                    if (is_array($answersForBlank)) {
                        foreach ($answersForBlank as $answer) {
                            if (!empty(trim($answer))) {
                                $answers[] = [
                                    'option_text' => trim($answer),
                                    'is_correct' => 1,
                                    'blank_number' => $blankNumber // Store which blank this answer belongs to
                                ];
                            }
                        }
                    }
                }
            } elseif ($questionData['question_type'] === 'short_answer') {
                $shortAnswers = $this->request->getPost('short_answers') ?: [];
                foreach ($shortAnswers as $answer) {
                    if (!empty(trim($answer))) {
                        $answers[] = [
                            'option_text' => trim($answer),
                            'is_correct' => 1
                        ];
                    }
                }
            } elseif ($questionData['question_type'] === 'math_equation') {
                $mathAnswers = $this->request->getPost('math_answers') ?: [];
                foreach ($mathAnswers as $answer) {
                    if (!empty(trim($answer))) {
                        $answers[] = [
                            'option_text' => trim($answer),
                            'is_correct' => 1
                        ];
                    }
                }
            }

            if (!empty($answers) && !$this->optionModel->saveQuestionOptions($questionId, $answers)) {
                // Rollback question creation
                $this->questionModel->delete($questionId);
                return ['success' => false, 'message' => 'Failed to save question answers. Please try again.'];
            }
        }

        return ['success' => true, 'question_id' => $questionId];
    }

    /**
     * AJAX endpoint for paginated questions
     */
    public function loadQuestions()
    {
        // Temporarily allow both AJAX and regular requests for debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        // }

        $userRole = $this->session->get('role');
        $isLoggedIn = $this->session->get('is_logged_in');

        // Enhanced debug logging
        log_message('debug', 'loadQuestions called by user role: ' . $userRole);
        log_message('debug', 'Is logged in: ' . ($isLoggedIn ? 'yes' : 'no'));
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'yes' : 'no'));
        log_message('debug', 'Session ID: ' . $this->session->session_id);

        // Check if user is logged in first
        if (!$isLoggedIn) {
            log_message('debug', 'User not logged in, returning error');
            return $this->response->setJSON(['success' => false, 'message' => 'Session expired. Please login again.']);
        }

        // Check permissions
        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            log_message('debug', 'Access denied for role: ' . $userRole);
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Get filters and pagination parameters
        $filters = [
            'subject_id' => $this->request->getGet('subject'),
            'question_type' => $this->request->getGet('type'),
            'difficulty' => $this->request->getGet('difficulty'),
            'class_id' => $this->request->getGet('class'),
            'session_id' => $this->request->getGet('session'),
            'term_id' => $this->request->getGet('term'),
            'search' => $this->request->getGet('search')
        ];

        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = (int)($this->request->getGet('per_page') ?? 15);

        // Validate per page limits
        if ($perPage < 5) $perPage = 5;
        if ($perPage > 100) $perPage = 100;

        try {
            $paginatedData = $this->getFilteredQuestionsWithPagination($filters, $page, $perPage);

            // Generate HTML for questions table
            $questionsHtml = $this->generateQuestionsTableHtml($paginatedData['questions']);

            log_message('debug', 'Successfully generated pagination data. Total: ' . $paginatedData['total']);

            return $this->response->setJSON([
                'success' => true,
                'html' => $questionsHtml,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $paginatedData['total'],
                    'total_pages' => ceil($paginatedData['total'] / $perPage),
                    'has_previous' => $page > 1,
                    'has_next' => $page < ceil($paginatedData['total'] / $perPage)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in loadQuestions: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading questions: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate HTML for questions table
     */
    private function generateQuestionsTableHtml($questions)
    {
        $userRole = $this->session->get('role');
        $routePrefix = $this->getRoutePrefix($userRole);
        $questionTypes = QuestionModel::TYPES;

        if (empty($questions)) {
            return '<tr><td colspan="9" class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">quiz</i>
                        <h6 class="text-muted">No questions found</h6>
                        <p class="text-muted small">Try adjusting your filters or search terms</p>
                    </td></tr>';
        }

        $html = '';
        foreach ($questions as $question) {
            $html .= '<tr>';
            $html .= '<td><input type="checkbox" class="form-check-input question-checkbox" value="' . $question['id'] . '"></td>';
            $html .= '<td>';
            $html .= '<div>';
            $html .= '<h6 class="mb-1 fw-semibold">' . esc(substr($question['question_text'], 0, 80));
            $html .= strlen($question['question_text']) > 80 ? '...' : '';
            $html .= '</h6>';
            if (!empty($question['explanation'])) {
                $html .= '<small class="text-muted"><i class="material-symbols-rounded me-1" style="font-size: 12px;">info</i>Has explanation</small>';
            }
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<td><span class="fw-medium">' . esc($question['subject_name'] ?? 'N/A') . '</span></td>';
            $html .= '<td><span class="text-muted">' . esc($question['class_name'] ?? 'N/A') . '</span></td>';
            $html .= '<td><span class="question-type-badge">' . esc($questionTypes[$question['question_type']] ?? $question['question_type']) . '</span></td>';
            $html .= '<td><span class="text-muted">';
            if (!empty($question['first_name']) || !empty($question['last_name'])) {
                $html .= esc(trim($question['first_name'] . ' ' . $question['last_name']));
            } else {
                $html .= 'N/A';
            }
            $html .= '</span></td>';
            $html .= '<td><div class="d-flex flex-column">';
            $html .= '<small class="text-muted">' . esc($question['session_name'] ?? 'N/A') . '</small>';
            $html .= '<small class="text-muted">' . esc($question['term_name'] ?? 'N/A') . '</small>';
            $html .= '</div></td>';
            $html .= '<td><span class="fw-medium">' . $question['points'] . ' mks</span></td>';
            $html .= '<td class="text-center">';
            $html .= '<div class="btn-group" role="group">';
            $html .= '<a href="' . base_url($routePrefix . 'questions/preview/' . $question['id']) . '" class="btn btn-outline-info btn-action" title="Preview Question" target="_blank">';
            $html .= '<i class="material-symbols-rounded" style="font-size: 18px;">visibility</i></a>';
            $html .= '<a href="' . base_url($routePrefix . 'questions/edit/' . $question['id']) . '" class="btn btn-outline-primary btn-action" title="Edit Question">';
            $html .= '<i class="material-symbols-rounded" style="font-size: 18px;">edit</i></a>';
            $html .= '<button type="button" class="btn btn-outline-warning btn-action" title="Duplicate Question" onclick="duplicateQuestion(' . $question['id'] . ')">';
            $html .= '<i class="material-symbols-rounded" style="font-size: 18px;">content_copy</i></button>';
            $html .= '<button type="button" class="btn btn-outline-danger btn-action" title="Delete Question" onclick="showDeleteModal(' . $question['id'] . ', \'' . esc(substr($question['question_text'], 0, 50)) . '\')">';
            $html .= '<i class="material-symbols-rounded" style="font-size: 18px;">delete</i></button>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    /**
     * AJAX endpoint to get classes for a specific subject (for teachers)
     */
    public function getClassesForSubject($subjectId)
    {
        $userRole = $this->session->get('role');

        if ($userRole === 'admin' || $userRole === 'principal') {
            $classModel = new \App\Models\ClassModel();
            $classes = $classModel->where('is_active', 1)->findAll();

            // Add display name with category for each class
            foreach ($classes as &$class) {
                $class['display_name'] = $this->getClassDisplayName($class);
            }
        } else {
            // For teachers, get classes from assignment system for specific subject
            $classes = $this->getAvailableClasses($subjectId);
        }

        return $this->response->setJSON(['success' => true, 'classes' => $classes]);
    }

    /**
     * Get appropriate layout based on user role
     */
    private function getLayoutForRole($role)
    {
        switch ($role) {
            case 'principal':
                return 'layouts/principal';
            case 'admin':
                return 'layouts/dashboard';
            case 'teacher':
                return 'layouts/dashboard';
            default:
                return 'layouts/dashboard';
        }
    }

    /**
     * Get route prefix based on user role
     */
    private function getRoutePrefix($role)
    {
        switch ($role) {
            case 'principal':
                return 'principal/';
            case 'admin':
                return '';
            case 'teacher':
                return '';
            default:
                return '';
        }
    }

    /**
     * AI Question Generation
     */
    public function aiGenerate()
    {
        // Check if user is logged in and has permission
        if (!$this->session->get('is_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Authentication required']);
        }

        $userRole = $this->session->get('role');
        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Check if AI generation is enabled
        $settingsModel = new \App\Models\SettingsModel();
        $settings = $settingsModel->getAllSettings();

        // Debug: Log the settings to see what we're getting
        log_message('debug', 'AI Generation Settings Check: ' . json_encode([
            'ai_generation_enabled' => $settings['ai_generation_enabled'] ?? 'NOT_SET',
            'ai_model_provider' => $settings['ai_model_provider'] ?? 'NOT_SET',
            'ai_model' => $settings['ai_model'] ?? 'NOT_SET',
            'groq_api_key_exists' => !empty($settings['groq_api_key']),
            'all_settings_count' => count($settings)
        ]));

        if (!($settings['ai_generation_enabled'] ?? false)) {
            return $this->response->setJSON(['success' => false, 'message' => 'AI generation is not enabled']);
        }

        // Check if provider and API key are configured
        if (empty($settings['ai_model_provider'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'AI provider is not configured']);
        }

        // Remove old settings array API key check
        // $providerApiKeyField = $settings['ai_model_provider'] . '_api_key';
        // if (empty($settings[$providerApiKeyField])) {
        //     return $this->response->setJSON(['success' => false, 'message' => 'API key not configured for selected provider']);
        // }

        try {
            // Get form data
            $subjectId = $this->request->getPost('subject_id');
            $classId = $this->request->getPost('class_id');
            $topics = $this->request->getPost('topics');
            $subtopics = $this->request->getPost('subtopics');
            $referenceLinks = $this->request->getPost('reference_links');
            $questionTypes = $this->request->getPost('question_types');

            // Validate required fields
            if (empty($subjectId) || empty($classId) || empty($topics) || empty($questionTypes)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please fill in all required fields']);
            }

            // Calculate total questions
            $totalQuestions = 0;
            $validQuestionTypes = [];
            foreach ($questionTypes as $type => $count) {
                $count = (int)$count;
                if ($count > 0) {
                    $validQuestionTypes[$type] = $count;
                    $totalQuestions += $count;
                }
            }

            if ($totalQuestions === 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please specify at least one question type with quantity']);
            }

            if ($totalQuestions > 50) {
                return $this->response->setJSON(['success' => false, 'message' => 'Maximum 50 questions can be generated at once']);
            }

            // Check teacher permissions
            if ($userRole === 'teacher') {
                $userId = $this->session->get('user_id');
                $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
                $currentSession = $this->getCurrentSession();
                $sessionId = $currentSession['id'] ?? null;

                if (!$assignmentModel->isTeacherAssigned($userId, $subjectId, $classId, $sessionId)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You are not assigned to this subject-class combination']);
                }
            }

            // Get subject and class names for context
            $subjectModel = new SubjectModel();
            $classModel = new ClassModel();
            $subject = $subjectModel->find($subjectId);
            $class = $classModel->find($classId);

            if (!$subject || !$class) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid subject or class selected']);
            }

            // Get the correct API key for the selected provider and model from the new table
            $provider = $settings['ai_model_provider'];
            $model = $settings['ai_model'];
            $apiKey = '';
            if ($provider && $model) {
                $apiKeyModel = new \App\Models\AIAPIKeyModel();
                $apiKeyRow = $apiKeyModel->getApiKey($provider, $model);
                if ($apiKeyRow && !empty($apiKeyRow['api_key'])) {
                    $apiKey = $apiKeyRow['api_key'];
                }
            }

            // Debug logging
            log_message('debug', 'Provider: ' . $provider);
            log_message('debug', 'Model: ' . $model);
            log_message('debug', 'API Key Length: ' . strlen($apiKey));
            log_message('debug', 'API Key First 10 chars: ' . substr($apiKey, 0, 10));

            if (empty($apiKey)) {
                return $this->response->setJSON(['success' => false, 'message' => 'API key not configured for selected provider and model']);
            }

            // Initialize AI generator
            $aiGenerator = new \App\Libraries\AIQuestionGenerator(
                $settings['ai_model_provider'],
                $settings['ai_model'],
                $apiKey
            );

            // Prepare parameters for AI generation
            $params = [
                'subject' => $subject['name'],
                'class' => $class['name'],
                'topics' => $topics,
                'subtopics' => $subtopics,
                'reference_links' => $referenceLinks,
                'question_types' => $validQuestionTypes,
                'total_questions' => $totalQuestions
            ];

            // Generate questions
            $questions = $aiGenerator->generateQuestions($params);

            return $this->response->setJSON([
                'success' => true,
                'questions' => $questions,
                'total_generated' => count($questions)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'AI Question Generation Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to generate questions: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Approve and save AI generated questions
     */
    public function aiApprove()
    {
        // Check if user is logged in and has permission
        if (!$this->session->get('is_logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Authentication required']);
        }

        $userRole = $this->session->get('role');
        if (!in_array($userRole, ['admin', 'teacher', 'principal'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        try {
            // Get form data
            $subjectId = $this->request->getPost('subject_id');
            $classId = $this->request->getPost('class_id');
            $questionsJson = $this->request->getPost('questions');

            if (empty($questionsJson)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No questions data received']);
            }

            $questions = json_decode($questionsJson, true);
            if (!$questions || !is_array($questions)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid questions data']);
            }

            // Check teacher permissions
            if ($userRole === 'teacher') {
                $userId = $this->session->get('user_id');
                $assignmentModel = new \App\Models\TeacherSubjectAssignmentModel();
                $currentSession = $this->getCurrentSession();
                $sessionId = $currentSession['id'] ?? null;

                if (!$assignmentModel->isTeacherAssigned($userId, $subjectId, $classId, $sessionId)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You are not assigned to this subject-class combination']);
                }
            }

            // Get current session and term
            $currentSession = $this->getCurrentSession();
            $currentTerm = $this->getCurrentTerm();

            $addedCount = 0;
            $errors = [];

            foreach ($questions as $questionData) {
                try {
                    // Map AI question types to system question types
                    $questionType = $this->mapAIQuestionType($questionData['question_type']);

                    // Check for duplicates before saving
                    $options = isset($questionData['options']) ? $questionData['options'] : [];
                    $isDuplicate = $this->questionModel->checkDuplicateQuestion(
                        $questionData['question_text'],
                        $questionType,
                        $subjectId,
                        $options,
                        $currentTerm['id'] ?? null,
                        null // exam_type_id
                    );

                    if ($isDuplicate) {
                        $errors[] = "Duplicate question skipped: " . substr($questionData['question_text'], 0, 50) . "...";
                        continue; // Skip this question
                    }

                    // Prepare question data for database
                    $dbQuestionData = [
                        'subject_id' => $subjectId,
                        'class_id' => $classId,
                        'session_id' => $currentSession['id'] ?? null,
                        'term_id' => $currentTerm['id'] ?? null,
                        'question_text' => $questionData['question_text'],
                        'question_type' => $questionType,
                        'difficulty' => $questionData['difficulty'],
                        'points' => $questionData['points'],
                        'explanation' => $questionData['explanation'],
                        'hints' => $questionData['hints'] ?? '',
                        'is_active' => 1,
                        'created_by' => $this->session->get('user_id'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    // Insert question
                    $questionId = $this->questionModel->insert($dbQuestionData);

                    if ($questionId) {
                        // Save options if applicable
                        if (isset($questionData['options']) && !empty($questionData['options'])) {
                            $options = [];
                            foreach ($questionData['options'] as $index => $optionText) {
                                $isCorrect = 0;

                                // Determine if this option is correct
                                if ($questionType === 'mcq') {
                                    // For MCQ, check if correct_answer matches option letter or text
                                    $optionLetter = chr(65 + $index); // A, B, C, D
                                    $isCorrect = ($questionData['correct_answer'] === $optionLetter ||
                                                $questionData['correct_answer'] === $optionText) ? 1 : 0;
                                } elseif (in_array($questionType, ['true_false', 'yes_no'])) {
                                    $isCorrect = ($questionData['correct_answer'] === $optionText) ? 1 : 0;
                                }

                                $options[] = [
                                    'option_text' => $optionText,
                                    'is_correct' => $isCorrect
                                ];
                            }

                            $this->optionModel->saveQuestionOptions($questionId, $options);
                        } elseif (in_array($questionType, ['short_answer', 'fill_blank', 'essay'])) {
                            // For non-MCQ questions, save the correct answer as an option
                            $options = [[
                                'option_text' => $questionData['correct_answer'],
                                'is_correct' => 1
                            ]];
                            $this->optionModel->saveQuestionOptions($questionId, $options);
                        }

                        $addedCount++;
                    } else {
                        $errors[] = "Failed to save question: " . substr($questionData['question_text'], 0, 50) . "...";
                    }

                } catch (\Exception $e) {
                    $errors[] = "Error saving question: " . $e->getMessage();
                }
            }

            if ($addedCount > 0) {
                return $this->response->setJSON([
                    'success' => true,
                    'added_count' => $addedCount,
                    'errors' => $errors
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No questions were saved. Errors: ' . implode(', ', $errors)
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'AI Question Approval Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save questions: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Map AI question types to system question types
     */
    private function mapAIQuestionType($aiType)
    {
        $mapping = [
            'mcq' => 'mcq',
            'true_false' => 'true_false',
            'yes_no' => 'yes_no',
            'short_answer' => 'short_answer',
            'essay' => 'essay',
            'fill_blank' => 'fill_blank'
        ];

        return $mapping[$aiType] ?? 'mcq';
    }

    /**
     * Decrypt API key for use
     */
    private function decryptApiKey($encryptedApiKey)
    {
        // If the key is empty, return empty
        if (empty($encryptedApiKey)) {
            return '';
        }

        try {
            // First, try to decrypt using CodeIgniter's encrypter
            $encrypter = \Config\Services::encrypter();
            $decrypted = $encrypter->decrypt($encryptedApiKey);
            
            // If decryption successful and result is not empty, return it
            if (!empty($decrypted)) {
                return $decrypted;
            }
        } catch (\Exception $e) {
            log_message('debug', 'Primary decryption failed, trying fallback: ' . $e->getMessage());
        }

        try {
            // Fallback: try base64 decoding
            $decoded = base64_decode($encryptedApiKey, true);
            if ($decoded !== false && !empty($decoded)) {
                return $decoded;
            }
        } catch (\Exception $e) {
            log_message('debug', 'Base64 decoding failed: ' . $e->getMessage());
        }

        // Final fallback: return the key as-is (might be stored unencrypted)
        log_message('debug', 'Using API key as-is (unencrypted)');
        return $encryptedApiKey;
    }

    /**
     * Get question statistics based on user role
     */
    private function getQuestionStatsForRole($userRole)
    {
        if ($userRole === 'teacher') {
            // For teachers, get only their own question statistics
            $userId = $this->session->get('user_id');
            return $this->getTeacherQuestionStats($userId);
        } else {
            // For admin and principal, get overall statistics
            return $this->questionModel->getQuestionStatsWithSessionTerm();
        }
    }

    /**
     * Get question statistics for a specific teacher
     */
    private function getTeacherQuestionStats($teacherId)
    {
        $stats = [];

        // Total questions by teacher
        $stats['total'] = $this->questionModel->where('created_by', $teacherId)
                                             ->where('is_active', 1)
                                             ->countAllResults();

        // Active questions
        $stats['active'] = $stats['total']; // All counted questions are already active

        // By type
        $stats['by_type'] = [];
        foreach (QuestionModel::TYPES as $key => $label) {
            $count = $this->questionModel->where('created_by', $teacherId)
                                        ->where('question_type', $key)
                                        ->where('is_active', 1)
                                        ->countAllResults();
            $stats['by_type'][$key] = $count;

            // Add direct access for MCQ
            if ($key === 'mcq') {
                $stats['mcq'] = $count;
            }
        }

        // By difficulty
        $stats['by_difficulty'] = [];
        foreach (QuestionModel::DIFFICULTIES as $key => $label) {
            $count = $this->questionModel->where('created_by', $teacherId)
                                        ->where('difficulty', $key)
                                        ->where('is_active', 1)
                                        ->countAllResults();
            $stats['by_difficulty'][$key] = $count;

            // Add direct access for common difficulties
            $stats[$key] = $count;
        }

        // Session and term breakdown for teacher
        $sessionModel = new \App\Models\AcademicSessionModel();
        $termModel = new \App\Models\AcademicTermModel();

        $currentSession = $sessionModel->getCurrentSession();
        $currentTerm = $termModel->getCurrentTerm();

        if ($currentSession && $currentTerm) {
            $stats['current_session'] = $this->questionModel->where('created_by', $teacherId)
                                                           ->where('session_id', $currentSession['id'])
                                                           ->where('is_active', 1)
                                                           ->countAllResults();
            $stats['current_term'] = $this->questionModel->where('created_by', $teacherId)
                                                        ->where('session_id', $currentSession['id'])
                                                        ->where('term_id', $currentTerm['id'])
                                                        ->where('is_active', 1)
                                                        ->countAllResults();
        }

        return $stats;
    }
}
