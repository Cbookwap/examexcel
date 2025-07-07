<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-item {
        text-align: center;
        padding: 1rem;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    .question-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        margin-bottom: 1rem;
    }
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .difficulty-badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
    }
    .difficulty-easy {
        background: #d4edda;
        color: #155724;
    }
    .difficulty-medium {
        background: #fff3cd;
        color: #856404;
    }
    .difficulty-hard {
        background: #f8d7da;
        color: #721c24;
    }
    .category-badge {
        background: var(--primary-color);
        color: white;
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
        font-weight: 500;
        min-width: 70px;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        color: white !important;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        transform: translateY(-1px);
        color: white !important;
    }
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white !important;
    }
    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
        transform: translateY(-1px);
        color: white !important;
    }
    .d-flex.gap-2 {
        gap: 0.5rem !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-2 fw-bold"><?= $pageTitle ?></h2>
        <p class="mb-0 text-muted"><?= $pageSubtitle ?></p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= base_url('admin/practice-questions/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Question
        </a>
    </div>
</div>

<!-- Statistics Overview -->
<?php if ($stats['total_questions'] > 0): ?>
<div class="stats-card">
    <div class="row">
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total_questions'] ?></span>
                <span class="stat-label">Total Questions</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <span class="stat-number"><?= count($stats['categories']) ?></span>
                <span class="stat-label">Categories</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <span class="stat-number">
                    <?php
                    $easyCount = 0;
                    foreach ($stats['difficulty_breakdown'] as $diff) {
                        if ($diff['difficulty'] === 'easy') $easyCount = $diff['count'];
                    }
                    echo $easyCount;
                    ?>
                </span>
                <span class="stat-label">Easy Questions</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <span class="stat-number">
                    <?php
                    $hardCount = 0;
                    foreach ($stats['difficulty_breakdown'] as $diff) {
                        if ($diff['difficulty'] === 'hard') $hardCount = $diff['count'];
                    }
                    echo $hardCount;
                    ?>
                </span>
                <span class="stat-label">Hard Questions</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($stats['categories'] as $cat): ?>
                                <option value="<?= esc($cat['category']) ?>" <?= $currentCategory === $cat['category'] ? 'selected' : '' ?>>
                                    <?= esc($cat['category']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="<?= base_url('admin/practice-questions') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Practice Questions List -->
<?php if (!empty($practiceQuestions)): ?>
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <?php foreach ($practiceQuestions as $question): ?>
                    <div class="question-card border-0 shadow-none border-bottom">
                        <div class="card-body p-3">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex gap-2 mb-2">
                                        <span class="category-badge"><?= esc($question['category']) ?></span>
                                        <span class="difficulty-badge difficulty-<?= $question['difficulty'] ?>">
                                            <?= ucfirst($question['difficulty']) ?>
                                        </span>
                                        <span class="badge bg-secondary"><?= $question['points'] ?> mark(s)</span>
                                    </div>
                                    <h6 class="mb-2 fw-semibold"><?= esc($question['question_text']) ?></h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">A. <?= esc($question['option_a']) ?></small><br>
                                            <small class="text-muted">B. <?= esc($question['option_b']) ?></small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">C. <?= esc($question['option_c']) ?></small><br>
                                            <small class="text-muted">D. <?= esc($question['option_d']) ?></small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-success fw-semibold">
                                            <i class="fas fa-check me-1"></i>Correct Answer: <?= $question['correct_answer'] ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('M j, Y', strtotime($question['created_at'])) ?>
                                    </small>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= base_url('admin/practice-questions/edit/' . $question['id']) ?>"
                                           class="btn btn-primary btn-sm" title="Edit Question">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm"
                                                onclick="deleteQuestion(<?= $question['id'] ?>)" title="Delete Question">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($pager): ?>
<div class="row mt-4">
    <div class="col-12">
        <?= $pager->links() ?>
    </div>
</div>
<?php endif; ?>

<?php else: ?>
<div class="empty-state">
    <i class="fas fa-question-circle text-muted"></i>
    <h5>No Practice Questions Found</h5>
    <p>Create your first practice question to get started.</p>
    <div class="mt-3">
        <a href="<?= base_url('admin/practice-questions/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create First Question
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Generate Sample Questions Modal Removed - Questions are pre-loaded via migration -->

<!-- Delete Question Modal -->
<div class="modal fade" id="deleteQuestionModal" tabindex="-1" aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title" id="deleteQuestionModalLabel">
                    <i class="fas fa-trash me-2"></i>Delete Question
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5>Are you sure?</h5>
                    <p class="text-muted">This action cannot be undone. The practice question will be permanently deleted.</p>
                </div>
                <div class="alert alert-danger">
                    <small><i class="fas fa-info-circle me-1"></i>
                    Students will no longer see this question in their practice tests.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Delete Question
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let questionToDelete = null;

function deleteQuestion(id) {
    questionToDelete = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteQuestionModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!questionToDelete) return;

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteQuestionModal'));
    modal.hide();

    fetch(`<?= base_url('admin/practice-questions/delete/') ?>${questionToDelete}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            CBT.showToast('Question deleted successfully', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            CBT.showToast(data.message || 'Failed to delete question', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        CBT.showToast('An error occurred while deleting the question', 'error');
    });

    questionToDelete = null;
});

document.addEventListener('DOMContentLoaded', function() {
    const questionCount = <?= $stats['total_questions'] ?>;
    if (questionCount > 0) {
        CBT.showToast(`You have ${questionCount} practice questions available.`, 'info');
    }
});
</script>
<?= $this->endSection() ?>
