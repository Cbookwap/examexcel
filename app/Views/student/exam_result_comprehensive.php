<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.result-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.performance-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: transform 0.2s ease;
}

.performance-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.question-review-card {
    background: white;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.question-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.question-header.correct {
    background: #f0fdf4;
    border-left: 4px solid #22c55e;
}

.question-header.wrong {
    background: #fef2f2;
    border-left: 4px solid #ef4444;
}

.question-header.unanswered {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
}

.question-content {
    padding: 1.5rem;
}

.option-item {
    padding: 0.75rem 1rem;
    margin: 0.5rem 0;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
}

.option-item.selected {
    background: #dbeafe;
    border-color: #3b82f6;
}

.option-item.correct {
    background: #dcfce7;
    border-color: #22c55e;
}

.option-item.wrong {
    background: #fee2e2;
    border-color: #ef4444;
}

.option-item.wrong.selected {
    background: #fecaca;
    border-color: #dc2626;
    border-width: 2px;
}

.option-item.correct.selected {
    background: #bbf7d0;
    border-color: #16a34a;
    border-width: 2px;
}

.class-comparison {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.rank-badge {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
}

.subject-performance {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-bar-custom {
    height: 8px;
    border-radius: 4px;
    background: #e5e7eb;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.nav-tabs-custom {
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
}

.nav-tabs-custom .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6b7280;
    font-weight: 500;
    padding: 1rem 1.5rem;
}

.nav-tabs-custom .nav-link.active {
    color: #667eea;
    border-bottom-color: #667eea;
    background: none;
}

.tab-content {
    min-height: 400px;
}

/* Marks Sheet Styles */
.marks-sheet-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.marks-sheet-table {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.marks-sheet-table thead th {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--primary-dark) 100%);
    color: white !important;
    font-weight: 600;
    border: none;
    padding: 1.2rem 1rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-align: center;
}

.marks-sheet-table thead th:first-child {
    text-align: left;
}

.marks-sheet-table tbody tr {
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f1f5f9;
}

.marks-sheet-table tbody tr:hover {
    background-color: #f8fafc;
}

.marks-sheet-table tbody tr.table-warning {
    background-color: #fef3c7 !important;
    border-top: 3px solid #f59e0b;
    font-weight: 600;
}

.marks-sheet-table tbody tr.table-warning:hover {
    background-color: #fde68a !important;
}

.marks-sheet-table td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #f1f5f9;
}

.marks-sheet-table .badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
}

.bg-orange {
    background-color: #f97316 !important;
}

.marks-sheet-table .fw-bold {
    color: #1f2937;
}
</style>

<div class="container-fluid">
    <!-- Result Header -->
    <div class="result-header text-center">
        <?php
        // Use marks sheet data if available (for multi-subject exams) as it has the most accurate calculation
        if ($isMultiSubject && !empty($marksSheetData)) {
            // Find the "Grand Total" row in marks sheet data
            $grandTotalRow = null;
            foreach ($marksSheetData as $row) {
                if (isset($row['is_total_row']) && $row['is_total_row']) {
                    $grandTotalRow = $row;
                    break;
                }
            }

            if ($grandTotalRow) {
                $marksObtained = $grandTotalRow['marks_obtained'];
                $totalMarks = $grandTotalRow['total_marks'];
                $percentage = $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0;
            } else {
                // Fallback to performance calculation
                $correctAnswers = $performance['correct_answers'] ?? 0;
                $totalQuestions = $performance['total_questions'] ?? 1;
                $totalMarks = $exam['total_marks'] ?? 1;
                $pointsPerQuestion = $totalQuestions > 0 ? ($totalMarks / $totalQuestions) : 1;
                $marksObtained = $correctAnswers * $pointsPerQuestion;
                $percentage = $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0;
            }
        } else {
            // For single-subject exams, use performance calculation
            $correctAnswers = $performance['correct_answers'] ?? 0;
            $totalQuestions = $performance['total_questions'] ?? 1;
            $totalMarks = $exam['total_marks'] ?? 1;
            $pointsPerQuestion = $totalQuestions > 0 ? ($totalMarks / $totalQuestions) : 1;
            $marksObtained = $correctAnswers * $pointsPerQuestion;
            $percentage = $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0;
        }

        $passingMarks = $exam['passing_marks'] ?? 60;
        $passed = $percentage >= 60 || $marksObtained >= $passingMarks;
        ?>
        <div class="row align-items-center">
            <div class="col-md-8">
                <?php if ($passed): ?>
                    <div class="mb-3">
                        <i class="fas fa-trophy fa-3x text-warning"></i>
                    </div>
                    <h2 class="fw-bold mb-2"><font color='white'>Congratulations!</font></h2>
                    <p class="lead mb-0">You have successfully passed the exam</p>
                <?php else: ?>
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-info"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Keep Improving!</h2>
                    <p class="lead mb-0">Review your answers and practice more</p>
                <?php endif; ?>
                <h4 class="mt-3 mb-1"><?= esc($attempt['exam_title']) ?></h4>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar me-2"></i>
                    <?= date('F j, Y \a\t g:i A', strtotime($attempt['submitted_at'] ?: $attempt['created_at'])) ?>
                </p>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="display-4 fw-bold mb-2"><?= $percentage ?>%</div>
                    <div class="h5 mb-2"><font color='white'><?= $marksObtained ?>/<?= $totalMarks ?> mks</font></div>
                    <div class="rank-badge">
                        <i class="fas fa-medal me-1"></i>
                        Rank #<?= $classRank ?> in class
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= base_url('student/exams') ?>" class="btn btn-outline-light bg-white text-dark">
                    <i class="fas fa-arrow-left me-2"></i>Back to Exams
                </a>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Result
                    </button>
                    <button class="btn btn-primary" onclick="downloadResult()">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="performance-card text-center">
                <div class="stat-number text-primary"><?= $performance['accuracy'] ?>%</div>
                <div class="stat-label">Accuracy</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="performance-card text-center">
                <div class="stat-number text-success"><?= $performance['correct_answers'] ?></div>
                <div class="stat-label">Correct</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="performance-card text-center">
                <div class="stat-number text-danger"><?= $performance['wrong_answers'] ?></div>
                <div class="stat-label">Wrong</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="performance-card text-center">
                <div class="stat-number text-warning"><?= $performance['unanswered'] ?></div>
                <div class="stat-label">Unanswered</div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs nav-tabs-custom" id="resultTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                <i class="fas fa-chart-pie me-2"></i>Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions" type="button" role="tab">
                <i class="fas fa-list-alt me-2"></i>Question Review
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="comparison-tab" data-bs-toggle="tab" data-bs-target="#comparison" type="button" role="tab">
                <i class="fas fa-users me-2"></i>Class Comparison
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab">
                <i class="fas fa-book me-2"></i>Subject Analysis
            </button>
        </li>
        <?php if ($isMultiSubject && !empty($marksSheetData)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="marks-sheet-tab" data-bs-toggle="tab" data-bs-target="#marks-sheet" type="button" role="tab">
                <i class="fas fa-table me-2"></i>Marks Sheet
            </button>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="resultTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="performance-card">
                        <h5 class="fw-semibold mb-4">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Performance Summary
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-medium">Overall Score</span>
                                        <span class="fw-bold text-primary"><?= $percentage ?>%</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill bg-primary" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-medium">Accuracy Rate</span>
                                        <span class="fw-bold text-success"><?= $performance['accuracy'] ?>%</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill bg-success" style="width: <?= $performance['accuracy'] ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-medium">Questions Attempted</span>
                                        <span class="fw-bold"><?= $performance['correct_answers'] + $performance['wrong_answers'] ?>/<?= $performance['total_questions'] ?></span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <?php $attemptedPercentage = $performance['total_questions'] > 0 ? (($performance['correct_answers'] + $performance['wrong_answers']) / $performance['total_questions']) * 100 : 0; ?>
                                        <div class="progress-fill bg-info" style="width: <?= $attemptedPercentage ?>%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-medium">Time Efficiency</span>
                                        <span class="fw-bold text-warning">Good</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill bg-warning" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="performance-card">
                        <h5 class="fw-semibold mb-4">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Exam Details
                        </h5>
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Exam Title</div>
                            <div class="fw-medium"><?= esc($attempt['exam_title']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Subject</div>
                            <div class="fw-medium"><?= esc($attempt['subject_name']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Class</div>
                            <div class="fw-medium"><?= esc($attempt['class_name']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Duration</div>
                            <div class="fw-medium"><?= $attempt['time_taken'] ?? 'N/A' ?> minutes</div>
                        </div>
                        <div class="mb-3">
                            <div class="small text-muted mb-1">Passing Marks</div>
                            <div class="fw-medium"><?= $passingMarks ?> marks</div>
                        </div>
                        <div class="mb-0">
                            <div class="small text-muted mb-1">Status</div>
                            <div>
                                <?php if ($passed): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>PASSED
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>FAILED
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Review Tab -->
        <div class="tab-pane fade" id="questions" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="performance-card mb-4">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-list-alt text-primary me-2"></i>
                            Detailed Question Review
                        </h5>
                        <p class="text-muted mb-0">Review each question, your answers, and the correct solutions</p>
                    </div>





                    <?php foreach ($questions as $index => $question): ?>
                        <?php
                        // === SIMPLIFIED ANSWER ANALYSIS LOGIC ===
                        $questionId = $question['id'];
                        $studentAnswer = $studentAnswers[$questionId] ?? $studentAnswers[(int)$questionId] ?? $studentAnswers[(string)$questionId] ?? null;



                        // Check if question was answered
                        $isAnswered = ($studentAnswer !== null && $studentAnswer !== '' && $studentAnswer !== '0');

                        // Initialize arrays for tracking
                        $selectedOptionLetter = null;
                        $correctOptionLetters = [];
                        $isCorrect = false;

                        if (in_array($question['question_type'], ['mcq', 'true_false', 'yes_no'])) {
                            // Build option mapping: ID -> Letter and Letter -> ID
                            $optionIdToLetter = [];
                            $optionLetterToId = [];
                            $correctOptionIds = [];

                            if (isset($question['options']) && is_array($question['options']) && !empty($question['options'])) {
                                // New structure: use options array
                                foreach ($question['options'] as $option) {
                                    $optionIndex = $option['order_index'] - 1;
                                    $optionLetter = chr(65 + $optionIndex); // A, B, C, D

                                    $optionIdToLetter[$option['id']] = $optionLetter;
                                    $optionLetterToId[$optionLetter] = $option['id'];

                                    if ($option['is_correct']) {
                                        $correctOptionLetters[] = $optionLetter;
                                        $correctOptionIds[] = $option['id'];
                                    }
                                }


                            } else {
                                // Old structure: fallback logic
                                if ($question['question_type'] === 'true_false') {
                                    $optionIdToLetter = ['true' => 'A', 'false' => 'B'];
                                    $optionLetterToId = ['A' => 'true', 'B' => 'false'];
                                    $correctAnswer = $question['correct_answer'] ?? '';
                                    if ($correctAnswer === 'true') $correctOptionLetters[] = 'A';
                                    if ($correctAnswer === 'false') $correctOptionLetters[] = 'B';
                                } elseif ($question['question_type'] === 'yes_no') {
                                    $optionIdToLetter = ['yes' => 'A', 'no' => 'B'];
                                    $optionLetterToId = ['A' => 'yes', 'B' => 'no'];
                                    $correctAnswer = $question['correct_answer'] ?? '';
                                    if ($correctAnswer === 'yes') $correctOptionLetters[] = 'A';
                                    if ($correctAnswer === 'no') $correctOptionLetters[] = 'B';
                                } else {
                                    // MCQ with old structure
                                    $correctAnswer = $question['correct_answer'] ?? '';
                                    if (in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
                                        $correctOptionLetters[] = $correctAnswer;
                                    }
                                }
                            }

                            // Determine student's selected option letter
                            if ($isAnswered) {
                                $studentAnswerStr = (string)$studentAnswer;

                                // Try to convert student answer to letter
                                if (isset($optionIdToLetter[$studentAnswerStr])) {
                                    // Student answer is an option ID
                                    $selectedOptionLetter = $optionIdToLetter[$studentAnswerStr];
                                } elseif (in_array($studentAnswerStr, ['A', 'B', 'C', 'D'])) {
                                    // Student answer is already a letter
                                    $selectedOptionLetter = $studentAnswerStr;
                                } elseif (isset($optionIdToLetter[$studentAnswerStr])) {
                                    // Handle special cases like true/false, yes/no
                                    $selectedOptionLetter = $optionIdToLetter[$studentAnswerStr];
                                }

                                // Check if answer is correct
                                $isCorrect = in_array($selectedOptionLetter, $correctOptionLetters);
                            }
                        }

                        $questionStatus = $isAnswered ? ($isCorrect ? 'correct' : 'wrong') : 'unanswered';
                        ?>



                        <div class="question-review-card">
                            <div class="question-header <?= $questionStatus ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <?php if ($questionStatus === 'correct'): ?>
                                                <i class="fas fa-check-circle text-success fa-2x"></i>
                                            <?php elseif ($questionStatus === 'wrong'): ?>
                                                <i class="fas fa-times-circle text-danger fa-2x"></i>
                                            <?php else: ?>
                                                <i class="fas fa-question-circle text-warning fa-2x"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Question <?= $index + 1 ?></h6>
                                            <small class="text-muted">
											<b>Subject: <?= esc($question['subject_name']) ?></b> |
                                                <?= ucfirst($questionStatus) ?> •
                                                <?= $question['points'] ?> mks  
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $questionStatus === 'correct' ? 'success' : ($questionStatus === 'wrong' ? 'danger' : 'warning') ?>">
                                            <?= $questionStatus === 'correct' ? '+' . $question['points'] : '0' ?> mk(s)
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="question-content">
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-2">Question:</h6>
                                    <p class="mb-0"><?= esc($question['question_text']) ?></p>
                                </div>

                                <?php if (in_array($question['question_type'], ['mcq', 'true_false', 'yes_no'])): ?>
                                    <div class="mb-4">
                                        <h6 class="fw-semibold mb-3">Options:</h6>
                                        <div class="row">
                                            <?php
                                            // Use the options from the database
                                            $options = [];
                                            if (isset($question['options']) && is_array($question['options'])) {
                                                foreach ($question['options'] as $option) {
                                                    $optionIndex = $option['order_index'] - 1; // Convert to 0-based index
                                                    $optionLetter = chr(65 + $optionIndex); // Convert to A, B, C, D
                                                    $options[$optionLetter] = $option['option_text'];
                                                }
                                            } else {
                                                // Fallback to old structure if options array is not available
                                                if ($question['question_type'] === 'mcq') {
                                                    $options = [
                                                        'A' => $question['option_a'] ?? '',
                                                        'B' => $question['option_b'] ?? '',
                                                        'C' => $question['option_c'] ?? '',
                                                        'D' => $question['option_d'] ?? ''
                                                    ];
                                                } elseif ($question['question_type'] === 'true_false') {
                                                    $options = ['A' => 'True', 'B' => 'False'];
                                                } elseif ($question['question_type'] === 'yes_no') {
                                                    $options = ['A' => 'Yes', 'B' => 'No'];
                                                }
                                            }
                                            ?>

                                            <?php foreach ($options as $optionKey => $optionText): ?>
                                                <?php if (!empty($optionText)): ?>
                                                    <div class="col-md-6 mb-2">
                                                        <?php
                                                        // === SIMPLIFIED OPTION ANALYSIS ===
                                                        $isSelected = ($selectedOptionLetter === $optionKey);
                                                        $isCorrectOption = in_array($optionKey, $correctOptionLetters);

                                                        $optionClass = '';
                                                        $iconClass = '';

                                                        if ($isSelected && $isCorrectOption) {
                                                            // Student selected the correct answer
                                                            $optionClass = 'correct selected';
                                                            $iconClass = 'fas fa-check-circle text-success';
                                                        } elseif ($isSelected && !$isCorrectOption) {
                                                            // Student selected the wrong answer
                                                            $optionClass = 'wrong selected';
                                                            $iconClass = 'fas fa-times-circle text-danger';
                                                        } elseif (!$isSelected && $isCorrectOption) {
                                                            // This is the correct answer but student didn't select it
                                                            $optionClass = 'correct';
                                                            $iconClass = 'fas fa-check text-success';
                                                        }
                                                        ?>

                                                        <div class="option-item <?= $optionClass ?>">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <strong><?= $optionKey ?>.</strong>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <?= esc($optionText) ?>
                                                                </div>
                                                                <div class="ms-2">
                                                                    <?php if (!empty($iconClass)): ?>
                                                                        <i class="<?= $iconClass ?>"></i>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($question['explanation'])): ?>
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h6 class="fw-semibold mb-2">
                                            <i class="fas fa-lightbulb text-warning me-2"></i>
                                            Explanation:
                                        </h6>
                                        <p class="mb-0 text-muted"><?= esc($question['explanation']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Class Comparison Tab -->
        <div class="tab-pane fade" id="comparison" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="performance-card">
                        <h5 class="fw-semibold mb-4">
                            <i class="fas fa-users text-primary me-2"></i>
                            Class Performance Comparison
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="stat-number text-primary"><?= $classRank ?></div>
                                <div class="stat-label">Your Rank</div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-number text-info"><?= $classPerformance['total_students'] ?></div>
                                <div class="stat-label">Total Students</div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-number text-success"><?= $classPerformance['class_average'] ?>%</div>
                                <div class="stat-label">Class Average</div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-number text-warning"><?= $classPerformance['pass_rate'] ?>%</div>
                                <div class="stat-label">Pass Rate</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Performance Distribution</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Your Score</span>
                                            <span class="fw-bold text-primary"><?= $percentage ?>%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill bg-primary" style="width: <?= $percentage ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Class Average</span>
                                            <span class="fw-bold text-info"><?= $classPerformance['class_average'] ?>%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill bg-info" style="width: <?= $classPerformance['class_average'] ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Highest Score</span>
                                            <span class="fw-bold text-success"><?= $classPerformance['highest_score'] ?>%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill bg-success" style="width: <?= $classPerformance['highest_score'] ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Lowest Score</span>
                                            <span class="fw-bold text-danger"><?= $classPerformance['lowest_score'] ?>%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill bg-danger" style="width: <?= $classPerformance['lowest_score'] ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="performance-card">
                        <h5 class="fw-semibold mb-4">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            Top Performers
                        </h5>

                        <?php if (!empty($classPerformance['top_performers'])): ?>
                            <?php foreach ($classPerformance['top_performers'] as $index => $performer): ?>
                                <div class="d-flex align-items-center mb-3 <?= $performer['student_id'] === $attempt['student_id_number'] ? 'bg-light p-2 rounded' : '' ?>">
                                    <div class="me-3">
                                        <div class="rank-badge" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                            #<?= $index + 1 ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium"><?= esc($performer['first_name'] . ' ' . $performer['last_name']) ?></div>
                                        <small class="text-muted"><?= esc($performer['student_id']) ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary"><?= $performer['calculated_percentage'] ?>%</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">No performance data available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Analysis Tab -->
        <div class="tab-pane fade" id="subjects" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="performance-card mb-4">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-book text-primary me-2"></i>
                            Subject-wise Performance Analysis
                        </h5>
                        <p class="text-muted mb-0">Detailed breakdown of your performance by subject</p>
                    </div>

                    <?php if (!empty($subjectPerformance)): ?>
                        <div class="row">
                            <?php foreach ($subjectPerformance as $subjectName => $performance): ?>
                                <div class="col-lg-6 mb-4">
                                    <div class="subject-performance">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-semibold mb-0"><?= esc($subjectName) ?></h6>
                                            <span class="badge bg-primary">
                                                <?= $performance['correct'] ?>/<?= $performance['total'] ?> correct
                                            </span>
                                        </div>

                                        <div class="row text-center mb-3">
                                            <div class="col-3">
                                                <div class="fw-bold text-success"><?= $performance['correct'] ?></div>
                                                <small class="text-muted">Correct</small>
                                            </div>
                                            <div class="col-3">
                                                <div class="fw-bold text-danger"><?= $performance['wrong'] ?></div>
                                                <small class="text-muted">Wrong</small>
                                            </div>
                                            <div class="col-3">
                                                <div class="fw-bold text-warning"><?= $performance['unanswered'] ?></div>
                                                <small class="text-muted">Skipped</small>
                                            </div>
                                            <div class="col-3">
                                                <div class="fw-bold text-primary">
                                                    <?= $performance['total'] > 0 ? round(($performance['correct'] / $performance['total']) * 100, 1) : 0 ?>%
                                                </div>
                                                <small class="text-muted">Score</small>
                                            </div>
                                        </div>

                                        <div class="progress-bar-custom">
                                            <?php $subjectPercentage = $performance['total'] > 0 ? ($performance['correct'] / $performance['total']) * 100 : 0; ?>
                                            <div class="progress-fill bg-primary" style="width: <?= $subjectPercentage ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">No Subject Data Available</h5>
                            <p class="text-muted">Subject performance analysis will be available for future exams.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Marks Sheet Tab (Multi-Subject Only) -->
        <?php if ($isMultiSubject && !empty($marksSheetData)): ?>
        <div class="tab-pane fade" id="marks-sheet" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="performance-card mb-4">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-table text-primary me-2"></i>
                            Marks Sheet
                        </h5>
                        <p class="text-muted mb-0">Detailed breakdown of marks obtained per subject</p>
                    </div>

                    <div class="marks-sheet-container">
                        <div class="table-responsive">
                            <table class="table table-hover marks-sheet-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Subject(s)</th>
                                        <th scope="col" class="text-center">Subject Proportion</th>
                                        <th scope="col" class="text-center">No. of Questions</th>
                                        <th scope="col" class="text-center">Score</th>
                                        <th scope="col" class="text-center">Percentage</th>
                                        <th scope="col" class="text-center">Time Taken</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($marksSheetData as $index => $subject): ?>
                                    <tr class="<?= isset($subject['is_total_row']) && $subject['is_total_row'] ? 'table-warning fw-bold' : '' ?>">
                                        <td class="fw-medium">
                                            <?php if (isset($subject['is_total_row']) && $subject['is_total_row']): ?>
                                                <i class="fas fa-calculator me-2 text-warning"></i>
                                            <?php else: ?>
                                                <i class="fas fa-book me-2 text-primary"></i>
                                            <?php endif; ?>
                                            <?= esc($subject['subject_name']) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">
                                                <?= esc($subject['subject_proportion']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-medium"><?= esc($subject['question_count']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-primary">
                                                <?= esc($subject['marks_obtained']) ?>/<?= esc($subject['total_marks']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $percentage = (float) str_replace('%', '', $subject['percentage']);
                                            $badgeClass = 'bg-secondary';
                                            if ($percentage >= 80) $badgeClass = 'bg-success';
                                            elseif ($percentage >= 70) $badgeClass = 'bg-info';
                                            elseif ($percentage >= 60) $badgeClass = 'bg-warning';
                                            elseif ($percentage >= 50) $badgeClass = 'bg-orange';
                                            else $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> text-white">
                                                <?= esc($subject['percentage']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if (isset($subject['time_allocation']) && $subject['time_allocation'] > 0): ?>
                                                <small class="text-muted">
                                                    Allocated: <?= esc($subject['time_allocation']) ?> mins
                                                    <br>
                                                    <span class="badge bg-primary text-white">
                                                        Spent: <?= esc($subject['actual_time_taken'] ?? '0 sec') ?>
                                                    </span>
                                                </small>
                                            <?php else: ?>
                                                <span class="badge bg-primary text-white">
                                                    <?= esc($subject['actual_time_taken'] ?? '0 sec') ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {


    // Show result message
    const passed = <?= $passed ? 'true' : 'false' ?>;
    const score = <?= $percentage ?>;

    if (passed) {
        CBT.showToast('Congratulations! You scored ' + score + '% and passed the exam!', 'success');
    } else {
        CBT.showToast('You scored ' + score + '%. Review your answers and keep practicing!', 'info');
    }

    // Smooth scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

function downloadResult() {
    CBT.showToast('PDF download will be available soon!', 'info');
}
</script>
<?= $this->endSection() ?>
