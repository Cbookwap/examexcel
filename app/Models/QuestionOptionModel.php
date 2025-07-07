<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionOptionModel extends Model
{
    protected $table = 'question_options';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'question_id', 'option_text', 'is_correct', 'order_index',
        'image_url', 'explanation', 'blank_number'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'question_id' => 'required|integer',
        'option_text' => 'required|min_length[1]',
        'order_index' => 'integer'
    ];

    protected $validationMessages = [
        'question_id' => [
            'required' => 'Question ID is required',
            'integer' => 'Invalid question ID'
        ],
        'option_text' => [
            'required' => 'Option text is required',
            'min_length' => 'Option text cannot be empty'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getOptionsByQuestion($questionId)
    {
        return $this->where('question_id', $questionId)
                    ->orderBy('order_index', 'ASC')
                    ->findAll();
    }

    public function getCorrectOptions($questionId)
    {
        return $this->where('question_id', $questionId)
                    ->where('is_correct', 1)
                    ->orderBy('order_index', 'ASC')
                    ->findAll();
    }

    public function deleteQuestionOptions($questionId)
    {
        return $this->where('question_id', $questionId)->delete();
    }

    public function saveQuestionOptions($questionId, $options)
    {
        // Delete existing options
        $this->deleteQuestionOptions($questionId);

        // Insert new options
        $success = true;
        foreach ($options as $index => $option) {
            if (empty($option['option_text'])) {
                continue; // Skip empty options
            }

            $optionData = [
                'question_id' => $questionId,
                'option_text' => $option['option_text'],
                'is_correct' => !empty($option['is_correct']) ? 1 : 0,
                'order_index' => $index,
                'image_url' => $option['image_url'] ?? null,
                'explanation' => $option['explanation'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if (!$this->insert($optionData)) {
                $success = false;
                break;
            }
        }

        return $success;
    }

    public function reorderOptions($questionId, $newOrder)
    {
        $success = true;
        foreach ($newOrder as $index => $optionId) {
            if (!$this->update($optionId, ['order_index' => $index])) {
                $success = false;
                break;
            }
        }
        return $success;
    }

    public function duplicateOptions($fromQuestionId, $toQuestionId)
    {
        $options = $this->getOptionsByQuestion($fromQuestionId);

        $success = true;
        foreach ($options as $option) {
            unset($option['id']);
            $option['question_id'] = $toQuestionId;
            $option['created_at'] = date('Y-m-d H:i:s');

            if (!$this->insert($option)) {
                $success = false;
                break;
            }
        }

        return $success;
    }

    public function getOptionLabel($questionId, $optionId)
    {
        $options = $this->getOptionsByQuestion($questionId);
        $index = 0;

        foreach ($options as $i => $option) {
            if ($option['id'] == $optionId) {
                $index = $i;
                break;
            }
        }

        return chr(65 + $index); // A, B, C, D...
    }

    public function validateOptions($options, $questionType)
    {
        $errors = [];

        if (empty($options) || !is_array($options)) {
            $errors[] = 'Options are required for this question type';
            return $errors;
        }

        $validOptions = array_filter($options, function($option) {
            return !empty($option['option_text']);
        });

        if (count($validOptions) < 2) {
            $errors[] = 'At least 2 options are required';
        }

        $correctCount = 0;
        foreach ($validOptions as $option) {
            if (!empty($option['is_correct'])) {
                $correctCount++;
            }
        }

        if ($correctCount === 0) {
            $errors[] = 'At least one option must be marked as correct';
        }

        // Specific validation for different question types
        switch ($questionType) {
            case 'true_false':
            case 'yes_no':
                if (count($validOptions) !== 2) {
                    $errors[] = 'This question type requires exactly 2 options';
                }
                if ($correctCount !== 1) {
                    $errors[] = 'This question type requires exactly 1 correct answer';
                }
                break;

            case 'mcq':
                if (count($validOptions) < 2) {
                    $errors[] = 'Multiple choice questions require at least 2 options';
                }
                if (count($validOptions) > 10) {
                    $errors[] = 'Multiple choice questions cannot have more than 10 options';
                }
                break;
        }

        return $errors;
    }
}
