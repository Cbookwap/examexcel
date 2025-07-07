<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamAttemptModel extends Model
{
    protected $table = 'exam_attempts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_id', 'student_id', 'session_id', 'term_id', 'start_time', 'end_time', 'submitted_at',
        'time_taken_minutes', 'total_questions', 'answered_questions',
        'correct_answers', 'wrong_answers', 'marks_obtained', 'percentage',
        'status', 'ip_address', 'user_agent', 'browser_info', 'violations',
        'proctoring_data', 'answers', 'created_at', 'updated_at',
        'started_at', 'completed_at', 'score', 'time_spent', 'is_passed', 'security_flags', 'notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'browser_info' => 'json',
        'violations' => 'json',
        'proctoring_data' => 'json',
        'answers' => 'json',
        'security_flags' => 'json'
    ];

    // Validation rules
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Attempt statuses
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_AUTO_SUBMITTED = 'auto_submitted';
    const STATUS_COMPLETED = 'completed';
    const STATUS_TERMINATED = 'terminated';

    /**
     * Ensure JSON fields are properly initialized before insert/update
     */
    protected function ensureJsonFields($data)
    {
        $jsonFields = ['browser_info', 'violations', 'proctoring_data', 'answers', 'security_flags'];

        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && $data[$field] === null) {
                $data[$field] = ($field === 'browser_info') ? [] : [];
            }
        }

        return $data;
    }

    /**
     * Override insert to ensure JSON fields are properly set
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            $data = $this->ensureJsonFields($data);
        }
        return parent::insert($data, $returnID);
    }

    /**
     * Override update to ensure JSON fields are properly set
     */
    public function update($id = null, $data = null): bool
    {
        if (is_array($data)) {
            $data = $this->ensureJsonFields($data);
        }
        return parent::update($id, $data);
    }

    /**
     * Get attempt with exam and student details
     */
    public function getAttemptWithDetails($id)
    {
        return $this->select('exam_attempts.*, exams.title as exam_title, exams.total_marks, exams.exam_mode,
                             exams.passing_marks, exams.duration_minutes, exams.total_questions,
                             users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_id_number,
                             subjects.name as subject_name, classes.name as class_name')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('users', 'users.id = exam_attempts.student_id')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                   ->join('classes', 'classes.id = exams.class_id')
                   ->where('exam_attempts.id', $id)
                   ->first();
    }

    /**
     * Get attempts for an exam
     */
    public function getExamAttempts($examId)
    {
        return $this->select('exam_attempts.*, exams.total_marks, users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_id_number')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('users', 'users.id = exam_attempts.student_id')
                   ->where('exam_attempts.exam_id', $examId)
                   ->orderBy('exam_attempts.submitted_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get student's attempts
     */
    public function getStudentAttempts($studentId, $limit = null)
    {
        $builder = $this->select('exam_attempts.*, exams.title as exam_title, exams.total_marks, exams.exam_mode,
                                 subjects.name as subject_name')
                       ->join('exams', 'exams.id = exam_attempts.exam_id')
                       ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                       ->where('exam_attempts.student_id', $studentId)
                       // Show all attempts for now to handle existing records
                       // ->groupStart()
                       //     ->whereIn('exam_attempts.status', [self::STATUS_SUBMITTED, self::STATUS_AUTO_SUBMITTED, self::STATUS_COMPLETED])
                       //     ->orWhere('exam_attempts.marks_obtained IS NOT NULL')
                       //     ->orWhere('exam_attempts.percentage IS NOT NULL')
                       //     ->orWhere('exam_attempts.score IS NOT NULL')
                       // ->groupEnd()
                       ->orderBy('exam_attempts.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get student's active exam attempts (in progress)
     */
    public function getActiveAttempts($studentId)
    {
        return $this->select('exam_attempts.*, exams.title as exam_title, exams.duration_minutes,
                             exams.end_time as exam_end_time, subjects.name as subject_name')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left')
                   ->where('exam_attempts.student_id', $studentId)
                   ->where('exam_attempts.status', self::STATUS_IN_PROGRESS)
                   ->where('exams.is_active', 1)
                   ->where('exams.end_time >=', date('Y-m-d H:i:s'))
                   ->orderBy('exam_attempts.start_time', 'ASC')
                   ->findAll();
    }

    /**
     * Start exam attempt
     */
    public function startAttempt($examId, $studentId, $ipAddress, $userAgent)
    {
        // Get exam details to extract session and term
        $examModel = new ExamModel();
        $exam = $examModel->find($examId);

        $data = [
            'exam_id' => $examId,
            'student_id' => $studentId,
            'session_id' => $exam['session_id'] ?? null,
            'term_id' => $exam['term_id'] ?? null,
            'start_time' => date('Y-m-d H:i:s'),
            'started_at' => date('Y-m-d H:i:s'),
            'status' => self::STATUS_IN_PROGRESS,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'browser_info' => $this->getBrowserInfo($userAgent),
            'violations' => [],
            'proctoring_data' => [],
            'security_flags' => [],
            'answers' => [],
            'time_spent' => 0,
            'is_passed' => 0
        ];

        return $this->insert($data);
    }

    /**
     * Submit exam attempt
     */
    public function submitAttempt($attemptId, $answers, $autoSubmit = false)
    {
        $attempt = $this->find($attemptId);
        if (!$attempt) return false;

        $examModel = new ExamModel();
        $exam = $examModel->find($attempt['exam_id']);

        // Log submission details for debugging
        log_message('info', "=== EXAM SUBMISSION ===");
        log_message('info', "Attempt ID: {$attemptId}");
        log_message('info', "Exam ID: {$exam['id']}");
        log_message('info', "Auto Submit: " . ($autoSubmit ? 'true' : 'false'));
        log_message('info', "Answers received: " . json_encode($answers));

        // Ensure answers is countable before using count()
        $answersCount = 0;
        if (is_array($answers) || is_countable($answers)) {
            $answersCount = count($answers);
        } elseif (is_object($answers)) {
            $answersCount = count((array)$answers);
        }
        log_message('info', "Number of answers: " . $answersCount);

        // Calculate results
        $results = $this->calculateResults($exam, $answers);

        $updateData = [
            'end_time' => date('Y-m-d H:i:s'),
            'completed_at' => date('Y-m-d H:i:s'),
            'submitted_at' => date('Y-m-d H:i:s'),
            'time_taken_minutes' => $this->calculateTimeTaken($attempt['start_time']),
            'answers' => $answers,
            'total_questions' => $results['total_questions'],
            'answered_questions' => $results['answered_questions'],
            'correct_answers' => $results['correct_answers'],
            'wrong_answers' => $results['wrong_answers'],
            'marks_obtained' => $results['marks_obtained'],
            'score' => $results['marks_obtained'],
            'percentage' => $results['percentage'],
            'is_passed' => $results['marks_obtained'] >= $exam['passing_marks'] ? 1 : 0,
            'status' => $autoSubmit ? self::STATUS_AUTO_SUBMITTED : self::STATUS_SUBMITTED
        ];

        $result = $this->update($attemptId, $updateData);

        // Trigger term results calculation if session and term are available
        if ($result && !empty($attempt['session_id']) && !empty($attempt['term_id'])) {
            $this->updateTermResults($attempt['student_id'], $attempt['session_id'], $attempt['term_id'], $exam['class_id']);
        }

        return $result;
    }

    /**
     * Update term results after exam submission
     */
    private function updateTermResults($studentId, $sessionId, $termId, $classId)
    {
        try {
            $termResultsModel = new \App\Models\StudentTermResultsModel();
            $termResultsModel->calculateTermResults($studentId, $sessionId, $termId, $classId);
        } catch (\Exception $e) {
            // Log error but don't fail the exam submission
            log_message('error', 'Failed to update term results: ' . $e->getMessage());
        }
    }

    /**
     * Record violation
     */
    public function recordViolation($attemptId, $violationType, $details)
    {
        $attempt = $this->find($attemptId);
        if (!$attempt) return false;

        $violations = $attempt['violations'] ?? [];
        $violations[] = [
            'type' => $violationType,
            'details' => $details,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->update($attemptId, ['violations' => $violations]);
    }

    /**
     * Calculate exam results
     */
    private function calculateResults($exam, $answers)
    {
        // Convert answers to array if it's an object
        if (is_object($answers)) {
            $answers = (array) $answers;
        }

        // Ensure answers is an array
        if (!is_array($answers)) {
            $answers = [];
        }

        log_message('info', "=== SCORING CALCULATION ===");
        log_message('info', "Exam ID: {$exam['id']}");
        log_message('info', "Total answers received: " . count($answers));
        log_message('info', "Answers data: " . json_encode($answers));

        $examQuestionModel = new \App\Models\ExamQuestionModel();
        $questions = $examQuestionModel->getExamQuestionsGrouped($exam['id']);

        $totalQuestions = count($questions);
        $answeredQuestions = 0;
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $marksObtained = 0;

        foreach ($questions as $question) {
            $questionId = $question['id'];
            $studentAnswer = $answers[$questionId] ?? null;

            // Log each question processing for debugging
            log_message('info', "Processing question {$questionId}, answer: " . var_export($studentAnswer, true));

            // Check if question was answered (not null, not empty string, and not "0" for option IDs)
            $isAnswered = ($studentAnswer !== null && $studentAnswer !== '' && $studentAnswer !== 0);

            if ($isAnswered) {
                $answeredQuestions++;
                log_message('info', "Question {$questionId} marked as answered with value: " . var_export($studentAnswer, true));

                if ($this->isAnswerCorrect($question, $studentAnswer)) {
                    $correctAnswers++;
                    $marksObtained += $question['points'];
                    log_message('info', "Question {$questionId} is CORRECT, points: {$question['points']}");
                } else {
                    // Check for partial scoring in fill-in-the-blank questions
                    if ($question['question_type'] === 'fill_blank') {
                        $partialScore = $this->calculatePartialScore($question, $studentAnswer);
                        if ($partialScore > 0) {
                            $marksObtained += $partialScore;
                            log_message('info', "Question {$questionId} partial score: {$partialScore}");
                        }
                    }

                    $wrongAnswers++;
                    log_message('info', "Question {$questionId} is WRONG");

                    // Apply negative marking if enabled (but not for partial scores)
                    if ($exam['negative_marking'] && $question['question_type'] !== 'fill_blank') {
                        $marksObtained -= $exam['negative_marks_per_question'];
                        log_message('info', "Applied negative marking: -{$exam['negative_marks_per_question']}");
                    }
                }
            } else {
                log_message('info', "Question {$questionId} NOT answered");
            }
        }

        // Calculate actual total possible marks from questions
        $actualTotalMarks = array_sum(array_column($questions, 'points'));

        // Use actual total marks if available, otherwise fall back to exam total_marks
        $totalMarksForPercentage = $actualTotalMarks > 0 ? $actualTotalMarks : $exam['total_marks'];

        $percentage = $totalMarksForPercentage > 0 ? ($marksObtained / $totalMarksForPercentage) * 100 : 0;

        return [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'marks_obtained' => max(0, $marksObtained), // Ensure non-negative
            'percentage' => round($percentage, 2)
        ];
    }

    /**
     * Check if answer is correct
     */
    private function isAnswerCorrect($question, $studentAnswer)
    {
        $optionModel = new QuestionOptionModel();

        switch ($question['question_type']) {
            case 'mcq':
                $correctOption = $optionModel->where('question_id', $question['id'])
                                            ->where('is_correct', 1)
                                            ->first();
                // Convert both to string for comparison to handle type mismatches
                return $correctOption && (string)$correctOption['id'] === (string)$studentAnswer;

            case 'true_false':
            case 'yes_no':
                $correctOption = $optionModel->where('question_id', $question['id'])
                                            ->where('is_correct', 1)
                                            ->first();
                return $correctOption && strtolower($correctOption['option_text']) == strtolower($studentAnswer);

            case 'fill_blank':
                // For fill in the blank, check each blank individually
                $correctAnswers = $optionModel->where('question_id', $question['id'])
                                             ->where('is_correct', 1)
                                             ->findAll();

                // Parse student answers (JSON format: {"1": "answer1", "2": "answer2"})
                $studentAnswers = json_decode($studentAnswer, true);
                if (!is_array($studentAnswers)) {
                    return false;
                }

                // Get metadata to determine number of blanks
                $metadata = json_decode($question['metadata'] ?? '{}', true);
                $totalBlanks = $metadata['blank_count'] ?? 1;

                $correctBlanks = 0;

                // Check each blank
                for ($blankNumber = 1; $blankNumber <= $totalBlanks; $blankNumber++) {
                    $studentBlankAnswer = isset($studentAnswers[$blankNumber]) ?
                                        strtolower(trim($studentAnswers[$blankNumber])) : '';

                    if (empty($studentBlankAnswer)) {
                        continue; // Skip empty answers
                    }

                    // Find acceptable answers for this blank
                    $blankCorrectAnswers = array_filter($correctAnswers, function($answer) use ($blankNumber) {
                        return isset($answer['blank_number']) && $answer['blank_number'] == $blankNumber;
                    });

                    // Check if student answer matches any acceptable answer for this blank
                    foreach ($blankCorrectAnswers as $correctAnswer) {
                        $correctAnswerLower = strtolower(trim($correctAnswer['option_text']));
                        if ($studentBlankAnswer === $correctAnswerLower) {
                            $correctBlanks++;
                            break;
                        }
                    }
                }

                // Return true if all blanks are correct
                return $correctBlanks === $totalBlanks;

            case 'short_answer':
                // For short answer, check against acceptable answers/keywords
                $correctAnswers = $optionModel->where('question_id', $question['id'])
                                             ->where('is_correct', 1)
                                             ->findAll();

                $studentAnswerLower = strtolower(trim($studentAnswer));
                foreach ($correctAnswers as $correct) {
                    $correctAnswerLower = strtolower(trim($correct['option_text']));
                    // Check for exact match or keyword presence
                    if ($studentAnswerLower === $correctAnswerLower ||
                        strpos($studentAnswerLower, $correctAnswerLower) !== false) {
                        return true;
                    }
                }
                return false;

            case 'math_equation':
                // For math equations, check against acceptable mathematical answers
                $correctAnswers = $optionModel->where('question_id', $question['id'])
                                             ->where('is_correct', 1)
                                             ->findAll();

                $studentAnswerCleaned = preg_replace('/\s+/', '', strtolower($studentAnswer));
                foreach ($correctAnswers as $correct) {
                    $correctAnswerCleaned = preg_replace('/\s+/', '', strtolower($correct['option_text']));
                    if ($studentAnswerCleaned === $correctAnswerCleaned) {
                        return true;
                    }
                }
                return false;

            case 'essay':
            case 'image_based':
            case 'drag_drop':
            default:
                return false; // Manual grading required for essay, image_based, drag_drop, etc.
        }
    }

    /**
     * Calculate partial score for fill-in-the-blank questions
     */
    private function calculatePartialScore($question, $studentAnswer)
    {
        if ($question['question_type'] !== 'fill_blank') {
            return 0;
        }

        $optionModel = new QuestionOptionModel();
        $correctAnswers = $optionModel->where('question_id', $question['id'])
                                     ->where('is_correct', 1)
                                     ->findAll();

        // Parse student answers
        $studentAnswers = json_decode($studentAnswer, true);
        if (!is_array($studentAnswers)) {
            return 0;
        }

        // Get metadata to determine number of blanks
        $metadata = json_decode($question['metadata'] ?? '{}', true);
        $totalBlanks = $metadata['blank_count'] ?? 1;

        $correctBlanks = 0;

        // Check each blank
        for ($blankNumber = 1; $blankNumber <= $totalBlanks; $blankNumber++) {
            $studentBlankAnswer = isset($studentAnswers[$blankNumber]) ?
                                strtolower(trim($studentAnswers[$blankNumber])) : '';

            if (empty($studentBlankAnswer)) {
                continue;
            }

            // Find acceptable answers for this blank
            $blankCorrectAnswers = array_filter($correctAnswers, function($answer) use ($blankNumber) {
                return isset($answer['blank_number']) && $answer['blank_number'] == $blankNumber;
            });

            // Check if student answer matches any acceptable answer for this blank
            foreach ($blankCorrectAnswers as $correctAnswer) {
                $correctAnswerLower = strtolower(trim($correctAnswer['option_text']));
                if ($studentBlankAnswer === $correctAnswerLower) {
                    $correctBlanks++;
                    break;
                }
            }
        }

        // Calculate partial score: (correct blanks / total blanks) * total points
        if ($correctBlanks > 0 && $correctBlanks < $totalBlanks) {
            return ($correctBlanks / $totalBlanks) * $question['points'];
        }

        return 0; // No partial score if no blanks are correct
    }

    /**
     * Calculate time taken
     */
    private function calculateTimeTaken($startTime)
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime();
        $diff = $end->diff($start);

        return ($diff->h * 60) + $diff->i;
    }

    /**
     * Get browser info from user agent
     */
    private function getBrowserInfo($userAgent)
    {
        // Simple browser detection
        $browser = 'Unknown';
        $version = 'Unknown';

        if (preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        } elseif (preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        }

        return [
            'browser' => $browser,
            'version' => $version,
            'user_agent' => $userAgent
        ];
    }

    /**
     * Get exam performance data for reports
     */
    public function getExamPerformanceData()
    {
        return $this->select('exam_attempts.*, exams.title as exam_title, exams.total_marks, exams.exam_mode,
                             users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_id_number,
                             subjects.name as subject_name, classes.name as class_name')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('users', 'users.id = exam_attempts.student_id')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                   ->join('classes', 'classes.id = exams.class_id')
                   ->whereIn('exam_attempts.status', [self::STATUS_SUBMITTED, self::STATUS_AUTO_SUBMITTED])
                   ->orderBy('exam_attempts.submitted_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get grade distribution data
     */
    public function getGradeDistribution()
    {
        $attempts = $this->select('percentage, subjects.name as subject_name')
                        ->join('exams', 'exams.id = exam_attempts.exam_id')
                        ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                        ->whereIn('exam_attempts.status', [self::STATUS_SUBMITTED, self::STATUS_AUTO_SUBMITTED])
                        ->findAll();

        $distribution = [
            'A' => 0, // 80-100%
            'B' => 0, // 70-79%
            'C' => 0, // 60-69%
            'D' => 0, // 50-59%
            'F' => 0  // Below 50%
        ];

        foreach ($attempts as $attempt) {
            $percentage = $attempt['percentage'];
            if ($percentage >= 80) {
                $distribution['A']++;
            } elseif ($percentage >= 70) {
                $distribution['B']++;
            } elseif ($percentage >= 60) {
                $distribution['C']++;
            } elseif ($percentage >= 50) {
                $distribution['D']++;
            } else {
                $distribution['F']++;
            }
        }

        return $distribution;
    }

    /**
     * Get class performance data
     */
    public function getClassPerformanceData()
    {
        return $this->select('classes.name as class_name,
                             AVG(exam_attempts.percentage) as average_percentage,
                             COUNT(exam_attempts.id) as total_attempts,
                             SUM(CASE WHEN exam_attempts.percentage >= 50 THEN 1 ELSE 0 END) as passed_attempts')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->whereIn('exam_attempts.status', [self::STATUS_SUBMITTED, self::STATUS_AUTO_SUBMITTED])
                   ->groupBy('classes.id')
                   ->orderBy('average_percentage', 'DESC')
                   ->findAll();
    }

    /**
     * Get attendance data (exam participation)
     */
    public function getAttendanceData()
    {
        return $this->select('classes.name as class_name,
                             exams.title as exam_title,
                             COUNT(DISTINCT users.id) as total_students,
                             COUNT(exam_attempts.id) as attempted_students,
                             (COUNT(exam_attempts.id) / COUNT(DISTINCT users.id) * 100) as attendance_rate')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('classes', 'classes.id = exams.class_id')
                   ->join('users', 'users.class_id = classes.id AND users.role = "student"')
                   ->groupBy('exams.id, classes.id')
                   ->orderBy('attendance_rate', 'DESC')
                   ->findAll();
    }

    /**
     * Get recent attempts by teacher
     */
    public function getRecentAttemptsByTeacher($teacherId, $limit = 10)
    {
        return $this->select('exam_attempts.*, exams.title as exam_title,
                             users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_id,
                             subjects.name as subject_name')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('users', 'users.id = exam_attempts.student_id')
                   ->join('subjects', 'subjects.id = exams.subject_id')
                   ->where('exams.created_by', $teacherId)
                   ->whereIn('exam_attempts.status', [self::STATUS_SUBMITTED, self::STATUS_AUTO_SUBMITTED])
                   ->orderBy('exam_attempts.submitted_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get results by teacher
     */
    public function getResultsByTeacher($teacherId)
    {
        return $this->select('exam_attempts.*, exams.title as exam_title, exams.total_marks, exams.exam_mode,
                             users.first_name, users.last_name, COALESCE(users.student_id, users.username) as student_id_number,
                             subjects.name as subject_name, classes.name as class_name')
                   ->join('exams', 'exams.id = exam_attempts.exam_id')
                   ->join('users', 'users.id = exam_attempts.student_id')
                   ->join('subjects', 'subjects.id = exams.subject_id', 'left') // LEFT JOIN for multi-subject exams
                   ->join('classes', 'classes.id = exams.class_id')
                   ->where('exams.created_by', $teacherId)
                   ->whereIn('exam_attempts.status', [self::STATUS_SUBMITTED, self::STATUS_AUTO_SUBMITTED, self::STATUS_COMPLETED])
                   ->orderBy('exam_attempts.submitted_at', 'DESC')
                   ->findAll();
    }
}
