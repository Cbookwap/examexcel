<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .welcome-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(38, 166, 154, 0.3);
    }
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .border-left-info {
        border-left: 4px solid #17a2b8;
    }
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(38, 166, 154, 0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2 fw-bold"><?= $pageTitle ?></h2>
                    <p class="mb-0 opacity-75"><?= $pageSubtitle ?></p>
                    <small class="opacity-75">Teacher ID: <?= $teacher['employee_id'] ?? 'N/A' ?></small>
                </div>
                <div class="col-md-4 text-end">
                    <i class="fas fa-chalkboard-teacher" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">My Subjects</p>
                        <h4 class="mb-0"><?= $stats['total_subjects'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-lg">
                        <i class="fas fa-book opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">Assigned subjects</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Exams</p>
                        <h4 class="mb-0"><?= $stats['total_exams'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-success shadow-success shadow text-center border-radius-lg">
                        <i class="fas fa-clipboard-list opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <span class="text-success">Active: <?= $stats['active_exams'] ?></span> |
                    <span class="text-info">Upcoming: <?= $stats['upcoming_exams'] ?></span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Questions</p>
                        <h4 class="mb-0"><?= $stats['total_questions'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-info shadow-info shadow text-center border-radius-lg">
                        <i class="fas fa-question-circle opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">Questions created</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Students</p>
                        <h4 class="mb-0"><?= $stats['total_students'] ?></h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-warning shadow-warning shadow text-center border-radius-lg">
                        <i class="fas fa-users opacity-10"></i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">
                    <?php if ($stats['average_score'] > 0): ?>
                        Avg Score: <span class="text-success"><?= $stats['average_score'] ?>%</span>
                    <?php else: ?>
                        No attempts yet
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Performance Overview -->
<?php if ($stats['total_attempts'] > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Performance Overview</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <h4 class="text-primary"><?= $stats['total_attempts'] ?></h4>
                        <p class="text-muted mb-0">Total Attempts</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-success"><?= $performanceStats['average_score'] ?>%</h4>
                        <p class="text-muted mb-0">Average Score</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-info"><?= $performanceStats['pass_rate'] ?>%</h4>
                        <p class="text-muted mb-0">Pass Rate</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-warning"><?= $performanceStats['highest_score'] ?>%</h4>
                        <p class="text-muted mb-0">Highest Score</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px; background: var(--primary-color); color: white;">
                                <i class="fas fa-question"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">Add Questions</h6>
                                <p class="mb-0 text-muted small">Build question bank</p>
                            </div>
                            <a href="<?= base_url('questions/create') ?>" class="btn btn-primary btn-sm">Add</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px; background: var(--primary-color); color: white;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">View Results</h6>
                                <p class="mb-0 text-muted small">Check student performance</p>
                            </div>
                            <a href="<?= base_url('teacher/results') ?>" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Subjects -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">My Subjects</h5>
                    <span class="badge bg-primary"><?= count($subjects) ?></span>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <div class="d-flex align-items-center justify-content-between mb-3 p-3 border rounded hover-shadow">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px; background: var(--primary-color); color: white;">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold"><?= esc($subject['name']) ?></h6>
                                    <small class="text-muted"><?= esc($subject['code']) ?></small>
                                    <?php if (!empty($subject['classes'])): ?>
                                        <br><small class="text-info">Classes: <?= implode(', ', $subject['classes']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="d-flex gap-3">
                                    <div class="text-center">
                                        <div class="text-primary fw-bold"><?= $subject['question_count'] ?? 0 ?></div>
                                        <small class="text-muted">Questions</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-success fw-bold"><?= $subject['exam_count'] ?? 0 ?></div>
                                        <small class="text-muted">Exams</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-book text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">No subjects assigned yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Exams Section -->
<?php if (!empty($upcomingExams)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Upcoming Exams</h5>
                    <span class="badge bg-info"><?= count($upcomingExams) ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($upcomingExams as $exam): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-info">
                                <div class="card-body p-3">
                                    <h6 class="mb-1 fw-semibold"><?= esc($exam['title']) ?></h6>
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-book me-1"></i><?= esc($exam['subject_name']) ?><br>
                                        <i class="fas fa-users me-1"></i><?= esc($exam['class_name']) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-info">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('M j, g:i A', strtotime($exam['start_time'])) ?>
                                        </small>
                                        <span class="badge bg-info"><?= $exam['duration_minutes'] ?>min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Exam Attempts -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Recent Exam Attempts</h5>
                    <a href="<?= base_url('teacher/results') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-chart-line me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentAttempts)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold">Student</th>
                                    <th class="border-0 fw-semibold">Exam</th>
                                    <th class="border-0 fw-semibold">Subject</th>
                                    <th class="border-0 fw-semibold">Score</th>
                                    <th class="border-0 fw-semibold">Date</th>
                                    <th class="border-0 fw-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentAttempts as $attempt): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <strong><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></strong>
                                                    <br><small class="text-muted"><?= esc($attempt['student_id']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= esc($attempt['exam_title']) ?></td>
                                        <td><?= esc($attempt['subject_name']) ?></td>
                                        <td>
                                            <?php if ($attempt['percentage'] !== null): ?>
                                                <span class="badge bg-primary"><?= number_format($attempt['percentage'], 1) ?>%</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M j, Y g:i A', strtotime($attempt['submitted_at'])) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = 'bg-success';
                                            $statusText = 'Completed';
                                            if ($attempt['status'] === 'auto_submitted') {
                                                $statusClass = 'bg-warning';
                                                $statusText = 'Auto Submitted';
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No recent exam attempts found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
