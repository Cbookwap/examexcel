<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectClassAssignmentModel extends Model
{
    protected $table = 'subject_classes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'subject_id', 'class_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = ''; // No updated_at field in this table

    // Validation
    protected $validationRules = [
        'subject_id' => 'required|integer',
        'class_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Subject must be a valid ID'
        ],
        'class_id' => [
            'required' => 'Class is required',
            'integer' => 'Class must be a valid ID'
        ]
    ];

    /**
     * Get all subject-class assignments with subject and class details
     */
    public function getAssignmentsWithDetails()
    {
        return $this->select('subject_classes.*, subjects.name as subject_name, subjects.code as subject_code,
                             classes.name as class_name, classes.section as class_section, classes.academic_year')
                    ->join('subjects', 'subjects.id = subject_classes.subject_id')
                    ->join('classes', 'classes.id = subject_classes.class_id')
                    ->where('subjects.is_active', 1)
                    ->where('classes.is_active', 1)
                    ->orderBy('classes.name', 'ASC')
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }

    /**
     * Get subjects assigned to a specific class
     */
    public function getSubjectsByClass($classId)
    {
        return $this->select('subjects.*')
                    ->join('subjects', 'subjects.id = subject_classes.subject_id')
                    ->where('subject_classes.class_id', $classId)
                    ->where('subjects.is_active', 1)
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }

    /**
     * Get classes assigned to a specific subject
     */
    public function getClassesBySubject($subjectId)
    {
        return $this->select('classes.*')
                    ->join('classes', 'classes.id = subject_classes.class_id')
                    ->where('subject_classes.subject_id', $subjectId)
                    ->where('classes.is_active', 1)
                    ->orderBy('classes.name', 'ASC')
                    ->findAll();
    }

    /**
     * Check if a subject is already assigned to a class
     */
    public function isAssigned($subjectId, $classId)
    {
        return $this->where('subject_id', $subjectId)
                    ->where('class_id', $classId)
                    ->countAllResults() > 0;
    }

    /**
     * Assign a subject to a class
     */
    public function assignSubjectToClass($subjectId, $classId)
    {
        if ($this->isAssigned($subjectId, $classId)) {
            return false; // Already assigned
        }

        return $this->insert([
            'subject_id' => $subjectId,
            'class_id' => $classId
        ]);
    }

    /**
     * Remove subject assignment from a class
     */
    public function removeAssignment($subjectId, $classId)
    {
        return $this->where('subject_id', $subjectId)
                    ->where('class_id', $classId)
                    ->delete();
    }

    /**
     * Assign multiple subjects to a class
     */
    public function assignMultipleSubjectsToClass($subjectIds, $classId)
    {
        $data = [];
        foreach ($subjectIds as $subjectId) {
            if (!$this->isAssigned($subjectId, $classId)) {
                $data[] = [
                    'subject_id' => $subjectId,
                    'class_id' => $classId
                ];
            }
        }

        if (!empty($data)) {
            return $this->insertBatch($data);
        }

        return true;
    }

    /**
     * Remove all subject assignments from a class
     */
    public function removeAllAssignmentsFromClass($classId)
    {
        return $this->where('class_id', $classId)->delete();
    }

    /**
     * Get all existing assignments as a simple array for quick lookup
     * Returns array with keys like "subject_id-class_id" => true
     */
    public function getExistingAssignmentsMap()
    {
        $assignments = $this->select('subject_id, class_id')->findAll();
        $map = [];

        foreach ($assignments as $assignment) {
            $key = $assignment['subject_id'] . '-' . $assignment['class_id'];
            $map[$key] = true;
        }

        return $map;
    }

    /**
     * Get subjects already assigned to specific classes
     * Returns array grouped by class_id with assigned subject_ids
     */
    public function getAssignmentsByClasses($classIds = null)
    {
        $builder = $this->select('subject_id, class_id');

        if ($classIds && is_array($classIds)) {
            $builder->whereIn('class_id', $classIds);
        }

        $assignments = $builder->findAll();
        $grouped = [];

        foreach ($assignments as $assignment) {
            $classId = $assignment['class_id'];
            if (!isset($grouped[$classId])) {
                $grouped[$classId] = [];
            }
            $grouped[$classId][] = $assignment['subject_id'];
        }

        return $grouped;
    }

    /**
     * Get assignment statistics
     */
    public function getAssignmentStats()
    {
        $totalAssignments = $this->countAllResults();

        $subjectsWithClasses = $this->select('DISTINCT subject_id')
                                   ->countAllResults();

        $classesWithSubjects = $this->select('DISTINCT class_id')
                                   ->countAllResults();

        return [
            'total_assignments' => $totalAssignments,
            'subjects_with_classes' => $subjectsWithClasses,
            'classes_with_subjects' => $classesWithSubjects
        ];
    }

    /**
     * Get subjects grouped by class level (Primary, JSS, SSS)
     */
    public function getSubjectsByClassLevel()
    {
        $assignments = $this->getAssignmentsWithDetails();
        $grouped = [
            'Primary' => [],
            'JSS' => [],
            'SSS' => []
        ];

        foreach ($assignments as $assignment) {
            $className = $assignment['class_name'];

            if (strpos($className, 'Primary') !== false) {
                $level = 'Primary';
            } elseif (strpos($className, 'JSS') !== false) {
                $level = 'JSS';
            } elseif (strpos($className, 'SSS') !== false) {
                $level = 'SSS';
            } else {
                $level = 'Other';
            }

            if (!isset($grouped[$level])) {
                $grouped[$level] = [];
            }

            $grouped[$level][] = $assignment;
        }

        return $grouped;
    }
}
