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
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
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
        background: linear-gradient(135deg, #A05AFF 0%, #8B47E6 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .term-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Create Academic Session</h4>
                <p class="text-muted mb-0">Set up a new academic session with 3 terms for the Nigerian school system</p>
            </div>
            <a href="<?= base_url('admin/sessions') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Sessions
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
        <div class="info-card">
            <div class="d-flex align-items-center">
                <i class="material-symbols-rounded me-3" style="font-size: 32px;">info</i>
                <div>
                    <h6 class="mb-1">Nigerian Academic Session Structure</h6>
                    <p class="mb-0 small">
                        Each academic session runs from September to July and is divided into 3 terms: 
                        First Term (Sep-Dec), Second Term (Jan-Apr), and Third Term (Apr-Jul).
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Session Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">calendar_today</i>
                    Session Information
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/sessions/create', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                
                <div class="row">
                    <!-- Session Name -->
                    <div class="col-md-6 mb-3">
                        <label for="session_name" class="form-label">Session Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('session_name') ? 'is-invalid' : '' ?>" 
                               id="session_name" name="session_name" 
                               value="<?= old('session_name') ?>" 
                               placeholder="e.g., 2024/2025" required>
                        <?php if ($validation->hasError('session_name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('session_name') ?></div>
                        <?php endif; ?>
                        <div class="form-text">Format: YYYY/YYYY (e.g., 2024/2025)</div>
                    </div>

                    <!-- Auto-generate checkbox -->
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto_generate" checked>
                            <label class="form-check-label" for="auto_generate">
                                <strong>Auto-generate session name</strong>
                                <small class="text-muted d-block">Based on start date</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Start Date -->
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Session Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?= $validation->hasError('start_date') ? 'is-invalid' : '' ?>" 
                               id="start_date" name="start_date" 
                               value="<?= old('start_date') ?>" required>
                        <?php if ($validation->hasError('start_date')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('start_date') ?></div>
                        <?php endif; ?>
                        <div class="form-text">Typically starts in September</div>
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Session End Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?= $validation->hasError('end_date') ? 'is-invalid' : '' ?>" 
                               id="end_date" name="end_date" 
                               value="<?= old('end_date') ?>" required>
                        <?php if ($validation->hasError('end_date')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('end_date') ?></div>
                        <?php endif; ?>
                        <div class="form-text">Typically ends in July of the following year</div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" 
                              placeholder="Optional description for this academic session"><?= old('description') ?></textarea>
                </div>

                <!-- Set as Current -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_current" name="is_current" value="1" 
                               <?= old('is_current') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_current">
                            <strong>Set as Current Session</strong>
                            <small class="text-muted d-block">This will deactivate any existing current session</small>
                        </label>
                    </div>
                </div>

                <!-- Term Preview -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">preview</i>
                        Term Preview
                    </h6>
                    <div id="termPreview">
                        <p class="text-muted">Select start and end dates to preview the 3 terms</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/sessions') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Create Session
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

    // Auto-generate session name
    const autoGenerate = document.getElementById('auto_generate');
    const sessionName = document.getElementById('session_name');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    function generateSessionName() {
        if (autoGenerate.checked && startDate.value) {
            const startYear = new Date(startDate.value).getFullYear();
            const endYear = startYear + 1;
            sessionName.value = `${startYear}/${endYear}`;
            
            // Auto-set end date if not set
            if (!endDate.value) {
                const endDateValue = new Date(startYear + 1, 6, 31); // July 31st of next year
                endDate.value = endDateValue.toISOString().split('T')[0];
            }
        }
    }

    function generateTermPreview() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            // Calculate term dates (Nigerian system)
            const firstTermStart = new Date(start);
            const firstTermEnd = new Date(start.getFullYear(), 11, 15); // Dec 15
            
            const secondTermStart = new Date(start.getFullYear() + 1, 0, 8); // Jan 8
            const secondTermEnd = new Date(start.getFullYear() + 1, 3, 5); // Apr 5
            
            const thirdTermStart = new Date(start.getFullYear() + 1, 3, 22); // Apr 22
            const thirdTermEnd = new Date(end);
            
            const preview = `
                <div class="term-preview">
                    <h6 class="fw-semibold text-primary mb-2">First Term</h6>
                    <p class="mb-0 small">${firstTermStart.toLocaleDateString()} - ${firstTermEnd.toLocaleDateString()}</p>
                </div>
                <div class="term-preview">
                    <h6 class="fw-semibold text-info mb-2">Second Term</h6>
                    <p class="mb-0 small">${secondTermStart.toLocaleDateString()} - ${secondTermEnd.toLocaleDateString()}</p>
                </div>
                <div class="term-preview">
                    <h6 class="fw-semibold text-success mb-2">Third Term</h6>
                    <p class="mb-0 small">${thirdTermStart.toLocaleDateString()} - ${thirdTermEnd.toLocaleDateString()}</p>
                </div>
            `;
            
            document.getElementById('termPreview').innerHTML = preview;
        }
    }

    startDate.addEventListener('change', function() {
        generateSessionName();
        generateTermPreview();
    });

    endDate.addEventListener('change', generateTermPreview);

    autoGenerate.addEventListener('change', function() {
        if (this.checked) {
            generateSessionName();
        }
    });

    // Set default dates for current academic year
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();
    
    // If it's before September, use previous year as start
    const academicStartYear = currentMonth < 8 ? currentYear - 1 : currentYear;
    
    if (!startDate.value) {
        startDate.value = `${academicStartYear}-09-01`;
        generateSessionName();
    }
    
    if (!endDate.value) {
        endDate.value = `${academicStartYear + 1}-07-31`;
    }
    
    generateTermPreview();
});
</script>
<?= $this->endSection() ?>
