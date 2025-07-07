<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectModel extends Model
{
    protected $table = 'exam_subjects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_id', 'subject_id', 'question_count', 'total_marks',
        'time_allocation', 'subject_order'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'exam_id' => 'required|integer',
        'subject_id' => 'required|integer',
        'question_count' => 'required|integer|greater_than[0]',
        'total_marks' => 'required|integer|greater_than[0]',
        'time_allocation' => 'integer|greater_than_equal_to[0]',
        'subject_order' => 'integer|greater_than[0]'
    ];

    protected $validationMessages = [
        'exam_id' => [
            'required' => 'Exam ID is required',
            'integer' => 'Exam ID must be an integer'
        ],
        'subject_id' => [
            'required' => 'Subject ID is required',
            'integer' => 'Subject ID must be an integer'
        ],
        'question_count' => [
            'required' => 'Question count is required',
            'integer' => 'Question count must be an integer',
            'greater_than' => 'Question count must be greater than 0'
        ],
        'total_marks' => [
            'required' => 'Total marks is required',
            'integer' => 'Total marks must be an integer',
            'greater_than' => 'Total marks must be greater than 0'
        ]
    ];

    /**
     * Get subjects for an exam with details
     */
    public function getExamSubjects($examId)
    {
        return $this->select('exam_subjects.*, subjects.name as subject_name, subjects.code as subject_code')
                   ->join('subjects', 'subjects.id = exam_subjects.subject_id')
                   ->where('exam_subjects.exam_id', $examId)
                   ->orderBy('exam_subjects.subject_order', 'ASC')
                   ->findAll();
    }

    /**
     * Get a specific exam subject configuration
     */
    public function getExamSubject($examId, $subjectId)
    {
        return $this->select('exam_subjects.*, subjects.name as subject_name, subjects.code as subject_code')
                   ->join('subjects', 'subjects.id = exam_subjects.subject_id')
                   ->where('exam_subjects.exam_id', $examId)
                   ->where('exam_subjects.subject_id', $subjectId)
                   ->first();
    }

    /**
     * Get exam subjects with question counts
     */
    public function getExamSubjectsWithQuestions($examId)
    {
        $builder = $this->db->table('exam_subjects es');
        return $builder->select('es.*, s.name as subject_name, s.code as subject_code,
                                COUNT(eq.id) as configured_questions')
                      ->join('subjects s', 's.id = es.subject_id')
                      ->join('exam_questions eq', 'eq.exam_id = es.exam_id AND eq.subject_id = es.subject_id', 'left')
                      ->where('es.exam_id', $examId)
                      ->groupBy('es.id, s.id')
                      ->orderBy('es.subject_order', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Add subjects to exam
     */
    public function addSubjectsToExam($examId, $subjects)
    {
        $data = [];
        $order = 1;

        foreach ($subjects as $subject) {
            $data[] = [
                'exam_id' => $examId,
                'subject_id' => $subject['subject_id'],
                'question_count' => $subject['question_count'],
                'total_marks' => $subject['total_marks'],
                'time_allocation' => $subject['time_allocation'] ?? 0,
                'subject_order' => $order++,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        return $this->insertBatch($data);
    }

    /**
     * Update exam subject configuration
     */
    public function updateExamSubject($examId, $subjectId, $data)
    {
        return $this->where('exam_id', $examId)
                   ->where('subject_id', $subjectId)
                   ->set($data)
                   ->update();
    }

    /**
     * Remove subjects from exam
     */
    public function removeSubjectsFromExam($examId, $subjectIds = null)
    {
        $builder = $this->where('exam_id', $examId);

        if ($subjectIds) {
            $builder->whereIn('subject_id', $subjectIds);
        }

        return $builder->delete();
    }

    /**
     * Get total marks for exam
     */
    public function getExamTotalMarks($examId)
    {
        $result = $this->selectSum('total_marks')
                      ->where('exam_id', $examId)
                      ->first();

        return $result['total_marks'] ?? 0;
    }

    /**
     * Get total questions for exam
     */
    public function getExamTotalQuestions($examId)
    {
        $result = $this->selectSum('question_count')
                      ->where('exam_id', $examId)
                      ->first();

        return $result['question_count'] ?? 0;
    }

    /**
     * Get total time allocation for exam
     */
    public function getExamTotalTime($examId)
    {
        $result = $this->selectSum('time_allocation')
                      ->where('exam_id', $examId)
                      ->first();

        return $result['time_allocation'] ?? 0;
    }

    /**
     * Check if exam has subjects configured
     */
    public function hasSubjectsConfigured($examId)
    {
        return $this->where('exam_id', $examId)->countAllResults() > 0;
    }

    /**
     * Reorder exam subjects
     */
    public function reorderSubjects($examId, $subjectOrders)
    {
        foreach ($subjectOrders as $subjectId => $order) {
            $this->where('exam_id', $examId)
                 ->where('subject_id', $subjectId)
                 ->set(['subject_order' => $order])
                 ->update();
        }

        return true;
    }
}
