<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .form-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
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
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        color: white;
    }
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    /* Enhanced Form Control Visibility */
    .form-control, .form-select {
        color: #2d3748 !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 14px !important;
        font-weight: 400 !important;
        padding: 0.75rem 1rem !important;
        line-height: 1.5 !important;
        border-radius: 8px !important;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        color: #2d3748 !important;
        background-color: #ffffff !important;
        border-color: #A05AFF !important;
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25) !important;
        outline: none !important;
    }

    .form-control::placeholder {
        color: #a0aec0 !important;
        opacity: 1 !important;
    }

    /* Select dropdown styling */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
    }

    /* Invalid state */
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #e53e3e !important;
        color: #2d3748 !important;
    }

    .form-control.is-invalid:focus, .form-select.is-invalid:focus {
        border-color: #e53e3e !important;
        box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25) !important;
    }

    .invalid-feedback {
        display: block;
    }

    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }

    .class-checkbox {
        margin-bottom: 0.5rem;
    }

    .class-group {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
    }

    .class-group h6 {
        color: #A05AFF;
        font-weight: 600;
        margin-bottom: 0.75rem;
        border-bottom: 2px solid #A05AFF;
        padding-bottom: 0.25rem;
    }

    .form-check-input:checked {
        background-color: #A05AFF;
        border-color: #A05AFF;
    }

    .form-check-input:focus {
        border-color: #A05AFF;
        box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
    }

    /* Subject Selection Styles */
    .subject-selection-container {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        background-color: #f8f9fa;
    }

    .subject-group {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #ffffff;
    }

    .subject-group h6 {
        color: var(--primary-color);
        font-weight: 600;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.25rem;
    }

    .subject-checkbox {
        margin-bottom: 0.5rem;
    }

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.2;
        border-radius: 0.375rem;
    }

    /* Assignment Preview Styles */
    .assignment-preview-container {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        background-color: #f8f9fa;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .preview-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 0.5rem;
        margin-bottom: 0.25rem;
        border-radius: 6px;
        font-size: 0.875rem;
    }

    .preview-item.new-assignment {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .preview-item.existing-assignment {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }

    .preview-item.duplicate-warning {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .preview-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .badge-new {
        background-color: #28a745;
        color: white;
    }

    .badge-exists {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-duplicate {
        background-color: #dc3545;
        color: white;
    }

    .preview-summary {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 1rem;
    }

    .summary-stat {
        display: inline-block;
        margin-right: 1rem;
        font-size: 0.875rem;
    }

    .stat-number {
        font-weight: 600;
        font-size: 1.1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Bulk Assign Subjects to Classes</h4>
                <p class="text-muted mb-0">Select multiple subjects and assign them to one or more classes</p>
            </div>
            <a href="<?= base_url('admin/subject-assignments') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Assignments
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

<!-- Create Assignment Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">assignment</i>
                    Bulk Subject Assignment Information
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/subject-assignments/create', ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <!-- Subject Selection -->
                <div class="mb-4">
                    <label class="form-label">Subjects <span class="text-danger">*</span></label>
                    <?php if ($validation->hasError('subject_ids')): ?>
                        <div class="text-danger small mb-2"><?= $validation->getError('subject_ids') ?></div>
                    <?php endif; ?>

                    <?php
                    // Group subjects by category
                    $subjectGroups = [];
                    foreach ($subjects as $subject) {
                        $category = !empty($subject['category']) ? $subject['category'] : 'General';
                        if (!isset($subjectGroups[$category])) {
                            $subjectGroups[$category] = [];
                        }
                        $subjectGroups[$category][] = $subject;
                    }
                    ?>

                    <div class="subject-selection-container">
                        <!-- Select All Subjects Button -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="selectAllSubjects()">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">select_all</i>Select All Subjects
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectNoSubjects()">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">deselect</i>Clear All
                            </button>
                        </div>

                        <?php foreach ($subjectGroups as $category => $categorySubjects): ?>
                            <div class="subject-group">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><?= esc($category) ?> Subjects</h6>
                                    <div>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1"
                                                onclick="selectCategorySubjects('<?= esc($category) ?>')">
                                            Select All
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary"
                                                onclick="deselectCategorySubjects('<?= esc($category) ?>')">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php foreach ($categorySubjects as $subject): ?>
                                        <div class="col-md-6 subject-checkbox">
                                            <div class="form-check">
                                                <input class="form-check-input subject-checkbox-input" type="checkbox"
                                                       id="subject_<?= $subject['id'] ?>"
                                                       name="subject_ids[]"
                                                       value="<?= $subject['id'] ?>"
                                                       data-category="<?= esc($category) ?>"
                                                       <?= in_array($subject['id'], old('subject_ids', [])) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="subject_<?= $subject['id'] ?>">
                                                    <strong><?= esc($subject['name']) ?></strong>
                                                    <span class="text-muted">(<?= esc($subject['code']) ?>)</span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-text">Select one or more subjects to assign to classes</div>
                </div>

                <!-- Class Selection -->
                <div class="mb-4">
                    <label class="form-label">Classes <span class="text-danger">*</span></label>
                    <?php if ($validation->hasError('class_ids')): ?>
                        <div class="text-danger small mb-2"><?= $validation->getError('class_ids') ?></div>
                    <?php endif; ?>

                    <?php
                    // Group classes by level
                    $classGroups = [
                        'Primary' => [],
                        'JSS' => [],
                        'SSS' => [],
                        'Other' => []
                    ];

                    foreach ($classes as $class) {
                        $className = $class['name'];
                        if (strpos($className, 'Primary') !== false) {
                            $classGroups['Primary'][] = $class;
                        } elseif (strpos($className, 'JSS') !== false) {
                            $classGroups['JSS'][] = $class;
                        } elseif (strpos($className, 'SSS') !== false) {
                            $classGroups['SSS'][] = $class;
                        } else {
                            $classGroups['Other'][] = $class;
                        }
                    }
                    ?>

                    <?php foreach ($classGroups as $level => $levelClasses): ?>
                        <?php if (!empty($levelClasses)): ?>
                            <div class="class-group">
                                <h6><?= $level ?> Classes</h6>
                                <div class="row">
                                    <?php foreach ($levelClasses as $class): ?>
                                        <div class="col-md-6 class-checkbox">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       id="class_<?= $class['id'] ?>"
                                                       name="class_ids[]"
                                                       value="<?= $class['id'] ?>"
                                                       <?= in_array($class['id'], old('class_ids', [])) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="class_<?= $class['id'] ?>">
                                                    <strong><?= esc($class['name']) ?></strong>
                                                    <?php if (!empty($class['section'])): ?>
                                                        <span class="text-muted">- <?= esc($class['section']) ?></span>
                                                    <?php endif; ?>
                                                    <small class="text-muted d-block"><?= esc($class['academic_year']) ?></small>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Select All/None buttons for this group -->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2"
                                            onclick="selectAllInGroup('<?= strtolower($level) ?>')">
                                        Select All <?= $level ?>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            onclick="selectNoneInGroup('<?= strtolower($level) ?>')">
                                        Select None
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <div class="form-text">Select one or more classes where this subject will be taught</div>
                </div>

                <!-- Assignment Preview -->
                <div class="mb-4">
                    <div id="assignment-preview" class="assignment-preview-container" style="display: none;">
                        <h6 class="mb-3">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">preview</i>
                            Assignment Preview
                        </h6>
                        <div id="preview-content"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/subject-assignments') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create Bulk Assignments
                    </button>
                </div>

                <?= form_close() ?>
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

    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        // Check if at least one subject is selected
        const checkedSubjects = document.querySelectorAll('input[name="subject_ids[]"]:checked');
        if (checkedSubjects.length === 0) {
            event.preventDefault();
            event.stopPropagation();
            alert('Please select at least one subject.');
            return false;
        }

        // Check if at least one class is selected
        const checkedClasses = document.querySelectorAll('input[name="class_ids[]"]:checked');
        if (checkedClasses.length === 0) {
            event.preventDefault();
            event.stopPropagation();
            alert('Please select at least one class.');
            return false;
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Add event listeners for preview updates
    document.addEventListener('change', function(e) {
        if (e.target.name === 'subject_ids[]' || e.target.name === 'class_ids[]') {
            updateAssignmentPreview();
        }
    });
});

// Existing assignments data from PHP
const existingAssignments = <?= json_encode($existingAssignments) ?>;

// Subject and class data for preview
const subjectsData = <?= json_encode(array_column($subjects, null, 'id')) ?>;
const classesData = <?= json_encode(array_column($classes, null, 'id')) ?>;

// Subject selection functions
function selectAllSubjects() {
    const subjectCheckboxes = document.querySelectorAll('input[name="subject_ids[]"]');
    subjectCheckboxes.forEach(checkbox => checkbox.checked = true);
    updateAssignmentPreview();
}

function selectNoSubjects() {
    const subjectCheckboxes = document.querySelectorAll('input[name="subject_ids[]"]');
    subjectCheckboxes.forEach(checkbox => checkbox.checked = false);
    updateAssignmentPreview();
}

function selectCategorySubjects(category) {
    const categoryCheckboxes = document.querySelectorAll(`input[name="subject_ids[]"][data-category="${category}"]`);
    categoryCheckboxes.forEach(checkbox => checkbox.checked = true);
    updateAssignmentPreview();
}

function deselectCategorySubjects(category) {
    const categoryCheckboxes = document.querySelectorAll(`input[name="subject_ids[]"][data-category="${category}"]`);
    categoryCheckboxes.forEach(checkbox => checkbox.checked = false);
    updateAssignmentPreview();
}

// Class selection functions
function selectAllInGroup(level) {
    const group = document.querySelector(`.class-group h6:contains('${level.charAt(0).toUpperCase() + level.slice(1)}')`);
    if (!group) return;

    const groupContainer = group.closest('.class-group');
    const checkboxes = groupContainer.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function selectNoneInGroup(level) {
    const group = document.querySelector(`.class-group h6:contains('${level.charAt(0).toUpperCase() + level.slice(1)}')`);
    if (!group) return;

    const groupContainer = group.closest('.class-group');
    const checkboxes = groupContainer.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

// Fix for contains selector - use a more reliable method
function selectAllInGroup(level) {
    const headers = document.querySelectorAll('.class-group h6');
    let targetGroup = null;

    headers.forEach(header => {
        if (header.textContent.toLowerCase().includes(level.toLowerCase())) {
            targetGroup = header.closest('.class-group');
        }
    });

    if (targetGroup) {
        const checkboxes = targetGroup.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = true);
    }
    updateAssignmentPreview();
}

function selectNoneInGroup(level) {
    const headers = document.querySelectorAll('.class-group h6');
    let targetGroup = null;

    headers.forEach(header => {
        if (header.textContent.toLowerCase().includes(level.toLowerCase())) {
            targetGroup = header.closest('.class-group');
        }
    });

    if (targetGroup) {
        const checkboxes = targetGroup.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false);
    }
    updateAssignmentPreview();
}

// Assignment preview function
function updateAssignmentPreview() {
    const selectedSubjects = Array.from(document.querySelectorAll('input[name="subject_ids[]"]:checked')).map(cb => cb.value);
    const selectedClasses = Array.from(document.querySelectorAll('input[name="class_ids[]"]:checked')).map(cb => cb.value);

    const previewContainer = document.getElementById('assignment-preview');
    const previewContent = document.getElementById('preview-content');

    if (selectedSubjects.length === 0 || selectedClasses.length === 0) {
        previewContainer.style.display = 'none';
        return;
    }

    let newAssignments = 0;
    let existingAssignmentsCount = 0;
    let previewHTML = '';

    // Generate preview items
    selectedSubjects.forEach(subjectId => {
        const subject = subjectsData[subjectId];
        if (!subject) return;

        selectedClasses.forEach(classId => {
            const classData = classesData[classId];
            if (!classData) return;

            const assignmentKey = subjectId + '-' + classId;
            const exists = existingAssignments.hasOwnProperty(assignmentKey);

            if (exists) {
                existingAssignmentsCount++;
                previewHTML += `
                    <div class="preview-item existing-assignment">
                        <div class="flex-grow-1">
                            <strong>${subject.name}</strong> → <strong>${classData.name}</strong>
                            ${classData.section ? `(${classData.section})` : ''}
                        </div>
                        <span class="preview-badge badge-exists">Already Assigned</span>
                    </div>
                `;
            } else {
                newAssignments++;
                previewHTML += `
                    <div class="preview-item new-assignment">
                        <div class="flex-grow-1">
                            <strong>${subject.name}</strong> → <strong>${classData.name}</strong>
                            ${classData.section ? `(${classData.section})` : ''}
                        </div>
                        <span class="preview-badge badge-new">New Assignment</span>
                    </div>
                `;
            }
        });
    });

    // Add summary
    const totalAssignments = selectedSubjects.length * selectedClasses.length;
    previewHTML += `
        <div class="preview-summary">
            <div class="summary-stat">
                <span class="stat-number text-success">${newAssignments}</span> New Assignments
            </div>
            <div class="summary-stat">
                <span class="stat-number text-warning">${existingAssignmentsCount}</span> Already Exist
            </div>
            <div class="summary-stat">
                <span class="stat-number text-primary">${totalAssignments}</span> Total Selected
            </div>
        </div>
    `;

    previewContent.innerHTML = previewHTML;
    previewContainer.style.display = 'block';
}
</script>
<?= $this->endSection() ?>
