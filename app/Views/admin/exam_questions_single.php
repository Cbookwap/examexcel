<?= $this->extend('layouts/dashboard') ?>

<?php
// Create character limiter function for this view
if (!function_exists('character_limiter')) {
    function character_limiter($str, $limit = 500, $end_char = '...') {
        if (mb_strlen($str) <= $limit) {
            return $str;
        }
        return mb_substr($str, 0, $limit) . $end_char;
    }
}
?>

<?= $this->section('css') ?>
<style>
    .question-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .question-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15);
    }
    .question-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }
    .question-text {
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }
    .option-item {
        padding: 0.5rem 0.75rem;
        margin: 0.25rem 0;
        border-radius: 8px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }
    .option-item.correct {
        background-color: #dcfce7;
        border-color: #16a34a;
        color: #15803d;
    }
    .difficulty-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .btn-configure {
        background: linear-gradient(135deg, #A05AFF 0%, #8B47E6 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-configure:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(160, 90, 255, 0.3);
        color: white;
    }

    .subject-class-badges .badge {
        font-weight: 500;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-size: 0.85rem !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-1"><?= $pageSubtitle ?></p>
                <?php
                $subject = $subjectModel->find($exam['subject_id']);
                $classModel = new \App\Models\ClassModel();
                $class = $classModel->find($exam['class_id']);
                ?>
                <div class="d-flex align-items-center gap-3 mt-2 subject-class-badges">
                    <span class="badge bg-primary px-3 py-2">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">subject</i>
                        Subject: <?= $subject['name'] ?? 'Unknown Subject' ?>
                    </span>
                    <span class="badge bg-success px-3 py-2">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">school</i>
                        Class: <?= $class['name'] ?? 'Unknown Class' ?>
                    </span>
                </div>
            </div>
            <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Exams
            </a>
        </div>

        <!-- Exam Info Card -->
        <div class="stats-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2">
                        <i class="material-symbols-rounded me-2" style="font-size: 24px;">book</i>
                        Single Subject Exam
                    </h5>
                    <p class="mb-0"><strong>Mode:</strong> Questions from teachers assigned to this subject and class</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex flex-column align-items-end">
                        <div class="mb-2">
                            <span class="badge bg-light text-dark fs-6">
                                <?= count($selectedQuestions) ?> Questions Selected
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark fs-6">
                                <?= count($availableQuestions) ?> Available
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Selection Interface -->
        <form id="questionSelectionForm" method="POST">
            <?= csrf_field() ?>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">quiz</i>
                            Available Questions
                        </h5>
                        <button type="submit" class="btn btn-configure">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>
                            Save Selected Questions
                        </button>
                    </div>
                </div>
            <div class="card-body">
                <?php if (empty($availableQuestions)): ?>
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">quiz</i>
                        <h5 class="text-muted">No Questions Available</h5>
                        <p class="text-muted">No questions found for this subject and class combination.</p>
                        <a href="<?= base_url('questions/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>
                            Create Questions
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($availableQuestions as $question): ?>
                            <div class="col-md-6 mb-3">
                                <div class="question-card p-3" data-question-id="<?= $question['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input question-checkbox" type="checkbox"
                                                   name="selected_questions[]" value="<?= $question['id'] ?>"
                                                   id="question_<?= $question['id'] ?>"
                                                   <?= in_array($question['id'], $selectedQuestionIds ?? []) ? 'checked' : '' ?>>
                                            <label class="form-check-label fw-semibold" for="question_<?= $question['id'] ?>">
                                                Question #<?= $question['id'] ?>
                                            </label>
                                        </div>
                                        <span class="difficulty-badge bg-<?= \App\Models\QuestionModel::getDifficultyColor($question['difficulty']) ?>">
                                            <?= ucfirst($question['difficulty']) ?>
                                        </span>
                                    </div>

                                    <div class="question-text">
                                        <?= character_limiter(strip_tags($question['question_text']), 150) ?>
                                    </div>

                                    <?php if (!empty($question['options'])): ?>
                                        <div class="options-preview">
                                            <?php foreach (array_slice($question['options'], 0, 2) as $option): ?>
                                                <div class="option-item <?= $option['is_correct'] ? 'correct' : '' ?>">
                                                    <?= character_limiter($option['option_text'], 50) ?>
                                                    <?php if ($option['is_correct']): ?>
                                                        <i class="material-symbols-rounded float-end" style="font-size: 16px;">check</i>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php if (count($question['options']) > 2): ?>
                                                <small class="text-muted">... and <?= count($question['options']) - 2 ?> more options</small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">schedule</i>
                                            <b><?= $question['points'] ?> marks</b>
                                        </small>
                                        <small class="text-muted">
                                            Type: <?= \App\Models\QuestionModel::getTypeLabel($question['question_type']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-outline-primary me-2" onclick="selectAllQuestions()">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">select_all</i>
                            Select All
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">clear</i>
                            Clear Selection
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">quiz</i>
                    Confirm Question Configuration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="text-primary mb-3">
                        <i class="material-symbols-rounded" style="font-size: 48px;">help</i>
                    </div>
                    <p class="mb-0" id="confirmMessage">Are you sure you want to configure these questions for this exam?</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">close</i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">check</i>Yes, Configure
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" id="alertModalHeader">
                <h5 class="modal-title" id="alertModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;" id="alertModalIcon">info</i>
                    <span id="alertModalTitle">Information</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="alertMessage">Message content</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">check</i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize card selection states based on checked checkboxes
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        const card = checkbox.closest('.question-card');
        toggleCardSelection(card, checkbox.checked);
    });

    // Handle question card clicks
    document.querySelectorAll('.question-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('.question-checkbox');
                checkbox.checked = !checkbox.checked;
                toggleCardSelection(this, checkbox.checked);
            }
        });
    });

    // Handle checkbox changes
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.question-card');
            toggleCardSelection(card, this.checked);
        });
    });

    // Handle form submission
    document.getElementById('questionSelectionForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Always prevent default submission

        const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');

        if (selectedQuestions.length === 0) {
            showAlert('Please select at least one question for the exam.', 'warning');
            return false;
        }

        // Show confirmation modal
        const confirmMessage = document.getElementById('confirmMessage');
        confirmMessage.textContent = `Are you sure you want to configure ${selectedQuestions.length} questions for this exam?`;

        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });

    // Handle confirmation button click
    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        // Close the modal
        const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
        confirmModal.hide();

        // Submit the form
        document.getElementById('questionSelectionForm').submit();
    });
});

function toggleCardSelection(card, selected) {
    if (selected) {
        card.classList.add('selected');
    } else {
        card.classList.remove('selected');
    }
}

function selectAllQuestions() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = true;
        const card = checkbox.closest('.question-card');
        toggleCardSelection(card, true);
    });
}

function clearSelection() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = false;
        const card = checkbox.closest('.question-card');
        toggleCardSelection(card, false);
    });
}

function showAlert(message, type = 'info') {
    const alertModal = document.getElementById('alertModal');
    const alertModalHeader = document.getElementById('alertModalHeader');
    const alertModalIcon = document.getElementById('alertModalIcon');
    const alertModalTitle = document.getElementById('alertModalTitle');
    const alertMessage = document.getElementById('alertMessage');

    // Set message
    alertMessage.textContent = message;

    // Configure modal based on type
    switch (type) {
        case 'warning':
            alertModalHeader.className = 'modal-header bg-warning text-dark';
            alertModalIcon.textContent = 'warning';
            alertModalTitle.textContent = 'Warning';
            break;
        case 'error':
            alertModalHeader.className = 'modal-header bg-danger text-white';
            alertModalIcon.textContent = 'error';
            alertModalTitle.textContent = 'Error';
            break;
        case 'success':
            alertModalHeader.className = 'modal-header bg-success text-white';
            alertModalIcon.textContent = 'check_circle';
            alertModalTitle.textContent = 'Success';
            break;
        default:
            alertModalHeader.className = 'modal-header bg-primary text-white';
            alertModalIcon.textContent = 'info';
            alertModalTitle.textContent = 'Information';
    }

    // Show modal
    const modal = new bootstrap.Modal(alertModal);
    modal.show();
}
</script>
<?= $this->endSection() ?>
