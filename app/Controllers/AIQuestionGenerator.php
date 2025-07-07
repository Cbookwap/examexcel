<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuestionModel;
use App\Models\SubjectModel;
use App\Models\ClassModel;
use App\Models\SettingsModel;
use CodeIgniter\Controller;

class AIQuestionGenerator extends Controller
{
    protected $questionModel;
    protected $subjectModel;
    protected $classModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->questionModel = new QuestionModel();
        $this->subjectModel = new SubjectModel();
        $this->classModel = new ClassModel();
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        // Check if user has permission
        if (!in_array(session('role'), ['admin', 'principal'])) {
            return redirect()->to('/auth/login')->with('error', 'Access denied');
        }

        $classes = $this->classModel->where('is_active', 1)->findAll();

        // Add display name with category for each class
        foreach ($classes as &$class) {
            $class['display_name'] = $this->getClassDisplayName($class);
        }

        $data = [
            'title' => 'AI Question Generator',
            'subjects' => $this->subjectModel->where('is_active', 1)->findAll(),
            'classes' => $classes,
            'questionTypes' => [
                'mcq' => 'Multiple Choice (MCQ)',
                'true_false' => 'True/False',
                'yes_no' => 'Yes/No',
                'short_answer' => 'Short Answer',
                'essay' => 'Essay',
                'fill_blank' => 'Fill in the Blank'
            ]
        ];

        return view('ai_question_generator/index', $data);
    }

    /**
     * Generate display name for class with category
     */
    private function getClassDisplayName($class)
    {
        $name = $class['name'];
        $section = $class['section'] ?? '';

        // If section exists and it's a category (Science, Arts, Commercial), add it
        if (!empty($section) && in_array($section, ['Science', 'Arts', 'Commercial'])) {
            return $name . ' - ' . $section;
        }

        // If section exists but it's just a regular section (A, B, C), add it differently
        if (!empty($section) && !in_array($section, ['Science', 'Arts', 'Commercial'])) {
            return $name . ' (' . $section . ')';
        }

        // If no section, just return the name
        return $name;
    }

    public function generate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            // Get form data
            $subject = $this->request->getPost('subject');
            $class = $this->request->getPost('class');
            $topic = $this->request->getPost('topic');
            $subtopics = $this->request->getPost('subtopics');
            $referenceLinks = $this->request->getPost('reference_links');
            $provider = $this->request->getPost('provider') ?: 'groq';
            $model = $this->request->getPost('model');
            $apiKey = $this->request->getPost('api_key');
            
            // Question types and quantities
            $questionTypes = [];
            $mcqCount = (int)$this->request->getPost('mcq_count');
            $trueFalseCount = (int)$this->request->getPost('true_false_count');
            $yesNoCount = (int)$this->request->getPost('yes_no_count');
            $shortAnswerCount = (int)$this->request->getPost('short_answer_count');
            $essayCount = (int)$this->request->getPost('essay_count');
            $fillBlankCount = (int)$this->request->getPost('fill_blank_count');

            if ($mcqCount > 0) $questionTypes['mcq'] = $mcqCount;
            if ($trueFalseCount > 0) $questionTypes['true_false'] = $trueFalseCount;
            if ($yesNoCount > 0) $questionTypes['yes_no'] = $yesNoCount;
            if ($shortAnswerCount > 0) $questionTypes['short_answer'] = $shortAnswerCount;
            if ($essayCount > 0) $questionTypes['essay'] = $essayCount;
            if ($fillBlankCount > 0) $questionTypes['fill_blank'] = $fillBlankCount;

            if (empty($questionTypes)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please specify at least one question type with quantity']);
            }

            // Validate required fields
            if (empty($subject) || empty($class) || empty($topic) || empty($provider) || empty($model) || empty($apiKey)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please fill all required fields']);
            }

            // Get subject and class names
            $subjectData = $this->subjectModel->find($subject);
            $classData = $this->classModel->find($class);

            if (!$subjectData || !$classData) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid subject or class selected']);
            }

            // Generate questions using AI
            $questions = $this->generateQuestionsWithAI($provider, $model, $apiKey, [
                'subject' => $subjectData['name'],
                'class' => $classData['name'],
                'topic' => $topic,
                'subtopics' => $subtopics,
                'reference_links' => $referenceLinks,
                'question_types' => $questionTypes
            ]);

            if (!$questions['success']) {
                return $this->response->setJSON($questions);
            }

            return $this->response->setJSON([
                'success' => true,
                'questions' => $questions['questions'],
                'subject_id' => $subject,
                'class_id' => $class,
                'subject_name' => $subjectData['name'],
                'class_name' => $classData['name']
            ]);

        } catch (\Exception $e) {
            log_message('error', 'AI Question Generation Error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Generation failed: ' . $e->getMessage()]);
        }
    }

    private function generateQuestionsWithAI($provider, $model, $apiKey, $params)
    {
        switch ($provider) {
            case 'groq':
                return $this->generateWithGroq($model, $apiKey, $params);
            case 'gemini':
                return $this->generateWithGemini($model, $apiKey, $params);
            case 'openai':
                return $this->generateWithOpenAI($model, $apiKey, $params);
            case 'claude':
                return $this->generateWithClaude($model, $apiKey, $params);
            case 'huggingface':
                return $this->generateWithHuggingFace($model, $apiKey, $params);
            default:
                return ['success' => false, 'message' => 'Unsupported AI provider'];
        }
    }

    private function generateWithGroq($model, $apiKey, $params)
    {
        $prompt = $this->buildPrompt($params);

        $data = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an professor and expert educator in Nigeria. You have 25 years experience as an examiner and question bank manager for WAEC, NECO, JAMB and NABTEB. Generate high-quality complete questions in valid JSON format only. Do not include any explanatory text outside the JSON.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'model' => $model,
            'temperature' => 0.7,
            'max_tokens' => 4000
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'Groq API Error: HTTP ' . $httpCode];
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['choices'][0]['message']['content'])) {
            return ['success' => false, 'message' => 'Invalid response from Groq API'];
        }

        return $this->parseAIResponse($result['choices'][0]['message']['content']);
    }

    private function generateWithGemini($model, $apiKey, $params)
    {
        $prompt = $this->buildPrompt($params);

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 4000
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $apiKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'Gemini API Error: HTTP ' . $httpCode];
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return ['success' => false, 'message' => 'Invalid response from Gemini API'];
        }

        return $this->parseAIResponse($result['candidates'][0]['content']['parts'][0]['text']);
    }

    private function generateWithOpenAI($model, $apiKey, $params)
    {
        $prompt = $this->buildPrompt($params);

        $data = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert educator and question generator. Generate high-quality educational questions in valid JSON format only.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 4000
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'OpenAI API Error: HTTP ' . $httpCode];
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['choices'][0]['message']['content'])) {
            return ['success' => false, 'message' => 'Invalid response from OpenAI API'];
        }

        return $this->parseAIResponse($result['choices'][0]['message']['content']);
    }

    private function generateWithClaude($model, $apiKey, $params)
    {
        $prompt = $this->buildPrompt($params);

        $data = [
            'model' => $model,
            'max_tokens' => 4000,
            'temperature' => 0.7,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.anthropic.com/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'x-api-key: ' . $apiKey,
                'Content-Type: application/json',
                'anthropic-version: 2023-06-01'
            ],
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'Claude API Error: HTTP ' . $httpCode];
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['content'][0]['text'])) {
            return ['success' => false, 'message' => 'Invalid response from Claude API'];
        }

        return $this->parseAIResponse($result['content'][0]['text']);
    }

    private function generateWithHuggingFace($model, $apiKey, $params)
    {
        $prompt = $this->buildPrompt($params);

        $data = [
            'inputs' => $prompt,
            'parameters' => [
                'max_new_tokens' => 2000,
                'temperature' => 0.7,
                'return_full_text' => false
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api-inference.huggingface.co/models/' . $model,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'HuggingFace API Error: HTTP ' . $httpCode];
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result[0]['generated_text'])) {
            return ['success' => false, 'message' => 'Invalid response from HuggingFace API'];
        }

        return $this->parseAIResponse($result[0]['generated_text']);
    }

    private function buildPrompt($params)
    {
        $questionTypesText = '';
        foreach ($params['question_types'] as $type => $count) {
            $typeName = $this->getQuestionTypeName($type);
            $questionTypesText .= "- {$count} {$typeName} questions\n";
        }

        $prompt = "Generate educational questions for the following specifications:

Subject: {$params['subject']}
Class/Grade: {$params['class']}
Topic: {$params['topic']}";

        if (!empty($params['subtopics'])) {
            $prompt .= "\nSubtopics: {$params['subtopics']}";
        }

        if (!empty($params['reference_links'])) {
            $prompt .= "\nReference Materials: {$params['reference_links']}";
        }

        $prompt .= "\n\nQuestion Requirements:
{$questionTypesText}

IMPORTANT: Return ONLY a valid JSON object with this exact structure:
{
  \"questions\": [
    {
      \"type\": \"mcq\",
      \"question\": \"Question text here?\",
      \"options\": [\"A) Option 1\", \"B) Option 2\", \"C) Option 3\", \"D) Option 4\"],
      \"correct_answer\": \"A\",
      \"explanation\": \"Brief explanation of the correct answer\"
    },
    {
      \"type\": \"true_false\",
      \"question\": \"Statement to evaluate\",
      \"correct_answer\": \"true\",
      \"explanation\": \"Explanation why this is true/false\"
    }
  ]
}

CRITICAL REQUIREMENTS:
- For MCQ questions: ALWAYS include exactly 4 options labeled A), B), C), D)
- For MCQ questions: The correct_answer must be one of: \"A\", \"B\", \"C\", or \"D\"
- For True/False: Use \"true\" or \"false\" as correct_answer
- For Yes/No: Use \"yes\" or \"no\" as correct_answer
- For Short Answer: Provide model answer in correct_answer field
- For Essay: Provide key points in correct_answer field
- For Fill in the Blank: Use \"_____\" for blanks, provide answer in correct_answer field
- NEVER generate incomplete questions - every MCQ must have all 4 options
- Questions must be educationally appropriate for the specified class level
- Ensure all questions are directly related to the specified topic

Make questions challenging but appropriate for the grade level. Include clear explanations.";

        return $prompt;
    }

    private function getQuestionTypeName($type)
    {
        $names = [
            'mcq' => 'Multiple Choice',
            'true_false' => 'True/False',
            'yes_no' => 'Yes/No',
            'short_answer' => 'Short Answer',
            'essay' => 'Essay',
            'fill_blank' => 'Fill in the Blank'
        ];
        return $names[$type] ?? $type;
    }

    private function parseAIResponse($response)
    {
        try {
            // Log the raw AI response for debugging
            log_message('debug', 'Raw AI Response: ' . $response);

            // Clean the response - remove any markdown formatting or extra text
            $response = trim($response);

            // Try to extract JSON from the response - handle both object and array formats
            $jsonStr = '';

            // Check if response starts with array bracket
            if (strpos($response, '[') !== false) {
                $start = strpos($response, '[');
                $end = strrpos($response, ']');
                if ($start !== false && $end !== false) {
                    $jsonStr = substr($response, $start, $end - $start + 1);
                }
            }
            // Check if response starts with object bracket
            elseif (strpos($response, '{') !== false) {
                $start = strpos($response, '{');
                $end = strrpos($response, '}');
                if ($start !== false && $end !== false) {
                    $jsonStr = substr($response, $start, $end - $start + 1);
                }
            }

            if (!empty($jsonStr)) {
                log_message('debug', 'Extracted JSON: ' . $jsonStr);

                $data = json_decode($jsonStr, true);
                log_message('debug', 'Parsed JSON data: ' . json_encode($data));

                // Handle object format: {"questions": [...]}
                if ($data && isset($data['questions']) && is_array($data['questions'])) {
                    log_message('debug', 'Successfully parsed ' . count($data['questions']) . ' questions from object format');
                    return ['success' => true, 'questions' => $data['questions']];
                }
                // Handle array format: [{"question": "...", ...}, ...]
                elseif ($data && is_array($data) && !empty($data)) {
                    // Check if first element looks like a question
                    if (isset($data[0]) && is_array($data[0]) && isset($data[0]['question'])) {
                        log_message('debug', 'Successfully parsed ' . count($data) . ' questions from array format');
                        return ['success' => true, 'questions' => $data];
                    }
                }

                log_message('debug', 'JSON data does not contain valid questions');
            }

            // If JSON parsing fails, try to parse as plain text
            log_message('debug', 'JSON parsing failed, trying text parsing');
            return $this->parseTextResponse($response);

        } catch (\Exception $e) {
            log_message('error', 'Failed to parse AI response: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to parse AI response: ' . $e->getMessage()];
        }
    }

    private function parseTextResponse($response)
    {
        log_message('debug', 'parseTextResponse called with: ' . $response);

        // Fallback parser for non-JSON responses
        $questions = [];
        $lines = explode("\n", $response);

        $currentQuestion = null;
        $options = [];

        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            log_message('debug', "Line {$lineNum}: {$line}");

            // Look for question patterns
            if (preg_match('/^\d+\.\s*(.+)/', $line, $matches)) {
                log_message('debug', "Found question pattern: {$matches[1]}");

                // Save previous question if exists
                if ($currentQuestion) {
                    $questions[] = $currentQuestion;
                    log_message('debug', 'Saved previous question: ' . json_encode($currentQuestion));
                }

                $currentQuestion = [
                    'type' => 'mcq',
                    'question' => $matches[1],
                    'options' => [],
                    'correct_answer' => 'A',
                    'explanation' => 'Generated by AI'
                ];
                $options = [];
            } elseif (preg_match('/^[A-D]\)\s*(.+)/', $line, $matches)) {
                log_message('debug', "Found option: {$line}");
                $options[] = $line;
                if ($currentQuestion) {
                    $currentQuestion['options'] = $options;
                }
            }
        }

        // Add last question
        if ($currentQuestion) {
            $questions[] = $currentQuestion;
            log_message('debug', 'Saved last question: ' . json_encode($currentQuestion));
        }

        log_message('debug', 'Total questions parsed: ' . count($questions));

        if (empty($questions)) {
            log_message('debug', 'No valid questions found in text parsing');
            return ['success' => false, 'message' => 'No valid questions found in AI response'];
        }

        return ['success' => true, 'questions' => $questions];
    }

    public function saveQuestions()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $questions = $this->request->getPost('questions');
            $subjectId = $this->request->getPost('subject_id');
            $classId = $this->request->getPost('class_id');

            if (empty($questions) || empty($subjectId) || empty($classId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Missing required data']);
            }

            $questions = json_decode($questions, true);
            if (!$questions) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid questions data']);
            }

            $savedCount = 0;
            $errors = [];

            foreach ($questions as $questionData) {
                try {
                    $questionRecord = [
                        'subject_id' => $subjectId,
                        'class_id' => $classId,
                        'question_text' => $questionData['question'],
                        'question_type' => $questionData['type'],
                        'created_by' => session('user_id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    // Handle different question types
                    switch ($questionData['type']) {
                        case 'mcq':
                            $questionRecord['option_a'] = isset($questionData['options'][0]) ? substr($questionData['options'][0], 3) : '';
                            $questionRecord['option_b'] = isset($questionData['options'][1]) ? substr($questionData['options'][1], 3) : '';
                            $questionRecord['option_c'] = isset($questionData['options'][2]) ? substr($questionData['options'][2], 3) : '';
                            $questionRecord['option_d'] = isset($questionData['options'][3]) ? substr($questionData['options'][3], 3) : '';
                            $questionRecord['correct_answer'] = strtolower($questionData['correct_answer']);
                            break;

                        case 'true_false':
                        case 'yes_no':
                            $questionRecord['correct_answer'] = strtolower($questionData['correct_answer']);
                            break;

                        case 'short_answer':
                        case 'essay':
                        case 'fill_blank':
                            $questionRecord['correct_answer'] = $questionData['correct_answer'];
                            break;
                    }

                    if (isset($questionData['explanation'])) {
                        $questionRecord['explanation'] = $questionData['explanation'];
                    }

                    if ($this->questionModel->insert($questionRecord)) {
                        $savedCount++;
                    } else {
                        $errors[] = "Failed to save question: " . $questionData['question'];
                    }

                } catch (\Exception $e) {
                    $errors[] = "Error saving question: " . $e->getMessage();
                }
            }

            $message = "Successfully saved {$savedCount} questions";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode(', ', $errors);
            }

            return $this->response->setJSON([
                'success' => $savedCount > 0,
                'message' => $message,
                'saved_count' => $savedCount,
                'total_count' => count($questions)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Save Questions Error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save questions: ' . $e->getMessage()]);
        }
    }


}
