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

<?= $this->section($contentSection) ?>
<style>
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

</style>
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
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">print</i>Print Schedule
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

<!-- Exam Schedule Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">schedule</i>
                    Exam Schedule
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($schedules)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="stats-card">
                            <tr>
                                <th>Exam Title</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Created By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                            <?php
                            $now = date('Y-m-d H:i:s');
                            $status = 'upcoming';
                            $statusClass = 'warning';

                            if ($schedule['start_time'] <= $now && $schedule['end_time'] >= $now) {
                                $status = 'active';
                                $statusClass = 'success';
                            } elseif ($schedule['end_time'] < $now) {
                                $status = 'completed';
                                $statusClass = 'secondary';
                            }

                            $duration = (strtotime($schedule['end_time']) - strtotime($schedule['start_time'])) / 60;
                            ?>
                            <tr>
                                <td class="fw-medium"><?= esc($schedule['title']) ?></td>
                                <td><?= esc($schedule['subject_name']) ?></td>
                                <td><?= esc($schedule['class_name']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($schedule['start_time'])) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($schedule['end_time'])) ?></td>
                                <td><?= $duration ?> minutes</td>
                                <td><?= esc($schedule['first_name'] . ' ' . $schedule['last_name']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">schedule</i>
                    <h5 class="text-muted">No exam schedules available</h5>
                    <p class="text-muted">Exam schedules will appear here once they are created.</p>
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
    console.log('Exam Schedule Report loaded');
});
</script>
<?= $this->endSection() ?>
