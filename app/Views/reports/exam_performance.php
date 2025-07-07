<?php
// Determine layout based on user role
$userRole = session()->get('role');
$layout = 'layouts/dashboard'; // default to admin layout
$contentSection = 'page_content';

if ($userRole === 'principal') {
    $layout = 'layouts/principal';
    $contentSection = 'page_content'; // Principal layout uses page_content section
} elseif ($userRole === 'teacher') {
    $layout = 'layouts/teacher';
    $contentSection = 'content';
}
?>

<?= $this->extend($layout) ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
/* Ensure table headers have black text */
.table thead th {
    color: #000 !important;
    font-weight: 600;
}
.btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        color: white;
    }
      .section-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1rem 1.5rem;
        margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        border-radius: 15px 15px 0 0;
    }
	 .section-header h5 {
        margin: 0;
        font-weight: 600;
    }
	  .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.2);
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
    }

.table-light th {
    color: #000 !important;
    background-color: #f8f9fa !important;
}

/* Additional styling for better readability */
.table th {
    border-bottom: 2px solid #dee2e6;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section($contentSection) ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">print</i>Print Report
                </button>
<?php
                $backUrl = 'admin/reports'; // default
                if ($userRole === 'principal') {
                    $backUrl = 'principal/reports';
                } elseif ($userRole === 'teacher') {
                    $backUrl = 'teacher/reports';
                }
                ?>
                <a href="<?= base_url($backUrl) ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $stats['total_attempts'] ?? 0 ?></h5>
                        <p class="card-text mb-0">Total Attempts</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">quiz</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $stats['average_percentage'] ?? 0 ?>%</h5>
                        <p class="card-text mb-0">Average Mark</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">trending_up</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $stats['pass_rate'] ?? 0 ?>%</h5>
                        <p class="card-text mb-0">Pass Rate</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">check_circle</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $stats['average_marks'] ?? 0 ?></h5>
                        <p class="card-text mb-0">Average Marks</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">grade</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Performance Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">assessment</i>
                    Exam Performance Details
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($attempts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Marks</th>
                                <th>Percentage</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attempts as $attempt): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <?= strtoupper(substr($attempt['first_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-medium"><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></div>
                                            <small class="text-muted"><?= esc($attempt['student_id']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($attempt['exam_title']) ?></td>
                                <td><?= esc($attempt['subject_name']) ?></td>
                                <td><?= esc($attempt['class_name']) ?></td>
                                <td><?= esc($attempt['marks_obtained']) ?>/<?= esc($attempt['total_marks']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $attempt['percentage'] >= 50 ? 'success' : 'danger' ?>">
                                        <?= number_format($attempt['percentage'], 1) ?>%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $attempt['percentage'] >= 50 ? 'success' : 'danger' ?>">
                                        <?= $attempt['percentage'] >= 50 ? 'Pass' : 'Fail' ?>
                                    </span>
                                </td>
                                <td><?= date('M j, Y', strtotime($attempt['submitted_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">assessment</i>
                    <h5 class="text-muted">No exam performance data available</h5>
                    <p class="text-muted">Performance data will appear here once students complete exams.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Exam Performance Report loaded');
});
</script>
<?= $this->endSection() ?>
