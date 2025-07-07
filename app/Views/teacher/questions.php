<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .question-card {
        transition: transform 0.2s ease-in-out;
        border: 1px solid #e3e6f0;
    }
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .question-type-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    .difficulty-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    .question-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
        max-height: 4.2em;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div>
                <a href="<?= base_url('questions/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Question
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Questions Grid -->
<div class="row">
    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $question): ?>
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card question-card h-100">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <?php
                                $typeColors = [
                                    'mcq' => 'bg-primary',
                                    'true_false' => 'bg-success',
                                    'short_answer' => 'bg-info',
                                    'essay' => 'bg-warning',
                                    'fill_blank' => 'bg-secondary'
                                ];
                                $typeColor = $typeColors[$question['question_type']] ?? 'bg-secondary';
                                
                                $difficultyColors = [
                                    'easy' => 'bg-success',
                                    'medium' => 'bg-warning',
                                    'hard' => 'bg-danger'
                                ];
                                $difficultyColor = $difficultyColors[$question['difficulty']] ?? 'bg-secondary';
                                ?>
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge <?= $typeColor ?> question-type-badge">
                                        <?= ucfirst(str_replace('_', ' ', $question['question_type'])) ?>
                                    </span>
                                    <span class="badge <?= $difficultyColor ?> difficulty-badge">
                                        <?= ucfirst($question['difficulty']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="question-text">
                                <?= esc($question['question_text']) ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-book text-primary me-2"></i>
                                <span class="fw-medium"><?= esc($question['subject_name']) ?></span>
                            </div>
                            <?php if (!empty($question['class_name'])): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <span><?= esc($question['class_name']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($question['session_name']) && !empty($question['term_name'])): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <span><?= esc($question['session_name']) ?> - <?= esc($question['term_name']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold text-success"><?= $question['marks'] ?></span>
                                <small class="text-muted">marks</small>
                            </div>
                            <small class="text-muted">
                                <?= date('M j, Y', strtotime($question['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('questions/preview/' . $question['id']) ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Preview
                            </a>
                            <div>
                                <a href="<?= base_url('questions/edit/' . $question['id']) ?>" class="btn btn-outline-secondary btn-sm me-1">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteQuestion(<?= $question['id'] ?>)">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-question-circle text-muted mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mb-3">No Questions Created Yet</h5>
                    <p class="text-muted mb-4">Start building your question bank to create comprehensive exams for your students.</p>
                    <a href="<?= base_url('questions/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Your First Question
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
        window.location.href = '<?= base_url('questions/delete/') ?>' + questionId;
    }
}
</script>
<?= $this->endSection() ?>
