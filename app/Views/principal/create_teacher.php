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
                <h4 class="fw-bold mb-1" style="color: white;">Create New Teacher</h4>
                <p class="text-light mb-0">Add a new teacher to the system</p>
            </div>
            <a href="<?= base_url('principal/teachers') ?>" class="btn btn-light">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Teachers
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

<!-- Create Teacher Form -->
<div class="card form-card">
    <div class="card-body p-4">
        <form method="POST" action="<?= base_url('principal/teachers/create') ?>">
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
            
            <!-- Professional Information -->
            <div class="form-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">work</i>
                    Professional Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="employee_id" name="employee_id" 
                               value="<?= old('employee_id') ?>" placeholder="Leave blank for auto-generation">
                        <div class="form-text">If left blank, an employee ID will be automatically generated</div>
                        <?php if (isset($validation) && $validation->hasError('employee_id')): ?>
                            <div class="text-danger small"><?= $validation->getError('employee_id') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" 
                               value="<?= old('department') ?>" placeholder="e.g., Mathematics, Science, English">
                        <?php if (isset($validation) && $validation->hasError('department')): ?>
                            <div class="text-danger small"><?= $validation->getError('department') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control" id="qualification" name="qualification" 
                               value="<?= old('qualification') ?>" placeholder="e.g., B.Ed, M.A., Ph.D">
                        <?php if (isset($validation) && $validation->hasError('qualification')): ?>
                            <div class="text-danger small"><?= $validation->getError('qualification') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="experience" class="form-label">Years of Experience</label>
                        <input type="number" class="form-control" id="experience" name="experience" 
                               value="<?= old('experience') ?>" min="0" max="50">
                        <?php if (isset($validation) && $validation->hasError('experience')): ?>
                            <div class="text-danger small"><?= $validation->getError('experience') ?></div>
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
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="required">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= old('username') ?>" required>
                        <div class="form-text">Username must be unique and at least 3 characters long</div>
                        <?php if (isset($validation) && $validation->hasError('username')): ?>
                            <div class="text-danger small"><?= $validation->getError('username') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Password must be at least 6 characters long</div>
                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <div class="text-danger small"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-3">
                <a href="<?= base_url('principal/teachers') ?>" class="btn btn-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create Teacher
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

    // Auto-generate username from first and last name
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const usernameInput = document.getElementById('username');

    function generateUsername() {
        const firstName = firstNameInput.value.trim().toLowerCase();
        const lastName = lastNameInput.value.trim().toLowerCase();

        if (firstName && lastName) {
            const username = firstName + '.' + lastName;
            usernameInput.value = username.replace(/[^a-z0-9.]/g, '');
        }
    }

    firstNameInput.addEventListener('blur', generateUsername);
    lastNameInput.addEventListener('blur', generateUsername);

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
