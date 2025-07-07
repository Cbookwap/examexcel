<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.info-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.stat-item {
    text-align: center;
    padding: 16px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #6f42c1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.attempts-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    margin-top: 24px;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($exam['title']) ?></h4>
        <p class="text-muted mb-0">Exam Details and Management</p>
    </div>
    <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-secondary">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Exams
    </a>
</div>

<div class="info-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-2">
                <div class="exam-icon me-3">
                    <i class="material-symbols-rounded text-primary" style="font-size: 32px;">quiz</i>
                </div>
                <div>
                    <p class="mb-0 text-muted"><?= esc($exam['description']) ?></p>
                </div>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <span class="badge bg-<?= $status === 'active' ? 'success' : ($status === 'scheduled' ? 'warning' : 'secondary') ?> px-3 py-2">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">
                        <?php
                        switch($status) {
                            case 'active': echo 'play_circle'; break;
                            case 'scheduled': echo 'schedule'; break;
                            case 'completed': echo 'check_circle'; break;
                            default: echo 'block';
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

<!-- Validation Errors -->
<?php if (!empty($validationErrors)): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-start">
            <i class="material-symbols-rounded me-2 text-warning" style="font-size: 20px;">warning</i>
            <div>
                <h6 class="alert-heading mb-2">Configuration Issues Detected</h6>
                <p class="mb-2">This exam has the following configuration issues that need to be resolved:</p>
                <ul class="mb-0">
                    <?php foreach ($validationErrors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <hr>
                <p class="mb-0 small">
                    <strong>Note:</strong> Students will not be able to take this exam until these issues are resolved.
                    Please review the question assignments for each subject.
                </p>
            </div>
        </div>
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
                <a href="<?= base_url('admin/exam/edit/' . $exam['id']) ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">edit</i>Edit Exam
                </a>
                <a href="<?= base_url('admin/results?exam_id=' . $exam['id']) ?>" class="btn btn-outline-success">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">assessment</i>View Results
                </a>
                <button class="btn btn-outline-danger" onclick="confirmDelete(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Exam
                </button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">delete</i>
                    Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="text-danger mb-3">
                        <i class="material-symbols-rounded" style="font-size: 48px;">warning</i>
                    </div>
                    <p class="mb-0" id="deleteMessage">Are you sure you want to delete this exam?</p>
                    <small class="text-muted">This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">close</i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

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
let examToDelete = null;

function confirmDelete(examId, examTitle) {
    examToDelete = examId;
    const deleteMessage = document.getElementById('deleteMessage');
    deleteMessage.textContent = `Are you sure you want to delete the exam "${examTitle}"?`;

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteModal.show();
}

// Handle delete confirmation
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (examToDelete) {
        window.location.href = `<?= base_url('admin/exam/delete/') ?>${examToDelete}`;
    }
});
</script>
<?= $this->endSection() ?>
