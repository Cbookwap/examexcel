<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
        transition: transform 0.2s ease-in-out;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .class-info-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 12px;
    }
    .result-card {
        transition: transform 0.2s ease-in-out;
        border: 1px solid #e3e6f0;
    }
    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .score-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    .score-excellent { background: #28a745; color: white; }
    .score-good { background: #17a2b8; color: white; }
    .score-average { background: #ffc107; color: #212529; }
    .score-poor { background: #dc3545; color: white; }
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
                    <a href="<?= base_url('class-teacher/marksheet') ?>" class="btn btn-primary">
                        <i class="fas fa-table me-2"></i>View Marksheet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card class-info-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2 fw-bold"><?= esc($class['name']) ?></h5>
                            <p class="mb-1 opacity-75">Academic Year: <?= esc($class['academic_year']) ?></p>
                            <?php if ($class['section']): ?>
                                <p class="mb-1 opacity-75">Section: <?= esc($class['section']) ?></p>
                            <?php endif; ?>
                            <p class="mb-0 opacity-75">Max Students: <?= $class['max_students'] ?></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column align-items-md-end">
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-users me-1"></i>
                                        <?= count($students) ?> Students
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-book me-1"></i>
                                        <?= count($subjects) ?> Subjects
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h3 class="mb-1"><?= $classStats['total_students'] ?></h3>
                    <p class="mb-0 opacity-75">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-primary"><?= $classStats['total_attempts'] ?></h3>
                    <p class="mb-0 text-muted">Total Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-success"><?= number_format($classStats['average_score'], 1) ?>%</h3>
                    <p class="mb-0 text-muted">Class Average</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-info"><?= number_format($classStats['pass_rate'], 1) ?>%</h3>
                    <p class="mb-0 text-muted">Pass Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Results -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Recent Exam Results</h5>
                        <a href="<?= base_url('class-teacher/marksheet') ?>" class="btn btn-outline-primary btn-sm">
                            View All Results
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recentResults)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Student</th>
                                        <th class="border-0 fw-semibold">Exam</th>
                                        <th class="border-0 fw-semibold">Subject</th>
                                        <th class="border-0 fw-semibold">Mark</th>
                                        <th class="border-0 fw-semibold">Grade</th>
                                        <th class="border-0 fw-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentResults as $result): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong><?= esc($result['first_name'] . ' ' . $result['last_name']) ?></strong>
                                                    <br><small class="text-muted"><?= esc($result['student_id']) ?></small>
                                                </div>
                                            </td>
                                            <td><?= esc($result['exam_title']) ?></td>
                                            <td><?= esc($result['subject_name']) ?></td>
                                            <td>
                                                <?php
                                                $percentage = $result['percentage'];
                                                $scoreClass = 'score-poor';
                                                if ($percentage >= 80) $scoreClass = 'score-excellent';
                                                elseif ($percentage >= 60) $scoreClass = 'score-good';
                                                elseif ($percentage >= 40) $scoreClass = 'score-average';
                                                ?>
                                                <span class="score-badge <?= $scoreClass ?>">
                                                    <?= number_format($percentage, 1) ?>%
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    <?= $result['marks_obtained'] ?>/<?= $result['total_marks'] ?>
                                                </small>
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
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mb-3">No Results Available</h5>
                            <p class="text-muted">No exam results found for your class yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Students Overview -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">Class Students</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($students)): ?>
                        <div class="row">
                            <?php foreach (array_slice($students, 0, 8) as $student): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                <?= strtoupper(substr($student['first_name'], 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h6>
                                            <small class="text-muted"><?= esc($student['student_id']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($students) > 8): ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">And <?= count($students) - 8 ?> more students...</small>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-users text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No students assigned to this class yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Class Subjects -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">Class Subjects</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($subjects)): ?>
                        <div class="row">
                            <?php foreach ($subjects as $subject): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-book text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($subject['name']) ?></h6>
                                            <?php if ($subject['code']): ?>
                                                <small class="text-muted"><?= esc($subject['code']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-book text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No subjects assigned to this class yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
