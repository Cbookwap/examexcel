<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<style>
.exam-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    overflow: hidden;
    transition: all 0.3s ease;
}

.exam-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.exam-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-scheduled {
    background: #fff3cd;
    color: #856404;
}

.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .exam-card {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;">Exam Management</h4>
                <p class="text-light mb-0">Create and manage school examinations</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('principal/exams/create') ?>" class="btn btn-light">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create New Exam
                </a>
                <a href="<?= base_url('principal/dashboard') ?>" class="btn btn-outline-light">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Dashboard
                </a>
            </div>
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

<!-- Exams List -->
<div class="row">
    <?php if (!empty($exams)): ?>
        <?php foreach ($exams as $exam): ?>
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card exam-card h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-0 fw-semibold"><?= esc($exam['title']) ?></h6>
                        <?php
                        $statusClass = 'status-inactive';
                        $statusText = 'Inactive';
                        if ($exam['is_active']) {
                            $statusClass = 'status-active';
                            $statusText = 'Active';
                        }
                        ?>
                        <span class="exam-status <?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Subject</small>
                        <span class="fw-medium"><?= esc($exam['subject_name'] ?? 'Multiple Subjects') ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Class</small>
                        <span class="fw-medium"><?= esc($exam['class_name'] ?? 'Multiple Classes') ?></span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Duration</small>
                            <span class="fw-medium"><?= $exam['duration_minutes'] ?? 0 ?> min</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Questions</small>
                            <span class="fw-medium"><?= $exam['total_questions'] ?? 0 ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Schedule</small>
                        <span class="fw-medium">
                            <?= date('M j, Y', strtotime($exam['start_time'])) ?><br>
                            <small><?= date('g:i A', strtotime($exam['start_time'])) ?> - <?= date('g:i A', strtotime($exam['end_time'])) ?></small>
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex gap-2 mb-2">
                        <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-primary btn-action flex-fill">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">visibility</i>View
                        </a>
                        <a href="<?= base_url('principal/exams/edit/' . $exam['id']) ?>" class="btn btn-outline-secondary btn-action flex-fill">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">edit</i>Edit
                        </a>
                        <button class="btn btn-outline-danger btn-action" onclick="confirmDelete(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                            <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                        </button>
                    </div>
                    <div class="d-grid">
                        <a href="<?= base_url('principal/exams/' . $exam['id'] . '/questions') ?>" class="btn btn-outline-info btn-action">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">quiz</i>Manage Questions
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card exam-card">
                <div class="card-body text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">quiz</i>
                    <h5 class="text-muted">No Exams Created Yet</h5>
                    <p class="text-muted mb-4">Start by creating your first exam to assess student performance.</p>
                    <a href="<?= base_url('principal/exams/create') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create First Exam
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
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

function confirmDelete(examId, examTitle) {
    document.getElementById('examTitle').textContent = examTitle;
    document.getElementById('deleteLink').href = '<?= base_url('principal/exams/delete/') ?>' + examId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
<?= $this->endSection() ?>
