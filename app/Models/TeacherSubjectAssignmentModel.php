<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherSubjectAssignmentModel extends Model
{
    protected $table = 'teacher_subject_assignments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'teacher_id', 'subject_id', 'class_id', 'session_id', 'is_active', 'assigned_by'
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
        'teacher_id' => 'required|integer',
        'subject_id' => 'required|integer',
        'class_id' => 'required|integer',
        'session_id' => 'required|integer',
        'assigned_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'teacher_id' => [
            'required' => 'Teacher is required',
            'integer' => 'Invalid teacher selected'
        ],
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Invalid subject selected'
        ],
        'class_id' => [
            'required' => 'Class is required',
            'integer' => 'Invalid class selected'
        ],
        'session_id' => [
            'required' => 'Academic session is required',
            'integer' => 'Invalid session selected'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getTeacherAssignments($teacherId, $sessionId = null)
    {
        $builder = $this->select('teacher_subject_assignments.*, subjects.name as subject_name, subjects.code as subject_code, classes.name as class_name, academic_sessions.session_name')
                       ->join('subjects', 'subjects.id = teacher_subject_assignments.subject_id')
                       ->join('classes', 'classes.id = teacher_subject_assignments.class_id')
                       ->join('academic_sessions', 'academic_sessions.id = teacher_subject_assignments.session_id')
                       ->where('teacher_subject_assignments.teacher_id', $teacherId)
                       ->where('teacher_subject_assignments.is_active', 1);

        if ($sessionId) {
            $builder->where('teacher_subject_assignments.session_id', $sessionId);
        }

        return $builder->orderBy('subjects.name', 'ASC')
                      ->orderBy('classes.name', 'ASC')
                      ->findAll();
    }

    public function getSubjectTeachers($subjectId, $sessionId = null)
    {
        $builder = $this->select('teacher_subject_assignments.*, users.first_name, users.last_name, users.email, classes.name as class_name, academic_sessions.session_name')
                       ->join('users', 'users.id = teacher_subject_assignments.teacher_id')
                       ->join('classes', 'classes.id = teacher_subject_assignments.class_id')
                       ->join('academic_sessions', 'academic_sessions.id = teacher_subject_assignments.session_id')
                       ->where('teacher_subject_assignments.subject_id', $subjectId)
                       ->where('teacher_subject_assignments.is_active', 1);

        if ($sessionId) {
            $builder->where('teacher_subject_assignments.session_id', $sessionId);
        }

        return $builder->orderBy('users.first_name', 'ASC')
                      ->orderBy('classes.name', 'ASC')
                      ->findAll();
    }

    public function getClassSubjects($classId, $sessionId = null)
    {
        $builder = $this->select('teacher_subject_assignments.*, subjects.name as subject_name, subjects.code as subject_code, users.first_name, users.last_name, academic_sessions.session_name')
                       ->join('subjects', 'subjects.id = teacher_subject_assignments.subject_id')
                       ->join('users', 'users.id = teacher_subject_assignments.teacher_id')
                       ->join('academic_sessions', 'academic_sessions.id = teacher_subject_assignments.session_id')
                       ->where('teacher_subject_assignments.class_id', $classId)
                       ->where('teacher_subject_assignments.is_active', 1);

        if ($sessionId) {
            $builder->where('teacher_subject_assignments.session_id', $sessionId);
        }

        return $builder->orderBy('subjects.name', 'ASC')->findAll();
    }

    public function getAllAssignments($sessionId = null)
    {
        $builder = $this->select('teacher_subject_assignments.*, subjects.name as subject_name, subjects.code as subject_code, classes.name as class_name, users.first_name, users.last_name, academic_sessions.session_name')
                       ->join('subjects', 'subjects.id = teacher_subject_assignments.subject_id')
                       ->join('classes', 'classes.id = teacher_subject_assignments.class_id')
                       ->join('users', 'users.id = teacher_subject_assignments.teacher_id')
                       ->join('academic_sessions', 'academic_sessions.id = teacher_subject_assignments.session_id')
                       ->where('teacher_subject_assignments.is_active', 1);

        if ($sessionId) {
            $builder->where('teacher_subject_assignments.session_id', $sessionId);
        }

        return $builder->orderBy('academic_sessions.session_name', 'DESC')
                      ->orderBy('classes.name', 'ASC')
                      ->orderBy('subjects.name', 'ASC')
                      ->findAll();
    }

    public function isTeacherAssigned($teacherId, $subjectId, $classId, $sessionId)
    {
        return $this->where('teacher_id', $teacherId)
                   ->where('subject_id', $subjectId)
                   ->where('class_id', $classId)
                   ->where('session_id', $sessionId)
                   ->where('is_active', 1)
                   ->first() !== null;
    }

    public function assignTeacher($teacherId, $subjectId, $classId, $sessionId, $assignedBy)
    {
        // Check if assignment already exists
        if ($this->isTeacherAssigned($teacherId, $subjectId, $classId, $sessionId)) {
            return false; // Already assigned
        }

        $data = [
            'teacher_id' => $teacherId,
            'subject_id' => $subjectId,
            'class_id' => $classId,
            'session_id' => $sessionId,
            'assigned_by' => $assignedBy,
            'is_active' => 1
        ];

        return $this->insert($data);
    }

    public function removeAssignment($teacherId, $subjectId, $classId, $sessionId)
    {
        return $this->where('teacher_id', $teacherId)
                   ->where('subject_id', $subjectId)
                   ->where('class_id', $classId)
                   ->where('session_id', $sessionId)
                   ->delete();
    }

    public function getTeacherSubjects($teacherId, $sessionId = null)
    {
        $builder = $this->distinct()->select('subjects.id, subjects.name, subjects.code')
                       ->join('subjects', 'subjects.id = teacher_subject_assignments.subject_id')
                       ->where('teacher_subject_assignments.teacher_id', $teacherId)
                       ->where('teacher_subject_assignments.is_active', 1);

        if ($sessionId) {
            $builder->where('teacher_subject_assignments.session_id', $sessionId);
        }

        return $builder->orderBy('subjects.name', 'ASC')->findAll();
    }

    public function getTeacherClasses($teacherId, $sessionId = null)
    {
        $builder = $this->distinct()->select('classes.id, classes.name, classes.section')
                       ->join('classes', 'classes.id = teacher_subject_assignments.class_id')
                       ->where('teacher_subject_assignments.teacher_id', $teacherId)
                       ->where('teacher_subject_assignments.is_active', 1);

        if ($sessionId) {
            $builder->where('teacher_subject_assignments.session_id', $sessionId);
        }

        return $builder->orderBy('classes.name', 'ASC')->findAll();
    }

    public function getAssignmentStats($sessionId = null)
    {
        $stats = [];

        $builder = $this->where('is_active', 1);
        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $stats['total_assignments'] = $builder->countAllResults();

        // Unique teachers assigned
        $builder = $this->distinct()->select('teacher_id')->where('is_active', 1);
        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }
        $stats['teachers_assigned'] = count($builder->findAll());

        // Unique subjects assigned
        $builder = $this->distinct()->select('subject_id')->where('is_active', 1);
        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }
        $stats['subjects_assigned'] = count($builder->findAll());

        // Unique classes assigned
        $builder = $this->distinct()->select('class_id')->where('is_active', 1);
        if ($sessionId) {
            $builder->where('session_id', $sessionId);
        }
        $stats['classes_assigned'] = count($builder->findAll());

        return $stats;
    }
}
