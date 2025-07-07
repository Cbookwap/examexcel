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

    .session-badge {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
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
    .info-card {
        
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Create Teacher Subject-Class Assignment</h4>
                <p class="text-muted mb-0">Assign a teacher to a specific subject and class</p>
            </div>
            <a href="<?= base_url('admin/assignments') ?>" class="btn btn-primary">
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

<!-- Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="info-card btn-primary">
            <div class="d-flex align-items-center">
                <i class="material-symbols-rounded me-3" style="font-size: 32px;">info</i>
                <div>
                    <h6 class="mb-1">Purpose of Assignment</h6>
                    <p class="mb-0 small">
                        Here, subject teachers are assigned to specific class-subject combinations.
                        This allows teachers to create questions only for their assigned subjects and classes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Assignment Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">assignment_ind</i>
                    Assignment Details
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/assignments/create', ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <!-- Academic Session -->
                <div class="mb-3">
                    <label for="session_id" class="form-label">Academic Session <span class="text-danger">*</span></label>
                    <select class="form-select <?= $validation->hasError('session_id') ? 'is-invalid' : '' ?>"
                            id="session_id" name="session_id" required>
                        <option value="">Select Academic Session</option>
                        <?php foreach ($sessions as $session): ?>
                            <option value="<?= $session['id'] ?>"
                                    <?= (old('session_id') ?: ($currentSession['id'] ?? '')) == $session['id'] ? 'selected' : '' ?>>
                                <?= esc($session['session_name']) ?>
                                <?= $session['is_current'] ? ' (Current)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($validation->hasError('session_id')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('session_id') ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <!-- Teacher -->
                    <div class="col-md-6 mb-3">
                        <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                        <select class="form-select <?= $validation->hasError('teacher_id') ? 'is-invalid' : '' ?>"
                                id="teacher_id" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['id'] ?>" <?= old('teacher_id') == $teacher['id'] ? 'selected' : '' ?>>
                                    <?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('teacher_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('teacher_id') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Subject -->
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select class="form-select <?= $validation->hasError('subject_id') ? 'is-invalid' : '' ?>"
                                id="subject_id" name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>" <?= old('subject_id') == $subject['id'] ? 'selected' : '' ?>>
                                    <?= esc($subject['name']) ?> (<?= esc($subject['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('subject_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('subject_id') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Class -->
                <div class="mb-3">
                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                    <select class="form-select <?= $validation->hasError('class_id') ? 'is-invalid' : '' ?>"
                            id="class_id" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= old('class_id') == $class['id'] ? 'selected' : '' ?>>
                                <?= esc($class['name']) ?>
                                <?php if (!empty($class['section'])): ?>
                                    - <?= esc($class['section']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($validation->hasError('class_id')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('class_id') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Assignment Summary -->
                <div class="alert alert-light border" id="assignmentSummary" style="display: none;">
                    <h6 class="mb-2">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">assignment</i>
                        Assignment Summary
                    </h6>
                    <p class="mb-0" id="summaryText"></p>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= base_url('admin/assignments') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create Assignment
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
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Assignment summary
    const teacherSelect = document.getElementById('teacher_id');
    const subjectSelect = document.getElementById('subject_id');
    const classSelect = document.getElementById('class_id');
    const sessionSelect = document.getElementById('session_id');
    const summaryDiv = document.getElementById('assignmentSummary');
    const summaryText = document.getElementById('summaryText');

    function updateSummary() {
        const teacher = teacherSelect.options[teacherSelect.selectedIndex]?.text;
        const subject = subjectSelect.options[subjectSelect.selectedIndex]?.text;
        const className = classSelect.options[classSelect.selectedIndex]?.text;
        const session = sessionSelect.options[sessionSelect.selectedIndex]?.text;

        if (teacher && subject && className && session) {
            summaryText.innerHTML = `
                <strong>${teacher}</strong> will be assigned to teach
                <strong>${subject}</strong> for <strong>${className}</strong>
                in the <strong>${session}</strong> academic session.
            `;
            summaryDiv.style.display = 'block';
        } else {
            summaryDiv.style.display = 'none';
        }
    }

    teacherSelect.addEventListener('change', updateSummary);
    subjectSelect.addEventListener('change', updateSummary);
    classSelect.addEventListener('change', updateSummary);
    sessionSelect.addEventListener('change', updateSummary);

    // Initial summary update
    updateSummary();
});
</script>
<?= $this->endSection() ?>
