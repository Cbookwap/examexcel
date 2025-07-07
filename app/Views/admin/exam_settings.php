<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .settings-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .settings-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
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
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }
    .exam-types-list .list-group-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .exam-types-list .list-group-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .exam-types-list .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle !important;
    }
    .section-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1rem 1.5rem;
        margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        border-radius: 15px 15px 0 0;
    }
    .section-header h5 {
        margin: 0;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">CBT Exam Settings</h4>
                <p class="text-muted mb-0">Create assessment types and CBT examination preferences</p>
            </div>
            <a href="<?= base_url('admin/settings') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to General Settings
            </a>
        </div>
    </div>
</div>

<!-- Assessment Structure Overview -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card settings-card">
            <div class="card-body p-4">
                <div class="section-header">
                    <h5 class="mb-0 text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">assessment</i>
                        Assessment Structure Overview
                    </h5>
                </div>
                <p class="text-muted mb-3">Configure assessment structure to match what is in use in your school</p>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="material-symbols-rounded text-info mb-2" style="font-size: 32px;">quiz</i>
                                <h6 class="card-title">Continuous Assessment</h6>
                                <p class="card-text small text-muted">Tests and class assessments</p>
                                <div id="caTypesCount" class="badge bg-info">Loading...</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="material-symbols-rounded text-warning mb-2" style="font-size: 32px;">school</i>
                                <h6 class="card-title">Main Examinations</h6>
                                <p class="card-text small text-muted">Terminal and major exams</p>
                                <div id="examTypesCount" class="badge bg-warning">Loading...</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-secondary">
                            <div class="card-body text-center">
                                <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 32px;">fitness_center</i>
                                <h6 class="card-title">Practice Assessments</h6>
                                <p class="card-text small text-muted">Practice tests and drills</p>
                                <div id="practiceTypesCount" class="badge bg-secondary">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Types Management -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card settings-card">
            <div class="card-body p-4">
                <div class="section-header">
                    <h5 class="mb-0 text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">quiz</i>
                        Assessment Types Management
                    </h5>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="mb-0 text-muted">Create and manage assessment types with flexible marks allocation</p>
                        <small class="text-info">
                            <i class="material-symbols-rounded me-1" style="font-size: 14px;">info</i>
                            Configure total marks for each assessment type (e.g., 30 marks for tests, 70 marks for exams)
                        </small>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExamTypeModal">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add Assessment Type
                    </button>
                </div>

                <div class="exam-types-list" id="examTypesList">
                    <!-- Exam types will be loaded here via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading exam types...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Preferences -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card settings-card">
            <div class="card-body p-4">
                <div class="section-header">
                    <h5 class="mb-0 text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">settings</i>
                        Default Exam Settings
                    </h5>
                </div>

                <form id="examPreferencesForm" method="POST" action="<?= base_url('admin/exam-settings/update-preferences') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Default Exam Duration (minutes)</label>
                        <input type="number" name="default_exam_duration" class="form-control"
                               value="<?= old('default_exam_duration', $settings['default_exam_duration'] ?? 80) ?>"
                               min="1" max="600">
                        <small class="text-muted">Default duration for new exams</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default Max Attempts</label>
                        <input type="number" name="default_max_attempts" class="form-control"
                               value="<?= old('default_max_attempts', $settings['default_max_attempts'] ?? 5) ?>"
                               min="1" max="100">
                        <small class="text-muted">Default number of attempts students can make (1-100)</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="auto_submit_on_time_up"
                                   id="autoSubmit" value="1"
                                   <?= ($settings['auto_submit_on_time_up'] ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="autoSubmit">
                                Auto-submit on Time Up
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="calculator_enabled"
                                   id="calculatorEnabled" value="1"
                                   <?= ($settings['calculator_enabled'] ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="calculatorEnabled">
                                Enable Calculator for Students
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="exam_pause_enabled"
                                   id="examPauseEnabled" value="1"
                                   <?= ($settings['exam_pause_enabled'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="examPauseEnabled">
                                Allow Students to Pause Exams
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Save Preferences
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card settings-card">
            <div class="card-body p-4">
                <div class="section-header">
                    <h5 class="mb-0 text-white">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">security</i>
                        Security Settings
                    </h5>
                </div>

                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="material-symbols-rounded me-3" style="font-size: 2rem;">info</i>
                        <div>
                            <h6 class="mb-1">Security Settings Moved</h6>
                            <p class="mb-2">All exam security features have been moved to the dedicated Security Settings page for better organization.</p>
                            <a href="<?= base_url('admin/security/settings') ?>" class="btn btn-primary btn-sm">
                                <i class="material-symbols-rounded me-2" style="font-size: 16px;">security</i>
                                Configure Security Settings
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Security Features Available:</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1">
                            <i class="material-symbols-rounded me-2 text-success" style="font-size: 16px;">check_circle</i>
                            Browser Lockdown & Tab Switching Detection
                        </li>
                        <li class="mb-1">
                            <i class="material-symbols-rounded me-2 text-success" style="font-size: 16px;">check_circle</i>
                            Copy/Paste Prevention & Right-Click Blocking
                        </li>
                        <li class="mb-1">
                            <i class="material-symbols-rounded me-2 text-success" style="font-size: 16px;">check_circle</i>
                            Fullscreen Mode & Proctoring
                        </li>
                        <li class="mb-1">
                            <i class="material-symbols-rounded me-2 text-success" style="font-size: 16px;">check_circle</i>
                            Violation Limits & Auto-Submit Settings
                        </li>
                        <li class="mb-1">
                            <i class="material-symbols-rounded me-2 text-success" style="font-size: 16px;">check_circle</i>
                            Strict Security Mode & Advanced Features
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Exam Type Modal -->
<div class="modal fade" id="addExamTypeModal" tabindex="-1" aria-labelledby="addExamTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExamTypeModalLabel">Add Assessment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="examTypeForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="examTypeName" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="examTypeName" name="name" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="examTypeCode" class="form-label">Code *</label>
                        <input type="text" class="form-control" id="examTypeCode" name="code" required maxlength="20"
                               style="text-transform: uppercase;" placeholder="e.g., FIRST_CA">
                        <small class="text-muted">Unique identifier (uppercase, underscores allowed)</small>
                    </div>
                    <div class="mb-3">
                        <label for="examTypeDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="examTypeDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="examTypeTotalMarks" class="form-label">Default Total Marks</label>
                        <input type="number" class="form-control" id="examTypeTotalMarks" name="default_total_marks"
                               min="1" max="1000" placeholder="100" required>
                        <small class="text-muted">Default total marks for this assessment type</small>
                    </div>
                    <div class="mb-3">
                        <label for="assessmentCategory" class="form-label">Assessment Category</label>
                        <select class="form-select" id="assessmentCategory" name="assessment_category" required>
                            <option value="">Select Category</option>
                            <option value="continuous_assessment">Continuous Assessment (Tests/CAs)</option>
                            <option value="main_examination">Main Examination</option>
                            <option value="practice">Practice Assessment</option>
                        </select>
                        <small class="text-muted">Choose the category that best describes this assessment type</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">save</i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Exam Type Modal -->
<div class="modal fade" id="editExamTypeModal" tabindex="-1" aria-labelledby="editExamTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExamTypeModalLabel">Edit Assessment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExamTypeForm">
                <?= csrf_field() ?>
                <input type="hidden" id="editExamTypeId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editExamTypeName" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="editExamTypeName" name="name" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="editExamTypeCode" class="form-label">Code *</label>
                        <input type="text" class="form-control" id="editExamTypeCode" name="code" required maxlength="20"
                               style="text-transform: uppercase;" readonly>
                        <small class="text-muted">Code cannot be changed after creation</small>
                    </div>
                    <div class="mb-3">
                        <label for="editExamTypeDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editExamTypeDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editExamTypeTotalMarks" class="form-label">Default Total Marks</label>
                        <input type="number" class="form-control" id="editExamTypeTotalMarks" name="default_total_marks"
                               min="1" max="1000" required>
                        <small class="text-muted">Default total marks for this assessment type</small>
                    </div>
                    <div class="mb-3">
                        <label for="editAssessmentCategory" class="form-label">Assessment Category</label>
                        <select class="form-select" id="editAssessmentCategory" name="assessment_category" required>
                            <option value="">Select Category</option>
                            <option value="continuous_assessment">Continuous Assessment (Tests/CAs)</option>
                            <option value="main_examination">Main Examination</option>
                            <option value="practice">Practice Assessment</option>
                        </select>
                        <small class="text-muted">Choose the category that best describes this assessment type</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">save</i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load exam types on page load
    loadExamTypes();

    // Add exam type form submission
    document.getElementById('examTypeForm').addEventListener('submit', function(e) {
        console.log('Form submission event triggered!');
        e.preventDefault();

        const formData = new FormData(this);

        // Debug: Log form data
        console.log('Form data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        // Add loading state to button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">hourglass_empty</i>Saving...';
        submitBtn.disabled = true;

        fetch('<?= base_url('admin/exam-types/add') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            // Try to get response text first to see what we're getting
            return response.text().then(text => {
                console.log('Raw response:', text);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                }

                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error(`Invalid JSON response: ${text}`);
                }
            });
        })
        .then(data => {
            console.log('Parsed response data:', data);
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addExamTypeModal')).hide();
                this.reset();
                loadExamTypes();
                showAlert('Assessment type added successfully!', 'success');
            } else {
                showAlert(data.message || 'Failed to add exam type', 'danger');
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while adding exam type: ' + error.message, 'danger');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Edit exam type form submission
    document.getElementById('editExamTypeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const id = document.getElementById('editExamTypeId').value;

        // Add CSRF token
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        // Add loading state to button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">hourglass_empty</i>Updating...';
        submitBtn.disabled = true;

        fetch(`<?= base_url('admin/exam-types/update/') ?>${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editExamTypeModal')).hide();
                loadExamTypes();
                showAlert('Assessment type updated successfully!', 'success');
            } else {
                showAlert(data.message || 'Failed to update exam type', 'danger');
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating exam type: ' + error.message, 'danger');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});

// Helper function to show alerts (global scope)
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);

    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Exam Types Management Functions
function loadExamTypes() {
    fetch('<?= base_url('admin/exam-types/list') ?>', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON - user might not be logged in');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayExamTypes(data.examTypes);
                updateOverviewCards(data.examTypes);
            } else {
                console.error('Failed to load exam types:', data.message);
                document.getElementById('examTypesList').innerHTML = '<p class="text-danger">Failed to load exam types: ' + data.message + '</p>';
            }
        })
        .catch(error => {
            console.error('Error loading exam types:', error);
            if (error.message.includes('not JSON')) {
                document.getElementById('examTypesList').innerHTML = '<div class="alert alert-warning"><i class="material-symbols-rounded me-2">warning</i>Session expired. Please <a href="' + '<?= base_url('auth/login') ?>' + '">login again</a> to continue.</div>';
            } else {
                document.getElementById('examTypesList').innerHTML = '<p class="text-danger">Error loading exam types. Please refresh the page and try again.</p>';
            }
        });
}

function displayExamTypes(examTypes) {
    const container = document.getElementById('examTypesList');
    if (examTypes.length === 0) {
        container.innerHTML = '<p class="text-muted mb-0">No exam types configured yet.</p>';
        return;
    }

    let html = '<div class="list-group">';
    examTypes.forEach(type => {
        const statusBadge = type.is_active == 1
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';

        const categoryLabel = {
            'continuous_assessment': 'Continuous Assessment',
            'main_examination': 'Main Examination',
            'practice': 'Practice Assessment'
        }[type.assessment_category] || type.assessment_category;

        const categoryBadge = {
            'continuous_assessment': 'bg-info',
            'main_examination': 'bg-warning',
            'practice': 'bg-secondary'
        }[type.assessment_category] || 'bg-secondary';

        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${type.name}</h6>
                    <small class="text-muted">Code: ${type.code}</small>
                    ${type.description ? `<br><small class="text-muted">${type.description}</small>` : ''}
                    <br><small class="text-success">Total Marks: ${type.default_total_marks}</small>
                    <span class="badge ${categoryBadge} ms-2" style="font-size: 10px;">${categoryLabel}</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    ${statusBadge}
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editExamType(${type.id})">
                            <i class="material-symbols-rounded" style="font-size: 14px;">edit</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-${type.is_active == 1 ? 'warning' : 'success'}"
                                onclick="toggleExamTypeStatus(${type.id}, ${type.is_active})">
                            <i class="material-symbols-rounded" style="font-size: 14px;">${type.is_active == 1 ? 'visibility_off' : 'visibility'}</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteExamType(${type.id})">
                            <i class="material-symbols-rounded" style="font-size: 14px;">delete</i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

// Update overview cards with counts
function updateOverviewCards(examTypes) {
    const caCount = examTypes.filter(type => type.assessment_category === 'continuous_assessment' && type.is_active == 1).length;
    const examCount = examTypes.filter(type => type.assessment_category === 'main_examination' && type.is_active == 1).length;
    const practiceCount = examTypes.filter(type => type.assessment_category === 'practice' && type.is_active == 1).length;

    document.getElementById('caTypesCount').textContent = `${caCount} type${caCount !== 1 ? 's' : ''}`;
    document.getElementById('examTypesCount').textContent = `${examCount} type${examCount !== 1 ? 's' : ''}`;
    document.getElementById('practiceTypesCount').textContent = `${practiceCount} type${practiceCount !== 1 ? 's' : ''}`;
}

// Global functions for exam type management
function editExamType(id) {
    fetch(`<?= base_url('admin/exam-types/get/') ?>${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const type = data.examType;
                document.getElementById('editExamTypeId').value = type.id;
                document.getElementById('editExamTypeName').value = type.name;
                document.getElementById('editExamTypeCode').value = type.code;
                document.getElementById('editExamTypeDescription').value = type.description || '';
                document.getElementById('editExamTypeTotalMarks').value = type.default_total_marks || 100;
                document.getElementById('editAssessmentCategory').value = type.assessment_category || 'continuous_assessment';

                new bootstrap.Modal(document.getElementById('editExamTypeModal')).show();
            } else {
                showAlert('Failed to load exam type details', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while loading exam type details', 'danger');
        });
}

function toggleExamTypeStatus(id, currentStatus) {
    const action = currentStatus == 1 ? 'deactivate' : 'activate';
    const actionTitle = action.charAt(0).toUpperCase() + action.slice(1);

    showConfirmModal(
        `${actionTitle} Assessment Type`,
        `Are you sure you want to ${action} this assessment type?`,
        'warning',
        () => {
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch(`<?= base_url('admin/exam-types/toggle/') ?>${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    loadExamTypes();
                    showAlert(`Assessment type ${action}d successfully!`, 'success');
                } else {
                    showAlert(data.message || `Failed to ${action} assessment type`, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(`An error occurred while ${action}ing assessment type: ` + error.message, 'danger');
            });
        }
    );
}

function deleteExamType(id) {
    showConfirmModal(
        'Delete Assessment Type',
        'Are you sure you want to delete this assessment type? This action cannot be undone.',
        'danger',
        () => {
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            formData.append('_method', 'DELETE');

            fetch(`<?= base_url('admin/exam-types/delete/') ?>${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    loadExamTypes();
                    showAlert('Assessment type deleted successfully!', 'success');
                } else {
                    showAlert(data.message || 'Failed to delete assessment type', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while deleting assessment type: ' + error.message, 'danger');
            });
        }
    );
}

// Enhanced confirm modal function
function showConfirmModal(title, message, type = 'warning', onConfirm = null) {
    const typeColors = {
        'danger': 'text-danger',
        'warning': 'text-warning',
        'info': 'text-info',
        'success': 'text-success'
    };

    const typeIcons = {
        'danger': 'delete_forever',
        'warning': 'warning',
        'info': 'info',
        'success': 'check_circle'
    };

    const buttonColors = {
        'danger': 'btn-danger',
        'warning': 'btn-warning',
        'info': 'btn-info',
        'success': 'btn-success'
    };

    const modalId = 'confirmModal_' + Date.now();
    const modalHtml = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title ${typeColors[type]}">
                            <i class="material-symbols-rounded me-2">${typeIcons[type]}</i>
                            ${title}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="mb-0">${message}</p>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn ${buttonColors[type]}" id="confirmBtn_${modalId}">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">${typeIcons[type]}</i>
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove any existing confirm modals
    document.querySelectorAll('[id^="confirmModal_"]').forEach(modal => modal.remove());

    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const modal = new bootstrap.Modal(document.getElementById(modalId));
    const confirmBtn = document.getElementById(`confirmBtn_${modalId}`);

    // Handle confirm button click
    confirmBtn.addEventListener('click', function() {
        modal.hide();
        if (onConfirm && typeof onConfirm === 'function') {
            onConfirm();
        }
    });

    // Clean up modal after it's hidden
    document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
        this.remove();
    });

    modal.show();
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>