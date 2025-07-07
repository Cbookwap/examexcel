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
                <a href="<?= base_url($backUrl) ?>" class="btn btn-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Student Performance Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">person</i>
                    Student Performance Report
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($students)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Class</th>
                                <th>Total Attempts</th>
                                <th>Average Performance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <?= strtoupper(substr($student['first_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-medium"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></div>
                                            <small class="text-muted"><?= esc($student['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($student['student_id']) ?></td>
                                <td><?= esc($student['class_name'] ?? 'Not Assigned') ?></td>
                                <td><?= $student['total_attempts'] ?? 0 ?></td>
                                <td>
                                    <?php if ($student['average_percentage']): ?>
                                        <span class="badge bg-<?= $student['average_percentage'] >= 50 ? 'success' : 'danger' ?>">
                                            <?= number_format($student['average_percentage'], 1) ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">No attempts</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $student['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">person</i>
                    <h5 class="text-muted">No student data available</h5>
                    <p class="text-muted">Student performance data will appear here once students are registered and take exams.</p>
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
    console.log('Student Report loaded');
});
</script>
<?= $this->endSection() ?>
