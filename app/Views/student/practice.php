<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .subject-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .practice-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }
    .subject-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .question-count {
        background: rgba(var(--primary-color-rgb), 0.1);
        color: var(--primary-color);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    .practice-stats {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .practice-stats .stat-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .practice-stats .stat-item:last-child {
        margin-bottom: 0;
    }
    .practice-stats .stat-item i {
        width: 20px;
        margin-right: 0.5rem;
    }
    .practice-history-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        margin-bottom: 1rem;
    }
    .practice-history-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .score-badge {
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 25px;
    }
    .score-excellent {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    .score-good {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    .score-average {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    .score-poor {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    .stats-overview {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-box {
        text-align: center;
        padding: 1rem;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="practice-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2 fw-bold"><?= $pageTitle ?></h2>
            <p class="mb-0 opacity-75"><?= $pageSubtitle ?></p>
            <div class="d-flex flex-wrap gap-3 mt-2">
                <small class="opacity-75">
                    <i class="fas fa-id-card me-1"></i>
                    Student ID: <?= $student['student_id'] ?? 'N/A' ?>
                </small>
                <small class="opacity-75">
                    <i class="fas fa-users me-1"></i>
                    Class: <?php if (!empty($student['class_name'])): ?>
                        <?= esc($student['class_name']) ?><?= !empty($student['class_section']) ? ' - ' . esc($student['class_section']) : '' ?>
                    <?php else: ?>
                        <span class="text-warning">Not Assigned</span>
                    <?php endif; ?>
                </small>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <i class="fas fa-dumbbell" style="font-size: 4rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<!-- Practice Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            About Practice Tests
                        </h5>
                        <p class="text-muted mb-3">
                            Practice tests help you prepare for your exams by allowing you to test your knowledge
                            without any time pressure. Each practice session contains 10 randomly selected questions
                            from your class subjects.
                        </p>
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Unlimited attempts
                                </small>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Instant feedback
                                </small>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-check text-success me-2"></i>
                                    No time limit
                                </small>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Detailed explanations
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="practice-stats">
                            <div class="stat-item">
                                <i class="fas fa-brain text-primary"></i>
                                <span class="small"><?= count($categories ?? []) ?> Categories Available</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-question-circle text-primary"></i>
                                <span class="small"><?= array_sum(array_column($categories ?? [], 'question_count')) ?> Total Questions</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock text-primary"></i>
                                <span class="small">No Time Pressure</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Practice Statistics Overview -->
<?php if ($practiceStats['total_sessions'] > 0): ?>
<div class="stats-overview">
    <div class="row">
        <div class="col-md-3 col-6">
            <div class="stat-box">
                <span class="stat-number"><?= $practiceStats['total_sessions'] ?></span>
                <span class="stat-label">Total Sessions</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-box">
                <span class="stat-number"><?= $practiceStats['average_score'] ?>%</span>
                <span class="stat-label">Average Score</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-box">
                <span class="stat-number"><?= $practiceStats['best_score'] ?>%</span>
                <span class="stat-label">Best Score</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-box">
                <span class="stat-number"><?= $practiceStats['sessions_this_week'] ?></span>
                <span class="stat-label">This Week</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Practice Results -->
<?php if (!empty($recentPractices)): ?>
<div class="row mb-4">
    <div class="col-md-8">
        <h5 class="fw-semibold mb-3">
            <i class="fas fa-history text-primary me-2"></i>
            Recent Practice Results
        </h5>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= base_url('student/practiceHistory') ?>" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-list me-2"></i>View All Results
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <?php foreach ($recentPractices as $practice): ?>
                    <div class="practice-history-card border-0 shadow-none border-bottom">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1 fw-semibold"><?= esc($practice['subject_name']) ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('M j, Y \a\t g:i A', strtotime($practice['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-question-circle text-muted me-2"></i>
                                        <span class="small"><?= $practice['score'] ?>/<?= $practice['total_questions'] ?> Questions</span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <?php
                                    $scoreClass = 'score-poor';
                                    if ($practice['percentage'] >= 80) {
                                        $scoreClass = 'score-excellent';
                                    } elseif ($practice['percentage'] >= 70) {
                                        $scoreClass = 'score-good';
                                    } elseif ($practice['percentage'] >= 60) {
                                        $scoreClass = 'score-average';
                                    }
                                    ?>
                                    <span class="score-badge <?= $scoreClass ?>">
                                        <?= $practice['percentage'] ?>%
                                    </span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="<?= base_url('student/practiceResult/' . $practice['id']) ?>"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Available Practice Categories -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="fw-semibold mb-3">
            <i class="fas fa-list text-primary me-2"></i>
            Available Practice Categories
        </h5>
    </div>
</div>

<?php if (!empty($categories)): ?>
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="subject-card card h-100">
                    <div class="card-body p-4 text-center">
                        <!-- Category Icon -->
                        <div class="subject-icon mx-auto">
                            <i class="fas fa-brain"></i>
                        </div>

                        <!-- Category Name -->
                        <h5 class="card-title fw-bold mb-2"><?= esc($category['category']) ?></h5>

                        <!-- Category Description -->
                        <p class="text-muted small mb-3">Practice questions to improve your skills</p>

                        <!-- Question Count -->
                        <div class="mb-3">
                            <span class="question-count">
                                <i class="fas fa-question-circle me-1"></i>
                                <?= $category['question_count'] ?> Questions
                            </span>
                        </div>

                        <!-- Difficulty Badge -->
                        <div class="mb-3">
                            <small class="badge bg-info">
                                <i class="fas fa-chart-line me-1"></i>
                                Mixed Difficulty
                            </small>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-auto">
                            <?php if ($category['question_count'] > 0): ?>
                                <button class="btn btn-primary w-100" onclick="confirmStartPractice('<?= esc($category['category']) ?>', '<?= esc($category['category']) ?>', <?= $category['question_count'] ?>)">
                                    <i class="fas fa-play me-2"></i>Start Practice
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary w-100" disabled>
                                    <i class="fas fa-times me-2"></i>No Questions Available
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-brain"></i>
        <h5>No Practice Categories Available</h5>
        <p>There are currently no practice questions available. Check back later or contact your administrator.</p>
    </div>
<?php endif; ?>

<!-- Start Practice Confirmation Modal -->
<div class="modal fade" id="startPracticeModal" tabindex="-1" aria-labelledby="startPracticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title" id="startPracticeModalLabel">
                    <i class="fas fa-dumbbell me-2"></i>Start Practice Test
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                    <h5 id="practiceSubjectName">Subject Name</h5>
                    <p class="text-muted">Ready to start your practice session?</p>
                </div>
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Practice Session Details:
                    </h6>
                    <ul class="mb-0 small">
                        <li>You will get <strong>10 random questions</strong> from this subject</li>
                        <li>No time limit - take your time to think</li>
                        <li>You can retake the practice as many times as you want</li>
                        <li>Instant feedback after submission</li>
                    </ul>
                </div>
                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-question-circle me-1"></i>
                        <span id="totalQuestions">0</span> questions available in this subject
                    </small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmStartPracticeBtn">
                    <i class="fas fa-play me-2"></i>Start Practice
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let selectedCategory = null;

// Confirm start practice
function confirmStartPractice(category, categoryName, questionCount) {
    selectedCategory = category;
    document.getElementById('practiceSubjectName').textContent = categoryName;
    document.getElementById('totalQuestions').textContent = questionCount;

    const modal = new bootstrap.Modal(document.getElementById('startPracticeModal'));
    modal.show();
}

// Handle practice start confirmation
document.getElementById('confirmStartPracticeBtn').addEventListener('click', function() {
    if (selectedCategory) {
        window.location.href = '<?= base_url('student/startPractice/') ?>' + encodeURIComponent(selectedCategory);
    }
});

// Show welcome message
document.addEventListener('DOMContentLoaded', function() {
    const categoryCount = <?= count($categories ?? []) ?>;
    const practiceCount = <?= count($recentPractices ?? []) ?>;

    if (categoryCount > 0) {
        if (practiceCount > 0) {
            CBT.showToast('Welcome back! You have completed ' + practiceCount + ' practice session(s). Keep practicing to improve!', 'info');
        } else {
            CBT.showToast('Welcome to Practice Tests! You have ' + categoryCount + ' categor' + (categoryCount === 1 ? 'y' : 'ies') + ' available for practice.', 'info');
        }
    }

    // Add smooth animations to practice history cards
    const practiceCards = document.querySelectorAll('.practice-history-card');
    practiceCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
<?= $this->endSection() ?>
