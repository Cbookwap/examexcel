<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .result-card {
        transition: transform 0.2s ease-in-out;
        border: 1px solid #e3e6f0;
    }
    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .score-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    .score-excellent { background: #28a745; }
    .score-good { background: #17a2b8; }
    .score-average { background: #ffc107; color: #212529; }
    .score-poor { background: #dc3545; }
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
    }

    /* Ensure proper footer spacing */
    .page-content-wrapper {
        margin-bottom: 4rem;
        min-height: calc(100vh - 200px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="page-content-wrapper">
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
                    <i class="fas fa-download me-2"></i>Export Results
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-chart-line me-2"></i>Analytics
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<?php if (!empty($results)): ?>
    <?php
    $totalAttempts = count($results);
    $totalScore = array_sum(array_column($results, 'percentage'));
    $averageScore = $totalAttempts > 0 ? $totalScore / $totalAttempts : 0;
    $excellentCount = count(array_filter($results, function($r) { return $r['percentage'] >= 80; }));
    $goodCount = count(array_filter($results, function($r) { return $r['percentage'] >= 60 && $r['percentage'] < 80; }));
    $averageCount = count(array_filter($results, function($r) { return $r['percentage'] >= 40 && $r['percentage'] < 60; }));
    $poorCount = count(array_filter($results, function($r) { return $r['percentage'] < 40; }));
    ?>
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h3 class="mb-1"><?= $totalAttempts ?></h3>
                    <p class="mb-0 opacity-75">Total Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-primary"><?= number_format($averageScore, 1) ?>%</h3>
                    <p class="mb-0 text-muted">Average Score</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-success"><?= $excellentCount ?></h3>
                    <p class="mb-0 text-muted">Excellent (80%+)</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-danger"><?= $poorCount ?></h3>
                    <p class="mb-0 text-muted">Needs Help (<40%)</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Results Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Exam Results</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>All Subjects</option>
                            <option>Mathematics</option>
                            <option>English</option>
                            <option>Science</option>
                        </select>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>All Classes</option>
                            <option>Class 10</option>
                            <option>Class 11</option>
                            <option>Class 12</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($results)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold">Student</th>
                                    <th class="border-0 fw-semibold">Exam</th>
                                    <th class="border-0 fw-semibold">Subject</th>
                                    <th class="border-0 fw-semibold">Class</th>
                                    <th class="border-0 fw-semibold">Score</th>
                                    <th class="border-0 fw-semibold">Grade</th>
                                    <th class="border-0 fw-semibold">Date</th>
                                    <th class="border-0 fw-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong><?= esc($result['first_name'] . ' ' . $result['last_name']) ?></strong>
                                                    <br><small class="text-muted"><?= esc($result['student_id']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= esc($result['exam_title']) ?></td>
                                        <td><?= esc($result['subject_name']) ?></td>
                                        <td><?= esc($result['class_name']) ?></td>
                                        <td>
                                            <?php
                                            $percentage = $result['percentage'];
                                            $scoreClass = 'score-poor';
                                            if ($percentage >= 80) $scoreClass = 'score-excellent';
                                            elseif ($percentage >= 60) $scoreClass = 'score-good';
                                            elseif ($percentage >= 40) $scoreClass = 'score-average';
                                            ?>
                                            <div class="d-flex align-items-center">
                                                <div class="score-circle <?= $scoreClass ?> me-2" style="width: 40px; height: 40px; font-size: 0.8rem;">
                                                    <?= number_format($percentage, 0) ?>%
                                                </div>
                                                <div>
                                                    <small class="text-muted">
                                                        <?= $result['marks_obtained'] ?>/<?= $result['total_marks'] ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $grade = 'F';
                                            $gradeClass = 'bg-danger';
                                            if ($percentage >= 80) { $grade = 'A'; $gradeClass = 'bg-success'; }
                                            elseif ($percentage >= 70) { $grade = 'B'; $gradeClass = 'bg-primary'; }
                                            elseif ($percentage >= 60) { $grade = 'C'; $gradeClass = 'bg-info'; }
                                            elseif ($percentage >= 40) { $grade = 'D'; $gradeClass = 'bg-warning'; }
                                            ?>
                                            <span class="badge <?= $gradeClass ?>"><?= $grade ?></span>
                                        </td>
                                        <td><?= date('M j, Y', strtotime($result['submitted_at'])) ?></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= base_url('exams/result-detail/' . $result['id']) ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-secondary btn-sm" title="Download Report">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mb-3">No Results Available</h5>
                        <p class="text-muted">No exam results found. Students need to complete exams to see results here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
