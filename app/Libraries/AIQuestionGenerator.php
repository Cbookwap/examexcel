<?php

namespace App\Libraries;

use Exception;

/**
 * AI Question Generator Library
 * Supports multiple AI providers for generating educational questions
 */
class AIQuestionGenerator
{
    private $provider;
    private $model;
    private $apiKey;
    private $baseUrls = [
        'openai' => 'https://api.openai.com/v1/chat/completions',
        'gemini' => 'https://generativelanguage.googleapis.com/v1beta/models/',
        'claude' => 'https://api.anthropic.com/v1/messages',
        'groq' => 'https://api.groq.com/openai/v1/chat/completions',
        'huggingface' => 'https://api-inference.huggingface.co/models/'
    ];

    public function __construct($provider, $model, $apiKey)
    {
        $this->provider = $provider;
        $this->model = $model;
        $this->apiKey = $apiKey;
    }

    /**
     * Test connection to AI provider
     */
    public function testConnection(): array
    {
        try {
            $testPrompt = "Say 'Hello, AI connection test successful!' in exactly those words.";
            $response = $this->makeRequest($testPrompt, 1);
            
            if (stripos($response, 'Hello, AI connection test successful') !== false) {
                return ['success' => true, 'message' => 'Connection successful'];
            } else {
                return ['success' => false, 'message' => 'Unexpected response from AI'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate questions based on parameters
     */
    public function generateQuestions(array $params): array
    {
        $prompt = $this->buildPrompt($params);

        try {
            // Calculate appropriate token limit based on number of questions
            // Each question needs ~300-500 tokens (question + 4 options + answer + explanation)
            $totalQuestions = $params['total_questions'] ?? 1;
            $tokensPerQuestion = 500;
            $maxTokens = max(1000, $totalQuestions * $tokensPerQuestion);

            $response = $this->makeRequest($prompt, $maxTokens);
            return $this->parseResponse($response, $params);
        } catch (Exception $e) {
            throw new Exception('AI Generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Build prompt for AI based on parameters
     */
    private function buildPrompt(array $params): string
    {
        $questionTypes = $params['question_types'];
        $subject = $params['subject'];
        $class = $params['class'];
        $topics = $params['topics'];
        $subtopics = $params['subtopics'] ?? '';
        $referenceLinks = $params['reference_links'] ?? '';
        $totalQuestions = $params['total_questions'];

        $prompt = "You are an expert educational content creator. Generate exactly {$totalQuestions} high-quality educational questions for the following specifications:\n\n";
        $prompt .= "SUBJECT: {$subject}\n";
        $prompt .= "CLASS/GRADE: {$class}\n";
        $prompt .= "TOPICS: {$topics}\n";
        if ($subtopics) {
            $prompt .= "SUB-TOPICS: {$subtopics}\n";
        }
        if ($referenceLinks) {
            $prompt .= "REFERENCE MATERIALS: {$referenceLinks}\n";
        }
        $prompt .= "\nQUESTION TYPES NEEDED:\n";
        foreach ($questionTypes as $type => $count) {
            $prompt .= "- {$count} {$type} questions\n";
        }
        $prompt .= "\nCRITICAL FORMAT REQUIREMENTS:\n";
        $prompt .= "You MUST return ONLY a valid JSON array of objects. No explanations, no markdown, no additional text.\n";
        $prompt .= "Start your response with [ and end with ]\n\n";
        $prompt .= "Each question object MUST have these fields:\n";
        $prompt .= "  - question_text (string): The full question\n";
        $prompt .= "  - question_type (string): One of mcq, true_false, yes_no, short_answer, essay, fill_blank\n";
        $prompt .= "  - options (array): For MCQ, True/False, or Yes/No, provide options. For others, use an empty array\n";
        $prompt .= "  - correct_answer (string): The correct answer\n";
        $prompt .= "  - explanation (string): Explanation for the answer\n";
        $prompt .= "  - difficulty (string): easy, medium, or hard\n";
        $prompt .= "  - points (number): Points for the question\n";
        $prompt .= "  - hints (string): Optional hint for students\n";
        $prompt .= "\nSTRICT RULES:\n";
        $prompt .= "1. Generate exactly {$totalQuestions} questions\n";
        $prompt .= "2. Each question must be unique and relevant to the topic\n";
        $prompt .= "3. For MCQ questions, provide exactly 4 options\n";
        $prompt .= "4. For True/False questions, use options: [\"True\", \"False\"]\n";
        $prompt .= "5. For Yes/No questions, use options: [\"Yes\", \"No\"]\n";
        $prompt .= "6. Valid question_type values: mcq, true_false, yes_no, short_answer, essay, fill_blank\n";
        $prompt .= "7. Valid difficulty values: easy, medium, hard\n";
        $prompt .= "8. Points must be a number (1-10)\n";
        $prompt .= "9. correct_answer must match one of the options exactly (for MCQ, True/False, Yes/No)\n";
        $prompt .= "10. Return ONLY the JSON array, no other text whatsoever\n";
        $prompt .= "11. Do NOT return a list of answers or options only. Each object must have all required fields.\n";
        $prompt .= "\nIMPORTANT: For MCQ, each question_text must be a complete question, not just a topic or single word. Do NOT return just a term or phrase. Each MCQ must have four options and one correct answer.\n";

        return $prompt;
    }

    /**
     * Make API request to AI provider
     */
    private function makeRequest(string $prompt, int $maxTokens = 1000): string
    {
        switch ($this->provider) {
            case 'openai':
                return $this->makeOpenAIRequest($prompt, $maxTokens);
            case 'gemini':
                return $this->makeGeminiRequest($prompt, $maxTokens);
            case 'claude':
                return $this->makeClaudeRequest($prompt, $maxTokens);
            case 'groq':
                return $this->makeGroqRequest($prompt, $maxTokens);
            case 'huggingface':
                return $this->makeHuggingFaceRequest($prompt, $maxTokens);
            default:
                throw new Exception('Unsupported AI provider: ' . $this->provider);
        }
    }

    /**
     * OpenAI API request
     */
    private function makeOpenAIRequest(string $prompt, int $maxTokens): string
    {
        $data = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => $maxTokens, // Use calculated token limit
            'temperature' => 0.7
        ];

        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $response = $this->makeCurlRequest($this->baseUrls['openai'], $data, $headers);
        
        if (isset($response['choices'][0]['message']['content'])) {
            return $response['choices'][0]['message']['content'];
        }
        
        throw new Exception('Invalid OpenAI response');
    }

    /**
     * Google Gemini API request
     */
    private function makeGeminiRequest(string $prompt, int $maxTokens): string
    {
        $url = $this->baseUrls['gemini'] . $this->model . ':generateContent?key=' . $this->apiKey;

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => min($maxTokens, 8192), // Gemini has token limits
                'temperature' => 0.7,
                'topP' => 0.8,
                'topK' => 40
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];

        $headers = ['Content-Type: application/json'];
        $response = $this->makeCurlRequest($url, $data, $headers);

        // Handle different response formats
        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            return $response['candidates'][0]['content']['parts'][0]['text'];
        }

        // Check for safety filter blocks
        if (isset($response['candidates'][0]['finishReason']) &&
            $response['candidates'][0]['finishReason'] === 'SAFETY') {
            throw new Exception('Content was blocked by Gemini safety filters');
        }

        // Check for other error conditions
        if (isset($response['error'])) {
            throw new Exception('Gemini API Error: ' . $response['error']['message']);
        }

        throw new Exception('Invalid Gemini response: ' . json_encode($response));
    }

    /**
     * Anthropic Claude API request
     */
    private function makeClaudeRequest(string $prompt, int $maxTokens): string
    {
        $data = [
            'model' => $this->model,
            'max_tokens' => $maxTokens,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ];

        $headers = [
            'x-api-key: ' . $this->apiKey,
            'Content-Type: application/json',
            'anthropic-version: 2023-06-01'
        ];

        $response = $this->makeCurlRequest($this->baseUrls['claude'], $data, $headers);
        
        if (isset($response['content'][0]['text'])) {
            return $response['content'][0]['text'];
        }
        
        throw new Exception('Invalid Claude response');
    }

    /**
     * Groq API request (OpenAI compatible)
     */
    private function makeGroqRequest(string $prompt, int $maxTokens): string
    {
        $data = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => $maxTokens,
            'temperature' => 0.7
        ];

        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $response = $this->makeCurlRequest($this->baseUrls['groq'], $data, $headers);

        log_message('debug', 'Groq API Response: ' . json_encode($response));

        // Check for error in response
        if (isset($response['error'])) {
            $errorMsg = $response['error']['message'] ?? 'Unknown Groq API error';
            $errorType = $response['error']['type'] ?? 'unknown';

            if (strpos($errorMsg, 'rate limit') !== false || $errorType === 'rate_limit_exceeded') {
                throw new Exception('Groq API rate limit exceeded. Please try again in a few minutes.');
            } elseif (strpos($errorMsg, 'invalid_api_key') !== false || $errorType === 'invalid_api_key') {
                throw new Exception('Invalid Groq API key. Please check your API key in settings.');
            } elseif (strpos($errorMsg, 'insufficient_quota') !== false) {
                throw new Exception('Groq API quota exceeded. Please check your account limits.');
            } else {
                throw new Exception('Groq API Error: ' . $errorMsg);
            }
        }

        if (isset($response['choices'][0]['message']['content'])) {
            $content = $response['choices'][0]['message']['content'];
            log_message('debug', 'Groq Response Content Length: ' . strlen($content));
            log_message('debug', 'Groq Response Content: ' . $content);
            return $content;
        }

        throw new Exception('Invalid Groq response: ' . json_encode($response));
    }

    /**
     * Hugging Face API request
     */
    private function makeHuggingFaceRequest(string $prompt, int $maxTokens): string
    {
        $url = $this->baseUrls['huggingface'] . $this->model;
        
        $data = [
            'inputs' => $prompt,
            'parameters' => [
                'max_length' => $maxTokens,
                'temperature' => 0.7
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $response = $this->makeCurlRequest($url, $data, $headers);
        
        if (isset($response[0]['generated_text'])) {
            return $response[0]['generated_text'];
        }
        
        throw new Exception('Invalid Hugging Face response');
    }

    /**
     * Make CURL request
     */
    private function makeCurlRequest(string $url, array $data, array $headers): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception('CURL Error: ' . $error);
        }

        if ($httpCode !== 200) {
            // Try to decode error response for better error messages
            $errorResponse = json_decode($response, true);
            if ($errorResponse && isset($errorResponse['error'])) {
                $errorMsg = $errorResponse['error']['message'] ?? $response;
                throw new Exception('API Error (' . $httpCode . '): ' . $errorMsg);
            }
            throw new Exception('HTTP Error: ' . $httpCode . ' - ' . substr($response, 0, 200));
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response: ' . json_last_error_msg() . ' | Response: ' . substr($response, 0, 200));
        }

        return $decoded;
    }

    /**
     * Parse AI response into structured questions
     */
    private function parseResponse(string $response, array $params): array
    {
        // Log the raw response for debugging
        log_message('debug', 'AI Raw Response: ' . substr($response, 0, 1000) . (strlen($response) > 1000 ? '...' : ''));
        
        // Clean the response to extract JSON
        $response = trim($response);

        // Remove markdown code blocks if present
        $response = preg_replace('/```json\s*/', '', $response);
        $response = preg_replace('/```\s*$/', '', $response);
        $response = preg_replace('/```/', '', $response);

        // Remove any text before the JSON array
        $response = preg_replace('/^.*?(?=\[)/s', '', $response);

        // Remove any text after the JSON array
        $response = preg_replace('/\].*$/s', ']', $response);

        // Try to find JSON array in the response
        if (preg_match('/\[.*\]/s', $response, $matches)) {
            $jsonString = $matches[0];
        } else {
            $jsonString = $response;
        }

        // Additional cleaning
        $jsonString = trim($jsonString);

        log_message('debug', 'Extracted JSON String: ' . substr($jsonString, 0, 500) . (strlen($jsonString) > 500 ? '...' : ''));
        log_message('debug', 'Full JSON String Length: ' . strlen($jsonString));
        log_message('debug', 'Full JSON String: ' . $jsonString);

        $questions = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'JSON Parse Error: ' . json_last_error_msg() . ' | JSON String: ' . substr($jsonString, 0, 500));

            // Try to fix incomplete JSON by adding missing closing brackets and required fields
            $fixedJson = $this->fixIncompleteJson($jsonString);
            if ($fixedJson) {
                log_message('debug', 'Attempting to parse fixed JSON: ' . substr($fixedJson, 0, 500));
                $questions = json_decode($fixedJson, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    log_message('debug', 'Successfully parsed fixed JSON');
                } else {
                    log_message('error', 'Fixed JSON still invalid: ' . json_last_error_msg());
                    throw new Exception('Failed to parse AI response as JSON: ' . json_last_error_msg() . ' | Response preview: ' . substr($response, 0, 200));
                }
            } else {
                throw new Exception('Failed to parse AI response as JSON: ' . json_last_error_msg() . ' | Response preview: ' . substr($response, 0, 200));
            }
        }

        // If the response is an object with a 'questions' array (test page format), use that
        if (is_array($questions) && isset($questions['questions']) && is_array($questions['questions'])) {
            $questions = $questions['questions'];
            // Map fields from test page format to app format, fill in missing data
            $mappedQuestions = [];
            foreach ($questions as $q) {
                $questionText = $q['question'] ?? $q['question_text'] ?? '';
                // Fix: Only use question_text or question, never fallback to other fields
                if (empty($questionText)) {
                    $questionText = 'No question text provided';
                }
                $mappedQuestions[] = [
                    'question_text' => $questionText,
                    'question_type' => $q['type'] ?? $q['question_type'] ?? 'mcq',
                    'difficulty' => $q['difficulty'] ?? 'medium',
                    'points' => $q['points'] ?? 1,
                    'explanation' => $q['explanation'] ?? '',
                    'options' => $q['options'] ?? [],
                    'correct_answer' => $q['correct_answer'] ?? '',
                    'hints' => $q['hints'] ?? ''
                ];
            }
            $questions = $mappedQuestions;
        }

        // Prepare a flat list of requested types, e.g. [mcq, mcq, short_answer, ...]
        $requestedTypes = [];
        $totalRequested = 0;
        if (!empty($params['question_types']) && is_array($params['question_types'])) {
            foreach ($params['question_types'] as $type => $count) {
                for ($i = 0; $i < (int)$count; $i++) {
                    $requestedTypes[] = $type;
                    $totalRequested++;
                }
            }
        }

        // Validate and clean questions
        $validatedQuestions = [];
        $typeIndex = 0;
        foreach ($questions as $question) {
            // If the question is a string, wrap it in an array with default values and assign type from requestedTypes
            if (is_string($question)) {
                $questionType = $requestedTypes[$typeIndex] ?? 'short_answer';
                $question = [
                    'question_text' => $question,
                    'question_type' => $questionType,
                    'difficulty' => 'medium',
                    'points' => 1,
                    'explanation' => '',
                    'options' => in_array($questionType, ['mcq', 'true_false', 'yes_no']) ? [] : [],
                    'correct_answer' => '',
                    'hints' => ''
                ];
                $typeIndex++;
            } else {
                // Fill in missing fields for robustness
                // Fix: Only use question_text or question, never fallback to other fields
                if (empty($question['question_text'])) {
                    $question['question_text'] = 'No question text provided';
                }
                $question['question_type'] = $question['question_type'] ?? ($requestedTypes[$typeIndex] ?? 'short_answer');
                $question['difficulty'] = $question['difficulty'] ?? 'medium';
                $question['points'] = $question['points'] ?? 1;
                $question['explanation'] = $question['explanation'] ?? '';
                $question['options'] = $question['options'] ?? [];
                $question['correct_answer'] = $question['correct_answer'] ?? '';
                $question['hints'] = $question['hints'] ?? '';
                $typeIndex++;
            }
            if ($this->validateQuestion($question)) {
                $validatedQuestions[] = $this->cleanQuestion($question);
            }
            // Stop if we've reached the requested number
            if (count($validatedQuestions) >= $totalRequested) {
                break;
            }
        }

        if (empty($validatedQuestions)) {
            throw new Exception('No valid questions found in AI response');
        }

        // Ensure options array exists for MCQ and similar types
        foreach ($validatedQuestions as &$q) {
            if (isset($q['question_type']) && in_array($q['question_type'], ['mcq', 'true_false', 'yes_no'])) {
                if (!isset($q['options']) || !is_array($q['options'])) {
                    $q['options'] = [];
                }
            }
        }

        // Post-processing: Replace question_text that is just a number or a single word
        foreach ($validatedQuestions as &$q) {
            if (isset($q['question_text'])) {
                $qt = trim($q['question_text']);
                // If it's only digits or a single word (no spaces), flag it
                if (preg_match('/^\d+$/', $qt) || preg_match('/^[^\s]+$/', $qt)) {
                    $q['question_text'] = 'AI did not generate a valid question. Please edit this question.';
                }
            }
        }

        return $validatedQuestions;
    }

    /**
     * Fix incomplete JSON responses from AI
     */
    private function fixIncompleteJson(string $jsonString): ?string
    {
        try {
            // Check if it's an incomplete array
            if (strpos($jsonString, '[') === 0) {
                // Count opening and closing brackets
                $openBrackets = substr_count($jsonString, '{');
                $closeBrackets = substr_count($jsonString, '}');
                $openArrays = substr_count($jsonString, '[');
                $closeArrays = substr_count($jsonString, ']');

                // If we have unclosed objects, try to close them
                if ($openBrackets > $closeBrackets) {
                    // Find the last complete question object
                    $lastCompletePos = strrpos($jsonString, '}');
                    if ($lastCompletePos !== false) {
                        // Truncate to last complete object and add missing fields to incomplete one
                        $completeJson = substr($jsonString, 0, $lastCompletePos + 1);

                        // Check if there's an incomplete object after
                        $remaining = substr($jsonString, $lastCompletePos + 1);
                        if (strpos($remaining, '{') !== false) {
                            // There's an incomplete object, try to complete it
                            $incompleteStart = strpos($remaining, '{');
                            $incomplete = substr($remaining, $incompleteStart);

                            // Add missing required fields
                            if (strpos($incomplete, '"correct_answer"') === false) {
                                $incomplete = rtrim($incomplete, ' ,') . ', "correct_answer": "A"';
                            }
                            if (strpos($incomplete, '"explanation"') === false) {
                                $incomplete = rtrim($incomplete, ' ,') . ', "explanation": "Generated by AI"';
                            }

                            // Close the object
                            $incomplete .= '}';

                            $completeJson .= ', ' . $incomplete;
                        }

                        // Close the array if needed
                        if ($openArrays > $closeArrays) {
                            $completeJson .= ']';
                        }

                        return $completeJson;
                    }
                }

                // If just missing array closing bracket
                if ($openArrays > $closeArrays) {
                    return $jsonString . ']';
                }
            }

            return null;
        } catch (Exception $e) {
            log_message('error', 'Error fixing incomplete JSON: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate question structure
     */
    private function validateQuestion($question): bool
    {
        // Accept both array and string (string will be wrapped above)
        if (!is_array($question)) {
            return false;
        }
        $required = ['question_text', 'question_type', 'difficulty', 'points', 'explanation', 'correct_answer'];
        foreach ($required as $field) {
            if (!isset($question[$field]) || $question[$field] === null) {
                return false;
            }
        }
        $validTypes = ['mcq', 'true_false', 'yes_no', 'short_answer', 'essay', 'fill_blank'];
        if (!in_array($question['question_type'], $validTypes)) {
            return false;
        }
        $validDifficulties = ['easy', 'medium', 'hard'];
        if (!in_array($question['difficulty'], $validDifficulties)) {
            return false;
        }
        return true;
    }

    /**
     * Clean and format question data
     */
    private function cleanQuestion(array $question): array
    {
        return [
            'question_text' => trim($question['question_text']),
            'question_type' => $question['question_type'],
            'difficulty' => $question['difficulty'],
            'points' => (int)$question['points'],
            'explanation' => trim($question['explanation']),
            'options' => $question['options'] ?? [],
            'correct_answer' => trim($question['correct_answer']),
            'hints' => $question['hints'] ?? ''
        ];
    }
}
