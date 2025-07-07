<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<style>
/* Fix notification text visibility - FORCE WHITE TEXT */
.alert-info small,
.alert-info small *,
.alert-info small strong,
.alert-info small em,
.alert-info .text-muted,
.alert-info .text-white,
.alert-info .row small,
.alert-info .row small *,
.alert-info .col-md-6 small,
.alert-info .col-md-6 small *,
.alert-info .col-md-6 small strong,
.alert-info .col-md-6 small em,
.alert-info div small,
.alert-info div small *,
.alert-info div small strong,
.alert-info div small em {
    color: white !important;
    opacity: 1 !important;
}

.alert-info code,
.alert-info .col-md-6 code,
.alert-info small code {
    background-color: rgba(0,0,0,0.4) !important;
    color: white !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    opacity: 1 !important;
}

.form-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.form-section {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 0.5rem;
    font-size: 1.25rem;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}

.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
    padding: 0.75rem 2rem;
    font-weight: 500;
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

.btn-secondary {
    padding: 0.75rem 2rem;
    font-weight: 500;
}

.required {
    color: #dc3545;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .form-card {
        margin: 0 -15px;
        border-radius: 0;
    }
}
</style>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;">Create New Class</h4>
                <p class="text-light mb-0">Add a new class to the system</p>
            </div>
            <a href="<?= base_url('principal/classes') ?>" class="btn btn-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Classes
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">error</i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Class Teacher Info Notice - CUSTOM NOTIFICATION -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 25px; position: relative;">
    <div style="display: flex; align-items: flex-start;">
        <div style="margin-right: 15px; margin-top: 5px;">
            <i class="material-symbols-rounded" style="font-size: 24px; color: white;">info</i>
        </div>
        <div style="flex: 1;">
            <h6 style="color: white; font-weight: 600; margin-bottom: 10px; font-size: 1.1rem;">
                <i class="material-symbols-rounded" style="font-size: 18px; margin-right: 8px; color: white;">account_circle</i>
                Automatic Class Teacher Account Creation
            </h6>
            <p style="color: white; margin-bottom: 15px; font-size: 1rem;">
                When you create a new class, a <strong style="color: white;">Class Teacher account</strong> will be automatically created for this class.
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 250px;">
                    <div style="color: white; font-size: 0.875rem;">
                        <strong style="color: white;">Username:</strong> <span style="color: white;">Auto-generated from class name</span><br>
                        <em style="color: white;">Example: "JSS 1" → "JSS-ONE"</em>
                    </div>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <div style="color: white; font-size: 0.875rem;">
                        <strong style="color: white;">Default Password:</strong> <span style="background-color: rgba(0,0,0,0.3); color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace; border: 1px solid rgba(255,255,255,0.3);">class123</span><br>
                        <em style="color: white;">You can change this later in Class Management</em>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.style.display='none'" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 5px; position: absolute; top: 15px; right: 15px;">&times;</button>
    </div>
</div>

<!-- Create Class Form -->
<div class="card form-card">
    <div class="card-body p-4">
        <form method="POST" action="<?= base_url('principal/classes/create') ?>">
            <?= csrf_field() ?>
            
            <!-- Basic Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">school</i>
                    Basic Information
                </h5>
                
                <div class="row">
                    <!-- Class Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Class Name <span class="required">*</span></label>
                        <div class="mb-2">
                            <select class="form-select" id="classNameSelect" onchange="handleClassNameSelection()">
                                <option value="">Select from predefined classes</option>
                                <optgroup label="Primary School">
                                    <option value="Primary 1">Primary 1</option>
                                    <option value="Primary 2">Primary 2</option>
                                    <option value="Primary 3">Primary 3</option>
                                    <option value="Primary 4">Primary 4</option>
                                    <option value="Primary 5">Primary 5</option>
                                    <option value="Primary 6">Primary 6</option>
                                </optgroup>
                                <optgroup label="Junior Secondary">
                                    <option value="JSS 1">JSS 1</option>
                                    <option value="JSS 2">JSS 2</option>
                                    <option value="JSS 3">JSS 3</option>
                                </optgroup>
                                <optgroup label="Senior Secondary">
                                    <option value="SS 1">SS 1</option>
                                    <option value="SS 2">SS 2</option>
                                    <option value="SS 3">SS 3</option>
                                </optgroup>
                                <option value="custom">✏️ Create Custom Class Name</option>
                            </select>
                        </div>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?= old('name') ?>" required
                               placeholder="Enter class name or select from dropdown above">
                        <small class="form-text text-muted">You can select from predefined classes above or type your own custom class name</small>
                        <?php if (isset($validation) && $validation->hasError('name')): ?>
                            <div class="text-danger small"><?= $validation->getError('name') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Section -->
                    <div class="col-md-6 mb-3">
                        <label for="section" class="form-label">Section/Stream</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                            <optgroup label="Primary/JSS Sections">
                                <option value="A" <?= old('section') == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= old('section') == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="C" <?= old('section') == 'C' ? 'selected' : '' ?>>C</option>
                            </optgroup>
                            <optgroup label="SS Streams">
                                <option value="Science" <?= old('section') == 'Science' ? 'selected' : '' ?>>Science</option>
                                <option value="Arts" <?= old('section') == 'Arts' ? 'selected' : '' ?>>Arts</option>
                                <option value="Commercial" <?= old('section') == 'Commercial' ? 'selected' : '' ?>>Commercial</option>
                            </optgroup>
                        </select>
                        <div class="form-text">Optional: Specify section if class has multiple sections</div>
                        <?php if (isset($validation) && $validation->hasError('section')): ?>
                            <div class="text-danger small"><?= $validation->getError('section') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="academic_year" class="form-label">Academic Year <span class="required">*</span></label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year" 
                               value="<?= old('academic_year', date('Y') . '/' . (date('Y') + 1)) ?>" required 
                               placeholder="e.g., 2024/2025">
                        <?php if (isset($validation) && $validation->hasError('academic_year')): ?>
                            <div class="text-danger small"><?= $validation->getError('academic_year') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="max_students" class="form-label">Maximum Students <span class="required">*</span></label>
                        <input type="number" class="form-control" id="max_students" name="max_students" 
                               value="<?= old('max_students', '40') ?>" required min="1" max="200">
                        <div class="form-text">Maximum number of students that can be enrolled in this class</div>
                        <?php if (isset($validation) && $validation->hasError('max_students')): ?>
                            <div class="text-danger small"><?= $validation->getError('max_students') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" 
                              placeholder="Optional description about the class"><?= old('description') ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('description')): ?>
                        <div class="text-danger small"><?= $validation->getError('description') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">settings</i>
                    Class Settings
                </h5>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" <?= old('is_active', '1') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Class</strong>
                            </label>
                            <div class="form-text">Active classes are available for student enrollment and exam assignments</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-3">
                <a href="<?= base_url('principal/classes') ?>" class="btn btn-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create Class
                </button>
            </div>
        </form>
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
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Remove invalid class on input
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Auto-generate academic year
    const academicYearInput = document.getElementById('academic_year');
    if (!academicYearInput.value) {
        const currentYear = new Date().getFullYear();
        const nextYear = currentYear + 1;
        academicYearInput.value = currentYear + '/' + nextYear;
    }

    // Allow manual typing to clear the dropdown selection
    document.getElementById('name').addEventListener('input', function() {
        const select = document.getElementById('classNameSelect');
        const input = this;

        // If user is typing and the value doesn't match any option, reset dropdown
        if (input.value !== select.value && select.value !== 'custom') {
            select.value = '';
        }
    });
});
</script>
<?= $this->endSection() ?>
