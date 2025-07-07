<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
.form-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    background: white;
}

.form-floating > .form-control {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus {
    border-color: #A05AFF;
    box-shadow: 0 0 0 0.2rem rgba(160, 90, 255, 0.25);
}

.form-floating > label {
    color: #666;
    font-size: 0.875rem;
}

.btn-action {
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(var(--primary-color-rgb), 0.25);
}

.invalid-feedback {
    display: block;
    font-size: 0.75rem;
    color: #dc3545;
    margin-top: 0.25rem;
}

.term-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-left: 4px solid var(--primary-color);
}

.current-badge {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>



<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="material-symbols-rounded">check_circle</i></span>
        <span class="alert-text"><?= session()->getFlashdata('success') ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="material-symbols-rounded">error</i></span>
        <span class="alert-text"><?= session()->getFlashdata('error') ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Edit Academic Session</h4>
                <p class="text-muted mb-0">Update session information for the Nigerian school system</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if ($session['is_current']): ?>
                    <span class="current-badge">Current Session</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Session Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">edit</i>
                    Session Information
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/sessions/edit/' . $session['id'], ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <!-- Session Name -->
                <div class="form-floating mb-3">
                    <input type="text"
                           class="form-control <?= $validation->hasError('session_name') ? 'is-invalid' : '' ?>"
                           id="session_name"
                           name="session_name"
                           placeholder="Session Name"
                           value="<?= old('session_name', $session['session_name']) ?>"
                           required>
                    <label for="session_name">Session Name (e.g., 2024/2025)</label>
                    <?php if ($validation->hasError('session_name')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('session_name') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Date Range -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date"
                                   class="form-control <?= $validation->hasError('start_date') ? 'is-invalid' : '' ?>"
                                   id="start_date"
                                   name="start_date"
                                   value="<?= old('start_date', $session['start_date']) ?>"
                                   required>
                            <label for="start_date">Start Date</label>
                            <?php if ($validation->hasError('start_date')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('start_date') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date"
                                   class="form-control <?= $validation->hasError('end_date') ? 'is-invalid' : '' ?>"
                                   id="end_date"
                                   name="end_date"
                                   value="<?= old('end_date', $session['end_date']) ?>"
                                   required>
                            <label for="end_date">End Date</label>
                            <?php if ($validation->hasError('end_date')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('end_date') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Current Session Toggle -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_current"
                               name="is_current"
                               value="1"
                               <?= old('is_current', $session['is_current']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-medium" for="is_current">
                            Set as Current Academic Session
                        </label>
                        <div class="form-text">
                            Only one session can be current at a time. Setting this will deactivate the current session.
                        </div>
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
                    <a href="<?= base_url('admin/sessions') ?>" class="btn btn-secondary btn-action">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-action">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Update Session
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

    // Date validation
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    function validateDates() {
        if (startDate.value && endDate.value) {
            if (new Date(startDate.value) >= new Date(endDate.value)) {
                endDate.setCustomValidity('End date must be after start date');
            } else {
                endDate.setCustomValidity('');
            }
        }
    }

    startDate.addEventListener('change', validateDates);
    endDate.addEventListener('change', validateDates);

    // Term preview
    function updateTermPreview() {
        const start = startDate.value;
        const end = endDate.value;

        if (start && end) {
            const startDateObj = new Date(start);
            const endDateObj = new Date(end);

            // Calculate term durations (approximately equal)
            const totalDays = (endDateObj - startDateObj) / (1000 * 60 * 60 * 24);
            const termDays = Math.floor(totalDays / 3);

            // First Term
            const firstTermStart = new Date(startDateObj);
            const firstTermEnd = new Date(firstTermStart);
            firstTermEnd.setDate(firstTermEnd.getDate() + termDays);

            // Second Term
            const secondTermStart = new Date(firstTermEnd);
            secondTermStart.setDate(secondTermStart.getDate() + 1);
            const secondTermEnd = new Date(secondTermStart);
            secondTermEnd.setDate(secondTermEnd.getDate() + termDays);

            // Third Term
            const thirdTermStart = new Date(secondTermEnd);
            thirdTermStart.setDate(thirdTermStart.getDate() + 1);
            const thirdTermEnd = new Date(endDateObj);

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

    startDate.addEventListener('change', updateTermPreview);
    endDate.addEventListener('change', updateTermPreview);

    // Initial preview update
    updateTermPreview();
});
</script>
<?= $this->endSection() ?>
