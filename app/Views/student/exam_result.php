<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="container-fluid">
    <!-- Result Header -->
    <div class="text-center mb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-5">
                <?php
                // Handle null values for existing records
                $marksObtained = $attempt['marks_obtained'] ?? 0;
                $totalMarks = $attempt['total_marks'] ?? $exam['total_marks'] ?? 1;
                // Recalculate percentage based on correct score to handle data inconsistencies
                $percentage = $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0;
                $passingMarks = $exam['passing_marks'] ?? 60;
                $passed = $percentage >= 60 || $marksObtained >= $passingMarks;
                ?>
                <?php if ($passed): ?>
                    <div class="text-success mb-4">
                        <i class="fas fa-check-circle fa-5x"></i>
                    </div>
                    <h2 class="fw-bold text-success mb-3">Congratulations!</h2>
                    <p class="lead mb-4">You have successfully passed the exam</p>
                <?php else: ?>
                    <div class="text-warning mb-4">
                        <i class="fas fa-exclamation-triangle fa-5x"></i>
                    </div>
                    <h2 class="fw-bold text-warning mb-3">Keep Trying!</h2>
                    <p class="lead mb-4">You didn't pass this time, but don't give up</p>
                <?php endif; ?>

                <h3 class="fw-bold mb-2"><?= esc($attempt['exam_title']) ?></h3>
                <div class="display-4 fw-bold mb-2 <?= $passed ? 'text-success' : 'text-danger' ?>"><?= $percentage ?>%</div>
                <div class="h5 mb-2 text-muted"><?= $marksObtained ?>/<?= $totalMarks ?> marks</div>
                <p class="text-muted"><?= esc($pageSubtitle) ?></p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= base_url('student/exams') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Exams
                </a>
                <div class="text-muted small">
                    <i class="fas fa-calendar me-1"></i>
                    <?php
                    $dateToShow = $attempt['submitted_at'] ?: $attempt['created_at'];
                    if ($dateToShow && strtotime($dateToShow) > 0):
                    ?>
                        <?= date('F j, Y \a\t g:i A', strtotime($dateToShow)) ?>
                    <?php else: ?>
                        Date unavailable
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Summary -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i class="fas fa-chart-pie fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1 <?= $passed ? 'text-success' : 'text-danger' ?>">
                        <?= $percentage ?>%
                    </h3>
                    <p class="text-muted mb-0 small">Your Score</p>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar <?= $passed ? 'bg-success' : 'bg-danger' ?>"
                             style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i class="fas fa-bullseye fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1"><?= $marksObtained ?></h3>
                    <p class="text-muted mb-0 small">Marks Obtained</p>
                    <small class="text-muted">out of <?= $attempt['total_marks'] ?? $exam['total_marks'] ?? 'N/A' ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i class="fas fa-flag-checkered fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1"><?= $passingMarks ?></h3>
                    <p class="text-muted mb-0 small">Passing Marks</p>
                    <small class="text-muted">Required to pass</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-secondary mb-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-1">
                        <?php
                        $duration = $attempt['time_taken_minutes'] ?? $attempt['time_taken'] ?? 0;
                        if ($duration > 0) {
                            $hours = floor($duration / 60);
                            $minutes = $duration % 60;

                            if ($hours > 0) {
                                echo sprintf('%02d:%02d', $hours, $minutes);
                            } else {
                                echo sprintf('%02d min', $minutes);
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </h3>
                    <p class="text-muted mb-0 small">Time Taken</p>
                    <small class="text-muted">Duration</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade and Status -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="fw-semibold mb-3">Grade Achieved</h5>
                    <?php
                    if ($percentage >= 90) { $grade = 'A+'; $gradeClass = 'bg-success'; }
                    elseif ($percentage >= 80) { $grade = 'A'; $gradeClass = 'bg-success'; }
                    elseif ($percentage >= 70) { $grade = 'B'; $gradeClass = 'bg-primary'; }
                    elseif ($percentage >= 60) { $grade = 'C'; $gradeClass = 'bg-info'; }
                    elseif ($percentage >= 40) { $grade = 'D'; $gradeClass = 'bg-warning'; }
                    else { $grade = 'F'; $gradeClass = 'bg-danger'; }
                    ?>
                    <div class="display-1 fw-bold">
                        <span class="badge <?= $gradeClass ?> p-4" style="font-size: 3rem;"><?= $grade ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="fw-semibold mb-3">Exam Status</h5>
                    <div class="display-6">
                        <?php if ($passed): ?>
                            <span class="badge bg-success p-3" style="font-size: 1.5rem;">
                                <i class="fas fa-check me-2"></i>PASSED
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger p-3" style="font-size: 1.5rem;">
                                <i class="fas fa-times me-2"></i>FAILED
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <?php
                            $dateToShow = $attempt['submitted_at'] ?: $attempt['created_at'];
                            if ($dateToShow && strtotime($dateToShow) > 0):
                            ?>
                                Submitted on <?= date('F j, Y \a\t g:i A', strtotime($dateToShow)) ?>
                            <?php else: ?>
                                Date unavailable
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Details -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-semibold mb-0">
                <i class="fas fa-info-circle me-2 text-primary"></i>Exam Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-semibold text-muted small">Exam Title:</label>
                        <div><?= esc($attempt['exam_title']) ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold text-muted small">Subject(s):</label>
                        <div>
                            <?php if (!empty($attempt['subject_name'])): ?>
                                <?= esc($attempt['subject_name']) ?>
                            <?php elseif (isset($attempt['exam_mode']) && $attempt['exam_mode'] === 'multi_subject'): ?>
                                <span class="badge bg-info">Multi-Subject Exam</span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold text-muted small">Total Questions:</label>
                        <div><?= $attempt['total_questions'] ?? 'N/A' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-semibold text-muted small">Exam Duration:</label>
                        <div><?= $exam['duration_minutes'] ?? 'N/A' ?> minutes</div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold text-muted small">Attempt Status:</label>
                        <div>
                            <?php
                            $statusLabels = [
                                'submitted' => '<span class="badge bg-success">Submitted</span>',
                                'auto_submitted' => '<span class="badge bg-warning">Auto-Submitted</span>',
                                'completed' => '<span class="badge bg-info">Completed</span>',
                                'in_progress' => '<span class="badge bg-secondary">Time Expired</span>'
                            ];
                            echo $statusLabels[$attempt['status']] ?? '<span class="badge bg-secondary">Unknown</span>';
                            ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold text-muted small">Attempt ID:</label>
                        <div><code>#<?= $attempt['id'] ?></code></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="text-center mb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">What's Next?</h5>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= base_url('student/exams') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Exams
                    </a>
                    <a href="<?= base_url('student/results') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>View All Results
                    </a>
                    <a href="<?= base_url('student/practice') ?>" class="btn btn-outline-info">
                        <i class="fas fa-dumbbell me-2"></i>Practice More
                    </a>
                    <?php if ($passed): ?>
                        <button class="btn btn-success" onclick="downloadCertificate()">
                            <i class="fas fa-certificate me-2"></i>Download Certificate
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show result message
    const passed = <?= $passed ? 'true' : 'false' ?>;
    const score = <?= $percentage ?>;

    if (passed) {
        CBT.showToast('Congratulations! You scored ' + score + '% and passed the exam!', 'success');
    } else {
        CBT.showToast('You scored ' + score + '%. Keep practicing and try again!', 'info');
    }

    // Smooth scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

function downloadCertificate() {
    CBT.showToast('Certificate download will be available soon!', 'info');
}
</script>
<?= $this->endSection() ?>
