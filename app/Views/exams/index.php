<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .exam-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .exam-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .exam-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }
    .status-active { background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; }
    .status-scheduled { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; }
    .status-completed { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
    .status-draft { background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; }
    .btn-action {
        border-radius: 8px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        margin: 0 0.125rem;
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <?php if ($role === 'admin'): ?>
                <a href="<?= base_url('exam/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create New Exam
                </a>
            <?php endif; ?>
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

<!-- Exams Grid -->
<div class="row">
    <?php if (!empty($exams)): ?>
        <?php foreach ($exams as $exam): ?>
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="exam-card card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="exam-avatar me-3">
                                <i class="material-symbols-rounded" style="font-size: 24px;">quiz</i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fw-semibold"><?= esc($exam['title']) ?></h5>
                                <small class="text-muted"><?= esc($exam['subject_name']) ?> • <?= esc($exam['class_name']) ?></small>
                            </div>
                            <span class="status-badge status-<?= $exam['status'] ?>">
                                <?= ucfirst($exam['status']) ?>
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="mb-0 text-primary"><?= $exam['duration_minutes'] ?></h6>
                                        <small class="text-muted">Minutes</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="mb-0 text-success"><?= $exam['total_marks'] ?></h6>
                                        <small class="text-muted">Marks</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="mb-0 text-warning"><?= $exam['passing_marks'] ?></h6>
                                    <small class="text-muted">Pass</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">schedule</i>
                                <?= date('M j, Y g:i A', strtotime($exam['start_time'])) ?>
                            </small>
                            <small class="text-muted d-block">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">event</i>
                                Ends: <?= date('M j, Y g:i A', strtotime($exam['end_time'])) ?>
                            </small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('exam/view/' . $exam['id']) ?>" 
                               class="btn btn-outline-primary btn-action flex-fill" title="View Details">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">visibility</i>View
                            </a>
                            <?php if ($role === 'admin'): ?>
                                <a href="<?= base_url('exam/edit/' . $exam['id']) ?>" 
                                   class="btn btn-outline-warning btn-action" title="Edit Exam">
                                    <i class="material-symbols-rounded" style="font-size: 16px;">edit</i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-action" 
                                        title="Delete Exam" onclick="confirmDelete(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                                    <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">quiz</i>
                <h5 class="text-muted">No Exams Found</h5>
                <p class="text-muted">There are no exams available at the moment.</p>
                <?php if ($role === 'admin'): ?>
                    <a href="<?= base_url('exam/create') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create First Exam
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
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
function confirmDelete(examId, examTitle) {
    if (confirm(`Are you sure you want to delete the exam "${examTitle}"? This action cannot be undone.`)) {
        window.location.href = `<?= base_url('exam/delete/') ?>${examId}`;
    }
}
</script>
<?= $this->endSection() ?>
