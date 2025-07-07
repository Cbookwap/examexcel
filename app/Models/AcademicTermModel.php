<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicTermModel extends Model
{
    protected $table = 'academic_terms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'session_id', 'term_number', 'term_name', 'start_date', 'end_date', 'is_current', 'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'session_id' => 'required|integer',
        'term_number' => 'required|integer|in_list[1,2,3]',
        'term_name' => 'required|min_length[3]|max_length[50]',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'session_id' => [
            'required' => 'Academic session is required',
            'integer' => 'Invalid session selected'
        ],
        'term_number' => [
            'required' => 'Term number is required',
            'integer' => 'Term number must be a number',
            'in_list' => 'Term number must be 1, 2, or 3'
        ],
        'term_name' => [
            'required' => 'Term name is required',
            'min_length' => 'Term name must be at least 3 characters',
            'max_length' => 'Term name cannot exceed 50 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getCurrentTerm()
    {
        return $this->select('academic_terms.*, academic_sessions.session_name')
                   ->join('academic_sessions', 'academic_sessions.id = academic_terms.session_id')
                   ->where('academic_terms.is_current', 1)
                   ->where('academic_terms.is_active', 1)
                   ->first();
    }

    public function getTermsBySession($sessionId)
    {
        return $this->where('session_id', $sessionId)
                   ->where('is_active', 1)
                   ->orderBy('term_number', 'ASC')
                   ->findAll();
    }

    public function setCurrentTerm($termId)
    {
        // First, unset all current terms
        $this->where('is_current', 1)->set(['is_current' => 0])->update();

        // Then set the new current term
        return $this->update($termId, ['is_current' => 1]);
    }

    public function getTermWithSession($termId)
    {
        return $this->select('academic_terms.*, academic_sessions.session_name')
                   ->join('academic_sessions', 'academic_sessions.id = academic_terms.session_id')
                   ->where('academic_terms.id', $termId)
                   ->first();
    }

    public function getActiveTerms()
    {
        return $this->select('academic_terms.*, academic_sessions.session_name')
                   ->join('academic_sessions', 'academic_sessions.id = academic_terms.session_id')
                   ->where('academic_terms.is_active', 1)
                   ->orderBy('academic_sessions.session_name', 'DESC')
                   ->orderBy('academic_terms.term_number', 'ASC')
                   ->findAll();
    }

    public function getTermStats()
    {
        $stats = [];

        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        $stats['current'] = $this->where('is_current', 1)->countAllResults();

        // Terms by number
        $stats['by_term'] = [];
        for ($i = 1; $i <= 3; $i++) {
            $stats['by_term'][$i] = $this->where('term_number', $i)
                                         ->where('is_active', 1)
                                         ->countAllResults();
        }

        return $stats;
    }

    public function getTermName($termNumber)
    {
        $termNames = [
            1 => 'First Term',
            2 => 'Second Term',
            3 => 'Third Term'
        ];

        return $termNames[$termNumber] ?? 'Unknown Term';
    }

    public function isTermActive($termId)
    {
        $term = $this->find($termId);
        if (!$term) {
            return false;
        }

        $currentDate = date('Y-m-d');
        return $term['is_active'] &&
               $currentDate >= $term['start_date'] &&
               $currentDate <= $term['end_date'];
    }

    public function getTermsForCurrentSession()
    {
        $sessionModel = new AcademicSessionModel();
        $currentSession = $sessionModel->getCurrentSession();

        if (!$currentSession) {
            return [];
        }

        return $this->getTermsBySession($currentSession['id']);
    }

    public function getExamsByTerm($termId)
    {
        $examModel = new \App\Models\ExamModel();
        return $examModel->where('term_id', $termId)
                        ->where('is_active', 1)
                        ->findAll();
    }

    public function getStudentResultsByTerm($termId, $studentId)
    {
        $attemptModel = new \App\Models\ExamAttemptModel();
        return $attemptModel->select('exam_attempts.*, exams.title as exam_title, exams.total_marks, subjects.name as subject_name')
                           ->join('exams', 'exams.id = exam_attempts.exam_id')
                           ->join('subjects', 'subjects.id = exams.subject_id')
                           ->where('exam_attempts.term_id', $termId)
                           ->where('exam_attempts.student_id', $studentId)
                           ->whereIn('exam_attempts.status', [\App\Models\ExamAttemptModel::STATUS_SUBMITTED, \App\Models\ExamAttemptModel::STATUS_AUTO_SUBMITTED])
                           ->orderBy('exam_attempts.submitted_at', 'DESC')
                           ->findAll();
    }
}
