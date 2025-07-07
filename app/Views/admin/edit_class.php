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

    /* Textarea specific styling */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Edit Class</h4>
                <p class="text-muted mb-0">Update class information</p>
            </div>
            <a href="<?= base_url('admin/classes') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Classes
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

<!-- Edit Class Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">edit</i>
                    Edit Class: <?= esc($class['name']) ?> <?= esc($class['section']) ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/classes/edit/' . $class['id'], ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <div class="row">
                    <!-- Class Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Class Name <span class="text-danger">*</span></label>
                        <div class="mb-2">
                            <select class="form-select" id="classNameSelect" onchange="handleClassNameSelection()">
                                <option value="">Select from predefined classes</option>
                                <optgroup label="Primary School">
                                    <option value="Primary 1" <?= (old('name') ?: $class['name']) == 'Primary 1' ? 'selected' : '' ?>>Primary 1</option>
                                    <option value="Primary 2" <?= (old('name') ?: $class['name']) == 'Primary 2' ? 'selected' : '' ?>>Primary 2</option>
                                    <option value="Primary 3" <?= (old('name') ?: $class['name']) == 'Primary 3' ? 'selected' : '' ?>>Primary 3</option>
                                    <option value="Primary 4" <?= (old('name') ?: $class['name']) == 'Primary 4' ? 'selected' : '' ?>>Primary 4</option>
                                    <option value="Primary 5" <?= (old('name') ?: $class['name']) == 'Primary 5' ? 'selected' : '' ?>>Primary 5</option>
                                    <option value="Primary 6" <?= (old('name') ?: $class['name']) == 'Primary 6' ? 'selected' : '' ?>>Primary 6</option>
                                </optgroup>
                                <optgroup label="Junior Secondary">
                                    <option value="JSS 1" <?= (old('name') ?: $class['name']) == 'JSS 1' ? 'selected' : '' ?>>JSS 1</option>
                                    <option value="JSS 2" <?= (old('name') ?: $class['name']) == 'JSS 2' ? 'selected' : '' ?>>JSS 2</option>
                                    <option value="JSS 3" <?= (old('name') ?: $class['name']) == 'JSS 3' ? 'selected' : '' ?>>JSS 3</option>
                                </optgroup>
                                <optgroup label="Senior Secondary">
                                    <option value="SS 1" <?= (old('name') ?: $class['name']) == 'SS 1' ? 'selected' : '' ?>>SS 1</option>
                                    <option value="SS 2" <?= (old('name') ?: $class['name']) == 'SS 2' ? 'selected' : '' ?>>SS 2</option>
                                    <option value="SS 3" <?= (old('name') ?: $class['name']) == 'SS 3' ? 'selected' : '' ?>>SS 3</option>
                                </optgroup>
                                <option value="custom">✏️ Create Custom Class Name</option>
                            </select>
                        </div>
                        <input type="text" class="form-control <?= $validation->hasError('name') ? 'is-invalid' : '' ?>"
                               id="name" name="name"
                               value="<?= old('name') ?: $class['name'] ?>"
                               placeholder="Enter class name or select from dropdown above" required>
                        <small class="text-muted">You can select from predefined classes above or type your own custom class name</small>
                        <?php if ($validation->hasError('name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Section -->
                    <div class="col-md-6 mb-3">
                        <label for="section" class="form-label">Section/Stream</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                            <optgroup label="Primary/JSS Sections">
                                <option value="A" <?= (old('section') ?: $class['section']) == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= (old('section') ?: $class['section']) == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="C" <?= (old('section') ?: $class['section']) == 'C' ? 'selected' : '' ?>>C</option>
                            </optgroup>
                            <optgroup label="SSS Streams">
                                <option value="Science" <?= (old('section') ?: $class['section']) == 'Science' ? 'selected' : '' ?>>Science</option>
                                <option value="Arts" <?= (old('section') ?: $class['section']) == 'Arts' ? 'selected' : '' ?>>Arts</option>
                                <option value="Commercial" <?= (old('section') ?: $class['section']) == 'Commercial' ? 'selected' : '' ?>>Commercial</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Academic Year -->
                    <div class="col-md-6 mb-3">
                        <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('academic_year') ? 'is-invalid' : '' ?>"
                               id="academic_year" name="academic_year"
                               value="<?= old('academic_year') ?: $class['academic_year'] ?>"
                               placeholder="e.g., 2024/2025" required>
                        <?php if ($validation->hasError('academic_year')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('academic_year') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Maximum Students -->
                    <div class="col-md-6 mb-3">
                        <label for="max_students" class="form-label">Maximum Students <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?= $validation->hasError('max_students') ? 'is-invalid' : '' ?>"
                               id="max_students" name="max_students"
                               value="<?= old('max_students') ?: $class['max_students'] ?>"
                               min="1" max="100" required>
                        <?php if ($validation->hasError('max_students')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('max_students') ?></div>
                        <?php endif; ?>
                    </div>
                </div>



                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="Optional description for this class"><?= old('description') ?: $class['description'] ?></textarea>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               <?= (old('is_active') !== null ? old('is_active') : $class['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            <strong>Active Class</strong>
                            <small class="text-muted d-block">Students can be enrolled and exams can be conducted</small>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/classes') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Update Class
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
// Handle class name selection from dropdown
function handleClassNameSelection() {
    const select = document.getElementById('classNameSelect');
    const input = document.getElementById('name');

    if (select.value === 'custom') {
        input.value = '';
        input.focus();
        input.placeholder = 'Enter your custom class name';
    } else if (select.value !== '') {
        input.value = select.value;
        input.placeholder = 'Enter class name or select from dropdown above';
    }
}

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
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Allow manual typing to clear the dropdown selection
    document.getElementById('name').addEventListener('input', function() {
        const select = document.getElementById('classNameSelect');
        const input = this;

        // If user is typing and the value doesn't match any option, reset dropdown
        if (input.value !== select.value && select.value !== 'custom') {
            select.value = '';
        }
    });

    // Initialize dropdown selection based on current value
    const currentName = document.getElementById('name').value;
    const select = document.getElementById('classNameSelect');

    // Check if current name matches any predefined option
    let foundMatch = false;
    for (let option of select.options) {
        if (option.value === currentName) {
            select.value = currentName;
            foundMatch = true;
            break;
        }
    }

    // If no match found and there's a value, it's a custom name
    if (!foundMatch && currentName) {
        select.value = '';
    }
});
</script>
<?= $this->endSection() ?>
