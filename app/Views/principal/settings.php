<?= $this->extend('layouts/principal') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<style>
.settings-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.form-control:focus {
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

.setting-section {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
}

.setting-section:last-child {
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

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.alert-info {
    background-color: rgba(var(--primary-color-rgb), 0.1);
    border-color: var(--primary-color);
    color: var(--primary-dark);
}

@media (max-width: 768px) {
    .settings-card {
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
                <h4 class="fw-bold mb-1" style="color: white;">Principal Settings</h4>
                <p class="text-light mb-0">Manage basic school settings and information</p>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">check_circle</i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="material-symbols-rounded me-2">error</i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Settings Form -->
<div class="card settings-card">
    <div class="card-body p-4">
        <div class="alert alert-info" role="alert">
            <i class="material-symbols-rounded me-2">info</i>
            <strong>Note:</strong> As a Principal, you have access to limited settings. For advanced system configuration, please contact your system administrator.
        </div>

        <form method="POST" action="<?= base_url('principal/settings') ?>">
            <?= csrf_field() ?>
            
            <!-- School Information -->
            <div class="setting-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">school</i>
                    School Information
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institution_name" class="form-label">School Name</label>
                        <input type="text" class="form-control" id="institution_name"
                               value="<?= esc($settings['institution_name'] ?? 'ExamExcel') ?>"
                               placeholder="Enter your school name" readonly disabled>
                        <div class="form-text">Official name of your school (displayed throughout the system)</div>
                        <small class="text-muted">This field cannot be modified</small>
                        <input type="hidden" name="institution_name" value="ExamExcel">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year"
                               value="<?= esc($settings['academic_year'] ?? '') ?>"
                               placeholder="e.g., 2024/2025">
                        <div class="form-text">Current academic year</div>
                    </div>
                </div>
            </div>
            
            <!-- Additional School Details -->
            <div class="setting-section">
                <h5 class="section-title">
                    <i class="material-symbols-rounded">location_on</i>
                    School Contact Details
                </h5>
                
                <div class="mb-3">
                    <label for="school_address" class="form-label">School Address</label>
                    <textarea class="form-control" id="school_address" name="school_address" rows="3"><?= esc($settings['school_address'] ?? '') ?></textarea>
                    <div class="form-text">Complete address of the school</div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="school_phone" class="form-label">School Phone</label>
                        <input type="tel" class="form-control" id="school_phone" name="school_phone" 
                               value="<?= esc($settings['school_phone'] ?? '') ?>">
                        <div class="form-text">Main contact number</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="school_email" class="form-label">School Email</label>
                        <input type="email" class="form-control" id="school_email" name="school_email" 
                               value="<?= esc($settings['school_email'] ?? '') ?>">
                        <div class="form-text">Official email address</div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Additional Information -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card settings-card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="material-symbols-rounded me-2">help</i>
                    Need More Access?
                </h6>
                <p class="card-text">
                    If you need access to advanced settings like user management, exam configuration, 
                    or system security settings, please contact your system administrator.
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card settings-card">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="material-symbols-rounded me-2">support</i>
                    Support
                </h6>
                <p class="card-text">
                    For technical support or questions about the system, please contact your 
                    IT department or system administrator.
                </p>
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
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Basic validation can be added here if needed
        console.log('Settings form submitted');
    });
    
    // Auto-save indication (optional)
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Could add auto-save functionality here
            console.log('Setting changed:', this.name, this.value);
        });
    });
});
</script>
<?= $this->endSection() ?>
