<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamTypeModel extends Model
{
    protected $table = 'exam_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'code',
        'description',
        'default_total_marks',
        'is_test',
        'assessment_category',
        'is_active',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'code' => 'required|max_length[20]|is_unique[exam_types.code,id,{id}]',
        'default_total_marks' => 'required|integer|greater_than[0]|less_than_equal_to[1000]',
        'assessment_category' => 'required|in_list[continuous_assessment,main_examination,practice]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Exam type name is required',
            'max_length' => 'Exam type name cannot exceed 100 characters'
        ],
        'code' => [
            'required' => 'Exam type code is required',
            'max_length' => 'Exam type code cannot exceed 20 characters',
            'is_unique' => 'This exam type code already exists'
        ],
        'default_total_marks' => [
            'required' => 'Default total marks is required',
            'integer' => 'Total marks must be a whole number',
            'greater_than' => 'Total marks must be greater than 0',
            'less_than_equal_to' => 'Total marks cannot exceed 1000'
        ],
        'assessment_category' => [
            'required' => 'Assessment category is required',
            'in_list' => 'Invalid assessment category selected'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all active exam types
     */
    public function getActiveExamTypes()
    {
        return $this->where('is_active', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get exam type by code
     */
    public function getByCode($code)
    {
        return $this->where('code', $code)
                   ->where('is_active', 1)
                   ->first();
    }

    /**
     * Get exam types for dropdown
     */
    public function getForDropdown()
    {
        $examTypes = $this->getActiveExamTypes();
        $dropdown = [];

        foreach ($examTypes as $type) {
            $dropdown[$type['id']] = $type['name'];
        }

        return $dropdown;
    }

    /**
     * Get exam type statistics
     */
    public function getExamTypeStats()
    {
        $stats = [
            'total' => $this->countAllResults(),
            'active' => $this->where('is_active', 1)->countAllResults(),
            'inactive' => $this->where('is_active', 0)->countAllResults()
        ];

        return $stats;
    }

    /**
     * Toggle exam type status
     */
    public function toggleStatus($id)
    {
        $examType = $this->find($id);
        if (!$examType) {
            return false;
        }

        $newStatus = $examType['is_active'] ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Get exam types by category
     */
    public function getExamTypesByCategory($category = null)
    {
        $builder = $this->where('is_active', 1);

        if ($category) {
            $builder->where('assessment_category', $category);
        }

        return $builder->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get continuous assessment types
     */
    public function getContinuousAssessmentTypes()
    {
        return $this->getExamTypesByCategory('continuous_assessment');
    }

    /**
     * Get main examination types
     */
    public function getMainExaminationTypes()
    {
        return $this->getExamTypesByCategory('main_examination');
    }

    /**
     * Get test types (is_test = 1)
     */
    public function getTestTypes()
    {
        return $this->where('is_active', 1)
                   ->where('is_test', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get exam types (is_test = 0)
     */
    public function getExamTypes()
    {
        return $this->where('is_active', 1)
                   ->where('is_test', 0)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get default marks for an exam type
     */
    public function getDefaultMarks($examTypeId)
    {
        $examType = $this->find($examTypeId);
        return $examType ? $examType['default_total_marks'] : 100;
    }
}
