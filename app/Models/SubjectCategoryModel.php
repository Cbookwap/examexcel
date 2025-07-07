<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectCategoryModel extends Model
{
    protected $table = 'subject_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'description', 'color', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[subject_categories.name,id,{id}]',
        'color' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 2 characters',
            'max_length' => 'Category name cannot exceed 100 characters',
            'is_unique' => 'Category name already exists'
        ],
        'color' => [
            'required' => 'Category color is required',
            'regex_match' => 'Color must be a valid hex color code (e.g., #FF0000)'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
    }

    public function getCategoryStats()
    {
        $stats = [];
        
        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        
        return $stats;
    }

    public function getCategoryWithSubjectCount($categoryId)
    {
        $category = $this->find($categoryId);
        if (!$category) {
            return null;
        }

        // Get subject count for this category
        $subjectModel = new SubjectModel();
        $category['subject_count'] = $subjectModel->where('category', $category['name'])
                                                 ->where('is_active', 1)
                                                 ->countAllResults();

        return $category;
    }

    public function getCategoriesWithSubjectCounts()
    {
        $categories = $this->orderBy('name', 'ASC')->findAll();
        $subjectModel = new SubjectModel();

        foreach ($categories as &$category) {
            $category['subject_count'] = $subjectModel->where('category', $category['name'])
                                                     ->where('is_active', 1)
                                                     ->countAllResults();
        }

        return $categories;
    }

    public function canDelete($categoryId)
    {
        $category = $this->find($categoryId);
        if (!$category) {
            return false;
        }

        // Check if any subjects are using this category
        $subjectModel = new SubjectModel();
        $subjectCount = $subjectModel->where('category', $category['name'])->countAllResults();

        return $subjectCount === 0;
    }

    public function toggleStatus($categoryId)
    {
        $category = $this->find($categoryId);
        if (!$category) {
            return false;
        }

        return $this->update($categoryId, ['is_active' => $category['is_active'] ? 0 : 1]);
    }

    public function searchCategories($keyword)
    {
        return $this->groupStart()
                    ->like('name', $keyword)
                    ->orLike('description', $keyword)
                    ->groupEnd()
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getRandomColor()
    {
        $colors = [
            '#dc3545', '#28a745', '#ffc107', '#17a2b8', '#6f42c1', 
            '#fd7e14', '#e83e8c', '#20c997', '#6c757d', '#007bff'
        ];
        
        return $colors[array_rand($colors)];
    }
}
