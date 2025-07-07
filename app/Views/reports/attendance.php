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

<!-- Attendance Report Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">login</i>
                    Exam Attendance Report
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($attendanceData)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Class</th>
                                <th>Exam</th>
                                <th>Total Students</th>
                                <th>Attempted</th>
                                <th>Attendance Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendanceData as $data): ?>
                            <?php
                            $attendanceRate = $data['attendance_rate'];
                            $statusClass = 'danger';
                            $status = 'Poor';

                            if ($attendanceRate >= 90) {
                                $status = 'Excellent';
                                $statusClass = 'success';
                            } elseif ($attendanceRate >= 75) {
                                $status = 'Good';
                                $statusClass = 'info';
                            } elseif ($attendanceRate >= 60) {
                                $status = 'Average';
                                $statusClass = 'warning';
                            }
                            ?>
                            <tr>
                                <td class="fw-medium"><?= esc($data['class_name']) ?></td>
                                <td><?= esc($data['exam_title']) ?></td>
                                <td><?= $data['total_students'] ?></td>
                                <td><?= $data['attempted_students'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-<?= $statusClass ?>"
                                                 role="progressbar"
                                                 style="width: <?= $attendanceRate ?>%">
                                            </div>
                                        </div>
                                        <span class="fw-medium"><?= number_format($attendanceRate, 1) ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= $status ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">login</i>
                    <h5 class="text-muted">No attendance data available</h5>
                    <p class="text-muted">Attendance data will appear here once students participate in exams.</p>
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
    console.log('Attendance Report loaded');
});
</script>
<?= $this->endSection() ?>
