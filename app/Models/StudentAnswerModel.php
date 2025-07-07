<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAnswerModel extends Model
{
    protected $table = 'student_answers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_attempt_id', 'question_id', 'answer_text', 'selected_options',
        'is_correct', 'points_earned', 'answered_at', 'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'selected_options' => 'json'
    ];

    // Validation rules
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    // Dates
    protected $useTimestamps = false; // We handle created_at manually
    protected $dateFormat = 'datetime';

    /**
     * Get all answers for a specific exam attempt
     */
    public function getAttemptAnswers($attemptId)
    {
        return $this->select('student_answers.*, questions.question_text, questions.question_type')
                   ->join('questions', 'questions.id = student_answers.question_id')
                   ->where('exam_attempt_id', $attemptId)
                   ->orderBy('question_id', 'ASC')
                   ->findAll();
    }

    /**
     * Get answer for a specific question in an attempt
     */
    public function getQuestionAnswer($attemptId, $questionId)
    {
        return $this->where('exam_attempt_id', $attemptId)
                   ->where('question_id', $questionId)
                   ->first();
    }

    /**
     * Save or update student answer
     */
    public function saveAnswer($attemptId, $questionId, $answerData)
    {
        $existing = $this->getQuestionAnswer($attemptId, $questionId);
        
        $data = [
            'exam_attempt_id' => $attemptId,
            'question_id' => $questionId,
            'answer_text' => $answerData['answer_text'] ?? null,
            'selected_options' => $answerData['selected_options'] ?? null,
            'is_correct' => $answerData['is_correct'] ?? 0,
            'points_earned' => $answerData['points_earned'] ?? 0,
            'answered_at' => date('Y-m-d H:i:s')
        ];

        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->insert($data);
        }
    }

    /**
     * Get answers grouped by question for an attempt
     */
    public function getAnswersGroupedByQuestion($attemptId)
    {
        $answers = $this->getAttemptAnswers($attemptId);
        $grouped = [];
        
        foreach ($answers as $answer) {
            $grouped[$answer['question_id']] = $answer;
        }
        
        return $grouped;
    }

    /**
     * Calculate attempt statistics
     */
    public function getAttemptStatistics($attemptId)
    {
        $answers = $this->getAttemptAnswers($attemptId);
        
        $stats = [
            'total_questions' => count($answers),
            'answered_questions' => 0,
            'correct_answers' => 0,
            'wrong_answers' => 0,
            'unanswered' => 0,
            'total_points' => 0
        ];
        
        foreach ($answers as $answer) {
            if (!empty($answer['answer_text']) || !empty($answer['selected_options'])) {
                $stats['answered_questions']++;
                
                if ($answer['is_correct']) {
                    $stats['correct_answers']++;
                } else {
                    $stats['wrong_answers']++;
                }
                
                $stats['total_points'] += $answer['points_earned'];
            } else {
                $stats['unanswered']++;
            }
        }
        
        return $stats;
    }
}
