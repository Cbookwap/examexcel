<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
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
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="page-header">
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
                        <?= esc($student['class_name']) ?>
                    <?php else: ?>
                        <span class="text-warning">Not Assigned</span>
                    <?php endif; ?>
                </small>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= base_url('student/practice') ?>" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Back to Practice
            </a>
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
                <span class="stat-number"><?= $practiceStats['total_questions_attempted'] ?></span>
                <span class="stat-label">Questions Attempted</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Practice History -->
<?php if (!empty($allPractices)): ?>
<div class="row mb-4">
    <div class="col-12">
        <h5 class="fw-semibold mb-3">
            <i class="fas fa-history text-primary me-2"></i>
            Complete Practice History (<?= count($allPractices) ?> sessions)
        </h5>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <?php foreach ($allPractices as $practice): ?>
                    <div class="practice-history-card border-0 shadow-none border-bottom">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1 fw-semibold"><?= esc($practice['subject_name']) ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('M j, Y \a\t g:i A', strtotime($practice['created_at'])) ?>
                                    </small>
                                    <?php if (!empty($practice['end_time'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Duration: <?php
                                                $start = new DateTime($practice['start_time']);
                                                $end = new DateTime($practice['end_time']);
                                                $duration = $start->diff($end);
                                                echo $duration->format('%H:%I:%S');
                                            ?>
                                        </small>
                                    <?php endif; ?>
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
<?php else: ?>
<div class="empty-state">
    <i class="fas fa-history text-muted"></i>
    <h5>No Practice History</h5>
    <p>You haven't completed any practice sessions yet. Start practicing to see your results here!</p>
    <a href="<?= base_url('student/practice') ?>" class="btn btn-primary">
        <i class="fas fa-play me-2"></i>Start Your First Practice
    </a>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations to practice history cards
    const practiceCards = document.querySelectorAll('.practice-history-card');
    practiceCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
    
    // Show statistics message
    const totalSessions = <?= $practiceStats['total_sessions'] ?>;
    if (totalSessions > 0) {
        CBT.showToast('You have completed ' + totalSessions + ' practice session(s). Keep up the great work!', 'info');
    }
});
</script>
<?= $this->endSection() ?>
