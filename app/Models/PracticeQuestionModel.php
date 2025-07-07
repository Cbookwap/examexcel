<?php

namespace App\Models;

use CodeIgniter\Model;

class PracticeQuestionModel extends Model
{
    protected $table = 'practice_questions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'category',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'difficulty',
        'points',
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
        'category' => 'required|min_length[2]|max_length[100]',
        'question_text' => 'required|min_length[10]',
        'option_a' => 'required|min_length[1]',
        'option_b' => 'required|min_length[1]',
        'option_c' => 'required|min_length[1]',
        'option_d' => 'required|min_length[1]',
        'correct_answer' => 'required|in_list[A,B,C,D]',
        'difficulty' => 'required|in_list[easy,medium,hard]',
        'points' => 'required|integer|greater_than[0]'
    ];

    protected $validationMessages = [
        'category' => [
            'required' => 'Category is required',
            'min_length' => 'Category must be at least 2 characters long',
            'max_length' => 'Category cannot exceed 100 characters'
        ],
        'question_text' => [
            'required' => 'Question text is required',
            'min_length' => 'Question text must be at least 10 characters long'
        ],
        'option_a' => [
            'required' => 'Option A is required'
        ],
        'option_b' => [
            'required' => 'Option B is required'
        ],
        'option_c' => [
            'required' => 'Option C is required'
        ],
        'option_d' => [
            'required' => 'Option D is required'
        ],
        'correct_answer' => [
            'required' => 'Correct answer is required',
            'in_list' => 'Correct answer must be A, B, C, or D'
        ],
        'difficulty' => [
            'required' => 'Difficulty level is required',
            'in_list' => 'Difficulty must be easy, medium, or hard'
        ],
        'points' => [
            'required' => 'Points are required',
            'integer' => 'Points must be a number',
            'greater_than' => 'Points must be greater than 0'
        ]
    ];

    /**
     * Get questions by category
     */
    public function getQuestionsByCategory($category, $limit = null)
    {
        $builder = $this->where('category', $category)
                       ->where('is_active', 1)
                       ->orderBy('RAND()');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        return $this->select('category')
                   ->where('is_active', 1)
                   ->groupBy('category')
                   ->orderBy('category', 'ASC')
                   ->findAll();
    }

    /**
     * Get question count by category
     */
    public function getQuestionCountByCategory()
    {
        return $this->select('category, COUNT(*) as count')
                   ->where('is_active', 1)
                   ->groupBy('category')
                   ->orderBy('category', 'ASC')
                   ->findAll();
    }

    /**
     * Get random questions from all categories
     */
    public function getRandomQuestions($limit = 10)
    {
        return $this->where('is_active', 1)
                   ->orderBy('RAND()')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get questions with pagination
     */
    public function getPaginatedQuestions($perPage = 20, $page = 1, $category = null)
    {
        $builder = $this->where('is_active', 1);

        if ($category) {
            $builder->where('category', $category);
        }

        return $builder->orderBy('created_at', 'DESC')
                      ->paginate($perPage, 'default', $page);
    }

    /**
     * Get practice statistics
     */
    public function getPracticeStatistics()
    {
        $stats = [
            'total_questions' => $this->where('is_active', 1)->countAllResults(),
            'categories' => $this->getCategories(),
            'difficulty_breakdown' => $this->getDifficultyBreakdown(),
            'category_breakdown' => $this->getQuestionCountByCategory()
        ];

        return $stats;
    }

    /**
     * Get difficulty breakdown
     */
    public function getDifficultyBreakdown()
    {
        return $this->select('difficulty, COUNT(*) as count')
                   ->where('is_active', 1)
                   ->groupBy('difficulty')
                   ->orderBy('difficulty', 'ASC')
                   ->findAll();
    }

    /**
     * Bulk insert questions
     */
    public function bulkInsert($questions)
    {
        return $this->insertBatch($questions);
    }

    /**
     * Search questions
     */
    public function searchQuestions($searchTerm, $category = null)
    {
        $builder = $this->where('is_active', 1);

        if ($category) {
            $builder->where('category', $category);
        }

        return $builder->groupStart()
                      ->like('question_text', $searchTerm)
                      ->orLike('option_a', $searchTerm)
                      ->orLike('option_b', $searchTerm)
                      ->orLike('option_c', $searchTerm)
                      ->orLike('option_d', $searchTerm)
                      ->groupEnd()
                      ->orderBy('created_at', 'DESC')
                      ->findAll();
    }
}
