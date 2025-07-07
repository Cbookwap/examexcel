<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionInstructionModel extends Model
{
    protected $table = 'question_instructions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title',
        'instruction_text',
        'subject_id',
        'class_id',
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
        'title' => 'required|max_length[200]',
        'instruction_text' => 'required'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Instruction title is required',
            'max_length' => 'Instruction title cannot exceed 200 characters'
        ],
        'instruction_text' => [
            'required' => 'Instruction text is required'
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
     * Get all active instructions
     */
    public function getActiveInstructions()
    {
        return $this->where('is_active', 1)
                   ->orderBy('title', 'ASC')
                   ->findAll();
    }

    /**
     * Get instructions by subject
     */
    public function getBySubject($subjectId)
    {
        return $this->where('subject_id', $subjectId)
                   ->orWhere('subject_id', null)
                   ->where('is_active', 1)
                   ->orderBy('title', 'ASC')
                   ->findAll();
    }

    /**
     * Get instructions by class
     */
    public function getByClass($classId)
    {
        return $this->where('class_id', $classId)
                   ->orWhere('class_id', null)
                   ->where('is_active', 1)
                   ->orderBy('title', 'ASC')
                   ->findAll();
    }

    /**
     * Get instructions for dropdown
     */
    public function getForDropdown($subjectId = null, $classId = null)
    {
        $builder = $this->where('is_active', 1);
        
        if ($subjectId) {
            $builder->groupStart()
                   ->where('subject_id', $subjectId)
                   ->orWhere('subject_id', null)
                   ->groupEnd();
        }
        
        if ($classId) {
            $builder->groupStart()
                   ->where('class_id', $classId)
                   ->orWhere('class_id', null)
                   ->groupEnd();
        }
        
        $instructions = $builder->orderBy('title', 'ASC')->findAll();
        $dropdown = ['0' => 'No Instruction'];
        
        foreach ($instructions as $instruction) {
            $dropdown[$instruction['id']] = $instruction['title'];
        }
        
        return $dropdown;
    }

    /**
     * Get instructions with relationships
     */
    public function getWithRelationships()
    {
        return $this->select('question_instructions.*, subjects.name as subject_name, classes.name as class_name')
                   ->join('subjects', 'subjects.id = question_instructions.subject_id', 'left')
                   ->join('classes', 'classes.id = question_instructions.class_id', 'left')
                   ->where('question_instructions.is_active', 1)
                   ->orderBy('question_instructions.title', 'ASC')
                   ->findAll();
    }

    /**
     * Get instruction statistics
     */
    public function getInstructionStats()
    {
        $stats = [
            'total' => $this->countAllResults(),
            'active' => $this->where('is_active', 1)->countAllResults(),
            'inactive' => $this->where('is_active', 0)->countAllResults(),
            'general' => $this->where('subject_id', null)
                             ->where('class_id', null)
                             ->where('is_active', 1)
                             ->countAllResults(),
            'subject_specific' => $this->where('subject_id !=', null)
                                      ->where('is_active', 1)
                                      ->countAllResults()
        ];

        return $stats;
    }

    /**
     * Toggle instruction status
     */
    public function toggleStatus($id)
    {
        $instruction = $this->find($id);
        if (!$instruction) {
            return false;
        }

        $newStatus = $instruction['is_active'] ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }
}
