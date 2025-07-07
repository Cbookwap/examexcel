<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'subject_id', 'class_id', 'session_id', 'term_id', 'exam_type_id', 'instruction_id',
        'question_text', 'question_type', 'difficulty', 'points',
        'time_limit', 'explanation', 'hints', 'image_url', 'metadata',
        'randomize_options', 'is_active', 'created_by', 'enable_rubric', 'rubric_data',
        'model_answer', 'decimal_places', 'tolerance'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'subject_id' => 'required|integer',
        'question_text' => 'required|min_length[6]',
        'question_type' => 'required|in_list[mcq,true_false,yes_no,fill_blank,short_answer,essay,drag_drop,image_based,math_equation]',
        'difficulty' => 'required|in_list[easy,medium,hard]',
        'points' => 'required|integer|greater_than[0]',
        'created_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Invalid subject selected'
        ],
        'question_text' => [
            'required' => 'Question text is required',
            'min_length' => 'Question must be at least 6 characters long'
        ],
        'question_type' => [
            'required' => 'Question type is required',
            'in_list' => 'Invalid question type selected'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Question types
    public const TYPES = [
        'mcq' => 'Multiple Choice',
        'true_false' => 'True/False',
        'yes_no' => 'Yes/No',
        'fill_blank' => 'Fill in the Blank',
        'short_answer' => 'Short Answer',
        'essay' => 'Essay',
        'drag_drop' => 'Drag & Drop',
        'image_based' => 'Image Based',
        'math_equation' => 'Math Equation'
    ];

    // Difficulty levels
    public const DIFFICULTIES = [
        'easy' => 'Easy',
        'medium' => 'Medium',
        'hard' => 'Hard'
    ];

    // Custom methods
    public function getQuestionsWithSubject($limit = null, $offset = null)
    {
        $builder = $this->db->table($this->table . ' q');
        $builder->select('q.*, s.name as subject_name, s.code as subject_code, u.first_name, u.last_name,
                         sess.session_name, term.term_name, term.term_number, c.name as class_name');
        $builder->join('subjects s', 's.id = q.subject_id', 'left');
        $builder->join('users u', 'u.id = q.created_by', 'left');
        $builder->join('academic_sessions sess', 'sess.id = q.session_id', 'left');
        $builder->join('academic_terms term', 'term.id = q.term_id', 'left');
        $builder->join('classes c', 'c.id = q.class_id', 'left');
        $builder->orderBy('q.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    public function getQuestionsWithSubjectAndClass($filters = [])
    {
        $builder = $this->db->table($this->table . ' q');
        $builder->select('q.*, s.name as subject_name, s.code as subject_code, u.first_name, u.last_name,
                         sess.session_name, term.term_name, term.term_number, c.name as class_name');
        $builder->join('subjects s', 's.id = q.subject_id', 'left');
        $builder->join('users u', 'u.id = q.created_by', 'left');
        $builder->join('academic_sessions sess', 'sess.id = q.session_id', 'left');
        $builder->join('academic_terms term', 'term.id = q.term_id', 'left');
        $builder->join('classes c', 'c.id = q.class_id', 'left');

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

        $builder->where('q.is_active', 1);
        $builder->orderBy('q.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getQuestionsBySubject($subjectId, $isActive = true)
    {
        $builder = $this->where('subject_id', $subjectId);
        if ($isActive) {
            $builder->where('is_active', 1);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function getQuestionsBySubjectAndClass($subjectId, $classId, $examTypeId = null, $isActive = true)
    {
        $builder = $this->select('questions.id as question_id, questions.*, question_options.option_text, question_options.is_correct, question_options.order_index as option_order')
                        ->join('question_options', 'question_options.question_id = questions.id', 'left')
                        ->where('questions.subject_id', $subjectId)
                        ->where('questions.class_id', $classId);

        if ($examTypeId) {
            $builder->where('questions.exam_type_id', $examTypeId);
        }

        if ($isActive) {
            $builder->where('questions.is_active', 1);
        }

        $results = $builder->orderBy('questions.created_at', 'DESC')
                          ->orderBy('question_options.order_index', 'ASC')
                          ->get()
                          ->getResultArray();

        // Group options by question
        $questions = [];
        foreach ($results as $row) {
            $questionId = $row['question_id']; // Use the aliased column

            if (!isset($questions[$questionId])) {
                $questions[$questionId] = $row;
                $questions[$questionId]['id'] = $row['question_id']; // Set the id field explicitly
                $questions[$questionId]['options'] = [];
            }

            if ($row['option_text']) {
                $questions[$questionId]['options'][] = [
                    'option_text' => $row['option_text'],
                    'is_correct' => $row['is_correct'],
                    'order_index' => $row['option_order']
                ];
            }
        }

        return array_values($questions);
    }

    public function getQuestionsByType($type, $isActive = true)
    {
        $builder = $this->where('question_type', $type);
        if ($isActive) {
            $builder->where('is_active', 1);
        }
        return $builder->findAll();
    }

    public function getQuestionsByDifficulty($difficulty, $isActive = true)
    {
        $builder = $this->where('difficulty', $difficulty);
        if ($isActive) {
            $builder->where('is_active', 1);
        }
        return $builder->findAll();
    }

    public function getRandomQuestions($subjectId, $count, $difficulty = null, $classId = null)
    {
        $builder = $this->where('subject_id', $subjectId)
                        ->where('is_active', 1);

        // CRITICAL FIX: Filter by class if provided
        if ($classId) {
            $builder->where('class_id', $classId);
        }

        if ($difficulty) {
            $builder->where('difficulty', $difficulty);
        }

        return $builder->orderBy('RAND()')
                      ->limit($count)
                      ->findAll();
    }

    public function searchQuestions($keyword, $filters = [])
    {
        $builder = $this->db->table($this->table . ' q');
        $builder->select('q.*, s.name as subject_name, s.code as subject_code, u.first_name, u.last_name,
                         sess.session_name, term.term_name, term.term_number, c.name as class_name');
        $builder->join('subjects s', 's.id = q.subject_id', 'left');
        $builder->join('users u', 'u.id = q.created_by', 'left');
        $builder->join('academic_sessions sess', 'sess.id = q.session_id', 'left');
        $builder->join('academic_terms term', 'term.id = q.term_id', 'left');
        $builder->join('classes c', 'c.id = q.class_id', 'left');

        $builder->groupStart()
                ->like('q.question_text', $keyword)
                ->orLike('q.explanation', $keyword)
                ->orLike('q.hints', $keyword)
                ->groupEnd();

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

        if (isset($filters['is_active'])) {
            $builder->where('q.is_active', $filters['is_active']);
        } else {
            $builder->where('q.is_active', 1);
        }

        return $builder->orderBy('q.created_at', 'DESC')->get()->getResultArray();
    }

    public function getQuestionStats()
    {
        $stats = [];

        // Total questions
        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        $stats['inactive'] = $stats['total'] - $stats['active'];

        // By type - add direct access for common types
        $stats['by_type'] = [];
        foreach (self::TYPES as $key => $label) {
            $count = $this->where('question_type', $key)
                          ->where('is_active', 1)
                          ->countAllResults();
            $stats['by_type'][$key] = $count;

            // Add direct access for MCQ
            if ($key === 'mcq') {
                $stats['mcq'] = $count;
            }
        }

        // By difficulty - add direct access
        $stats['by_difficulty'] = [];
        foreach (self::DIFFICULTIES as $key => $label) {
            $count = $this->where('difficulty', $key)
                          ->where('is_active', 1)
                          ->countAllResults();
            $stats['by_difficulty'][$key] = $count;

            // Add direct access for each difficulty
            $stats[$key] = $count;
        }

        return $stats;
    }

    public function duplicateQuestion($questionId, $newSubjectId = null)
    {
        $question = $this->find($questionId);
        if (!$question) {
            return false;
        }

        // Remove ID and update fields
        unset($question['id']);
        $question['created_at'] = date('Y-m-d H:i:s');
        $question['updated_at'] = date('Y-m-d H:i:s');

        if ($newSubjectId) {
            $question['subject_id'] = $newSubjectId;
        }

        return $this->insert($question);
    }

    public function getQuestionWithOptions($questionId)
    {
        // Get question with related data using joins
        $builder = $this->db->table($this->table . ' q');
        $builder->select('q.*, s.name as subject_name, s.code as subject_code,
                         c.name as class_name, c.section as class_section,
                         u.first_name, u.last_name,
                         sess.session_name, term.term_name, term.term_number');
        $builder->join('subjects s', 's.id = q.subject_id', 'left');
        $builder->join('classes c', 'c.id = q.class_id', 'left');
        $builder->join('users u', 'u.id = q.created_by', 'left');
        $builder->join('academic_sessions sess', 'sess.id = q.session_id', 'left');
        $builder->join('academic_terms term', 'term.id = q.term_id', 'left');
        $builder->where('q.id', $questionId);

        $question = $builder->get()->getRowArray();

        if (!$question) {
            return null;
        }

        // Get options if question type requires them
        if (in_array($question['question_type'], ['mcq', 'true_false', 'yes_no', 'drag_drop'])) {
            $optionModel = new QuestionOptionModel();
            $question['options'] = $optionModel->where('question_id', $questionId)
                                              ->orderBy('order_index', 'ASC')
                                              ->findAll();
        }

        return $question;
    }

    public function validateQuestionData($data)
    {
        $errors = [];

        // Check if question type requires options
        if (in_array($data['question_type'], ['mcq', 'true_false', 'yes_no', 'drag_drop'])) {
            if (empty($data['options']) || !is_array($data['options'])) {
                $errors[] = 'This question type requires options';
            } else {
                // Validate based on question type
                if (in_array($data['question_type'], ['true_false', 'yes_no'])) {
                    // For True/False and Yes/No, check radio button selection
                    $hasCorrect = false;
                    if (isset($data['single_correct_option']) && is_numeric($data['single_correct_option'])) {
                        $hasCorrect = true;
                    }

                    if (!$hasCorrect) {
                        $errors[] = 'Please select the correct answer for ' . ($data['question_type'] === 'true_false' ? 'True/False' : 'Yes/No') . ' question';
                    }

                    // Ensure exactly 2 options
                    if (count($data['options']) !== 2) {
                        $errors[] = ucfirst(str_replace('_', '/', $data['question_type'])) . ' questions must have exactly 2 options';
                    }

                    // Validate option texts
                    $expectedTexts = $data['question_type'] === 'true_false' ? ['True', 'False'] : ['Yes', 'No'];
                    $actualTexts = array_column($data['options'], 'option_text');
                    if (array_diff($expectedTexts, $actualTexts) || array_diff($actualTexts, $expectedTexts)) {
                        $errors[] = ucfirst(str_replace('_', '/', $data['question_type'])) . ' questions must have the correct option texts';
                    }
                } else {
                    // For MCQ and Drag & Drop, check checkbox selections
                    $hasCorrect = false;
                    $correctCount = 0;
                    foreach ($data['options'] as $option) {
                        if (!empty($option['is_correct'])) {
                            $hasCorrect = true;
                            $correctCount++;
                        }
                    }

                    if (!$hasCorrect) {
                        $errors[] = 'At least one option must be marked as correct';
                    }

                    // Ensure minimum number of options
                    if (count($data['options']) < 2) {
                        $errors[] = 'Questions must have at least 2 options';
                    }
                }
            }
        }

        // Validate math equations
        if ($data['question_type'] === 'math_equation') {
            if (empty($data['metadata']['equation_format'])) {
                $errors[] = 'Math equation format is required';
            }
        }

        // Validate image-based questions
        if ($data['question_type'] === 'image_based') {
            if (empty($data['image_url'])) {
                $errors[] = 'Image is required for image-based questions';
            }
        }

        return $errors;
    }

    public static function getTypeLabel($type)
    {
        return self::TYPES[$type] ?? $type;
    }

    public static function getDifficultyLabel($difficulty)
    {
        return self::DIFFICULTIES[$difficulty] ?? $difficulty;
    }

    public static function getDifficultyColor($difficulty)
    {
        $colors = [
            'easy' => 'success',
            'medium' => 'warning',
            'hard' => 'danger'
        ];
        return $colors[$difficulty] ?? 'secondary';
    }

    public static function getTypeIcon($type)
    {
        $icons = [
            'mcq' => 'fas fa-list-ul',
            'true_false' => 'fas fa-check-circle',
            'yes_no' => 'fas fa-thumbs-up',
            'fill_blank' => 'fas fa-edit',
            'short_answer' => 'fas fa-pen',
            'essay' => 'fas fa-file-alt',
            'drag_drop' => 'fas fa-arrows-alt',
            'image_based' => 'fas fa-image',
            'math_equation' => 'fas fa-calculator'
        ];
        return $icons[$type] ?? 'fas fa-question';
    }

    public function getQuestionsBySessionAndTerm($sessionId, $termId, $isActive = true)
    {
        $builder = $this->where('session_id', $sessionId)->where('term_id', $termId);
        if ($isActive) {
            $builder->where('is_active', 1);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function getQuestionsBySubjectSessionTerm($subjectId, $sessionId, $termId, $isActive = true)
    {
        $builder = $this->where('subject_id', $subjectId)
                        ->where('session_id', $sessionId)
                        ->where('term_id', $termId);
        if ($isActive) {
            $builder->where('is_active', 1);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function getCurrentSessionTermQuestions($subjectId = null, $isActive = true)
    {
        $sessionModel = new \App\Models\AcademicSessionModel();
        $termModel = new \App\Models\AcademicTermModel();

        $currentSession = $sessionModel->getCurrentSession();
        $currentTerm = $termModel->getCurrentTerm();

        if (!$currentSession || !$currentTerm) {
            return [];
        }

        $builder = $this->where('session_id', $currentSession['id'])
                        ->where('term_id', $currentTerm['id']);

        if ($subjectId) {
            $builder->where('subject_id', $subjectId);
        }

        if ($isActive) {
            $builder->where('is_active', 1);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    public function getQuestionStatsWithSessionTerm()
    {
        $stats = $this->getQuestionStats();

        // Add session and term breakdown
        $sessionModel = new \App\Models\AcademicSessionModel();
        $termModel = new \App\Models\AcademicTermModel();

        $currentSession = $sessionModel->getCurrentSession();
        $currentTerm = $termModel->getCurrentTerm();

        if ($currentSession && $currentTerm) {
            $stats['current_session'] = $this->where('session_id', $currentSession['id'])
                                             ->where('is_active', 1)
                                             ->countAllResults();
            $stats['current_term'] = $this->where('session_id', $currentSession['id'])
                                          ->where('term_id', $currentTerm['id'])
                                          ->where('is_active', 1)
                                          ->countAllResults();
        }

        return $stats;
    }

    /**
     * Get questions by teacher
     */
    public function getQuestionsByTeacher($teacherId)
    {
        return $this->select('questions.*, subjects.name as subject_name, subjects.code as subject_code,
                             academic_sessions.session_name, academic_terms.term_name, classes.name as class_name')
                   ->join('subjects', 'subjects.id = questions.subject_id', 'left')
                   ->join('academic_sessions', 'academic_sessions.id = questions.session_id', 'left')
                   ->join('academic_terms', 'academic_terms.id = questions.term_id', 'left')
                   ->join('classes', 'classes.id = questions.class_id', 'left')
                   ->where('questions.created_by', $teacherId)
                   ->where('questions.is_active', 1)
                   ->orderBy('questions.created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get question count by teacher
     */
    public function getQuestionCountByTeacher($teacherId)
    {
        return $this->where('created_by', $teacherId)
                   ->where('is_active', 1)
                   ->countAllResults();
    }

    /**
     * Check for duplicate questions with comprehensive comparison
     */
    public function checkDuplicateQuestion($questionText, $questionType, $subjectId, $options = [], $termId = null, $examTypeId = null, $excludeId = null)
    {
        // First, check for exact question text match
        $builder = $this->builder()
            ->where('question_text', $questionText)
            ->where('question_type', $questionType)
            ->where('is_active', 1);

        if ($subjectId) {
            $builder->where('subject_id', $subjectId);
        }

        // Add term_id check for duplicate validation
        if ($termId) {
            $builder->where('term_id', $termId);
        } else {
            // If no term_id provided, check for questions with NULL term_id
            $builder->where('term_id IS NULL');
        }

        // Add exam_type_id check for duplicate validation
        if ($examTypeId) {
            $builder->where('exam_type_id', $examTypeId);
        } else {
            // If no exam_type_id provided, check for questions with NULL exam_type_id
            $builder->where('exam_type_id IS NULL');
        }

        // Exclude current question (for edit form)
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        $existingQuestions = $builder->get()->getResultArray();

        if (empty($existingQuestions)) {
            return false; // No duplicate found
        }

        // If we have potential duplicates, check options for MCQ, True/False, Yes/No
        if (in_array($questionType, ['mcq', 'true_false', 'yes_no']) && !empty($options)) {
            foreach ($existingQuestions as $existingQuestion) {
                if ($this->compareQuestionOptions($existingQuestion['id'], $options)) {
                    return true; // Found exact duplicate including options
                }
            }
            return false; // Same question text but different options
        }

        // For other question types, question text match is sufficient
        return true;
    }

    /**
     * Compare question options for duplicate detection
     */
    private function compareQuestionOptions($questionId, $newOptions)
    {
        $optionModel = new QuestionOptionModel();
        $existingOptions = $optionModel->where('question_id', $questionId)
                                      ->orderBy('id', 'ASC')
                                      ->findAll();

        // If different number of options, not a duplicate
        if (count($existingOptions) !== count($newOptions)) {
            return false;
        }

        // Sort both arrays by option text for comparison
        $existingTexts = array_map(function($option) {
            return trim(strtolower($option['option_text']));
        }, $existingOptions);

        $newTexts = array_map(function($option) {
            return trim(strtolower($option['option_text'] ?? ''));
        }, $newOptions);

        sort($existingTexts);
        sort($newTexts);

        // Compare sorted option texts
        return $existingTexts === $newTexts;
    }

    /**
     * Get similarity score between two questions (for future enhancement)
     */
    public function getQuestionSimilarity($questionText1, $questionText2)
    {
        // Simple similarity check using similar_text function
        $similarity = 0;
        similar_text(
            strtolower(trim($questionText1)),
            strtolower(trim($questionText2)),
            $similarity
        );

        return $similarity;
    }
}
