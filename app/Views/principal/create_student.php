<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<style>
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
                <h4 class="fw-bold mb-1" style="color: white;">Create New Student</h4>
                <p class="text-light mb-0">Add a new student to the system</p>
            </div>
            <a href="<?= base_url('principal/students') ?>" class="btn btn-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Students
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

<!-- Create Student Form -->
<div class="card form-card">
    <div class="card-body p-4">
        <form method="POST" action="<?= base_url('principal/students/create') ?>">
            <?= csrf_field() ?>
            
            <!-- Personal Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">person</i>
                    Personal Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="<?= old('first_name') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                            <div class="text-danger small"><?= $validation->getError('first_name') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="<?= old('last_name') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                            <div class="text-danger small"><?= $validation->getError('last_name') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= old('email') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <div class="text-danger small"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?= old('phone') ?>">
                        <?php if (isset($validation) && $validation->hasError('phone')): ?>
                            <div class="text-danger small"><?= $validation->getError('phone') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="<?= old('date_of_birth') ?>">
                        <?php if (isset($validation) && $validation->hasError('date_of_birth')): ?>
                            <div class="text-danger small"><?= $validation->getError('date_of_birth') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= old('gender') == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('gender')): ?>
                            <div class="text-danger small"><?= $validation->getError('gender') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('address')): ?>
                        <div class="text-danger small"><?= $validation->getError('address') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Academic Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">school</i>
                    Academic Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="student_id" name="student_id"
                                   value="<?= old('student_id') ?>" placeholder="Click generate or leave blank for auto-generation">
                            <button type="button" class="btn btn-outline-primary" id="generateIdBtn" onclick="generateStudentId()" title="Generate new Student ID">
                                <i class="material-symbols-rounded" style="font-size: 16px;">refresh</i>
                            </button>
                        </div>
                        <div class="form-text">Click the generate button or leave blank for automatic generation</div>
                        <?php if (isset($validation) && $validation->hasError('student_id')): ?>
                            <div class="text-danger small"><?= $validation->getError('student_id') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" id="class_id" name="class_id">
                            <option value="">Select Class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= old('class_id') == $class['id'] ? 'selected' : '' ?>>
                                    <?= esc($class['display_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('class_id')): ?>
                            <div class="text-danger small"><?= $validation->getError('class_id') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">lock</i>
                    Account Information
                </h5>

                <div class="row">
                    <!-- Hidden username field that will be populated with student ID -->
                    <input type="hidden" id="username" name="username" value="<?= old('username') ?>">

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Password must be at least 6 characters long</div>
                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <div class="text-danger small"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-text text-info">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">info</i>
                            The student will use their Student ID as username to login
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-3">
                <a href="<?= base_url('principal/students') ?>" class="btn btn-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create Student
                </button>
            </div>
        </form>
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

    // Sync username with student ID
    const studentIdInput = document.getElementById('student_id');
    const usernameInput = document.getElementById('username');

    function syncUsernameWithStudentId() {
        const studentId = studentIdInput.value.trim();
        if (studentId) {
            usernameInput.value = studentId;
            console.log('Username synced with Student ID:', studentId);
        }
    }

    // Sync username when student ID changes
    studentIdInput.addEventListener('input', syncUsernameWithStudentId);
    studentIdInput.addEventListener('blur', syncUsernameWithStudentId);

    // Auto-generate student ID and sync username when form loads
    window.addEventListener('load', function() {
        // If student ID is empty, we'll let the backend generate it
        // But if there's a value, sync it with username
        if (studentIdInput.value.trim()) {
            syncUsernameWithStudentId();
        }
    });

    // Generate student ID function
    window.generateStudentId = function() {
        const studentIdField = document.getElementById('student_id');
        const usernameInput = document.getElementById('username');

        // Show loading state
        studentIdField.value = 'Generating...';
        studentIdField.style.color = '#6c757d';

        // Get the student ID from the principal endpoint
        fetch('<?= base_url('principal/generate-student-id') ?>')
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

                    // Update username field with the same value
                    usernameInput.value = data.student_id;
                    console.log('Updated username field with:', data.student_id);
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

                // Update username field with fallback value
                usernameInput.value = fallbackId;
                console.log('Using fallback student ID:', fallbackId);
            });
    };
    
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
});
</script>
<?= $this->endSection() ?>
