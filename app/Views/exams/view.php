<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .exam-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.2);
    }
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: none;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .status-badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .status-active { background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; }
    .status-scheduled { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; }
    .status-completed { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
    .status-draft { background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; }
    .status-cancelled { background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white; }
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
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
    .stat-item {
        text-align: center;
        padding: 1rem;
        border-radius: 10px;
        background: rgba(255,255,255,0.1);
        margin-bottom: 1rem;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }
    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    .attempts-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Back Button -->
<div class="row mb-3">
    <div class="col-12">
        <a href="<?= base_url('exam') ?>" class="btn btn-outline-secondary">
            <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Exams
        </a>
    </div>
</div>

<!-- Exam Header -->
<div class="exam-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2 fw-bold"><?= esc($exam['title']) ?></h2>
            <p class="mb-3 opacity-75"><?= esc($exam['description'] ?? 'No description available') ?></p>
            <div class="d-flex flex-wrap gap-3">
                <span class="status-badge status-<?= $status ?>">
                    <i class="material-symbols-rounded" style="font-size: 16px;">
                        <?php
                        switch($status) {
                            case 'active': echo 'play_circle'; break;
                            case 'scheduled': echo 'schedule'; break;
                            case 'completed': echo 'check_circle'; break;
                            case 'cancelled': echo 'cancel'; break;
                            default: echo 'draft'; break;
                        }
                        ?>
                    </i>
                    <?= ucfirst($status) ?>
                </span>
                <small class="opacity-75">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">book</i>
                    <?= esc($exam['subject_name']) ?>
                </small>
                <small class="opacity-75">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">group</i>
                    <?= esc($exam['class_name']) ?>
                </small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-6">
                    <div class="stat-item">
                        <div class="stat-value"><?= $exam['duration_minutes'] ?></div>
                        <div class="stat-label">Minutes</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-item">
                        <div class="stat-value"><?= $exam['total_marks'] ?? 'Not set' ?></div>
                        <div class="stat-label">Total Marks</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Exam Details -->
<div class="row">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2 text-primary" style="font-size: 20px;">info</i>
                Exam Information
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">Start Time</label>
                    <div class="fw-medium"><?= date('M j, Y g:i A', strtotime($exam['start_time'])) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">End Time</label>
                    <div class="fw-medium"><?= date('M j, Y g:i A', strtotime($exam['end_time'])) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">Passing Marks</label>
                    <div class="fw-medium"><?= $exam['passing_marks'] ?> marks</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">Question Count</label>
                    <div class="fw-medium"><?= $exam['question_count'] ?? 'Not set' ?> questions</div>
                </div>
            </div>
        </div>

        <!-- Exam Settings -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2 text-primary" style="font-size: 20px;">settings</i>
                Exam Settings
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2">
                        <i class="material-symbols-rounded me-2 text-<?= $exam['randomize_questions'] ? 'success' : 'muted' ?>" style="font-size: 16px;">
                            <?= $exam['randomize_questions'] ? 'check_circle' : 'cancel' ?>
                        </i>
                        <span class="<?= $exam['randomize_questions'] ? 'text-success' : 'text-muted' ?>">
                            Randomize Questions
                        </span>
                    </div>
                    <div class="mb-2">
                        <i class="material-symbols-rounded me-2 text-<?= $exam['randomize_options'] ? 'success' : 'muted' ?>" style="font-size: 16px;">
                            <?= $exam['randomize_options'] ? 'check_circle' : 'cancel' ?>
                        </i>
                        <span class="<?= $exam['randomize_options'] ? 'text-success' : 'text-muted' ?>">
                            Randomize Options
                        </span>
                    </div>
                    <div class="mb-2">
                        <i class="material-symbols-rounded me-2 text-<?= $exam['show_result_immediately'] ? 'success' : 'muted' ?>" style="font-size: 16px;">
                            <?= $exam['show_result_immediately'] ? 'check_circle' : 'cancel' ?>
                        </i>
                        <span class="<?= $exam['show_result_immediately'] ? 'text-success' : 'text-muted' ?>">
                            Show Result Immediately
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2">
                        <i class="material-symbols-rounded me-2 text-<?= $exam['allow_review'] ? 'success' : 'muted' ?>" style="font-size: 16px;">
                            <?= $exam['allow_review'] ? 'check_circle' : 'cancel' ?>
                        </i>
                        <span class="<?= $exam['allow_review'] ? 'text-success' : 'text-muted' ?>">
                            Allow Review
                        </span>
                    </div>
                    <div class="mb-2">
                        <i class="material-symbols-rounded me-2 text-<?= $exam['calculator_enabled'] ? 'success' : 'muted' ?>" style="font-size: 16px;">
                            <?= $exam['calculator_enabled'] ? 'check_circle' : 'cancel' ?>
                        </i>
                        <span class="<?= $exam['calculator_enabled'] ? 'text-success' : 'text-muted' ?>">
                            Calculator Enabled
                        </span>
                    </div>
                    <div class="mb-2">
                        <i class="material-symbols-rounded me-2 text-<?= $exam['exam_pause_enabled'] ? 'success' : 'muted' ?>" style="font-size: 16px;">
                            <?= $exam['exam_pause_enabled'] ? 'check_circle' : 'cancel' ?>
                        </i>
                        <span class="<?= $exam['exam_pause_enabled'] ? 'text-success' : 'text-muted' ?>">
                            Exam Pause Enabled
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2 text-primary" style="font-size: 20px;">bolt</i>
                Quick Actions
            </h5>
            <div class="d-grid gap-2">
                <?php if ($role === 'admin'): ?>
                    <a href="<?= base_url('exam/edit/' . $exam['id']) ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">edit</i>Edit Exam
                    </a>
                    <a href="<?= base_url('admin/results?exam_id=' . $exam['id']) ?>" class="btn btn-outline-success">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">assessment</i>View Results
                    </a>
                    <button class="btn btn-outline-danger" onclick="confirmDelete(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Exam
                    </button>
                <?php else: ?>
                    <?php if ($status === 'active'): ?>
                        <a href="<?= base_url('student/startExam/' . $exam['id']) ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">play_arrow</i>Start Exam
                        </a>
                    <?php elseif ($status === 'scheduled'): ?>
                        <button class="btn btn-outline-warning" disabled>
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">schedule</i>Not Started Yet
                        </button>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">block</i>Not Available
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Exam Statistics -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2 text-primary" style="font-size: 20px;">analytics</i>
                Statistics
            </h5>
            <div class="row text-center">
                <div class="col-6 mb-3">
                    <div class="border-end">
                        <h4 class="mb-0 text-primary"><?= count($attempts) ?></h4>
                        <small class="text-muted">Attempts</small>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <h4 class="mb-0 text-success">
                        <?= !empty($attempts) ? number_format(array_sum(array_column($attempts, 'percentage')) / count($attempts), 1) : '0' ?>%
                    </h4>
                    <small class="text-muted">Avg Mark</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Attempts (Admin Only) -->
<?php if ($role === 'admin' && !empty($attempts)): ?>
<div class="attempts-table">
    <div class="card-header bg-white border-bottom-0 py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="material-symbols-rounded me-2 text-primary" style="font-size: 20px;">assignment</i>
            Exam Attempts
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 fw-semibold">Student</th>
                        <th class="border-0 fw-semibold">Score</th>
                        <th class="border-0 fw-semibold">Percentage</th>
                        <th class="border-0 fw-semibold">Status</th>
                        <th class="border-0 fw-semibold">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attempts as $attempt): ?>
                    <tr>
                        <td>
                            <div class="fw-medium"><?= esc($attempt['first_name'] . ' ' . $attempt['last_name']) ?></div>
                            <small class="text-muted"><?= esc($attempt['student_id'] ?? $attempt['email']) ?></small>
                        </td>
                        <td>
                            <span class="fw-medium"><?= $attempt['marks_obtained'] ?>/<?= $attempt['total_marks'] ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $attempt['percentage'] >= 50 ? 'success' : 'danger' ?>">
                                <?= number_format($attempt['percentage'], 1) ?>%
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?= in_array($attempt['status'], ['submitted', 'auto_submitted', 'completed']) ? 'success' : 'warning' ?>">
                                <?= ucfirst(str_replace('_', ' ', $attempt['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?= $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : 'Not submitted' ?>
                            </small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Delete confirmation
function confirmDelete(examId, examTitle) {
    if (confirm(`Are you sure you want to delete the exam "${examTitle}"? This action cannot be undone.`)) {
        window.location.href = `<?= base_url('exam/delete/') ?>${examId}`;
    }
}
</script>
<?= $this->endSection() ?>
