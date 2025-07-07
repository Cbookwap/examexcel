<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .result-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .score-display {
        font-size: 3rem;
        font-weight: bold;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .option-item {
        padding: 0.5rem 1rem;
        margin: 0.25rem 0;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
    }
    .option-item.correct {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .option-item.wrong {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .option-item.selected {
        background: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }
    .option-correct {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .option-selected {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .option-selected.option-correct {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .question-card {
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: white;
    }
    .question-card.correct {
        border-left: 4px solid #28a745;
    }
    .question-card.wrong {
        border-left: 4px solid #dc3545;
    }
    .question-card.unanswered {
        border-left: 4px solid #ffc107;
    }
    .performance-chart {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 8px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;"><?= $pageTitle ?></h4>
                <p class="text-light mb-0"><?= $pageSubtitle ?></p>
            </div>
            <a href="<?= base_url('principal/results') ?>" class="btn btn-outline-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Results
            </a>
        </div>
    </div>
</div>

<!-- Result Header -->
<div class="result-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2"><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></h2>
            <p class="mb-1 opacity-75">Student ID: <?= esc($attempt['student_id']) ?></p>
            <p class="mb-0 opacity-75">Exam: <?= esc($attempt['exam_title']) ?></p>
        </div>
        <div class="col-md-4 text-end">
            <?php
            $studentScore = $attempt['marks_obtained'] ?? $attempt['score'] ?? 0;
            $totalMarks = $attempt['total_marks'] ?? 1;
            // Recalculate percentage based on correct score to handle data inconsistencies
            $percentage = $totalMarks > 0 ? round(($studentScore / $totalMarks) * 100, 2) : 0;
            ?>
            <div class="score-display"><?= $percentage ?>%</div>
            <p class="mb-0 opacity-75"><?= $studentScore ?> / <?= $totalMarks ?> marks</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Performance Overview -->
        <div class="info-card">
            <h5 class="mb-4 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">analytics</i>
                Performance Overview
            </h5>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light rounded">
                        <div class="h4 fw-bold text-primary"><?= $performance['total_questions'] ?></div>
                        <div class="small text-muted">Total Questions</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light rounded">
                        <div class="h4 fw-bold text-success"><?= $performance['correct_answers'] ?></div>
                        <div class="small text-muted">Correct</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light rounded">
                        <div class="h4 fw-bold text-danger"><?= $performance['wrong_answers'] ?></div>
                        <div class="small text-muted">Wrong</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light rounded">
                        <div class="h4 fw-bold text-warning"><?= $performance['unanswered'] ?></div>
                        <div class="small text-muted">Unanswered</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Accuracy Rate</span>
                    <span class="fw-bold"><?= $performance['accuracy'] ?>%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: <?= $performance['accuracy'] ?>%"></div>
                </div>
            </div>
        </div>

        <!-- Questions Review -->
        <div class="info-card">
            <h5 class="mb-4 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">quiz</i>
                Questions Review
            </h5>
            
            <?php if (empty($questions)): ?>
                <div class="text-center py-4">
                    <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">quiz</i>
                    <p class="text-muted">No questions found for this exam attempt.</p>
                </div>
            <?php else: ?>
                <?php foreach ($questions as $index => $question): ?>
                    <?php
                    // Get student answer for this question
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
                            <?php if (!empty($question['model_answer'])): ?>
                                <div class="mb-2">
                                    <strong>Expected Answer:</strong>
                                    <div class="option-item correct">
                                        <?= esc($question['model_answer']) ?>
                                        <i class="material-symbols-rounded text-success float-end">check_circle</i>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($question['explanation'])): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>Explanation:</strong>
                            <div class="mt-2"><?= $question['explanation'] ?></div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Exam Details -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">info</i>
                Exam Details
            </h5>
            <div class="small">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subject:</span>
                    <span class="fw-medium"><?= esc($exam['subject_name'] ?? 'Multiple Subjects') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Class:</span>
                    <span class="fw-medium"><?= esc($exam['class_name']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Duration:</span>
                    <span class="fw-medium"><?= $exam['duration_minutes'] ?> minutes</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Questions:</span>
                    <span class="fw-medium"><?= $exam['total_questions'] ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Passing Marks:</span>
                    <span class="fw-medium"><?= $exam['passing_marks'] ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Status:</span>
                    <span class="badge bg-<?= $attempt['score'] >= $exam['passing_marks'] ? 'success' : 'danger' ?>">
                        <?= $attempt['score'] >= $exam['passing_marks'] ? 'Passed' : 'Failed' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Attempt Information -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">schedule</i>
                Attempt Information
            </h5>
            <div class="small">
                <div class="d-flex justify-content-between mb-2">
                    <span>Started:</span>
                    <span class="fw-medium"><?= date('M j, Y g:i A', strtotime($attempt['created_at'])) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Submitted:</span>
                    <span class="fw-medium">
                        <?= $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : 'Not submitted' ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Time Taken:</span>
                    <span class="fw-medium">
                        <?php
                        if ($attempt['submitted_at']) {
                            $start = new DateTime($attempt['created_at']);
                            $end = new DateTime($attempt['submitted_at']);
                            $diff = $start->diff($end);
                            echo $diff->format('%H:%I:%S');
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Attempt #:</span>
                    <span class="fw-medium"><?= $attempt['attempt_number'] ?? 1 ?></span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">settings</i>
                Actions
            </h5>
            <div class="d-grid gap-2">
                <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">quiz</i>View Exam
                </a>
                <a href="<?= base_url('principal/results?exam_id=' . $exam['id']) ?>" class="btn btn-outline-info">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">assessment</i>All Results
                </a>
                <button class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">print</i>Print Result
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print functionality
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
});
</script>
<?= $this->endSection() ?>
