<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
            <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
        </div>
        <div>
            <a href="<?= base_url('student/exams') ?>" class="btn btn-outline-primary">
                <i class="fas fa-clipboard-list me-2"></i>Available Exams
            </a>
        </div>
    </div>

    <?php if (!empty($attempts)): ?>
        <!-- Results Summary Cards -->
        <div class="row mb-4">
            <?php
            $totalAttempts = count($attempts);
            $passedAttempts = 0;
            $totalPercentage = 0;
            $highestScore = 0;

            // Use corrected statistics from controller
            foreach ($attempts as $attempt) {
                $correctedPercentage = $attempt['actual_percentage'] ?? 0;

                $totalPercentage += $correctedPercentage;
                if ($correctedPercentage >= 60) $passedAttempts++;
                if ($correctedPercentage > $highestScore) $highestScore = $correctedPercentage;
            }

            $averageScore = $totalAttempts > 0 ? round($totalPercentage / $totalAttempts, 1) : 0;
            $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0;
            ?>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-1"><?= $totalAttempts ?></h3>
                        <p class="text-muted mb-0 small">Total Exams Taken</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-1"><?= $averageScore ?>%</h3>
                        <p class="text-muted mb-0 small">Average Score</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-1"><?= $highestScore ?>%</h3>
                        <p class="text-muted mb-0 small">Highest Score</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-1"><?= $passRate ?>%</h3>
                        <p class="text-muted mb-0 small">Pass Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-history me-2 text-primary"></i>Exam History
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="exportResults()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterResults('all')">All Results</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterResults('passed')">Passed Only</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterResults('failed')">Failed Only</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="filterResults('recent')">Recent (30 days)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="resultsTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold" style="color: black !important;">Exam</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Subject</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Score</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Percentage</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Grade</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Status</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Date</th>
                                <th class="border-0 fw-semibold" style="color: black !important;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attempts as $attempt): ?>
                                <?php
                                // Use corrected marks and percentage from controller
                                $actualMarksObtained = $attempt['actual_marks_obtained'] ?? 0;
                                $examTotalMarks = $attempt['total_marks'] ?? 1;
                                $percentage = $attempt['actual_percentage'] ?? 0;
                                $passed = $percentage >= 60;

                                // Determine grade
                                if ($percentage >= 90) { $grade = 'A+'; $gradeClass = 'bg-success'; }
                                elseif ($percentage >= 80) { $grade = 'A'; $gradeClass = 'bg-success'; }
                                elseif ($percentage >= 70) { $grade = 'B'; $gradeClass = 'bg-primary'; }
                                elseif ($percentage >= 60) { $grade = 'C'; $gradeClass = 'bg-info'; }
                                elseif ($percentage >= 40) { $grade = 'D'; $gradeClass = 'bg-warning'; }
                                else { $grade = 'F'; $gradeClass = 'bg-danger'; }
                                ?>
                                <tr class="result-row" data-status="<?= $passed ? 'passed' : 'failed' ?>"
                                    data-date="<?= $attempt['submitted_at'] ?: $attempt['created_at'] ?>">
                                    <td>
                                        <div class="fw-semibold"><?= esc($attempt['exam_title']) ?></div>
                                        <small class="text-muted">ID: #<?= $attempt['id'] ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($attempt['subject_name'])): ?>
                                            <span class="badge bg-light text-dark"><?= esc($attempt['subject_name']) ?></span>
                                        <?php elseif (isset($attempt['exam_mode']) && $attempt['exam_mode'] === 'multi_subject'): ?>
                                            <span class="badge bg-info text-white">Multi-Subject</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-semibold"><?= round($actualMarksObtained, 1) ?></span>
                                        <span class="text-muted">/ <?= $examTotalMarks ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 60px; height: 8px;">
                                                <div class="progress-bar <?= $passed ? 'bg-success' : 'bg-danger' ?>"
                                                     style="width: <?= $percentage ?>%"></div>
                                            </div>
                                            <span class="fw-semibold <?= $passed ? 'text-success' : 'text-danger' ?>">
                                                <?= $percentage ?>%
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $gradeClass ?>"><?= $grade ?></span>
                                    </td>
                                    <td>
                                        <?php if ($passed): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Passed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Failed
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <?php
                                            $dateToShow = $attempt['submitted_at'] ?: $attempt['created_at'];
                                            if ($dateToShow && strtotime($dateToShow) > 0):
                                            ?>
                                                <?= date('M j, Y', strtotime($dateToShow)) ?>
                                                <br>
                                                <span class="text-muted"><?= date('g:i A', strtotime($dateToShow)) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Date unavailable</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="<?= base_url('student/examResult/' . $attempt['id']) ?>"
                                               class="btn btn-outline-primary btn-sm"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-md-inline ms-1">View</span>
                                            </a>
                                            <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="downloadCertificate(<?= $attempt['id'] ?>)"
                                                    title="Download Certificate"
                                                    <?= !$passed ? 'disabled' : '' ?>>
                                                <i class="fas fa-certificate"></i>
                                                <span class="d-none d-md-inline ms-1">Cert</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                    </div>
                    <h4 class="fw-semibold mb-3">No Exam Results Yet</h4>
                    <p class="text-muted mb-4">
                        You haven't taken any exams yet. Start by taking your first exam to see your results here.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= base_url('student/exams') ?>" class="btn btn-primary">
                            <i class="fas fa-clipboard-list me-2"></i>View Available Exams
                        </a>
                        <a href="<?= base_url('student/practice') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-dumbbell me-2"></i>Practice Tests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Filter results
function filterResults(type) {
    const rows = document.querySelectorAll('.result-row');
    const now = new Date();
    const thirtyDaysAgo = new Date(now.getTime() - (30 * 24 * 60 * 60 * 1000));

    rows.forEach(row => {
        let show = true;

        switch(type) {
            case 'passed':
                show = row.dataset.status === 'passed';
                break;
            case 'failed':
                show = row.dataset.status === 'failed';
                break;
            case 'recent':
                const rowDate = new Date(row.dataset.date);
                show = rowDate >= thirtyDaysAgo;
                break;
            case 'all':
            default:
                show = true;
                break;
        }

        row.style.display = show ? '' : 'none';
    });
}

// Export results
function exportResults() {
    CBT.showToast('Export functionality will be available soon!', 'info');
}

// Download certificate
function downloadCertificate(attemptId) {
    CBT.showToast('Certificate download will be available soon!', 'info');
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
});
</script>
<?= $this->endSection() ?>
