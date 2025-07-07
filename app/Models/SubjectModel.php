<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'code', 'description', 'category', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'code' => 'required|min_length[2]|max_length[20]|is_unique[subjects.code,id,{id}]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Subject name is required',
            'min_length' => 'Subject name must be at least 2 characters',
            'max_length' => 'Subject name cannot exceed 255 characters'
        ],
        'code' => [
            'required' => 'Subject code is required',
            'min_length' => 'Subject code must be at least 2 characters',
            'max_length' => 'Subject code cannot exceed 20 characters',
            'is_unique' => 'Subject code already exists'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getActiveSubjects()
    {
        return $this->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
    }

    public function getSubjectsByCategory($category = null)
    {
        $builder = $this->where('is_active', 1);
        if ($category) {
            $builder->where('category', $category);
        }
        return $builder->orderBy('name', 'ASC')->findAll();
    }

    public function getSubjectStats()
    {
        $stats = [];

        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        $stats['with_teachers'] = $this->where('teacher_id IS NOT NULL')->where('is_active', 1)->countAllResults();

        // Categories
        $categories = $this->select('category, COUNT(*) as count')
                          ->where('is_active', 1)
                          ->where('category IS NOT NULL')
                          ->groupBy('category')
                          ->findAll();

        $stats['categories'] = $categories;

        return $stats;
    }



    public function getSubjectsWithTeacher()
    {
        $builder = $this->db->table($this->table . ' s');
        $builder->select('s.*, u.first_name, u.last_name, u.email');
        $builder->join('users u', 'u.id = s.teacher_id', 'left');
        $builder->orderBy('s.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getSubjectsByTeacher($teacherId)
    {
        return $this->select('subjects.*,
                             (SELECT COUNT(*) FROM questions WHERE questions.subject_id = subjects.id AND questions.is_active = 1) as question_count,
                             (SELECT COUNT(*) FROM exams WHERE exams.subject_id = subjects.id AND exams.is_active = 1) as exam_count')
                    ->where('teacher_id', $teacherId)
                    ->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }



    public function searchSubjects($keyword)
    {
        return $this->groupStart()
                    ->like('name', $keyword)
                    ->orLike('code', $keyword)
                    ->orLike('description', $keyword)
                    ->groupEnd()
                    ->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }



    public function getSubjectWithQuestionCount($subjectId)
    {
        $subject = $this->find($subjectId);
        if (!$subject) {
            return null;
        }

        // Get question count
        $questionModel = new QuestionModel();
        $subject['question_count'] = $questionModel->where('subject_id', $subjectId)
                                                  ->where('is_active', 1)
                                                  ->countAllResults();

        // Get exam count
        $examModel = new ExamModel();
        $subject['exam_count'] = $examModel->where('subject_id', $subjectId)
                                          ->countAllResults();

        return $subject;
    }

    public function assignTeacher($subjectId, $teacherId)
    {
        return $this->update($subjectId, ['teacher_id' => $teacherId]);
    }

    public function removeTeacher($subjectId)
    {
        return $this->update($subjectId, ['teacher_id' => null]);
    }

    public function getAvailableCategories()
    {
        $builder = $this->db->table($this->table);
        $builder->select('category');
        $builder->where('category IS NOT NULL');
        $builder->where('category !=', '');
        $builder->groupBy('category');
        $builder->orderBy('category', 'ASC');

        $result = $builder->get()->getResultArray();
        return array_column($result, 'category');
    }

    public function duplicateSubject($subjectId, $newCode, $newName = null)
    {
        $subject = $this->find($subjectId);
        if (!$subject) {
            return false;
        }

        // Remove ID and update fields
        unset($subject['id']);
        $subject['code'] = $newCode;
        $subject['name'] = $newName ?? $subject['name'] . ' (Copy)';
        $subject['created_at'] = date('Y-m-d H:i:s');
        $subject['updated_at'] = date('Y-m-d H:i:s');

        return $this->insert($subject);
    }

    public function canDelete($subjectId)
    {
        // Check if subject has questions
        $questionModel = new QuestionModel();
        $questionCount = $questionModel->where('subject_id', $subjectId)->countAllResults();

        if ($questionCount > 0) {
            return [
                'can_delete' => false,
                'reason' => "Cannot delete subject. It has {$questionCount} question(s) associated with it."
            ];
        }

        // Check if subject has exams
        $examModel = new ExamModel();
        $examCount = $examModel->where('subject_id', $subjectId)->countAllResults();

        if ($examCount > 0) {
            return [
                'can_delete' => false,
                'reason' => "Cannot delete subject. It has {$examCount} exam(s) associated with it."
            ];
        }

        return ['can_delete' => true];
    }

    /**
     * Get subjects assigned to a specific class
     */
    public function getSubjectsByClass($classId)
    {
        return $this->select('subjects.*, subject_classes.id as assignment_id')
                   ->join('subject_classes', 'subject_classes.subject_id = subjects.id')
                   ->where('subject_classes.class_id', $classId)
                   ->where('subjects.is_active', 1)
                   ->orderBy('subjects.name', 'ASC')
                   ->findAll();
    }
}
