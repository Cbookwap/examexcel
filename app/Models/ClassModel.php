<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'section', 'academic_year', 'description', 'max_students',
        'class_teacher_id', 'is_active', 'created_at', 'updated_at'
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
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'academic_year' => 'required|min_length[4]|max_length[20]',
        'max_students' => 'required|integer|greater_than[0]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getActiveClasses()
    {
        return $this->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
    }

    public function getClassStats()
    {
        $stats = [];

        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        $stats['with_teachers'] = $this->where('class_teacher_id IS NOT NULL')->where('is_active', 1)->countAllResults();

        return $stats;
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = ['createClassTeacher'];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = ['deleteClassTeacher'];



    /**
     * Get class with teacher information
     */
    public function getClassWithTeacher($id)
    {
        return $this->select('classes.*, users.first_name, users.last_name, users.email')
                   ->join('users', 'users.id = classes.class_teacher_id', 'left')
                   ->where('classes.id', $id)
                   ->first();
    }

    /**
     * Get classes with student count
     */
    public function getClassesWithStudentCount()
    {
        return $this->select('classes.*, COUNT(users.id) as student_count')
                   ->join('users', 'users.class_id = classes.id AND users.role = "student"', 'left')
                   ->where('classes.is_active', 1)
                   ->groupBy('classes.id')
                   ->orderBy('classes.name', 'ASC')
                   ->findAll();
    }



    /**
     * Check if class name exists
     */
    public function classNameExists($name, $section = null, $excludeId = null)
    {
        $builder = $this->where('name', $name);

        if ($section) {
            $builder->where('section', $section);
        }

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get students in a class
     */
    public function getClassStudents($classId)
    {
        $userModel = new UserModel();
        return $userModel->where('class_id', $classId)
                        ->where('role', 'student')
                        ->where('is_active', 1)
                        ->orderBy('first_name', 'ASC')
                        ->findAll();
    }

    /**
     * Get class subjects
     */
    public function getClassSubjects($classId)
    {
        $subjectClassModel = new \App\Models\SubjectClassAssignmentModel();
        return $subjectClassModel->getSubjectsByClass($classId);
    }

    /**
     * Callback: Create class teacher after class is created
     */
    protected function createClassTeacher(array $data)
    {
        if (isset($data['id']) && isset($data['data']['name'])) {
            $userModel = new UserModel();
            $classId = $data['id'];
            $className = $data['data']['name'];

            // Check if class teacher already exists
            $existingClassTeacher = $userModel->findClassTeacher($classId);
            if (!$existingClassTeacher) {
                $userModel->createClassTeacher($classId, $className);
                log_message('info', "Class teacher created for class: {$className} (ID: {$classId})");
            }
        }

        return $data;
    }

    /**
     * Callback: Delete class teacher when class is deleted
     */
    protected function deleteClassTeacher(array $data)
    {
        if (isset($data['id'])) {
            $userModel = new UserModel();
            $classId = is_array($data['id']) ? $data['id'][0] : $data['id'];

            // Find and deactivate class teacher
            $classTeacher = $userModel->findClassTeacher($classId);
            if ($classTeacher) {
                $userModel->update($classTeacher['id'], ['is_active' => 0]);
                log_message('info', "Class teacher deactivated for class ID: {$classId}");
            }
        }

        return $data;
    }

    /**
     * Get class with class teacher information
     */
    public function getClassWithClassTeacher($classId)
    {
        $userModel = new UserModel();
        $class = $this->find($classId);

        if ($class) {
            $classTeacher = $userModel->findClassTeacher($classId);
            $class['class_teacher'] = $classTeacher;
        }

        return $class;
    }

    /**
     * Update class teacher credentials
     */
    public function updateClassTeacherCredentials($classId, $username, $password = null)
    {
        $userModel = new UserModel();
        $classTeacher = $userModel->findClassTeacher($classId);

        if ($classTeacher) {
            $updateData = ['username' => $username];
            if ($password) {
                $updateData['password'] = $password; // Will be hashed by beforeUpdate callback
            }

            return $userModel->update($classTeacher['id'], $updateData);
        }

        return false;
    }
}
