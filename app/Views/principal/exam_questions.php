<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .question-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
    }
    .question-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15);
    }
    .question-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }
    .subject-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
    }
    .subject-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15);
    }
    .subject-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }
    .difficulty-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
    }
    .selection-counter {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        background: var(--primary-color);
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: none;
    }
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;"><?= $pageTitle ?></h4>
                <p class="text-light mb-0"><?= $pageSubtitle ?></p>
            </div>
            <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Exam
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

<?php if ($examMode === 'single_subject'): ?>
    <!-- Single Subject Question Management -->
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1 fw-bold">
                    <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">quiz</i>
                    Select Questions for <?= esc($exam['subject_name']) ?>
                </h5>
                <p class="text-muted mb-0">Choose questions to include in this exam</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">select_all</i>Select All
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">clear</i>Clear All
                </button>
            </div>
        </div>

        <form method="POST" id="questionSelectionForm">
            <?= csrf_field() ?>
            
            <?php if (empty($availableQuestions)): ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">quiz</i>
                    <h5 class="text-muted">No Questions Available</h5>
                    <p class="text-muted">No questions found for this subject and class combination.</p>
                    <a href="<?= base_url('questions/create') ?>" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create Questions
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
                                               name="questions[]" value="<?= $question['id'] ?>"
                                               id="question_<?= $question['id'] ?>"
                                               <?= in_array($question['id'], array_column($selectedQuestions, 'question_id')) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold" for="question_<?= $question['id'] ?>">
                                            Question #<?= $question['id'] ?>
                                        </label>
                                    </div>
                                    <span class="difficulty-badge bg-<?= $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($question['difficulty']) ?>
                                    </span>
                                </div>
                                
                                <div class="question-content mb-3">
                                    <p class="mb-2"><?= character_limiter(strip_tags($question['question_text']), 100) ?></p>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span>
                                        <i class="material-symbols-rounded me-1" style="font-size: 14px;">grade</i>
                                        <?= $question['marks'] ?> marks
                                    </span>
                                    <span>
                                        <i class="material-symbols-rounded me-1" style="font-size: 14px;">schedule</i>
                                        <?= $question['time_limit'] ?> min
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Save Questions
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>

<?php else: ?>
    <!-- Multi-Subject Question Management -->
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1 fw-bold">
                    <i class="material-symbols-rounded me-2" style="color: var(--primary-color); font-size: 20px;">library_books</i>
                    Configure Multi-Subject Exam
                </h5>
                <p class="text-muted mb-0">Select subjects and configure question counts for each</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">clear</i>Clear All
                </button>
            </div>
        </div>

        <form method="POST" id="multiSubjectForm">
            <?= csrf_field() ?>
            
            <div class="row">
                <?php foreach ($subjects as $subject): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="subject-card p-4" data-subject-id="<?= $subject['id'] ?>">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="form-check">
                                    <input class="form-check-input subject-checkbox" type="checkbox"
                                           id="subject_<?= $subject['id'] ?>" value="<?= $subject['id'] ?>">
                                    <label class="form-check-label fw-semibold" for="subject_<?= $subject['id'] ?>">
                                        <?= esc($subject['name']) ?>
                                    </label>
                                </div>
                                <span class="badge bg-primary"><?= esc($subject['code']) ?></span>
                            </div>
                            
                            <div class="subject-config-form" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label small">Number of Questions</label>
                                    <input type="number" class="form-control form-control-sm" 
                                           name="subjects[<?= $subject['id'] ?>][question_count]" 
                                           min="1" max="50" value="10">
                                    <input type="hidden" name="subjects[<?= $subject['id'] ?>][subject_id]" value="<?= $subject['id'] ?>">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label small">Marks per Question</label>
                                        <input type="number" class="form-control form-control-sm" 
                                               name="subjects[<?= $subject['id'] ?>][marks_per_question]" 
                                               min="0.5" max="10" step="0.5" value="1">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">Time per Question (min)</label>
                                        <input type="number" class="form-control form-control-sm" 
                                               name="subjects[<?= $subject['id'] ?>][time_per_question]" 
                                               min="0.5" max="10" step="0.5" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="<?= base_url('principal/exams/view/' . $exam['id']) ?>" class="btn btn-outline-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Configure Exam
                </button>
            </div>
        </form>
    </div>

    <!-- Currently Configured Subjects -->
    <?php if (!empty($examSubjects)): ?>
        <div class="info-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1 fw-bold">
                        <i class="material-symbols-rounded me-2" style="color: #28a745; font-size: 20px;">check_circle</i>
                        Currently Configured Subjects
                    </h5>
                    <p class="text-muted mb-0">Manage questions for each configured subject</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="resetExamSubjects()" title="Reset all configured subjects">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">refresh</i>
                        Reset Configuration
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearAllSubjects()" title="Clear all configured subjects">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">clear_all</i>
                        Clear All
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr style="color: white;">
                            <th>Subject</th>
                            <th>Questions</th>
                            <th>Total Marks</th>
                            <th>Time Allocation</th>
                            <th>Questions Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examSubjects as $examSubject): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($examSubject['subject_name']) ?></strong>
                                    <br><small class="text-muted"><?= $examSubject['subject_code'] ?></small>
                                </td>
                                <td><?= $examSubject['question_count'] ?></td>
                                <td><?= $examSubject['total_marks'] ?></td>
                                <td><?= $examSubject['time_allocation'] ?> min</td>
                                <td>
                                    <span class="badge bg-<?= $examSubject['configured_questions'] > 0 ? 'success' : 'warning' ?>">
                                        <?= $examSubject['configured_questions'] ?> / <?= $examSubject['question_count'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm"
                                                style="background-color: var(--primary-color); color: white; border: none;"
                                                onclick="manageSubjectQuestions(<?= $examSubject['subject_id'] ?>)">
                                            <i class="material-symbols-rounded" style="font-size: 16px;">edit</i>
                                            Manage Questions
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="removeExamSubject(<?= $examSubject['subject_id'] ?>, '<?= esc($examSubject['subject_name']) ?>')"
                                                title="Remove this subject from exam">
                                            <i class="material-symbols-rounded" style="font-size: 16px;">remove</i>
                                            Remove
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Selection Counter -->
<div class="selection-counter" id="selectionCounter">
    <span id="selectedCount">0</span> questions selected
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetConfirmModal" tabindex="-1" aria-labelledby="resetConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="resetConfirmModalLabel">
                    <i class="material-symbols-rounded me-2 text-warning">warning</i>
                    Confirm Reset Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="material-symbols-rounded me-2" style="font-size: 24px;">info</i>
                    <div>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                </div>
                <p class="mb-3">Are you sure you want to reset the subject configuration?</p>
                <p class="text-muted small mb-0">
                    This will permanently remove:
                </p>
                <ul class="text-muted small mt-2">
                    <li>All configured subjects and their question assignments</li>
                    <li>All selected questions for this exam</li>
                    <li>Current exam totals and configuration status</li>
                </ul>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">close</i>
                    Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmResetBtn">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">refresh</i>
                    Reset Configuration
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Remove Subject Confirmation Modal -->
<div class="modal fade" id="removeSubjectModal" tabindex="-1" aria-labelledby="removeSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="removeSubjectModalLabel">
                    <i class="material-symbols-rounded me-2 text-danger">warning</i>
                    Remove Subject from Exam
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="material-symbols-rounded me-2" style="font-size: 24px;">info</i>
                    <div>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                </div>
                <p class="mb-3">Are you sure you want to remove <strong id="subjectToRemove"></strong> from this exam?</p>
                <p class="text-muted small mb-0">
                    This will permanently remove:
                </p>
                <ul class="text-muted small mt-2">
                    <li>The subject configuration from this exam</li>
                    <li>All questions assigned to this subject for this exam</li>
                    <li>The subject's contribution to exam totals</li>
                </ul>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">close</i>
                    Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmRemoveSubjectBtn">
                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">remove</i>
                    Remove Subject
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($examMode === 'single_subject'): ?>
        // Single subject question management
        document.querySelectorAll('.question-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('.question-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        updateSelection();
                    }
                }
            });
        });
        
        document.querySelectorAll('.question-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelection);
        });

        function updateSelection() {
            const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
            const counter = document.getElementById('selectionCounter');
            const countSpan = document.getElementById('selectedCount');
            
            countSpan.textContent = selectedQuestions.length;
            
            if (selectedQuestions.length > 0) {
                counter.style.display = 'block';
            } else {
                counter.style.display = 'none';
            }
            
            // Update card styles
            document.querySelectorAll('.question-card').forEach(card => {
                const checkbox = card.querySelector('.question-checkbox');
                if (checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
        }

        // Form submission
        document.getElementById('questionSelectionForm').addEventListener('submit', function(e) {
            const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
            
            if (selectedQuestions.length === 0) {
                e.preventDefault();
                alert('Please select at least one question for the exam.');
                return false;
            }
        });

        // Initialize selection display
        updateSelection();

    <?php else: ?>
        // Multi-subject exam management
        document.querySelectorAll('.subject-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox' && !e.target.closest('.subject-config-form')) {
                    const checkbox = this.querySelector('.subject-checkbox');
                    checkbox.checked = !checkbox.checked;
                    toggleSubjectSelection(this, checkbox.checked);
                }
            });
        });
        
        document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const card = this.closest('.subject-card');
                toggleSubjectSelection(card, this.checked);
            });
        });

        function toggleSubjectSelection(card, isSelected) {
            const configForm = card.querySelector('.subject-config-form');
            const inputs = configForm.querySelectorAll('input');
            
            if (isSelected) {
                card.classList.add('selected');
                configForm.style.display = 'block';
                inputs.forEach(input => input.disabled = false);
            } else {
                card.classList.remove('selected');
                configForm.style.display = 'none';
                inputs.forEach(input => input.disabled = true);
            }
        }

        // Form submission
        document.getElementById('multiSubjectForm').addEventListener('submit', function(e) {
            const selectedSubjects = document.querySelectorAll('.subject-checkbox:checked');
            
            if (selectedSubjects.length === 0) {
                e.preventDefault();
                alert('Please select at least one subject for the exam.');
                return false;
            }
        });
    <?php endif; ?>
});

function selectAll() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    <?php if ($examMode === 'single_subject'): ?>
    updateSelection();
    <?php endif; ?>
}

function clearSelection() {
    <?php if ($examMode === 'single_subject'): ?>
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
    <?php else: ?>
    document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.checked = false;
        const card = checkbox.closest('.subject-card');
        toggleSubjectSelection(card, false);
    });
    <?php endif; ?>
}

function manageSubjectQuestions(subjectId) {
    // Redirect to subject-specific question management
    window.location.href = `<?= base_url('principal/exams/' . $exam['id'] . '/subject/') ?>${subjectId}/questions`;
}

// Reset exam subjects configuration
function resetExamSubjects() {
    // Show confirmation modal
    const resetModal = new bootstrap.Modal(document.getElementById('resetConfirmModal'));
    resetModal.show();
}

// Handle reset confirmation for principal
document.addEventListener('DOMContentLoaded', function() {
    const confirmResetBtn = document.getElementById('confirmResetBtn');
    if (confirmResetBtn) {
        confirmResetBtn.addEventListener('click', function() {
            // Hide the modal
            const resetModal = bootstrap.Modal.getInstance(document.getElementById('resetConfirmModal'));
            resetModal.hide();

            // Show loading state on the confirm button
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">hourglass_empty</i>Resetting...';
            this.disabled = true;

            // Make AJAX request to reset subjects
            fetch(`<?= base_url('principal/exams/' . $exam['id'] . '/reset-subjects') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message and reload
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    successAlert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    successAlert.innerHTML = `
                        <i class="material-symbols-rounded me-2">check_circle</i>
                        Subject configuration has been reset successfully. The page will reload.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(successAlert);

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Show error message
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                    errorAlert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    errorAlert.innerHTML = `
                        <i class="material-symbols-rounded me-2">error</i>
                        ${data.message || 'Failed to reset subject configuration. Please try again.'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(errorAlert);

                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                errorAlert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                errorAlert.innerHTML = `
                    <i class="material-symbols-rounded me-2">error</i>
                    An error occurred while resetting the configuration. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(errorAlert);

                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
});

// Clear all subjects (alternative to reset)
function clearAllSubjects() {
    // Use the same modal as reset since functionality is identical
    resetExamSubjects();
}

// Remove individual subject from exam
function removeExamSubject(subjectId, subjectName) {
    // Set subject name in modal
    document.getElementById('subjectToRemove').textContent = subjectName;

    // Store subject ID for confirmation
    document.getElementById('confirmRemoveSubjectBtn').setAttribute('data-subject-id', subjectId);

    // Show confirmation modal
    const removeModal = new bootstrap.Modal(document.getElementById('removeSubjectModal'));
    removeModal.show();
}

// Handle remove subject confirmation
document.addEventListener('DOMContentLoaded', function() {
    const confirmRemoveBtn = document.getElementById('confirmRemoveSubjectBtn');
    if (confirmRemoveBtn) {
        confirmRemoveBtn.addEventListener('click', function() {
            const subjectId = this.getAttribute('data-subject-id');
            const originalText = this.innerHTML;

            // Show loading state
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Removing...';
            this.disabled = true;

            // Make AJAX request to remove subject
            fetch(`<?= base_url('principal/exams/' . $exam['id'] . '/remove-subject/') ?>${subjectId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('removeSubjectModal'));
                    modal.hide();

                    // Show success message and reload page
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Failed to remove subject', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while removing the subject. Please try again.', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
