<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .result-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .result-header {
        background: linear-gradient(135deg, <?= $passed ? '#10b981' : '#ef4444' ?> 0%, <?= $passed ? '#059669' : '#dc2626' ?> 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        text-align: center;
    }
    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        font-weight: bold;
    }
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .stat-item {
        text-align: center;
        padding: 1rem;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    .question-review-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .question-review-header {
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    .question-review-header.correct {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left: 4px solid #10b981;
    }
    .question-review-header.incorrect {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
    }
    .answer-option {
        padding: 0.75rem 1rem;
        margin: 0.5rem 0;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .answer-option.correct {
        background: #d1fae5;
        border-color: #10b981;
        color: #065f46;
    }
    .answer-option.incorrect {
        background: #fee2e2;
        border-color: #ef4444;
        color: #991b1b;
    }
    .answer-option.student-answer {
        background: #dbeafe;
        border-color: #3b82f6;
        color: #1e40af;
    }
    .performance-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .performance-excellent {
        background: #d1fae5;
        color: #065f46;
    }
    .performance-good {
        background: #fef3c7;
        color: #92400e;
    }
    .performance-needs-improvement {
        background: #fee2e2;
        color: #991b1b;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="result-container">
    <!-- Result Header -->
    <div class="result-header">
        <div class="score-circle">
            <?= $practice['percentage'] ?>%
        </div>
        <h2 class="mb-2 fw-bold">
            <?php if ($passed): ?>
                <i class="fas fa-trophy me-2"></i>Excellent Work!
            <?php else: ?>
                <i class="fas fa-redo me-2"></i>Keep Practicing!
            <?php endif; ?>
        </h2>
        <p class="mb-3 opacity-75">Practice Test: <?= esc($category) ?></p>

        <?php
        $performanceLevel = '';
        $performanceClass = '';
        if ($practice['percentage'] >= 90) {
            $performanceLevel = 'Excellent';
            $performanceClass = 'performance-excellent';
        } elseif ($practice['percentage'] >= 70) {
            $performanceLevel = 'Good';
            $performanceClass = 'performance-good';
        } else {
            $performanceLevel = 'Needs Improvement';
            $performanceClass = 'performance-needs-improvement';
        }
        ?>

        <span class="performance-badge <?= $performanceClass ?>">
            <?= $performanceLevel ?>
        </span>
    </div>

    <!-- Statistics -->
    <div class="stats-card">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?= $practice['score'] ?></div>
                    <div class="stat-label">Correct Answers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?= $practice['total_questions'] ?></div>
                    <div class="stat-label">Total Questions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value"><?= $practice['percentage'] ?>%</div>
                    <div class="stat-label">Score Percentage</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value">
                        <?php
                        $startTime = new DateTime($practice['start_time']);
                        $endTime = new DateTime($practice['end_time']);
                        $duration = $startTime->diff($endTime);
                        echo $duration->format('%H:%I:%S');
                        ?>
                    </div>
                    <div class="stat-label">Time Taken</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mb-4">
        <a href="<?= base_url('student/practice') ?>" class="btn btn-primary me-2">
            <i class="fas fa-redo me-2"></i>Practice Again
        </a>
        <a href="<?= base_url('student/startPractice/' . $practice['subject_id']) ?>" class="btn btn-outline-primary me-2">
            <i class="fas fa-play me-2"></i>New Practice Session
        </a>
        <a href="<?= base_url('student/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-home me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Detailed Review -->
    <div class="mb-4">
        <h4 class="fw-semibold mb-3">
            <i class="fas fa-search me-2"></i>
            Detailed Review
        </h4>
        <p class="text-muted mb-4">
            Review each question to understand the correct answers and improve your knowledge.
        </p>
    </div>

    <!-- Question Reviews -->
    <?php foreach ($detailedResults as $index => $result): ?>
        <div class="question-review-card">
            <div class="question-review-header <?= $result['is_correct'] ? 'correct' : 'incorrect' ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <?php if ($result['is_correct']): ?>
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-danger fa-2x"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-semibold">Question <?= $index + 1 ?></h6>
                            <small class="text-muted">
                                <?= $result['is_correct'] ? 'Correct' : 'Incorrect' ?> •
                                <?= $result['question']['points'] ?> mark(s)
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        <?php if ($result['is_correct']): ?>
                            <span class="badge bg-success">+<?= $result['question']['points'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger">0</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Question Text -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3"><?= nl2br(esc($result['question']['question_text'])) ?></h6>

                    <!-- Question Image if exists -->
                    <?php if (!empty($result['question']['question_image'])): ?>
                        <div class="text-center mb-3">
                            <img src="<?= base_url('uploads/questions/' . $result['question']['question_image']) ?>"
                                 alt="Question Image" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Answer Options -->
                <div class="mb-4">
                    <?php
                    $options = ['A', 'B', 'C', 'D'];
                    $optionFields = ['option_a', 'option_b', 'option_c', 'option_d'];
                    ?>

                    <?php foreach ($options as $i => $option): ?>
                        <?php if (!empty($result['question'][$optionFields[$i]])): ?>
                            <div class="answer-option
                                <?php if ($option === $result['correct_answer']): ?>correct<?php endif; ?>
                                <?php if ($option === $result['student_answer'] && $option !== $result['correct_answer']): ?>incorrect<?php endif; ?>
                                <?php if ($option === $result['student_answer'] && $option === $result['correct_answer']): ?>student-answer<?php endif; ?>
                            ">
                                <div class="d-flex align-items-center">
                                    <strong class="me-3"><?= $option ?>.</strong>
                                    <span><?= nl2br(esc($result['question'][$optionFields[$i]])) ?></span>
                                    <div class="ms-auto">
                                        <?php if ($option === $result['correct_answer']): ?>
                                            <i class="fas fa-check text-success"></i>
                                            <small class="text-success ms-1">Correct Answer</small>
                                        <?php endif; ?>
                                        <?php if ($option === $result['student_answer'] && $option !== $result['correct_answer']): ?>
                                            <i class="fas fa-times text-danger"></i>
                                            <small class="text-danger ms-1">Your Answer</small>
                                        <?php endif; ?>
                                        <?php if ($option === $result['student_answer'] && $option === $result['correct_answer']): ?>
                                            <i class="fas fa-check text-success"></i>
                                            <small class="text-success ms-1">Your Correct Answer</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Explanation/Instructions -->
                <?php if (!empty($result['question']['explanation'])): ?>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>Explanation:
                        </h6>
                        <p class="mb-0"><?= nl2br(esc($result['question']['explanation'])) ?></p>
                    </div>
                <?php elseif (!empty($result['question']['hints'])): ?>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>Hint:
                        </h6>
                        <p class="mb-0"><?= nl2br(esc($result['question']['hints'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Answer Summary -->
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Your Answer:</small>
                            <div class="fw-semibold">
                                <?php if (!empty($result['student_answer'])): ?>
                                    Option <?= $result['student_answer'] ?>
                                <?php else: ?>
                                    <span class="text-muted">Not answered</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Correct Answer:</small>
                            <div class="fw-semibold text-success">Option <?= $result['correct_answer'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Final Actions -->
    <div class="text-center mt-5 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Keep Learning!
                </h5>
                <p class="text-muted mb-4">
                    Practice makes perfect. The more you practice, the better you'll perform in your actual exams.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= base_url('student/startPractice/' . $practice['subject_id']) ?>" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Practice This Subject Again
                    </a>
                    <a href="<?= base_url('student/practice') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Choose Another Subject
                    </a>
                    <a href="<?= base_url('student/exams') ?>" class="btn btn-outline-success">
                        <i class="fas fa-clipboard-list me-2"></i>View Available Exams
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show completion message
    const score = <?= $practice['percentage'] ?>;
    const passed = <?= $passed ? 'true' : 'false' ?>;

    if (passed) {
        CBT.showToast('Congratulations! You scored ' + score + '% in your practice test!', 'success');
    } else {
        CBT.showToast('Practice completed! Review the explanations and try again to improve your score.', 'info');
    }

    // Smooth scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
<?= $this->endSection() ?>
