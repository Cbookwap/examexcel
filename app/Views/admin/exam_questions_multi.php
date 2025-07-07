<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .subject-card {
        border: 2px solid #e2e8f0;
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
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .btn-configure {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #A05AFF;
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
    }
    .subject-config-form {
        background: #f8fafc;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #e2e8f0;
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
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
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
                        <i class="material-symbols-rounded me-2" style="font-size: 24px;">library_books</i>
                        Multi-Subject Exam
                    </h5>
                    <p class="mb-1"><strong>Class:</strong> 
                        <?php 
                        $classModel = new \App\Models\ClassModel();
                        $class = $classModel->find($exam['class_id']);
                        echo $class['name'] ?? 'Unknown Class';
                        ?>
                    </p>
                    <p class="mb-0"><strong>Mode:</strong> Students take multiple subjects in one session with individual scoring</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex flex-column align-items-end">
                        <div class="mb-2">
                            <span class="badge bg-light text-dark fs-6">
                                <?= count($examSubjects) ?> Subjects Configured
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark fs-6">
                                <?= count($availableSubjects) ?> Available
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Selection and Configuration -->
        <form id="multiSubjectForm" method="POST">
            <?= csrf_field() ?>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #A05AFF;">subject</i>
                            Configure Exam Subjects
                        </h5>
                        <button type="submit" class="btn btn-configure">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>
                            Save Configuration
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($availableSubjects)): ?>
                        <div class="text-center py-5">
                            <i class="material-symbols-rounded text-muted mb-3" style="font-size: 64px;">subject</i>
                            <h5 class="text-muted">No Subjects Available</h5>
                            <p class="text-muted">No subjects are assigned to this class.</p>
                            <a href="<?= base_url('admin/subject-assignments') ?>" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">assignment</i>
                                Assign Subjects to Class
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($availableSubjects as $subject): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="subject-card p-3" data-subject-id="<?= $subject['id'] ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input subject-checkbox" type="checkbox" 
                                                       value="<?= $subject['id'] ?>" id="subject_<?= $subject['id'] ?>"
                                                       name="subjects[]">
                                                <label class="form-check-label fw-semibold" for="subject_<?= $subject['id'] ?>">
                                                    <?= esc($subject['name']) ?>
                                                </label>
                                            </div>
                                            <span class="badge bg-primary">
                                                <?= $subject['code'] ?>
                                            </span>
                                        </div>
                                        
                                        <?php if (!empty($subject['description'])): ?>
                                            <p class="text-muted small mb-2"><?= esc($subject['description']) ?></p>
                                        <?php endif; ?>
                                        
                                        <!-- Subject Configuration Form (Hidden by default) -->
                                        <div class="subject-config-form" id="config_<?= $subject['id'] ?>" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small fw-semibold">Questions</label>
                                                    <input type="number" class="form-control form-control-sm" 
                                                           name="subject_config[<?= $subject['id'] ?>][question_count]" 
                                                           placeholder="10" min="1" max="50">
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small fw-semibold">Marks</label>
                                                    <input type="number" class="form-control form-control-sm" 
                                                           name="subject_config[<?= $subject['id'] ?>][total_marks]" 
                                                           placeholder="30" min="1" max="100">
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small fw-semibold">Time (min)</label>
                                                    <input type="number" class="form-control form-control-sm" 
                                                           name="subject_config[<?= $subject['id'] ?>][time_allocation]" 
                                                           placeholder="30" min="5" max="180">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-outline-primary me-2" onclick="selectAllSubjects()">
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

        <!-- Currently Configured Subjects -->
        <?php if (!empty($examSubjects)): ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="material-symbols-rounded me-2" style="font-size: 20px; color: #28a745;">check_circle</i>
                            Currently Configured Subjects
                        </h5>
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
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
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
                                                <button type="button" class="btn btn-sm btn-primary"
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
            </div>
        <?php endif; ?>
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
    // Handle subject card clicks
    document.querySelectorAll('.subject-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox' && !e.target.closest('.subject-config-form')) {
                const checkbox = this.querySelector('.subject-checkbox');
                checkbox.checked = !checkbox.checked;
                toggleSubjectSelection(this, checkbox.checked);
            }
        });
    });
    
    // Handle checkbox changes
    document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.subject-card');
            toggleSubjectSelection(card, this.checked);
        });
    });
});

function toggleSubjectSelection(card, selected) {
    const configForm = card.querySelector('.subject-config-form');
    
    if (selected) {
        card.classList.add('selected');
        configForm.style.display = 'block';
        // Make config fields required
        configForm.querySelectorAll('input').forEach(input => {
            input.required = true;
        });
    } else {
        card.classList.remove('selected');
        configForm.style.display = 'none';
        // Remove required attribute
        configForm.querySelectorAll('input').forEach(input => {
            input.required = false;
            input.value = '';
        });
    }
}

function selectAllSubjects() {
    document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.checked = true;
        const card = checkbox.closest('.subject-card');
        toggleSubjectSelection(card, true);
    });
}

function clearSelection() {
    document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.checked = false;
        const card = checkbox.closest('.subject-card');
        toggleSubjectSelection(card, false);
    });
}

function manageSubjectQuestions(subjectId) {
    // Redirect to subject-specific question management
    window.location.href = `<?= base_url('admin/exam/' . $exam['id'] . '/subject/') ?>${subjectId}/questions`;
}

// Form validation
document.getElementById('multiSubjectForm').addEventListener('submit', function(e) {
    const selectedSubjects = document.querySelectorAll('.subject-checkbox:checked');
    
    if (selectedSubjects.length === 0) {
        e.preventDefault();
        showAlert('Please select at least one subject for the exam.', 'warning');
        return false;
    }
    
    // Validate configuration for each selected subject
    let isValid = true;
    selectedSubjects.forEach(checkbox => {
        const subjectId = checkbox.value;
        const configForm = document.getElementById(`config_${subjectId}`);
        const inputs = configForm.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            if (!input.value || input.value <= 0) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
    });
    
    if (!isValid) {
        e.preventDefault();
        showAlert('Please fill in all configuration fields for selected subjects.', 'warning');
        return false;
    }
});

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

// Reset exam subjects configuration
function resetExamSubjects() {
    // Show confirmation modal
    const resetModal = new bootstrap.Modal(document.getElementById('resetConfirmModal'));
    resetModal.show();
}

// Handle reset confirmation
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
            fetch(`<?= base_url('admin/exam/' . $exam['id'] . '/reset-subjects') ?>`, {
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
                    showAlert('Subject configuration has been reset successfully. The page will reload.', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showAlert(data.message || 'Failed to reset subject configuration. Please try again.', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while resetting the configuration. Please try again.', 'error');
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
            fetch(`<?= base_url('admin/exam/' . $exam['id'] . '/remove-subject/') ?>${subjectId}`, {
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
