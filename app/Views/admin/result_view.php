<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.result-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(var(--primary-color-rgb), 0.3);
}

.performance-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.question-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.question-card.correct {
    border-left-color: #10b981;
    background: linear-gradient(to right, #ecfdf5, #ffffff);
}

.question-card.wrong {
    border-left-color: #ef4444;
    background: linear-gradient(to right, #fef2f2, #ffffff);
}

.question-card.unanswered {
    border-left-color: #f59e0b;
    background: linear-gradient(to right, #fffbeb, #ffffff);
}

.option-item {
    padding: 0.75rem;
    margin: 0.5rem 0;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.option-item.correct {
    background: #dcfce7;
    border-color: #10b981;
    color: #065f46;
}

.option-item.wrong {
    background: #fee2e2;
    border-color: #ef4444;
    color: #991b1b;
}

.option-item.selected {
    background: #dbeafe;
    border-color: #3b82f6;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.status-pass {
    background: #dcfce7;
    color: #065f46;
}

.status-fail {
    background: #fee2e2;
    color: #991b1b;
}

.progress-ring {
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.progress-ring circle {
    fill: transparent;
    stroke-width: 8;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}

.progress-ring .background {
    stroke: #e5e7eb;
}

.progress-ring .progress {
    stroke: #10b981;
    stroke-dasharray: 314;
    stroke-dashoffset: 314;
    transition: stroke-dashoffset 0.5s ease-in-out;
}

.btn-back {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-back:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.breadcrumb {
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #6b7280;
    font-weight: 500;
}
</style>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/dashboard') ?>">
                <i class="material-symbols-rounded me-1" style="font-size: 16px;">dashboard</i>
                Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/results') ?>">Results & Analytics</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?> - <?= esc($attempt['exam_title']) ?>
        </li>
    </ol>
</nav>

<!-- Back Button -->
<div class="mb-3">
    <a href="<?= base_url('admin/results') ?>" class="btn btn-outline-primary btn-back">
        <i class="material-symbols-rounded me-1">arrow_back</i>
        Back to Results
    </a>
</div>

<!-- Result Header -->
<div class="result-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="mb-2"><?= esc($attempt['exam_title']) ?></h1>
            <div class="d-flex flex-wrap gap-3">
                <div>
                    <i class="material-symbols-rounded me-1">person</i>
                    <strong><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></strong>
                </div>
                <div>
                    <i class="material-symbols-rounded me-1">badge</i>
                    <?= esc($attempt['student_id_number']) ?>
                </div>
                <div>
                    <i class="material-symbols-rounded me-1">school</i>
                    <?= esc($attempt['class_name']) ?>
                </div>
                <div>
                    <i class="material-symbols-rounded me-1">subject</i>
                    <?= esc($attempt['subject_name']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="mb-2">
                <?php
                $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
                $totalMarks = $attempt['total_marks'] ?? 1;
                $passingThreshold = $attempt['passing_marks'] ?? 0;
                $isPassed = $studentScore >= $passingThreshold;
                // Recalculate percentage based on correct score to handle data inconsistencies
                $correctedPercentage = $totalMarks > 0 ? round(($studentScore / $totalMarks) * 100, 2) : 0;
                ?>
                <span class="status-badge <?= $isPassed ? 'status-pass' : 'status-fail' ?>">
                    <?= $isPassed ? 'PASSED' : 'FAILED' ?>
                </span>
            </div>
            <div class="text-white-50">
                <small>Submitted: <?= date('M j, Y g:i A', strtotime($attempt['submitted_at'])) ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Performance Overview -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="performance-card">
            <div class="stat-item">
                <div class="stat-number text-primary"><?= $correctedPercentage ?>%</div>
                <div class="stat-label">Overall Mark</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="performance-card">
            <div class="stat-item">
                <div class="stat-number text-success"><?= $performance['correct_answers'] ?></div>
                <div class="stat-label">Correct Answers</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="performance-card">
            <div class="stat-item">
                <div class="stat-number text-danger"><?= $performance['wrong_answers'] ?></div>
                <div class="stat-label">Wrong Answers</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="performance-card">
            <div class="stat-item">
                <div class="stat-number text-warning"><?= $performance['unanswered'] ?></div>
                <div class="stat-label">Unanswered</div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Results -->
<div class="row">
    <div class="col-lg-8">
        <div class="performance-card">
            <h5 class="mb-4">
                <i class="material-symbols-rounded me-2">quiz</i>
                Question-by-Question Analysis
            </h5>
            
            <?php if (!empty($questions)): ?>
                <?php if (empty($studentAnswers)): ?>
                    <!-- Show message when detailed answer data is not available -->
                    <div class="alert alert-info">
                        <i class="material-symbols-rounded me-2">info</i>
                        <strong>Note:</strong> Detailed answer data is not available for this exam attempt.
                        The performance summary above shows the stored results from when the exam was submitted.
                    </div>

                    <!-- Show basic question list without detailed answers -->
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="mb-0">Question <?= $index + 1 ?></h6>
                                <span class="badge bg-secondary">Data Not Available</span>
                            </div>
                            <p class="mb-3"><?= esc($question['question_text']) ?></p>
                            <div class="text-muted">
                                <small><i class="material-symbols-rounded me-1">info</i>Answer details not available</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Show detailed answers when data is available -->
                    <?php foreach ($questions as $index => $question): ?>
                        <?php
                        // Get student's answer for this question
                        $studentAnswer = $studentAnswers[$question['id']] ?? null;

                        // Determine question status
                        $questionStatus = 'unanswered';
                        $isCorrect = false;

                        if ($studentAnswer && (!empty($studentAnswer['answer_text']) || !empty($studentAnswer['selected_options']))) {
                            $isCorrect = $studentAnswer['is_correct'];
                            $questionStatus = $isCorrect ? 'correct' : 'wrong';
                        }
                        ?>
                    
                    <div class="question-card <?= $questionStatus ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="mb-0">Question <?= $index + 1 ?></h6>
                            <span class="badge bg-<?= $questionStatus === 'correct' ? 'success' : ($questionStatus === 'wrong' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($questionStatus) ?>
                            </span>
                        </div>
                        
                        <p class="mb-3"><?= esc($question['question_text']) ?></p>
                        
                        <div class="options">
                            <?php if (in_array($question['question_type'], ['mcq', 'true_false', 'yes_no'])): ?>
                                <?php
                                $selectedOptions = $studentAnswer ? json_decode($studentAnswer['selected_options'] ?? '[]', true) : [];
                                $selectedOption = !empty($selectedOptions) ? $selectedOptions[0] : null;

                                if (!empty($question['options'])):
                                    foreach ($question['options'] as $index => $option):
                                        $optionLetter = chr(65 + $index); // A, B, C, D...
                                        $isCorrectOption = $option['is_correct'];
                                        $isSelectedOption = $selectedOption === $optionLetter;

                                        $optionClass = '';
                                        if ($isCorrectOption) {
                                            $optionClass = 'correct';
                                        } elseif ($isSelectedOption && !$isCorrectOption) {
                                            $optionClass = 'wrong';
                                        } elseif ($isSelectedOption) {
                                            $optionClass = 'selected';
                                        }
                                ?>
                                    <div class="option-item <?= $optionClass ?>">
                                        <strong><?= $optionLetter ?>.</strong>
                                        <?= esc($option['option_text']) ?>
                                        <?php if ($isCorrectOption): ?>
                                            <i class="material-symbols-rounded text-success float-end">check_circle</i>
                                        <?php elseif ($isSelectedOption && !$isCorrectOption): ?>
                                            <i class="material-symbols-rounded text-danger float-end">cancel</i>
                                        <?php endif; ?>
                                    </div>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            <?php elseif (in_array($question['question_type'], ['fill_blank', 'short_answer'])): ?>
                                <div class="mb-2">
                                    <strong>Student Answer:</strong>
                                    <div class="option-item <?= $isCorrect ? 'correct' : 'wrong' ?>">
                                        <?= esc($studentAnswer['answer_text'] ?? 'No answer provided') ?>
                                        <?php if ($isCorrect): ?>
                                            <i class="material-symbols-rounded text-success float-end">check_circle</i>
                                        <?php else: ?>
                                            <i class="material-symbols-rounded text-danger float-end">cancel</i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <strong>Acceptable Answers:</strong>
                                    <?php if (!empty($question['options'])): ?>
                                        <?php foreach ($question['options'] as $option): ?>
                                            <?php if ($option['is_correct']): ?>
                                                <div class="option-item correct">
                                                    <?= esc($option['option_text']) ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!$studentAnswer || (empty($studentAnswer['answer_text']) && empty($studentAnswer['selected_options']))): ?>
                            <div class="mt-2">
                                <small class="text-warning">
                                    <i class="material-symbols-rounded me-1">info</i>
                                    This question was not answered
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="material-symbols-rounded text-muted mb-3" style="font-size: 3rem;">quiz</i>
                    <p class="text-muted">No questions found for this exam</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="performance-card">
            <h5 class="mb-4">
                <i class="fas fa-chart-bar me-2"></i>
                Performance Summary
            </h5>
            
            <!-- Accuracy Circle -->
            <div class="text-center mb-4">
                <svg class="progress-ring">
                    <circle class="background" cx="60" cy="60" r="50"></circle>
                    <circle class="progress" cx="60" cy="60" r="50" 
                            style="stroke-dashoffset: <?= 314 - (314 * $performance['accuracy'] / 100) ?>"></circle>
                </svg>
                <div class="mt-2">
                    <h4 class="mb-0"><?= $performance['accuracy'] ?>%</h4>
                    <small class="text-muted">Accuracy Rate</small>
                </div>
            </div>
            
            <!-- Exam Details -->
            <div class="border-top pt-3">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-number text-primary"><?= $studentScore ?></div>
                        <div class="stat-label">Mark Obtained</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-number text-secondary"><?= $attempt['total_marks'] ?></div>
                        <div class="stat-label">Total Marks</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-number text-info">
                            <?= $attempt['time_taken_minutes'] ?? $attempt['time_spent'] ?? $attempt['time_taken'] ?? 'N/A' ?>
                        </div>
                        <div class="stat-label">Minutes Taken</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-number text-success"><?= $performance['total_questions'] ?></div>
                        <div class="stat-label">Total Questions</div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-grid gap-2 mt-4">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="material-symbols-rounded me-1">print</i>
                    Print Report
                </button>
                <a href="<?= base_url('admin/results/download/' . $attempt['id']) ?>" class="btn btn-outline-secondary">
                    <i class="material-symbols-rounded me-1">download</i>
                    Download Report
                </a>
                <a href="<?= base_url('admin/results') ?>" class="btn btn-outline-dark">
                    <i class="material-symbols-rounded me-1">arrow_back</i>
                    Back to Results
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Print functionality
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});

// Add print styles
const printStyles = `
@media print {
    .btn, .navbar, .sidebar { display: none !important; }
    .result-header { background: var(--primary-color) !important; -webkit-print-color-adjust: exact; }
    .performance-card { break-inside: avoid; }
    .question-card { break-inside: avoid; margin-bottom: 1rem; }
}
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = printStyles;
document.head.appendChild(styleSheet);
</script>
<?= $this->endSection() ?>
