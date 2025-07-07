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

    /* Disabled state */
    .form-control:disabled, .form-select:disabled {
        background-color: #f7fafc !important;
        color: #a0aec0 !important;
        border-color: #e2e8f0 !important;
        opacity: 1 !important;
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

    /* Valid state */
    .form-control.is-valid, .form-select.is-valid {
        border-color: #38a169 !important;
        color: #2d3748 !important;
    }

    .form-control.is-valid:focus, .form-select.is-valid:focus {
        border-color: #38a169 !important;
        box-shadow: 0 0 0 0.2rem rgba(56, 161, 105, 0.25) !important;
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
    .role-specific-fields {
        display: none;
    }
    .role-specific-fields.show {
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Edit User</h4>
                <p class="text-muted mb-0">Update user information</p>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Users
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="material-symbols-rounded me-2" style="font-size: 18px;">error</i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Edit User Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">edit</i>
                    Edit User: <?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/users/edit/' . $user['id'], ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('first_name') ? 'is-invalid' : '' ?>"
                               id="first_name" name="first_name" value="<?= old('first_name', $user['first_name']) ?>" required>
                        <?php if ($validation->hasError('first_name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('last_name') ? 'is-invalid' : '' ?>"
                               id="last_name" name="last_name" value="<?= old('last_name', $user['last_name']) ?>" required>
                        <?php if ($validation->hasError('last_name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6" id="username-field">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('username') ? 'is-invalid' : '' ?>"
                               id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                        <?php if ($validation->hasError('username')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
                        <?php endif; ?>
                    </div>
                    <!-- Hidden username field for students (will be populated with student_id) -->
                    <input type="hidden" id="username-hidden" name="username_hidden" value="<?= old('username', $user['username']) ?>" style="display: none;">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                               id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                        <?php if ($validation->hasError('email')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : '' ?>"
                               id="password" name="password">
                        <?php if ($validation->hasError('password')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select <?= $validation->hasError('role') ? 'is-invalid' : '' ?>"
                                id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="teacher" <?= old('role', $user['role']) === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                            <option value="student" <?= old('role', $user['role']) === 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="principal" <?= old('role', $user['role']) === 'principal' ? 'selected' : '' ?>>Principal</option>
                        </select>
                        <?php if ($validation->hasError('role')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Title field for Principal role -->
                <div id="title-field" class="role-specific-fields mb-4">
                    <h6 class="fw-semibold mb-3 text-primary">Principal Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <select class="form-select <?= $validation->hasError('title') ? 'is-invalid' : '' ?>"
                                    id="title" name="title">
                                <option value="">Select Title</option>
                                <option value="Principal" <?= old('title', $user['title'] ?? '') === 'Principal' ? 'selected' : '' ?>>Principal</option>
                                <option value="Vice Principal" <?= old('title', $user['title'] ?? '') === 'Vice Principal' ? 'selected' : '' ?>>Vice Principal</option>
                                <option value="Assistant Principal" <?= old('title', $user['title'] ?? '') === 'Assistant Principal' ? 'selected' : '' ?>>Assistant Principal</option>
                                <option value="Head of Department" <?= old('title', $user['title'] ?? '') === 'Head of Department' ? 'selected' : '' ?>>Head of Department (HOD)</option>
                                <option value="Academic Director" <?= old('title', $user['title'] ?? '') === 'Academic Director' ? 'selected' : '' ?>>Academic Director</option>
                                <option value="Dean" <?= old('title', $user['title'] ?? '') === 'Dean' ? 'selected' : '' ?>>Dean</option>
                                <option value="Director" <?= old('title', $user['title'] ?? '') === 'Director' ? 'selected' : '' ?>>Director</option>
                            </select>
                            <small class="text-muted">Select the official title for this principal role</small>
                            <?php if ($validation->hasError('title')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('title') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="custom_title" class="form-label">Custom Title</label>
                            <input type="text" class="form-control" id="custom_title" name="custom_title"
                                   value="<?= old('custom_title', (!in_array($user['title'] ?? '', ['Principal', 'Vice Principal', 'Assistant Principal', 'Head of Department', 'Academic Director', 'Dean', 'Director']) && !empty($user['title'])) ? $user['title'] : '') ?>"
                                   placeholder="Enter custom title if not listed above">
                            <small class="text-muted">Leave blank if using predefined title above</small>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone', $user['phone']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" <?= old('gender', $user['gender']) === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= old('gender', $user['gender']) === 'female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $user['address']) ?></textarea>
                </div>

                <!-- Teacher-specific fields -->
                <div id="teacher-fields" class="role-specific-fields">
                    <h6 class="fw-semibold mb-3 text-primary">Teacher Information</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" value="<?= old('employee_id', $user['employee_id']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" value="<?= old('department', $user['department']) ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control" id="qualification" name="qualification" value="<?= old('qualification', $user['qualification']) ?>">
                    </div>
                </div>

                <!-- Student-specific fields -->
                <div id="student-fields" class="role-specific-fields">
                    <h6 class="fw-semibold mb-3 text-primary">Student Information</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_id" name="student_id"
                                   value="<?= old('student_id', $user['student_id']) ?>"
                                   <?= !empty($user['student_id']) ? 'readonly' : '' ?> required>
                            <?php if (!empty($user['student_id'])): ?>
                                <small class="text-muted">Student ID cannot be changed once assigned</small>
                            <?php else: ?>
                                <small class="text-muted">Student ID will be auto-generated if empty</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" id="class_id" name="class_id">
                                <option value="">Select Class</option>
                                <?php if (isset($classes) && !empty($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= (old('class_id', $user['class_id']) == $class['id']) ? 'selected' : '' ?>>
                                            <?= esc($class['name']) ?><?= !empty($class['section']) ? ' - ' . esc($class['section']) : '' ?> (<?= esc($class['academic_year']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No classes available - Please create classes first</option>
                                <?php endif; ?>
                            </select>
                            <?php if (!isset($classes) || empty($classes)): ?>
                                <small class="text-muted mt-1">
                                    <i class="material-symbols-rounded" style="font-size: 14px;">info</i>
                                    No classes found. <a href="<?= base_url('admin/classes/create') ?>" class="text-primary">Create a class first</a>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Status Settings -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   <?= old('is_active', $user['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active User
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1"
                                   <?= old('is_verified', $user['is_verified']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_verified">
                                Verified User
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Update User
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

    // Role-specific fields toggle
    const roleSelect = document.getElementById('role');
    const teacherFields = document.getElementById('teacher-fields');
    const studentFields = document.getElementById('student-fields');
    const titleField = document.getElementById('title-field');

    function toggleRoleFields() {
        const selectedRole = roleSelect.value;
        const classField = document.getElementById('class_id');
        const studentIdField = document.getElementById('student_id');
        const usernameField = document.getElementById('username-field');
        const usernameInput = document.getElementById('username');

        // Hide all role-specific fields
        teacherFields.classList.remove('show');
        studentFields.classList.remove('show');
        titleField.classList.remove('show');

        // Remove required attributes
        classField.removeAttribute('required');
        studentIdField.removeAttribute('required');
        const titleSelect = document.getElementById('title');
        titleSelect.removeAttribute('required');

        // Show relevant fields based on role
        if (selectedRole === 'teacher') {
            teacherFields.classList.add('show');
            // Show username field for teachers
            usernameField.style.display = 'block';
            usernameInput.setAttribute('required', 'required');
            usernameInput.disabled = false; // Enable visible username input
            usernameInput.name = 'username'; // Set correct name for submission

            // Disable hidden username field for non-students
            const hiddenUsernameInput = document.getElementById('username-hidden');
            hiddenUsernameInput.disabled = true;
            hiddenUsernameInput.name = 'username_hidden'; // Change name so it's not submitted
        } else if (selectedRole === 'student') {
            studentFields.classList.add('show');
            // Hide visible username field for students and enable hidden one
            usernameField.style.display = 'none';
            usernameInput.removeAttribute('required');
            usernameInput.disabled = true; // Disable visible username input
            usernameInput.name = 'username_disabled'; // Change name so it's not submitted

            // Enable hidden username field for students and sync with student_id
            const hiddenUsernameInput = document.getElementById('username-hidden');
            hiddenUsernameInput.disabled = false;
            hiddenUsernameInput.name = 'username'; // Set correct name for submission
            hiddenUsernameInput.value = studentIdField.value; // Sync with current student_id

            // Make class and student ID required for students
            classField.setAttribute('required', 'required');
            studentIdField.setAttribute('required', 'required');
        } else if (selectedRole === 'admin') {
            // Show username field for admins
            usernameField.style.display = 'block';
            usernameInput.setAttribute('required', 'required');
            usernameInput.disabled = false; // Enable visible username input
            usernameInput.name = 'username'; // Set correct name for submission

            // Disable hidden username field for non-students
            const hiddenUsernameInput = document.getElementById('username-hidden');
            hiddenUsernameInput.disabled = true;
            hiddenUsernameInput.name = 'username_hidden'; // Change name so it's not submitted
        } else if (selectedRole === 'principal') {
            titleField.classList.add('show');
            // Show username field for principals
            usernameField.style.display = 'block';
            usernameInput.setAttribute('required', 'required');
            usernameInput.disabled = false; // Enable visible username input
            usernameInput.name = 'username'; // Set correct name for submission

            // Disable hidden username field for non-students
            const hiddenUsernameInput = document.getElementById('username-hidden');
            hiddenUsernameInput.disabled = true;
            hiddenUsernameInput.name = 'username_hidden'; // Change name so it's not submitted

            // Make title required for principals
            titleSelect.setAttribute('required', 'required');
        } else {
            // Default state - show username field
            usernameField.style.display = 'block';
            usernameInput.setAttribute('required', 'required');
            usernameInput.disabled = false; // Enable visible username input
            usernameInput.name = 'username'; // Set correct name for submission

            // Disable hidden username field for non-students
            const hiddenUsernameInput = document.getElementById('username-hidden');
            hiddenUsernameInput.disabled = true;
            hiddenUsernameInput.name = 'username_hidden'; // Change name so it's not submitted
        }
    }

    roleSelect.addEventListener('change', toggleRoleFields);

    // Add event listener to sync username with student ID for students
    const studentIdField = document.getElementById('student_id');
    studentIdField.addEventListener('input', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'student') {
            const hiddenUsernameInput = document.getElementById('username-hidden');
            if (hiddenUsernameInput.name === 'username') { // Only sync if it's the active username field
                hiddenUsernameInput.value = this.value;
                console.log('Synced username with student ID:', this.value);
            }
        }
    });

    // Initialize on page load
    toggleRoleFields();
});
</script>
<?= $this->endSection() ?>
