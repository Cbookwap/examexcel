<?php

namespace App\Models;

use CodeIgniter\Model;

class EssayGradingModel extends Model
{
    protected $table = 'essay_grading';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_attempt_id',
        'question_id',
        'student_answer',
        'ai_suggested_score',
        'ai_feedback',
        'teacher_score',
        'teacher_feedback',
        'status',
        'graded_by',
        'graded_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Submit essay for AI grading
     */
    public function submitForAIGrading($examAttemptId, $questionId, $studentAnswer)
    {
        $data = [
            'exam_attempt_id' => $examAttemptId,
            'question_id' => $questionId,
            'student_answer' => $studentAnswer,
            'status' => 'pending'
        ];

        return $this->insert($data);
    }

    /**
     * Update with AI suggested score
     */
    public function updateAISuggestion($id, $suggestedScore, $feedback = '')
    {
        return $this->update($id, [
            'ai_suggested_score' => $suggestedScore,
            'ai_feedback' => $feedback,
            'status' => 'ai_graded'
        ]);
    }

    /**
     * Teacher review and finalize score
     */
    public function teacherReview($id, $teacherScore, $teacherFeedback, $gradedBy)
    {
        return $this->update($id, [
            'teacher_score' => $teacherScore,
            'teacher_feedback' => $teacherFeedback,
            'status' => 'teacher_reviewed',
            'graded_by' => $gradedBy,
            'graded_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Finalize grading (make visible to student)
     */
    public function finalizeGrading($id)
    {
        return $this->update($id, [
            'status' => 'finalized'
        ]);
    }

    /**
     * Get essays pending teacher review
     */
    public function getPendingReview($teacherId = null)
    {
        $builder = $this->db->table($this->table . ' eg')
            ->select('eg.*, q.question_text, q.points, q.rubric_data, q.model_answer, 
                     ea.exam_id, e.title as exam_title, u.first_name, u.last_name')
            ->join('questions q', 'q.id = eg.question_id')
            ->join('exam_attempts ea', 'ea.id = eg.exam_attempt_id')
            ->join('exams e', 'e.id = ea.exam_id')
            ->join('users u', 'u.id = ea.student_id')
            ->where('eg.status', 'ai_graded');

        if ($teacherId) {
            $builder->where('e.created_by', $teacherId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get grading statistics
     */
    public function getGradingStats($examId = null)
    {
        $builder = $this->db->table($this->table . ' eg')
            ->select('eg.status, COUNT(*) as count')
            ->join('exam_attempts ea', 'ea.id = eg.exam_attempt_id')
            ->groupBy('eg.status');

        if ($examId) {
            $builder->where('ea.exam_id', $examId);
        }

        $results = $builder->get()->getResultArray();
        
        $stats = [
            'pending' => 0,
            'ai_graded' => 0,
            'teacher_reviewed' => 0,
            'finalized' => 0
        ];

        foreach ($results as $result) {
            $stats[$result['status']] = $result['count'];
        }

        return $stats;
    }

    /**
     * Simulate AI grading (placeholder for actual AI integration)
     */
    public function simulateAIGrading($questionId, $studentAnswer, $rubricData, $modelAnswer)
    {
        // This is a placeholder for actual AI integration
        // In a real implementation, you would call an AI service here
        
        $rubric = json_decode($rubricData, true);
        $maxScore = $rubric['max_score'] ?? 10;
        
        // Simple scoring based on word count and keyword matching
        $wordCount = str_word_count($studentAnswer);
        $keywords = explode(' ', strtolower($modelAnswer));
        $studentWords = explode(' ', strtolower($studentAnswer));
        
        $keywordMatches = count(array_intersect($keywords, $studentWords));
        $keywordScore = min(($keywordMatches / count($keywords)) * 0.7, 0.7);
        
        $lengthScore = min($wordCount / 100, 0.3); // Up to 30% for adequate length
        
        $totalScore = ($keywordScore + $lengthScore) * $maxScore;
        
        $feedback = "AI Analysis:\n";
        $feedback .= "- Word count: {$wordCount} words\n";
        $feedback .= "- Key concepts covered: {$keywordMatches}/" . count($keywords) . "\n";
        $feedback .= "- Suggested areas for improvement: Review key concepts and provide more detailed explanations.";
        
        return [
            'score' => round($totalScore, 2),
            'feedback' => $feedback
        ];
    }
}
