<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Subject Categories - SRMS CBT System
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>

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
.category-color-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
}

.stats-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border: none;
    color: white;
}

.stats-card .card-body {
    padding: 1.5rem;
}
 .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
    }
.category-card {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.action-buttons .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

.badge-status {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Subject Categories</h4>
                <p class="text-muted mb-0">Manage subject categories for better organization</p>
            </div>
            <a href="<?= base_url('admin/subject-categories/create') ?>" class="btn btn-primary">
                <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create Category
            </a>
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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 2.5rem;">category</i>
                <h3 class="mb-1 text-white"><?= $stats['total'] ?></h3>
                <p class="mb-0 opacity-8">Total Categories</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 2.5rem;">check_circle</i>
                <h3 class="mb-1 text-white"><?= $stats['active'] ?></h3>
                <p class="mb-0 opacity-8">Active Categories</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 2.5rem;">pause_circle</i>
                <h3 class="mb-1 text-white"><?= $stats['total'] - $stats['active'] ?></h3>
                <p class="mb-0 opacity-8">Inactive Categories</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="material-symbols-rounded mb-2" style="font-size: 2.5rem;">book</i>
                <h3 class="mb-1 text-white"><?= array_sum(array_column($categories, 'subject_count')) ?></h3>
                <p class="mb-0 opacity-8">Total Subjects</p>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card category-card">
    <div class="card-header bg-white border-bottom-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="material-symbols-rounded me-2" style="font-size: 20px;">list</i>
                All Categories
            </h5>
            <div class="d-flex gap-2 align-items-center">
                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions" style="display: flex;">
                    <select class="form-select form-select-sm me-2" id="bulkActionSelect" style="width: 150px;">
                        <option value="">Select Action</option>
                        <option value="delete">Delete Selected</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-primary me-1" id="applyBulkAction">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">play_arrow</i>Apply
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelBulkAction">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">close</i>Cancel
                    </button>
                </div>
                <!-- Search -->
                <input type="text" class="form-control form-control-sm" id="searchCategories"
                       placeholder="Search categories..." style="width: 200px;">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($categories)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="categoriesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold" style="width: 50px; color: black !important;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Category</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Color</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Subjects</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Status</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Created</th>
                            <th class="border-0 fw-semibold text-center" style="color: black !important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr data-status="<?= $category['is_active'] ? 'active' : 'inactive' ?>" data-category-id="<?= $category['id'] ?>" data-can-delete="<?= $category['subject_count'] == 0 ? 'true' : 'false' ?>">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input category-checkbox" type="checkbox" value="<?= $category['id'] ?>" id="category_<?= $category['id'] ?>">
                                    <label class="form-check-label" for="category_<?= $category['id'] ?>"></label>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="category-color-indicator me-3"
                                         style="background-color: <?= esc($category['color']) ?>"></div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?= esc($category['name']) ?></h6>
                                        <?php if (!empty($category['description'])): ?>
                                            <small class="text-muted"><?= esc($category['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background-color: <?= esc($category['color']) ?>; color: white;">
                                    <?= esc($category['color']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= $category['subject_count'] ?></span>
                                <small class="text-muted d-block">subjects</small>
                            </td>
                            <td>
                                <?php if ($category['is_active']): ?>
                                    <span class="badge bg-success badge-status">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary badge-status">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-medium"><?= date('M j, Y', strtotime($category['created_at'])) ?></span>
                                <small class="text-muted d-block"><?= date('g:i A', strtotime($category['created_at'])) ?></small>
                            </td>
                            <td>
                                <div class="action-buttons text-center">
                                    <a href="<?= base_url('admin/subject-categories/edit/' . $category['id']) ?>"
                                       class="btn btn-sm btn-outline-primary me-1" title="Edit Category">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">edit</i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-warning me-1"
                                            title="<?= $category['is_active'] ? 'Deactivate' : 'Activate' ?> Category"
                                            onclick="showToggleModal(<?= $category['id'] ?>, '<?= esc($category['name']) ?>', <?= $category['is_active'] ? 'true' : 'false' ?>)">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">
                                            <?= $category['is_active'] ? 'pause' : 'play_arrow' ?>
                                        </i>
                                    </button>
                                    <?php if ($category['subject_count'] == 0): ?>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete Category"
                                                onclick="showDeleteModal(<?= $category['id'] ?>, '<?= esc($category['name']) ?>')">
                                            <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete - has subjects">
                                            <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="material-symbols-rounded text-muted mb-3" style="font-size: 4rem;">category</i>
                <h5 class="text-muted">No Categories Found</h5>
                <p class="text-muted mb-4">Start by creating your first subject category</p>
                <a href="<?= base_url('admin/subject-categories/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create Category
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="material-symbols-rounded text-danger me-2">warning</i>
                    Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to delete the category <strong id="deleteCategoryName"></strong>?</p>
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="material-symbols-rounded me-2">info</i>
                    <small>This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="material-symbols-rounded me-2">delete</i>Delete Category
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Confirmation Modal -->
<div class="modal fade" id="toggleModal" tabindex="-1" aria-labelledby="toggleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="toggleModalLabel">
                    <i class="material-symbols-rounded text-warning me-2" id="toggleIcon">pause</i>
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to <span id="toggleAction">deactivate</span> the category <strong id="toggleCategoryName"></strong>?</p>
                <div class="alert alert-info d-flex align-items-center">
                    <i class="material-symbols-rounded me-2">info</i>
                    <small>You can change this status again later if needed.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmToggle">
                    <i class="material-symbols-rounded me-2" id="toggleButtonIcon">pause</i>
                    <span id="toggleButtonText">Deactivate Category</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Validation Modal -->
<div class="modal fade" id="bulkValidationModal" tabindex="-1" aria-labelledby="bulkValidationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="bulkValidationModalLabel">
                    <i class="material-symbols-rounded text-warning me-2">warning</i>
                    Action Required
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="material-symbols-rounded text-warning mb-3" style="font-size: 3rem;">error</i>
                <p class="mb-3" id="bulkValidationMessage">Please select an action to perform.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-2">check</i>Got it
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="bulkActionModalLabel">
                    <i class="material-symbols-rounded text-warning me-2" id="bulkActionIcon">warning</i>
                    Confirm Bulk Action
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to <strong id="bulkActionText">delete</strong> <strong id="bulkActionCount">0</strong> selected categories?</p>
                <div id="bulkActionWarning" class="alert alert-warning d-flex align-items-center" style="display: none;">
                    <i class="material-symbols-rounded me-2">warning</i>
                    <small id="bulkActionWarningText">Some categories cannot be deleted because they have subjects assigned.</small>
                </div>
                <div class="alert alert-info d-flex align-items-center">
                    <i class="material-symbols-rounded me-2">info</i>
                    <small id="bulkActionInfo">This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBulkAction">
                    <i class="material-symbols-rounded me-2" id="bulkActionButtonIcon">delete</i>
                    <span id="bulkActionButtonText">Delete Selected</span>
                </button>
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

    // Search functionality
    const searchInput = document.getElementById('searchCategories');
    const table = document.getElementById('categoriesTable');

    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const categoryName = row.querySelector('h6').textContent.toLowerCase();
                const description = row.querySelector('small') ? row.querySelector('small').textContent.toLowerCase() : '';

                if (categoryName.includes(searchTerm) || description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Bulk actions functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    const cancelBulkActionBtn = document.getElementById('cancelBulkAction');

    console.log('Elements found:');
    console.log('selectAllCheckbox:', selectAllCheckbox);
    console.log('categoryCheckboxes:', categoryCheckboxes.length);
    console.log('bulkActions:', bulkActions);
    console.log('bulkActionSelect:', bulkActionSelect);
    console.log('applyBulkActionBtn:', applyBulkActionBtn);
    console.log('cancelBulkActionBtn:', cancelBulkActionBtn);

    // Handle select all checkbox
    selectAllCheckbox.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    // Handle individual checkboxes
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === categoryCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < categoryCheckboxes.length;
            toggleBulkActions();
        });
    });

    // Show/hide bulk actions
    function toggleBulkActions() {
        const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActions.style.display = 'flex';
        } else {
            bulkActions.style.display = 'none';
            bulkActionSelect.value = '';
        }
    }

    // Cancel bulk actions
    cancelBulkActionBtn.addEventListener('click', function() {
        categoryCheckboxes.forEach(checkbox => checkbox.checked = false);
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        toggleBulkActions();
    });

    // Apply bulk action
    if (applyBulkActionBtn) {
        console.log('Attaching event listener to apply button');
        applyBulkActionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Apply button clicked - event listener fired');

            const selectedAction = bulkActionSelect.value;
            const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'));

            console.log('Selected action:', selectedAction);
            console.log('Selected categories count:', selectedCategories.length);

            if (!selectedAction) {
                console.log('No action selected, showing validation modal');
                showValidationModal('Please select an action to perform.');
                return;
            }

            if (selectedCategories.length === 0) {
                console.log('No categories selected, showing validation modal');
                showValidationModal('Please select at least one category.');
                return;
            }

            console.log('Calling showBulkActionModal');
            showBulkActionModal(selectedAction, selectedCategories);
        });
    } else {
        console.error('Apply bulk action button not found!');
    }
});

// Modal variables
let currentCategoryId = null;
let currentAction = null;
let selectedCategoriesForBulk = [];

// Show validation modal
function showValidationModal(message) {
    document.getElementById('bulkValidationMessage').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('bulkValidationModal'));
    modal.show();
}

// Show delete modal
function showDeleteModal(categoryId, categoryName) {
    currentCategoryId = categoryId;
    currentAction = 'delete';
    document.getElementById('deleteCategoryName').textContent = categoryName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Show toggle status modal
function showToggleModal(categoryId, categoryName, isActive) {
    currentCategoryId = categoryId;
    currentAction = 'toggle';

    const action = isActive ? 'deactivate' : 'activate';
    const icon = isActive ? 'pause' : 'play_arrow';
    const buttonText = isActive ? 'Deactivate Category' : 'Activate Category';

    document.getElementById('toggleCategoryName').textContent = categoryName;
    document.getElementById('toggleAction').textContent = action;
    document.getElementById('toggleIcon').textContent = icon;
    document.getElementById('toggleButtonIcon').textContent = icon;
    document.getElementById('toggleButtonText').textContent = buttonText;

    const modal = new bootstrap.Modal(document.getElementById('toggleModal'));
    modal.show();
}

// Show bulk action modal
function showBulkActionModal(action, selectedCategories) {
    selectedCategoriesForBulk = selectedCategories.map(cb => cb.value);
    const count = selectedCategories.length;

    // Update modal content based on action
    const actionText = action === 'delete' ? 'delete' : action;
    const actionIcon = action === 'delete' ? 'delete' : (action === 'activate' ? 'play_arrow' : 'pause');
    const buttonClass = action === 'delete' ? 'btn-danger' : 'btn-warning';
    const buttonText = action === 'delete' ? 'Delete Selected' : (action === 'activate' ? 'Activate Selected' : 'Deactivate Selected');

    document.getElementById('bulkActionText').textContent = actionText;
    document.getElementById('bulkActionCount').textContent = count;
    document.getElementById('bulkActionIcon').textContent = actionIcon;
    document.getElementById('bulkActionButtonIcon').textContent = actionIcon;
    document.getElementById('bulkActionButtonText').textContent = buttonText;

    // Update button class
    const confirmBtn = document.getElementById('confirmBulkAction');
    confirmBtn.className = `btn ${buttonClass}`;

    // Check for delete restrictions
    if (action === 'delete') {
        const cannotDelete = selectedCategories.filter(cb => {
            const row = cb.closest('tr');
            return row.dataset.canDelete === 'false';
        });

        if (cannotDelete.length > 0) {
            document.getElementById('bulkActionWarning').style.display = 'block';
            document.getElementById('bulkActionWarningText').textContent =
                `${cannotDelete.length} categories cannot be deleted because they have subjects assigned.`;
        } else {
            document.getElementById('bulkActionWarning').style.display = 'none';
        }

        document.getElementById('bulkActionInfo').textContent = 'This action cannot be undone.';
    } else {
        document.getElementById('bulkActionWarning').style.display = 'none';
        document.getElementById('bulkActionInfo').textContent = 'You can change the status again later if needed.';
    }

    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentCategoryId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = `<?= base_url('admin/subject-categories/delete/') ?>${currentCategoryId}`;
    }
});

// Handle toggle confirmation
document.getElementById('confirmToggle').addEventListener('click', function() {
    if (currentCategoryId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Redirect to toggle URL
        window.location.href = `<?= base_url('admin/subject-categories/toggle/') ?>${currentCategoryId}`;
    }
});

// Handle bulk action confirmation
document.getElementById('confirmBulkAction').addEventListener('click', function() {
    if (selectedCategoriesForBulk.length > 0) {
        const action = document.getElementById('bulkActionSelect').value;

        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url('admin/subject-categories/bulk-action') ?>`;

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        // Add action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);

        // Add selected categories
        selectedCategoriesForBulk.forEach(categoryId => {
            const categoryInput = document.createElement('input');
            categoryInput.type = 'hidden';
            categoryInput.name = 'categories[]';
            categoryInput.value = categoryId;
            form.appendChild(categoryInput);
        });

        document.body.appendChild(form);
        form.submit();
    }
});
</script>
<?= $this->endSection() ?>
