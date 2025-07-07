<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Edit Subject Category - ExamExcel
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
.form-card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
}

.form-card .card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.color-preview {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: inline-block;
    transition: all 0.3s ease;
}

.color-preset {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0.25rem;
}

.color-preset:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.color-preset.selected {
    transform: scale(1.2);
    box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.3);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
<!-- Page Header -->
<div class="page-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="mb-1 fw-bold">Edit Subject Category</h4>
                <p class="mb-0 opacity-8">Update category information</p>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('admin/subject-categories') ?>" class="btn btn-light">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Categories
                </a>
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

<!-- Edit Category Form -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">edit</i>
                    Edit Category: <?= esc($category['name']) ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('admin/subject-categories/edit/' . $category['id'], ['class' => 'needs-validation', 'novalidate' => '']) ?>

                <!-- Category Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= $validation->hasError('name') ? 'is-invalid' : '' ?>"
                           id="name" name="name" value="<?= old('name', $category['name']) ?>"
                           placeholder="e.g., Science, Arts, Languages" required>
                    <?php if ($validation->hasError('name')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="Optional description for this category"><?= old('description', $category['description']) ?></textarea>
                </div>

                <!-- Color Selection -->
                <div class="mb-3">
                    <label for="color" class="form-label">Category Color <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="color-preview" id="colorPreview" style="background-color: <?= esc($category['color']) ?>;"></div>
                        <input type="color" class="form-control form-control-color <?= $validation->hasError('color') ? 'is-invalid' : '' ?>"
                               id="color" name="color" value="<?= old('color', $category['color']) ?>" required>
                        <span class="text-muted">Choose a color to represent this category</span>
                    </div>

                    <!-- Color Presets -->
                    <div class="mb-2">
                        <small class="text-muted">Quick color presets:</small>
                    </div>
                    <div class="d-flex flex-wrap">
                        <div class="color-preset" data-color="#dc3545" style="background-color: #dc3545;" title="Red"></div>
                        <div class="color-preset" data-color="#28a745" style="background-color: #28a745;" title="Green"></div>
                        <div class="color-preset" data-color="#ffc107" style="background-color: #ffc107;" title="Yellow"></div>
                        <div class="color-preset" data-color="#17a2b8" style="background-color: #17a2b8;" title="Cyan"></div>
                        <div class="color-preset" data-color="#6f42c1" style="background-color: #6f42c1;" title="Purple"></div>
                        <div class="color-preset" data-color="#fd7e14" style="background-color: #fd7e14;" title="Orange"></div>
                        <div class="color-preset" data-color="#e83e8c" style="background-color: #e83e8c;" title="Pink"></div>
                        <div class="color-preset" data-color="#20c997" style="background-color: #20c997;" title="Teal"></div>
                        <div class="color-preset" data-color="#6c757d" style="background-color: #6c757d;" title="Gray"></div>
                        <div class="color-preset" data-color="#007bff" style="background-color: #007bff;" title="Blue"></div>
                    </div>

                    <?php if ($validation->hasError('color')): ?>
                        <div class="invalid-feedback d-block"><?= $validation->getError('color') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               <?= old('is_active', $category['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            <strong>Active Category</strong>
                            <small class="text-muted d-block">Category will be available for subject assignment</small>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/subject-categories') ?>" class="btn btn-secondary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">cancel</i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">save</i>Update Category
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

    // Color picker functionality
    const colorInput = document.getElementById('color');
    const colorPreview = document.getElementById('colorPreview');
    const colorPresets = document.querySelectorAll('.color-preset');

    // Update preview when color input changes
    colorInput.addEventListener('input', function() {
        colorPreview.style.backgroundColor = this.value;
        updateSelectedPreset(this.value);
    });

    // Handle color preset clicks
    colorPresets.forEach(preset => {
        preset.addEventListener('click', function() {
            const color = this.dataset.color;
            colorInput.value = color;
            colorPreview.style.backgroundColor = color;
            updateSelectedPreset(color);
        });
    });

    // Update selected preset indicator
    function updateSelectedPreset(color) {
        colorPresets.forEach(preset => {
            if (preset.dataset.color === color) {
                preset.classList.add('selected');
            } else {
                preset.classList.remove('selected');
            }
        });
    }

    // Initialize with current color
    updateSelectedPreset(colorInput.value);
});
</script>
<?= $this->endSection() ?>
