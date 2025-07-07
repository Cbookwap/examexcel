<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamQuestionModel extends Model
{
    protected $table = 'exam_questions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_id', 'question_id', 'subject_id', 'order_index', 'subject_order'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'exam_id' => 'required|integer',
        'question_id' => 'required|integer',
        'order_index' => 'integer'
    ];

    /**
     * Validate that questions belong to the correct class for the exam
     */
    public function validateQuestionClassMatch($examId, $questionIds)
    {
        if (empty($questionIds)) {
            return ['valid' => true, 'errors' => []];
        }

        // Get exam class
        $examModel = new \App\Models\ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return ['valid' => false, 'errors' => ['Exam not found']];
        }

        // Get question classes
        $questionModel = new \App\Models\QuestionModel();
        $questions = $questionModel->select('questions.id, questions.question_text, questions.class_id, questions.subject_id')
                                  ->join('classes', 'classes.id = questions.class_id')
                                  ->join('subjects', 'subjects.id = questions.subject_id')
                                  ->select('classes.name as class_name, subjects.name as subject_name')
                                  ->whereIn('questions.id', $questionIds)
                                  ->findAll();

        $errors = [];
        foreach ($questions as $question) {
            if ($question['class_id'] != $exam['class_id']) {
                $errors[] = "Question '{$question['question_text']}' (Subject: {$question['subject_name']}) belongs to class '{$question['class_name']}' but exam is for a different class";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Safely assign questions to exam with class validation
     */
    public function assignQuestionsWithValidation($examId, $questionData)
    {
        // Extract question IDs for validation
        $questionIds = array_column($questionData, 'question_id');

        // Validate class match
        $validation = $this->validateQuestionClassMatch($examId, $questionIds);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'errors' => $validation['errors']
            ];
        }

        // If validation passes, proceed with assignment
        try {
            $result = $this->insertBatch($questionData);
            return [
                'success' => $result !== false,
                'errors' => $result === false ? ['Failed to insert questions'] : []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ['Database error: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Check for existing class mismatches in exam questions
     */
    public function findClassMismatches($examId = null)
    {
        $builder = $this->db->table('exam_questions eq')
                           ->select('eq.exam_id, eq.question_id, e.title as exam_title, e.class_id as exam_class_id,
                                    ec.name as exam_class_name, q.class_id as question_class_id, qc.name as question_class_name,
                                    q.question_text, s.name as subject_name')
                           ->join('exams e', 'e.id = eq.exam_id')
                           ->join('classes ec', 'ec.id = e.class_id')
                           ->join('questions q', 'q.id = eq.question_id')
                           ->join('classes qc', 'qc.id = q.class_id')
                           ->join('subjects s', 's.id = q.subject_id')
                           ->where('e.class_id != q.class_id'); // Find mismatches

        if ($examId) {
            $builder->where('eq.exam_id', $examId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Clean up class mismatches for a specific exam
     */
    public function cleanupClassMismatches($examId)
    {
        $mismatches = $this->findClassMismatches($examId);

        if (empty($mismatches)) {
            return ['success' => true, 'message' => 'No class mismatches found', 'removed_count' => 0];
        }

        $questionIds = array_column($mismatches, 'question_id');
        $removedCount = $this->where('exam_id', $examId)
                            ->whereIn('question_id', $questionIds)
                            ->delete();

        return [
            'success' => true,
            'message' => "Removed {$removedCount} questions with class mismatches",
            'removed_count' => $removedCount,
            'removed_questions' => $mismatches
        ];
    }

    /**
     * Get questions for an exam with full question details
     */
    public function getExamQuestions($examId)
    {
        return $this->select('questions.id as question_id, questions.*, question_options.id as option_id, question_options.option_text, question_options.is_correct, question_options.order_index as option_order, exam_questions.order_index as question_order, questions.points as points_override')
                   ->join('questions', 'questions.id = exam_questions.question_id')
                   ->join('question_options', 'question_options.question_id = questions.id', 'left')
                   ->where('exam_questions.exam_id', $examId)
                   ->orderBy('exam_questions.order_index', 'ASC')
                   ->findAll();
    }

    /**
     * Get questions for an exam (grouped by question)
     */
    public function getExamQuestionsGrouped($examId, $randomizeQuestions = false, $randomizeOptions = false, $seed = null)
    {
        $questions = $this->select('questions.id as question_id, questions.*, exam_questions.order_index as question_order, questions.points as points_override, subjects.name as subject_name, subjects.code as subject_code')
                          ->join('questions', 'questions.id = exam_questions.question_id')
                          ->join('subjects', 'subjects.id = questions.subject_id', 'left')
                          ->where('exam_questions.exam_id', $examId)
                          ->orderBy('exam_questions.order_index', 'ASC')
                          ->findAll();

        // Apply question randomization if enabled
        if ($randomizeQuestions && !empty($questions)) {
            if ($seed !== null) {
                // Use seed for consistent randomization per student attempt
                mt_srand($seed);
            }
            shuffle($questions);
            if ($seed !== null) {
                mt_srand(); // Reset to random seed
            }
        }

        // Get options for each question
        $optionModel = new QuestionOptionModel();
        foreach ($questions as &$question) {
            $options = $optionModel->where('question_id', $question['id'])
                                  ->orderBy('order_index', 'ASC')
                                  ->findAll();

            // Apply option randomization if enabled
            if ($randomizeOptions && !empty($options)) {
                if ($seed !== null) {
                    // Use seed + question ID for consistent randomization per question
                    mt_srand($seed + $question['id']);
                }
                shuffle($options);
                if ($seed !== null) {
                    mt_srand(); // Reset to random seed
                }
            }

            $question['options'] = $options;
        }

        return $questions;
    }

    /**
     * Get questions for multi-subject exams with subject-specific randomization
     */
    public function getExamQuestionsGroupedBySubject($examId, $randomizeQuestions = false, $randomizeOptions = false, $seed = null)
    {
        // Get all questions for the exam grouped by subject
        $questions = $this->select('questions.id as question_id, questions.*, exam_questions.order_index as question_order, questions.points as points_override, subjects.name as subject_name, subjects.code as subject_code, questions.subject_id')
                          ->join('questions', 'questions.id = exam_questions.question_id')
                          ->join('subjects', 'subjects.id = questions.subject_id', 'left')
                          ->where('exam_questions.exam_id', $examId)
                          ->orderBy('questions.subject_id', 'ASC')
                          ->orderBy('exam_questions.order_index', 'ASC')
                          ->findAll();

        // Group questions by subject
        $questionsBySubject = [];
        foreach ($questions as $question) {
            $subjectId = $question['subject_id'];
            if (!isset($questionsBySubject[$subjectId])) {
                $questionsBySubject[$subjectId] = [
                    'subject_id' => $subjectId,
                    'subject_name' => $question['subject_name'],
                    'subject_code' => $question['subject_code'],
                    'questions' => []
                ];
            }
            $questionsBySubject[$subjectId]['questions'][] = $question;
        }

        // Apply subject-specific randomization
        if ($randomizeQuestions && !empty($questionsBySubject)) {
            foreach ($questionsBySubject as $subjectId => &$subjectData) {
                if ($seed !== null) {
                    // Use seed + subject ID for consistent randomization per subject
                    mt_srand($seed + $subjectId);
                }
                shuffle($subjectData['questions']);
                if ($seed !== null) {
                    mt_srand(); // Reset to random seed
                }
            }
        }

        // Get options for each question and apply option randomization
        $optionModel = new QuestionOptionModel();
        foreach ($questionsBySubject as &$subjectData) {
            foreach ($subjectData['questions'] as &$question) {
                $options = $optionModel->where('question_id', $question['id'])
                                      ->orderBy('order_index', 'ASC')
                                      ->findAll();

                // Apply option randomization if enabled
                if ($randomizeOptions && !empty($options)) {
                    if ($seed !== null) {
                        // Use seed + question ID for consistent randomization per question
                        mt_srand($seed + $question['id']);
                    }
                    shuffle($options);
                    if ($seed !== null) {
                        mt_srand(); // Reset to random seed
                    }
                }

                $question['options'] = $options;
            }
        }

        return $questionsBySubject;
    }

    /**
     * Get questions for a specific subject in an exam (for multi-subject exams)
     */
    public function getExamSubjectQuestions($examId, $subjectId)
    {
        return $this->select('questions.id as question_id, questions.*, exam_questions.order_index as question_order, questions.points as points_override')
                   ->join('questions', 'questions.id = exam_questions.question_id')
                   ->where('exam_questions.exam_id', $examId)
                   ->where('exam_questions.subject_id', $subjectId)
                   ->orderBy('exam_questions.order_index', 'ASC')
                   ->findAll();
    }

    /**
     * Check if question is assigned to exam
     */
    public function isQuestionAssigned($examId, $questionId)
    {
        return $this->where('exam_id', $examId)
                   ->where('question_id', $questionId)
                   ->countAllResults() > 0;
    }

    /**
     * Get exam question count
     */
    public function getExamQuestionCount($examId)
    {
        return $this->where('exam_id', $examId)->countAllResults();
    }

    /**
     * Reorder exam questions
     */
    public function reorderQuestions($examId, $questionOrders)
    {
        foreach ($questionOrders as $questionId => $order) {
            $this->where('exam_id', $examId)
                 ->where('question_id', $questionId)
                 ->set('question_order', $order)
                 ->update();
        }
        return true;
    }

    /**
     * Copy questions from another exam
     */
    public function copyQuestionsFromExam($sourceExamId, $targetExamId)
    {
        $sourceQuestions = $this->where('exam_id', $sourceExamId)
                               ->orderBy('question_order', 'ASC')
                               ->findAll();

        foreach ($sourceQuestions as $question) {
            $this->insert([
                'exam_id' => $targetExamId,
                'question_id' => $question['question_id'],
                'question_order' => $question['question_order'],
                'points_override' => $question['points_override']
            ]);
        }

        return count($sourceQuestions);
    }

    /**
     * Get random questions for exam with class validation
     */
    public function assignRandomQuestions($examId, $subjectId, $count, $difficulty = null, $classId = null)
    {
        // Get exam details for validation
        $examModel = new \App\Models\ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return ['success' => false, 'message' => 'Exam not found', 'count' => 0];
        }

        // Use exam's class if classId not provided
        $targetClassId = $classId ?? $exam['class_id'];

        $questionModel = new QuestionModel();
        $builder = $questionModel->where('subject_id', $subjectId)
                                ->where('class_id', $targetClassId) // CRITICAL: Always filter by class
                                ->where('is_active', 1);

        if ($difficulty) {
            $builder->where('difficulty', $difficulty);
        }

        $availableQuestions = $builder->orderBy('RAND()')
                                    ->limit($count)
                                    ->findAll();

        if (empty($availableQuestions)) {
            return [
                'success' => false,
                'message' => "No questions available for the specified criteria (Subject ID: {$subjectId}, Class ID: {$targetClassId})",
                'count' => 0
            ];
        }

        // Prepare question data for batch insert
        $questionData = [];
        foreach ($availableQuestions as $index => $question) {
            $questionData[] = [
                'exam_id' => $examId,
                'question_id' => $question['id'],
                'subject_id' => $subjectId,
                'order_index' => $index + 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        // Use validation method for assignment
        $result = $this->assignQuestionsWithValidation($examId, $questionData);

        return [
            'success' => $result['success'],
            'message' => $result['success'] ? 'Questions assigned successfully' : implode(', ', $result['errors']),
            'count' => $result['success'] ? count($availableQuestions) : 0
        ];
    }

    /**
     * Get exam statistics
     */
    public function getExamQuestionStats($examId)
    {
        $stats = $this->select('questions.difficulty, COUNT(*) as count')
                     ->join('questions', 'questions.id = exam_questions.question_id')
                     ->where('exam_questions.exam_id', $examId)
                     ->groupBy('questions.difficulty')
                     ->findAll();

        $result = [
            'total' => 0,
            'easy' => 0,
            'medium' => 0,
            'hard' => 0
        ];

        foreach ($stats as $stat) {
            $result[$stat['difficulty']] = $stat['count'];
            $result['total'] += $stat['count'];
        }

        return $result;
    }

    /**
     * Validate question count consistency for multi-subject exams
     */
    public function validateQuestionCountConsistency($examId)
    {
        $errors = [];

        // Get exam details
        $examModel = new \App\Models\ExamModel();
        $exam = $examModel->find($examId);

        if (!$exam) {
            $errors[] = 'Exam not found';
            return $errors;
        }

        if ($exam['exam_mode'] === 'multi_subject') {
            // Get configured subjects and their question counts
            $examSubjectModel = new \App\Models\ExamSubjectModel();
            $examSubjects = $examSubjectModel->getExamSubjectsWithQuestions($examId);

            foreach ($examSubjects as $subject) {
                $configuredCount = (int)$subject['question_count'];
                $assignedCount = (int)$subject['configured_questions'];

                if ($assignedCount !== $configuredCount) {
                    $errors[] = "Subject '{$subject['subject_name']}': Configured for {$configuredCount} questions but {$assignedCount} questions assigned";
                }
            }
        } else {
            // For single subject exams, check total question count
            $assignedCount = (int)$this->getExamQuestionCount($examId);
            $configuredCount = (int)$exam['question_count'];

            // Only flag as error if there's actually a mismatch
            if ($assignedCount !== $configuredCount) {
                $errors[] = "Configured for {$configuredCount} questions but {$assignedCount} questions assigned";
            }
        }

        return $errors;
    }

    /**
     * Validate exam questions
     */
    public function validateExamQuestions($examId)
    {
        $errors = [];

        // Check if exam has questions
        $questionCount = $this->getExamQuestionCount($examId);
        if ($questionCount === 0) {
            $errors[] = 'Exam must have at least one question';
        }

        // Check for duplicate questions
        $duplicates = $this->select('question_id, COUNT(*) as count')
                          ->where('exam_id', $examId)
                          ->groupBy('question_id')
                          ->having('count >', 1)
                          ->findAll();

        if (!empty($duplicates)) {
            $errors[] = 'Exam contains duplicate questions';
        }

        // Check question count consistency
        $consistencyErrors = $this->validateQuestionCountConsistency($examId);
        $errors = array_merge($errors, $consistencyErrors);

        // Check question order sequence
        $questions = $this->where('exam_id', $examId)
                         ->orderBy('question_order', 'ASC')
                         ->findAll();

        $expectedOrder = 1;
        foreach ($questions as $question) {
            if ($question['question_order'] != $expectedOrder) {
                $errors[] = 'Question order is not sequential';
                break;
            }
            $expectedOrder++;
        }

        return $errors;
    }
}
