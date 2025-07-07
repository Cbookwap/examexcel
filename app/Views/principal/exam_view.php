<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
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
    color: var(--theme-color);
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

.exam-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--theme-color), var(--theme-color-dark));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.validation-error {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;"><?= esc($exam['title']) ?></h4>
                <p class="text-light mb-0">Exam Details and Management</p>
            </div>
            <a href="<?= base_url('principal/exams') ?>" class="btn btn-outline-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Exams
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">check_circle</i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">error</i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Validation Errors -->
<?php if (!empty($validationErrors)): ?>
<div class="validation-error">
    <h6 class="fw-bold text-warning mb-2">
        <i class="material-symbols-rounded me-1">warning</i>Configuration Issues Found
    </h6>
    <ul class="mb-0">
        <?php foreach ($validationErrors as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <!-- Exam Overview -->
        <div class="info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <div class="exam-icon me-3">
                            <i class="material-symbols-rounded" style="font-size: 32px;">quiz</i>
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
                        <span class="badge bg-info px-3 py-2">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">
                                <?= $exam['exam_mode'] === 'single_subject' ? 'book' : 'library_books' ?>
                            </i>
                            <?= $exam['exam_mode'] === 'single_subject' ? 'Single Subject' : 'Multi-Subject' ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex flex-column gap-2">
                        <span class="text-muted small">Created</span>
                        <span class="fw-semibold"><?= date('M j, Y', strtotime($exam['created_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam Details -->
        <div class="info-card">
            <h5 class="mb-4 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--theme-color); font-size: 20px;">info</i>
                Exam Details
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Subject:</span>
                        <span class="fw-medium"><?= esc($exam['subject_name'] ?? 'Multiple Subjects') ?></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Class:</span>
                        <span class="fw-medium"><?= esc($exam['class_name']) ?></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Exam Type:</span>
                        <span class="fw-medium"><?= esc($exam['exam_type']) ?></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Duration:</span>
                        <span class="fw-medium"><?= $exam['duration_minutes'] ?> minutes</span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Marks:</span>
                        <span class="fw-medium"><?= $exam['total_marks'] ?></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Passing Marks:</span>
                        <span class="fw-medium"><?= $exam['passing_marks'] ?></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Questions:</span>
                        <span class="fw-medium"><?= $exam['total_questions'] ?? 0 ?></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Max Attempts:</span>
                        <span class="fw-medium"><?= $exam['max_attempts'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="info-card">
            <h5 class="mb-4 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--theme-color); font-size: 20px;">schedule</i>
                Schedule
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center p-3 bg-light rounded">
                        <i class="material-symbols-rounded mb-2" style="font-size: 24px; color: var(--theme-color);">play_arrow</i>
                        <div class="fw-semibold">Start Time</div>
                        <div class="text-muted"><?= date('M j, Y', strtotime($exam['start_time'])) ?></div>
                        <div class="fw-medium"><?= date('g:i A', strtotime($exam['start_time'])) ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center p-3 bg-light rounded">
                        <i class="material-symbols-rounded mb-2" style="font-size: 24px; color: var(--theme-color);">stop</i>
                        <div class="fw-semibold">End Time</div>
                        <div class="text-muted"><?= date('M j, Y', strtotime($exam['end_time'])) ?></div>
                        <div class="fw-medium"><?= date('g:i A', strtotime($exam['end_time'])) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="info-card">
            <h5 class="mb-4 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--theme-color); font-size: 20px;">analytics</i>
                Statistics
            </h5>
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value"><?= count($attempts) ?></div>
                        <div class="stat-label">Total Attempts</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value">
                            <?= count(array_filter($attempts, function($attempt) { return $attempt['status'] === 'completed' || $attempt['status'] === 'submitted'; })) ?>
                        </div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value">
                            <?php
                            $completedAttempts = array_filter($attempts, function($attempt) { 
                                return ($attempt['status'] === 'completed' || $attempt['status'] === 'submitted') && $attempt['percentage'] !== null; 
                            });
                            echo count($completedAttempts) > 0 ? round(array_sum(array_column($completedAttempts, 'percentage')) / count($completedAttempts), 1) : 0;
                            ?>%
                        </div>
                        <div class="stat-label">Average Mark</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value">
                            <?php
                            $passedAttempts = array_filter($attempts, function($attempt) use ($exam) { 
                                return ($attempt['status'] === 'completed' || $attempt['status'] === 'submitted') && 
                                       $attempt['score'] >= $exam['passing_marks']; 
                            });
                            echo count($passedAttempts);
                            ?>
                        </div>
                        <div class="stat-label">Passed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--theme-color); font-size: 20px;">bolt</i>
                Quick Actions
            </h5>
            <div class="d-grid gap-2">
                <a href="<?= base_url('principal/exams/edit/' . $exam['id']) ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">edit</i>Edit Exam
                </a>
                <a href="<?= base_url('principal/exams/' . $exam['id'] . '/questions') ?>" class="btn btn-outline-info">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">quiz</i>Manage Questions
                </a>
                <a href="<?= base_url('principal/results?exam_id=' . $exam['id']) ?>" class="btn btn-outline-success">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">assessment</i>View Results
                </a>
                <button class="btn btn-outline-danger" onclick="confirmDelete(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Exam
                </button>
            </div>
        </div>

        <!-- Exam Settings -->
        <div class="info-card">
            <h5 class="mb-3 fw-bold">
                <i class="material-symbols-rounded me-2" style="color: var(--theme-color); font-size: 20px;">settings</i>
                Settings
            </h5>
            <div class="small">
                <div class="d-flex justify-content-between mb-2">
                    <span>Negative Marking:</span>
                    <span class="<?= $exam['negative_marking'] ? 'text-success' : 'text-muted' ?>">
                        <?= $exam['negative_marking'] ? 'Enabled' : 'Disabled' ?>
                    </span>
                </div>
                <?php if ($exam['negative_marking']): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span>Negative Marks:</span>
                    <span><?= $exam['negative_marks_per_question'] ?> per question</span>
                </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between mb-2">
                    <span>Randomize Questions:</span>
                    <span class="<?= $exam['randomize_questions'] ? 'text-success' : 'text-muted' ?>">
                        <?= $exam['randomize_questions'] ? 'Yes' : 'No' ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Randomize Options:</span>
                    <span class="<?= $exam['randomize_options'] ? 'text-success' : 'text-muted' ?>">
                        <?= $exam['randomize_options'] ? 'Yes' : 'No' ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Show Result Immediately:</span>
                    <span class="<?= $exam['show_result_immediately'] ? 'text-success' : 'text-muted' ?>">
                        <?= $exam['show_result_immediately'] ? 'Yes' : 'No' ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Browser Lockdown:</span>
                    <span class="<?= $exam['browser_lockdown'] ? 'text-success' : 'text-muted' ?>">
                        <?= $exam['browser_lockdown'] ? 'Enabled' : 'Disabled' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the exam "<span id="examTitle"></span>"?</p>
                <p class="text-danger small">This action cannot be undone and will remove all associated data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteLink" class="btn btn-danger">Delete Exam</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function confirmDelete(examId, examTitle) {
    document.getElementById('examTitle').textContent = examTitle;
    document.getElementById('deleteLink').href = '<?= base_url('principal/exams/delete/') ?>' + examId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
<?= $this->endSection() ?>
