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
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25) !important;
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

    /* Role Selection Styling */
    #role {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        font-size: 16px !important;
    }

    #role:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-color-rgb), 0.25) !important;
        background: #ffffff;
    }

    #role option {
        padding: 10px;
        font-size: 16px;
    }

    /* Dynamic form container animation */
    #dynamic-form-container {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Role description animation */
    #role-description {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Enhanced alert styling */
    .alert-info {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Create New User</h4>
                <p class="text-muted mb-0">Add a new user to the CBT system</p>
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

<!-- Create User Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">person_add</i>
                    User Information
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/users/create', ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <!-- Role Selection - First Priority -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info border-0" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white;">
                            <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                            <strong>Step 1:</strong> Please select the user role first. This will determine which additional fields are required.
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6 mx-auto">
                        <label for="role" class="form-label fs-5 fw-bold text-center d-block mb-3">
                            <i class="material-symbols-rounded me-2" style="font-size: 24px;">badge</i>
                            Select User Role <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= $validation->hasError('role') ? 'is-invalid' : '' ?>"
                                id="role" name="role" required style="text-align: center; font-weight: 600; min-height: 60px;">
                            <option value="">🤔 Choose Role...</option>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>👑 Administrator - Full System Access</option>
                            <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>👨‍🏫 Teacher - Question & Class Management</option>
                            <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>🎓 Student - Take Exams & Practice</option>
                            <option value="principal" <?= old('role') === 'principal' ? 'selected' : '' ?>>🏛️ Principal - Administrative Oversight</option>
                        </select>
                        <?php if ($validation->hasError('role')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                        <?php endif; ?>
                        <small class="text-muted d-block text-center mt-2">Different roles require different information</small>

                        <!-- Role Description -->
                        <div id="role-description" class="mt-3" style="display: none;">
                            <div class="alert alert-light border text-center">
                                <div id="role-description-content"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Form Container -->
                <div id="dynamic-form-container" style="display: none;">
                    <hr class="my-4">

                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="material-symbols-rounded me-2" style="font-size: 18px;">person</i>
                                Basic Information
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= $validation->hasError('first_name') ? 'is-invalid' : '' ?>"
                                   id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                            <?php if ($validation->hasError('first_name')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= $validation->hasError('last_name') ? 'is-invalid' : '' ?>"
                                   id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
                            <?php if ($validation->hasError('last_name')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6" id="username-field">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= $validation->hasError('username') ? 'is-invalid' : '' ?>"
                                   id="username" name="username" value="<?= old('username') ?>" required>
                            <?php if ($validation->hasError('username')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
                            <?php endif; ?>
                        </div>
                        <!-- Hidden username field for students (will be populated with student_id) -->
                        <input type="hidden" id="username-hidden" name="username_hidden" value="<?= old('username') ?>" style="display: none;">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                                   id="email" name="email" value="<?= old('email') ?>" required>
                            <?php if ($validation->hasError('email')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : '' ?>"
                                   id="password" name="password" required>
                            <?php if ($validation->hasError('password')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <small class="text-muted">Re-enter the password to confirm</small>
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
                                <option value="Principal" <?= old('title') === 'Principal' ? 'selected' : '' ?>>Principal</option>
                                <option value="Vice Principal" <?= old('title') === 'Vice Principal' ? 'selected' : '' ?>>Vice Principal</option>
                                <option value="Assistant Principal" <?= old('title') === 'Assistant Principal' ? 'selected' : '' ?>>Assistant Principal</option>
                                <option value="Head of Department" <?= old('title') === 'Head of Department' ? 'selected' : '' ?>>Head of Department (HOD)</option>
                                <option value="Academic Director" <?= old('title') === 'Academic Director' ? 'selected' : '' ?>>Academic Director</option>
                                <option value="Dean" <?= old('title') === 'Dean' ? 'selected' : '' ?>>Dean</option>
                                <option value="Director" <?= old('title') === 'Director' ? 'selected' : '' ?>>Director</option>
                            </select>
                            <small class="text-muted">Select the official title for this principal role</small>
                            <?php if ($validation->hasError('title')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('title') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="custom_title" class="form-label">Custom Title</label>
                            <input type="text" class="form-control" id="custom_title" name="custom_title"
                                   value="<?= old('custom_title') ?>" placeholder="Enter custom title if not listed above">
                            <small class="text-muted">Leave blank if using predefined title above</small>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" <?= old('gender') === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= old('gender') === 'female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
                </div>

                <!-- Teacher-specific fields -->
                <div id="teacher-fields" class="role-specific-fields">
                    <h6 class="fw-semibold mb-3 text-primary">Teacher Information</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" value="<?= old('employee_id') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" value="<?= old('department') ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control" id="qualification" name="qualification" value="<?= old('qualification') ?>">
                    </div>
                </div>

                <!-- Student-specific fields -->
                <div id="student-fields" class="role-specific-fields">
                    <h6 class="fw-semibold mb-3 text-primary">Student Information</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="student_id" name="student_id" value="<?= old('student_id') ?>" placeholder="Auto-generated when Student role is selected" required>
                                <button type="button" class="btn btn-outline-primary" id="generateIdBtn" onclick="window.generateStudentId()" title="Generate new Student ID">
                                    <i class="material-symbols-rounded" style="font-size: 16px;">refresh</i>
                                </button>
                            </div>
                            <small class="text-muted">Student ID will be auto-generated when you select Student role</small>
                        </div>
                        <div class="col-md-6">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" id="class_id" name="class_id">
                                <option value="">Select Class</option>
                                <?php if (isset($classes) && !empty($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= old('class_id') == $class['id'] ? 'selected' : '' ?>>
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

                    <!-- Login Information for Students -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                                <div>
                                    <strong>Login Information:</strong> The student will use their Student ID as username to login. No separate username is required.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Settings -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   <?= old('is_active') ? 'checked' : 'checked' ?>>
                            <label class="form-check-label" for="is_active">
                                Active User
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1"
                                   <?= old('is_verified') ? 'checked' : '' ?>>
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
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create User
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
    console.log('Page loaded, initializing user creation form...');

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
        console.log('Form is submitting to server!');
        // Check password confirmation
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            event.preventDefault();
            event.stopPropagation();

            // Show error for password confirmation
            const confirmPasswordField = document.getElementById('confirm_password');
            confirmPasswordField.classList.add('is-invalid');

            // Add or update error message
            let errorDiv = confirmPasswordField.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                confirmPasswordField.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = 'Passwords do not match';

            alert('Passwords do not match. Please check and try again.');
            return false;
        }

        // Check for principal role specific validation
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'principal') {
            const title = document.getElementById('title').value;
            const customTitle = document.getElementById('custom_title').value;
            if (!title && !customTitle) {
                event.preventDefault();
                event.stopPropagation();
                // Show error below the title field
                let titleField = document.getElementById('title-field');
                let errorDiv = titleField.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    titleField.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Please select a title or enter a custom title for the principal role.';
                errorDiv.style.display = 'block';
                // Highlight the fields
                document.getElementById('title').classList.add('is-invalid');
                document.getElementById('custom_title').classList.add('is-invalid');
                return false;
            } else {
                // Remove error if present
                document.getElementById('title').classList.remove('is-invalid');
                document.getElementById('custom_title').classList.remove('is-invalid');
                let titleField = document.getElementById('title-field');
                let errorDiv = titleField.querySelector('.invalid-feedback');
                if (errorDiv) errorDiv.style.display = 'none';
            }
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Real-time password confirmation validation
    const confirmPasswordField = document.getElementById('confirm_password');
    confirmPasswordField.addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;

        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (confirmPassword && password === confirmPassword) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    // Role-specific fields toggle
    const roleSelect = document.getElementById('role');
    const teacherFields = document.getElementById('teacher-fields');
    const studentFields = document.getElementById('student-fields');
    const titleField = document.getElementById('title-field');
    const dynamicFormContainer = document.getElementById('dynamic-form-container');

    console.log('Elements found:', {
        roleSelect: !!roleSelect,
        teacherFields: !!teacherFields,
        studentFields: !!studentFields,
        titleField: !!titleField,
        dynamicFormContainer: !!dynamicFormContainer
    });

    if (!roleSelect) {
        console.error('Role select element not found!');
        console.log('Available elements with id containing "role":',
            Array.from(document.querySelectorAll('[id*="role"]')).map(el => el.id));
        return;
    }

    function toggleRoleFields() {
        console.log('=== ROLE TOGGLE START ===');
        const selectedRole = roleSelect.value;
        console.log('Selected role:', selectedRole);

        // Get elements
        const teacherDiv = document.getElementById('teacher-fields');
        const studentDiv = document.getElementById('student-fields');
        const titleDiv = document.getElementById('title-field');
        const usernameField = document.getElementById('username-field');
        const hiddenUsernameInput = document.getElementById('username-hidden');
        const visibleUsernameInput = document.getElementById('username');

        console.log('Found elements:', {
            teacherDiv: !!teacherDiv,
            studentDiv: !!studentDiv,
            titleDiv: !!titleDiv,
            usernameField: !!usernameField,
            hiddenUsernameInput: !!hiddenUsernameInput,
            visibleUsernameInput: !!visibleUsernameInput
        });

        // STEP 1: Hide ALL role sections
        console.log('Hiding all sections...');
        if (teacherDiv) {
            teacherDiv.classList.remove('show');
            console.log('Teacher section hidden');
        }
        if (studentDiv) {
            studentDiv.classList.remove('show');
            console.log('Student section hidden');
        }
        if (titleDiv) {
            titleDiv.classList.remove('show');
            console.log('Title section hidden');
        }

        // STEP 2: Handle username field visibility based on role
        if (selectedRole === 'student') {
            // For students: hide visible username field, enable hidden username field
            if (usernameField) {
                usernameField.style.display = 'none';
                console.log('Username field hidden for student');
            }
            if (hiddenUsernameInput && visibleUsernameInput) {
                hiddenUsernameInput.disabled = false;
                hiddenUsernameInput.name = 'username'; // Set correct name for submission
                visibleUsernameInput.disabled = true;
                visibleUsernameInput.name = 'username_disabled'; // Change name so it's not submitted
                console.log('Hidden username field enabled for student');
            }
        } else {
            // For other roles: show visible username field, disable hidden username field
            if (usernameField) {
                usernameField.style.display = 'block';
                console.log('Username field shown for non-student');
            }
            if (hiddenUsernameInput && visibleUsernameInput) {
                hiddenUsernameInput.disabled = true;
                hiddenUsernameInput.name = 'username_hidden'; // Change name so it's not submitted
                visibleUsernameInput.disabled = false;
                visibleUsernameInput.name = 'username'; // Set correct name for submission
                console.log('Visible username field enabled for non-student');
            }
        }

        // STEP 3: Show section for selected role
        if (selectedRole === 'teacher' && teacherDiv) {
            teacherDiv.classList.add('show');
            console.log('Teacher section shown');
        } else if (selectedRole === 'student' && studentDiv) {
            studentDiv.classList.add('show');
            console.log('Student section shown');
            // Auto-generate student ID
            setTimeout(() => {
                if (window.generateStudentId) {
                    window.generateStudentId();
                }
            }, 100);
        } else if (selectedRole === 'principal' && titleDiv) {
            titleDiv.classList.add('show');
            console.log('Principal section shown');
        }

        console.log('=== ROLE TOGGLE END ===');

        // Show/hide dynamic form container
        const dynamicFormContainer = document.getElementById('dynamic-form-container');
        if (dynamicFormContainer) {
            if (selectedRole) {
                dynamicFormContainer.style.display = 'block';
            } else {
                dynamicFormContainer.style.display = 'none';
            }
        }

        // Show role description
        const roleDescription = document.getElementById('role-description');
        const roleDescriptionContent = document.getElementById('role-description-content');
        const roleDescriptions = {
            'admin': '<strong>👑 Administrator</strong><br>Full system access, can manage all users, settings, and content.',
            'teacher': '<strong>👨‍🏫 Teacher</strong><br>Can create and manage questions, view student results, and access assigned subjects.',
            'student': '<strong>🎓 Student</strong><br>Can take exams, view results, and access practice questions.',
            'principal': '<strong>🏛️ Principal</strong><br>Administrative oversight with access to reports, user management, and system settings.'
        };

        if (selectedRole && roleDescriptions[selectedRole] && roleDescriptionContent) {
            roleDescriptionContent.innerHTML = roleDescriptions[selectedRole];
            if (roleDescription) roleDescription.style.display = 'block';
        } else {
            if (roleDescription) roleDescription.style.display = 'none';
        }
    }

    // Make function globally accessible
    window.generateStudentId = function() {
        const studentIdField = document.getElementById('student_id');
        const hiddenUsernameInput = document.getElementById('username-hidden');

        // Show loading state
        studentIdField.value = 'Generating...';
        studentIdField.style.color = '#6c757d';

        // Get the student ID prefix from settings (we'll make an AJAX call)
        fetch('<?= base_url('admin/generate-student-id') ?>')
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    studentIdField.value = data.student_id;
                    studentIdField.style.color = '#198754'; // Success color

                    // Update hidden username field with the same value
                    hiddenUsernameInput.value = data.student_id;
                    console.log('Updated hidden username field with:', data.student_id);
                } else {
                    throw new Error(data.message || 'Failed to generate student ID');
                }
            })
            .catch(error => {
                console.error('Error generating student ID:', error);
                // Fallback: generate a simple ID
                const randomDigits = Math.floor(1000 + Math.random() * 9000);
                const fallbackId = 'STD-' + randomDigits;
                studentIdField.value = fallbackId;
                studentIdField.style.color = '#fd7e14'; // Warning color

                // Update hidden username field with fallback value
                hiddenUsernameInput.value = fallbackId;
                console.log('Using fallback student ID:', fallbackId);
            });
    };

    roleSelect.addEventListener('change', function() {
        console.log('Role changed to:', this.value);

        // Add visual feedback
        const dynamicFormContainer = document.getElementById('dynamic-form-container');
        if (dynamicFormContainer && this.value) {
            dynamicFormContainer.style.opacity = '0.5';
            setTimeout(() => {
                toggleRoleFields();
                dynamicFormContainer.style.opacity = '1';
            }, 100);
        } else {
            toggleRoleFields();
        }
    });

    // Also listen for input event
    roleSelect.addEventListener('input', function() {
        console.log('Role input changed to:', this.value);
        toggleRoleFields();
    });

    // Handle custom title input
    const titleSelect = document.getElementById('title');
    const customTitleInput = document.getElementById('custom_title');

    if (titleSelect && customTitleInput) {
        // When custom title is entered, clear the dropdown selection
        customTitleInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                titleSelect.value = '';
            }
        });

        // When dropdown is selected, clear custom title
        titleSelect.addEventListener('change', function() {
            if (this.value !== '') {
                customTitleInput.value = '';
            }
        });
    }

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
    console.log('Initializing role fields...');
    console.log('Current role value:', roleSelect.value);

    // If there's a validation error and role is already selected, show the form
    if (roleSelect.value) {
        const dynamicFormContainer = document.getElementById('dynamic-form-container');
        if (dynamicFormContainer) {
            dynamicFormContainer.style.display = 'block';
        }
    }

    toggleRoleFields();

    // Force generation if student is already selected
    if (roleSelect.value === 'student') {
        console.log('Student role detected on page load, forcing ID generation...');
        setTimeout(() => {
            const studentIdField = document.getElementById('student_id');
            if (!studentIdField.value || studentIdField.value.trim() === '') {
                window.generateStudentId();
            }
        }, 500);
    }

    console.log('User creation form initialization complete.');

    // Ensure username field is enabled and named correctly before form submission
    const userForm = document.querySelector('form.needs-validation');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            clearError();
            let hasError = false;
            try {
                const role = roleSelect.value;
                const visibleUsernameInput = document.getElementById('username');
                const hiddenUsernameInput = document.getElementById('username-hidden');

                // For non-student roles, ensure visible username is enabled and named 'username'
                if (role !== 'student') {
                    if (visibleUsernameInput) {
                        visibleUsernameInput.disabled = false;
                        visibleUsernameInput.name = 'username';
                        if (!visibleUsernameInput.value.trim()) {
                            showError('Username is required for this role.');
                            visibleUsernameInput.focus();
                            hasError = true;
                        }
                    } else {
                        showError('Username field missing.');
                        hasError = true;
                    }
                    if (hiddenUsernameInput) {
                        hiddenUsernameInput.disabled = true;
                        hiddenUsernameInput.name = 'username_hidden';
                    }
                } else {
                    // For student, ensure hidden username is enabled and named 'username'
                    if (hiddenUsernameInput) {
                        hiddenUsernameInput.disabled = false;
                        hiddenUsernameInput.name = 'username';
                        if (!hiddenUsernameInput.value.trim()) {
                            showError('Student ID/Username is required.');
                            hiddenUsernameInput.focus();
                            hasError = true;
                        }
                    } else {
                        showError('Student username field missing.');
                        hasError = true;
                    }
                    if (visibleUsernameInput) {
                        visibleUsernameInput.disabled = true;
                        visibleUsernameInput.name = 'username_disabled';
                    }
                }
                // Check for other required fields
                const emailInput = document.getElementById('email');
                if (!emailInput.value.trim()) {
                    showError('Email is required.');
                    emailInput.focus();
                    hasError = true;
                }
                const passwordInput = document.getElementById('password');
                if (!passwordInput.value.trim()) {
                    showError('Password is required.');
                    passwordInput.focus();
                    hasError = true;
                }
                if (hasError) {
                    e.preventDefault();
                    console.log('Form blocked due to error.');
                    return false;
                }
                clearError();
                console.log('No errors, submitting form!');
                // Remove this event handler to avoid infinite loop with submit()
                userForm.removeEventListener('submit', arguments.callee);
                userForm.submit();
            } catch (err) {
                showError('JavaScript error: ' + err.message);
                console.error('Form submit error:', err);
                e.preventDefault();
                return false;
            }
        });
    }

    // Global error handler for debugging
    window.onerror = function(message, source, lineno, colno, error) {
        showError('JavaScript error: ' + message + ' at ' + source + ':' + lineno);
        return false;
    };

    // Helper to show error
    function showError(msg) {
        alert(msg);
    }
    function clearError() {
        // Remove existing error messages
        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(element => {
            element.remove();
        });
    }
});
</script>
<?= $this->endSection() ?>
