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
                <h4 class="fw-bold mb-1">Edit Subject</h4>
                <p class="text-muted mb-0">Update subject information</p>
            </div>
            <a href="<?= base_url('admin/subjects') ?>" class="btn btn-secondary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Subjects
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

<!-- Edit Subject Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">edit</i>
                    Edit Subject: <?= esc($subject['name']) ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/subjects/edit/' . $subject['id'], ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <div class="row">
                    <!-- Subject Name -->
                    <div class="col-md-8 mb-3">
                        <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('name') ? 'is-invalid' : '' ?>"
                               id="name" name="name" value="<?= old('name', $subject['name']) ?>"
                               placeholder="e.g., Mathematics, English Language" required>
                        <?php if ($validation->hasError('name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Subject Code -->
                    <div class="col-md-4 mb-3">
                        <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('code') ? 'is-invalid' : '' ?>"
                               id="code" name="code" value="<?= old('code', $subject['code']) ?>"
                               placeholder="e.g., MATH, ENG" style="text-transform: uppercase;" required>
                        <?php if ($validation->hasError('code')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('code') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc($category['name']) ?>"
                                        <?= old('category', $subject['category']) == $category['name'] ? 'selected' : '' ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">
                            <a href="<?= base_url('admin/subject-categories') ?>" class="text-decoration-none">
                                <i class="material-symbols-rounded" style="font-size: 14px;">add</i>
                                Manage Categories
                            </a>
                        </div>
                    </div>


                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="Optional description for this subject"><?= old('description', $subject['description']) ?></textarea>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               <?= old('is_active', $subject['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            <strong>Active Subject</strong>
                            <small class="text-muted d-block">Subject can be assigned to teachers and used in exams</small>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/subjects') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Update Subject
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

    // Ensure code is uppercase
    const codeInput = document.getElementById('code');
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
<?= $this->endSection() ?>
