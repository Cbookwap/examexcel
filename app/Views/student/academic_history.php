<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .academic-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .academic-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .session-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1rem 1.5rem;
    }

    .term-card {
        border-left: 4px solid var(--primary-color);
        margin-bottom: 1rem;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
    }

    .grade-badge {
        font-size: 1.2rem;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 25px;
    }

    .grade-A { background: linear-gradient(135deg, #10b981, #059669); color: white; }
    .grade-B { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
    .grade-C { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
    .grade-D { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    .grade-F { background: linear-gradient(135deg, #6b7280, #4b5563); color: white; }

    .progress-ring {
        width: 80px;
        height: 80px;
        position: relative;
    }

    .progress-ring svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .progress-ring circle {
        fill: transparent;
        stroke-width: 8;
        stroke-linecap: round;
    }

    .progress-ring .bg {
        stroke: #e5e7eb;
    }

    .progress-ring .progress {
        stroke: var(--primary-color);
        stroke-dasharray: 251.2;
        stroke-dashoffset: 251.2;
        transition: stroke-dashoffset 0.5s ease-in-out;
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 0.9rem;
        color: var(--primary-color);
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), var(--primary-light));
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 0 0 3px var(--primary-color);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Class Progression Styles */
    .progression-item {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }

    .progression-item::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 30px;
        bottom: -15px;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), transparent);
    }

    .progression-item:last-child::before {
        display: none;
    }

    .progression-number {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0 font-weight-bolder">Academic History</h3>
                <p class="mb-0">Your complete academic journey and performance records</p>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Academic Overview Stats -->
<div class="stats-grid">
    <div class="stat-item">
        <div class="stat-value"><?= $stats['terms_completed'] ?></div>
        <div class="stat-label">Terms Completed</div>
    </div>
    <div class="stat-item">
        <div class="stat-value"><?= number_format($stats['average_performance'], 1) ?>%</div>
        <div class="stat-label">Average Performance</div>
    </div>
    <div class="stat-item">
        <div class="stat-value"><?= $stats['promotions'] ?></div>
        <div class="stat-label">Promotions</div>
    </div>
    <div class="stat-item">
        <div class="stat-value"><?= esc($stats['current_class']) ?></div>
        <div class="stat-label">Current Class</div>
    </div>
</div>

<!-- Academic Timeline -->
<div class="row">
    <div class="col-lg-8">
        <div class="academic-card">
            <div class="session-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Academic Timeline
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($termResults)): ?>
                    <div class="timeline">
                        <?php 
                        $groupedResults = [];
                        foreach ($termResults as $result) {
                            $groupedResults[$result['session_name']][] = $result;
                        }
                        ?>
                        
                        <?php foreach ($groupedResults as $sessionName => $sessionResults): ?>
                            <div class="timeline-item">
                                <div class="academic-card">
                                    <div class="session-header">
                                        <h6 class="mb-0"><?= esc($sessionName) ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <?php foreach ($sessionResults as $result): ?>
                                            <div class="term-card p-3 mb-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-3">
                                                        <h6 class="mb-1"><?= esc($result['term_name']) ?></h6>
                                                        <small class="text-muted"><?= esc($result['class_name']) ?></small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="progress-ring">
                                                            <svg>
                                                                <circle class="bg" cx="40" cy="40" r="36"></circle>
                                                                <circle class="progress" cx="40" cy="40" r="36" 
                                                                        style="stroke-dashoffset: <?= 251.2 - (251.2 * $result['overall_percentage'] / 100) ?>"></circle>
                                                            </svg>
                                                            <div class="progress-text"><?= number_format($result['overall_percentage'], 1) ?>%</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span class="grade-badge grade-<?= substr($result['grade'], 0, 1) ?>">
                                                            <?= esc($result['grade']) ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="text-center">
                                                            <div class="fw-bold"><?= $result['position_in_class'] ?></div>
                                                            <small class="text-muted">of <?= $result['total_students'] ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-success">Passed: <?= $result['subjects_passed'] ?></span>
                                                            <span class="text-danger">Failed: <?= $result['subjects_failed'] ?></span>
                                                        </div>
                                                        <?php if ($result['promotion_status']): ?>
                                                            <small class="badge bg-<?= $result['promotion_status'] === 'promoted' ? 'success' : ($result['promotion_status'] === 'repeated' ? 'danger' : 'warning') ?>">
                                                                <?= ucfirst($result['promotion_status']) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <?php if ($result['teacher_remarks'] || $result['principal_remarks']): ?>
                                                    <div class="mt-3 pt-3 border-top">
                                                        <?php if ($result['teacher_remarks']): ?>
                                                            <p class="mb-1"><strong>Teacher's Remarks:</strong> <?= esc($result['teacher_remarks']) ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($result['principal_remarks']): ?>
                                                            <p class="mb-0"><strong>Principal's Remarks:</strong> <?= esc($result['principal_remarks']) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-4x text-muted mb-4"></i>
                        <h5 class="text-muted mb-3">Welcome to Your Academic Journey!</h5>
                        <p class="text-muted mb-4">Your academic timeline will appear here as you complete exams and progress through terms.</p>

                        <!-- Current Academic Info -->
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card border-primary border-2 bg-primary-subtle">
                                    <div class="card-body py-3">
                                        <h6 class="text-primary mb-2">
                                            <i class="fas fa-user-graduate me-2"></i>Current Academic Status
                                        </h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="fw-bold text-primary"><?= esc($stats['current_class']) ?></div>
                                                <small class="text-muted">Current Class</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="fw-bold text-success">Active</div>
                                                <small class="text-muted">Status</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Getting Started -->
                        <div class="mt-4">
                            <h6 class="text-primary mb-3">Get Started</h6>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="<?= base_url('student/exams') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-list me-1"></i>Take Exams
                                </a>
                                <a href="<?= base_url('student/practice') ?>" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-dumbbell me-1"></i>Practice Questions
                                </a>
                                <a href="<?= base_url('student/results') ?>" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-chart-bar me-1"></i>View Results
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="col-lg-4">
        <div class="academic-card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Performance Summary
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($termResults)): ?>
                    <?php
                    $bestTerm = array_reduce($termResults, function($best, $current) {
                        return (!$best || $current['overall_percentage'] > $best['overall_percentage']) ? $current : $best;
                    });
                    
                    $recentTerm = $termResults[0];
                    ?>
                    
                    <div class="mb-4">
                        <h6 class="text-primary">Best Performance</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= esc($bestTerm['term_name']) ?> - <?= esc($bestTerm['session_name']) ?></div>
                                <small class="text-muted"><?= esc($bestTerm['class_name']) ?></small>
                            </div>
                            <div class="text-end">
                                <div class="grade-badge grade-<?= substr($bestTerm['grade'], 0, 1) ?> small">
                                    <?= number_format($bestTerm['overall_percentage'], 1) ?>%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-info">Recent Performance</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= esc($recentTerm['term_name']) ?> - <?= esc($recentTerm['session_name']) ?></div>
                                <small class="text-muted"><?= esc($recentTerm['class_name']) ?></small>
                            </div>
                            <div class="text-end">
                                <div class="grade-badge grade-<?= substr($recentTerm['grade'], 0, 1) ?> small">
                                    <?= number_format($recentTerm['overall_percentage'], 1) ?>%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success">Subjects Performance</h6>
                        <?php
                        $totalSubjects = array_sum(array_column($termResults, 'total_subjects'));
                        $totalPassed = array_sum(array_column($termResults, 'subjects_passed'));
                        $passRate = $totalSubjects > 0 ? ($totalPassed / $totalSubjects) * 100 : 0;
                        ?>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $passRate ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Pass Rate: <?= number_format($passRate, 1) ?>%</small>
                            <small><?= $totalPassed ?>/<?= $totalSubjects ?> subjects</small>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">No Performance Data Yet</h6>
                        <p class="text-muted small mb-3">Your performance summary will appear here after you complete your first exam.</p>

                        <!-- Current Status -->
                        <div class="border-top pt-3 mt-3">
                            <div class="row text-center">
                                <div class="col-12 mb-2">
                                    <h6 class="text-primary mb-1">Current Status</h6>
                                    <div class="badge bg-primary-subtle text-primary px-3 py-2">
                                        <?= esc($stats['current_class']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-3">
                            <a href="<?= base_url('student/exams') ?>" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-clipboard-list me-1"></i>View Exams
                            </a>
                            <a href="<?= base_url('student/practice') ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-dumbbell me-1"></i>Practice
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Class Progression -->
        <div class="academic-card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-stairs me-2"></i>
                    Class Progression
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($progression)): ?>
                    <?php foreach ($progression as $index => $prog): ?>
                        <div class="progression-item">
                            <div class="progression-number">
                                <?= $index + 1 ?>
                            </div>
                            <div class="progression-content">
                                <div class="fw-bold text-dark"><?= esc($prog['class_name']) ?></div>
                                <div class="text-muted small"><?= esc($prog['session_name']) ?></div>
                                <?php if (!empty($prog['term_name'])): ?>
                                    <div class="text-muted small"><?= esc($prog['term_name']) ?></div>
                                <?php endif; ?>
                                <?php if ($prog['status'] !== 'active'): ?>
                                    <span class="badge bg-<?= $prog['status'] === 'promoted' ? 'success' : 'warning' ?> mt-1">
                                        <?= ucfirst($prog['status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary mt-1">Current</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-stairs fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">Class Progression</h6>
                        <p class="text-muted small mb-3">Your academic progression will be tracked here as you advance through different classes.</p>

                        <!-- Current Class Display -->
                        <div class="progression-item">
                            <div class="progression-number">1</div>
                            <div class="progression-content">
                                <div class="fw-bold text-primary"><?= esc($stats['current_class']) ?></div>
                                <div class="text-muted small">Current Class</div>
                                <span class="badge bg-success mt-1">Active</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
