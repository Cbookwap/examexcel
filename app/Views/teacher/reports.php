<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .report-card {
        transition: transform 0.2s ease-in-out;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .performance-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    .trend-up {
        color: #28a745;
    }
    .trend-down {
        color: #dc3545;
    }
    .trend-neutral {
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="fas fa-download me-2"></i>Export Report
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Overview Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <h3 class="mb-1"><?= $examStats['total_exams'] ?? 0 ?></h3>
                <p class="mb-0 opacity-75">Total Exams</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-1 text-primary"><?= $examStats['total_attempts'] ?? 0 ?></h3>
                <p class="mb-0 text-muted">Total Attempts</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-1 text-success"><?= number_format($examStats['average_score'] ?? 0, 1) ?>%</h3>
                <p class="mb-0 text-muted">Average Score</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-1 text-info"><?= number_format($examStats['completion_rate'] ?? 0, 1) ?>%</h3>
                <p class="mb-0 text-muted">Completion Rate</p>
            </div>
        </div>
    </div>
</div>

<!-- Grade Distribution -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card report-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Grade Distribution</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($performanceData['grade_distribution'])): ?>
                    <?php 
                    $grades = $performanceData['grade_distribution'];
                    $total = array_sum($grades);
                    $gradeColors = [
                        'A' => '#28a745', 'B' => '#17a2b8', 'C' => '#ffc107', 
                        'D' => '#fd7e14', 'F' => '#dc3545'
                    ];
                    ?>
                    <?php foreach ($grades as $grade => $count): ?>
                        <?php $percentage = $total > 0 ? ($count / $total) * 100 : 0; ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 20px; height: 20px; background-color: <?= $gradeColors[$grade] ?>; border-radius: 4px;"></div>
                                <span class="fw-medium">Grade <?= $grade ?></span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold"><?= $count ?></span>
                                <small class="text-muted">(<?= number_format($percentage, 1) ?>%)</small>
                            </div>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width: <?= $percentage ?>%; background-color: <?= $gradeColors[$grade] ?>;"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">No grade data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Subject Performance -->
    <div class="col-lg-6 mb-4">
        <div class="card report-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Subject Performance</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($examStats['subject_performance'])): ?>
                    <?php foreach ($examStats['subject_performance'] as $subject): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1"><?= esc($subject['subject']) ?></h6>
                                <small class="text-muted"><?= $subject['total_attempts'] ?> attempts</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-primary"><?= number_format($subject['average_score'], 1) ?>%</span>
                                <br>
                                <small class="text-muted">
                                    <?= $subject['lowest_score'] ?>% - <?= $subject['highest_score'] ?>%
                                </small>
                            </div>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: <?= $subject['average_score'] ?>%;"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-book text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">No subject performance data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Performance Trends -->
<?php if (!empty($examStats['monthly_trends'])): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card report-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Monthly Performance Trends</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th>Average Score</th>
                                <th>Total Attempts</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($examStats['monthly_trends'] as $index => $trend): ?>
                                <?php 
                                $trendIcon = '';
                                $trendClass = 'trend-neutral';
                                if ($index > 0) {
                                    $prevScore = $examStats['monthly_trends'][$index - 1]['average_score'];
                                    if ($trend['average_score'] > $prevScore) {
                                        $trendIcon = 'fas fa-arrow-up';
                                        $trendClass = 'trend-up';
                                    } elseif ($trend['average_score'] < $prevScore) {
                                        $trendIcon = 'fas fa-arrow-down';
                                        $trendClass = 'trend-down';
                                    } else {
                                        $trendIcon = 'fas fa-minus';
                                    }
                                }
                                ?>
                                <tr>
                                    <td><?= esc($trend['month']) ?></td>
                                    <td>
                                        <span class="fw-bold"><?= number_format($trend['average_score'], 1) ?>%</span>
                                    </td>
                                    <td><?= $trend['total_attempts'] ?></td>
                                    <td>
                                        <?php if ($trendIcon): ?>
                                            <i class="<?= $trendIcon ?> <?= $trendClass ?>"></i>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
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

<!-- Top Performers and Struggling Students -->
<div class="row mb-4">
    <!-- Top Performers -->
    <div class="col-lg-6 mb-4">
        <div class="card report-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Top Performers</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($performanceData['top_performers'])): ?>
                    <?php foreach ($performanceData['top_performers'] as $index => $student): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-success">#<?= $index + 1 ?></span>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h6>
                                    <small class="text-muted"><?= esc($student['student_id']) ?></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success"><?= number_format($student['average_score'], 1) ?>%</span>
                                <br>
                                <small class="text-muted"><?= $student['total_attempts'] ?> attempts</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-trophy text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">No performance data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Struggling Students -->
    <div class="col-lg-6 mb-4">
        <div class="card report-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Students Needing Support</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($performanceData['struggling_students'])): ?>
                    <?php foreach ($performanceData['struggling_students'] as $student): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h6>
                                    <small class="text-muted"><?= esc($student['student_id']) ?></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-warning"><?= number_format($student['average_score'], 1) ?>%</span>
                                <br>
                                <small class="text-muted"><?= $student['total_attempts'] ?> attempts</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-check text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">All students performing well!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
