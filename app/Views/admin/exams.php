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
    .status-active { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; }
    .status-inactive { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
    .status-published { background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; }
    .status-draft { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; }
    .status-active-text { color: var(--primary-color); }
    .status-inactive-text { color: #6c757d; }
    .status-published-text { color: #4caf50; }
    .status-draft-text { color: #ff9800; }
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
    .btn-outline-primary {
        color: #A05AFF;
        border-color: #A05AFF;
        background-color: transparent;
    }
    .btn-outline-primary:hover {
        color: white;
        background-color: #A05AFF;
        border-color: #A05AFF;
    }
    .btn-outline-warning {
        color: #ff9800;
        border-color: #ff9800;
        background-color: transparent;
    }
    .btn-outline-warning:hover {
        color: white;
        background-color: #ff9800;
        border-color: #ff9800;
    }
    .btn-outline-success {
        color: #4caf50;
        border-color: #4caf50;
        background-color: transparent;
    }
    .btn-outline-success:hover {
        color: white;
        background-color: #4caf50;
        border-color: #4caf50;
    }
    .btn-outline-danger {
        color: #f44336;
        border-color: #f44336;
        background-color: transparent;
    }
    .btn-outline-danger:hover {
        color: white;
        background-color: #f44336;
        border-color: #f44336;
    }
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
        background-color: transparent;
    }
    .btn-group {
        display: inline-flex;
        gap: 0.25rem;
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

    /* Ensure Material Icons display properly */
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
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
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Exam Management</h4>
                <p class="text-muted mb-0">Manage examinations and their settings</p>
            </div>
            <a href="<?= base_url('exam/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create New Exam
            </a>
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

<!-- Exam Statistics -->
<div class="row mb-4">
    <?php
    $totalExams = count($exams);
    $publishedExams = count(array_filter($exams, fn($e) => $e['status'] === 'published'));
    $draftExams = count(array_filter($exams, fn($e) => $e['status'] === 'draft'));
    $upcomingExams = count(array_filter($exams, fn($e) => strtotime($e['start_time']) > time()));
    ?>

    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $totalExams ?></h3>
            <p class="mb-0">Total Exams</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $publishedExams ?></h3>
            <p class="mb-0">Published</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $draftExams ?></h3>
            <p class="mb-0">Drafts</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $upcomingExams ?></h3>
            <p class="mb-0">Upcoming</p>
        </div>
    </div>
</div>

<!-- Exams Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">All Exams</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="searchExams" placeholder="Search exams...">
                            <span class="input-group-text"><i class="material-symbols-rounded" style="font-size: 18px;">search</i></span>
                        </div>
                        <select class="form-select" id="filterStatus" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($exams)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="examsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Exam</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Subject & Class</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Mode & Questions</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Schedule</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Status</th>
                                    <th class="border-0 fw-semibold" style="color: black !important;">Created By</th>
                                    <th class="border-0 fw-semibold text-center" style="color: black !important;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($exams as $exam): ?>
                                <tr data-status="<?= $exam['status'] ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="exam-avatar me-3">
                                                <i class="material-symbols-rounded" style="font-size: 24px;">quiz</i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($exam['title']) ?></h6>
                                                <small class="text-muted">
                                                    <?= $exam['duration_minutes'] ?> minutes • <?= $exam['total_marks'] ?> marks
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?= esc($exam['subject_name'] ?? 'Multiple Subjects') ?></span>
                                            <small class="text-muted d-block"><?= esc($exam['class_name']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-<?= $exam['exam_mode'] === 'single_subject' ? 'primary' : 'success' ?> mb-1">
                                                <?= $exam['exam_mode'] === 'single_subject' ? 'Single Subject' : 'Multi-Subject' ?>
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <?php if ($exam['questions_configured']): ?>
                                                    <i class="material-symbols-rounded text-success" style="font-size: 14px;">check_circle</i>
                                                    <?= $exam['total_questions'] ?> Questions Ready
                                                <?php else: ?>
                                                    <i class="material-symbols-rounded text-warning" style="font-size: 14px;">pending</i>
                                                    Questions Pending
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium"><?= date('M j, Y', strtotime($exam['start_time'])) ?></span>
                                            <small class="text-muted d-block"><?= date('g:i A', strtotime($exam['start_time'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="material-symbols-rounded me-2 status-<?= $exam['status'] ?>-text" style="font-size: 12px;">circle</i>
                                            <span class="status-<?= $exam['status'] ?>-text text-capitalize">
                                                <?= $exam['status'] ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($exam['first_name'])): ?>
                                            <span class="fw-medium"><?= esc($exam['first_name'] . ' ' . $exam['last_name']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Unknown</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/exam/' . $exam['id'] . '/questions') ?>"
                                               class="btn btn-outline-<?= $exam['questions_configured'] ? 'success' : 'warning' ?> btn-action"
                                               title="<?= $exam['questions_configured'] ? 'Manage Questions' : 'Configure Questions' ?>">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">quiz</i>
                                            </a>
                                            <a href="<?= base_url('admin/exam/edit/' . $exam['id']) ?>"
                                               class="btn btn-outline-primary btn-action" title="Edit Exam">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                                            </a>
                                            <a href="<?= base_url('admin/exam/view/' . $exam['id']) ?>"
                                               class="btn btn-outline-success btn-action" title="View Exam">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-action"
                                                    title="Delete Exam"
                                                    onclick="showDeleteModal(<?= $exam['id'] ?>, '<?= esc($exam['title']) ?>')">
                                                <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">quiz</i>
                        <h6 class="text-muted">No exams found</h6>
                        <p class="text-muted small">Start by creating your first exam</p>
                        <a href="<?= base_url('exam/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create First Exam
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">warning</i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the exam <strong id="deleteExamName"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Exam
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchExams');
    const statusFilter = document.getElementById('filterStatus');
    const table = document.getElementById('examsTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Modal variables
let currentExamId = null;

// Show delete modal
function showDeleteModal(examId, examName) {
    currentExamId = examId;
    document.getElementById('deleteExamName').textContent = examName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentExamId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = `<?= base_url('exam/delete/') ?>${currentExamId}`;
    }
});
</script>
<?= $this->endSection() ?>
