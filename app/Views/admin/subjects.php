<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    /* Filter Enhancement Styles */
    .filter-active {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25) !important;
    }

    #clearFilters {
        display: none;
        transition: all 0.3s ease;
    }

    #clearFilters:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    /* Results count styling */
    #resultsCount {
        font-weight: 500;
        color: #6c757d;
    }

    /* Enhanced table styling */
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }

    /* Subject avatar styling */
    .subject-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    /* Status indicators */
    .status-active-text {
        color: #28a745;
    }

    .status-inactive-text {
        color: #dc3545;
    }

    /* Button action styling */
    .btn-action {
        padding: 0.375rem 0.5rem;
        border-radius: 6px;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    /* Ensure table headers have black text */
    .table thead th {
        color: #000 !important;
        font-weight: 600;
    }

    .table-light th {
        color: #000 !important;
        background-color: #f8f9fa !important;
    }

    /* Additional styling for better readability */
    .table th {
        border-bottom: 2px solid #dee2e6;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Filter section responsive */
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }

        .input-group, .form-select {
            width: 100% !important;
        }
    }

    /* Additional styles for enhanced functionality */
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: transparent;
    }
    .btn-outline-primary:hover {
        color: white;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-outline-warning {
        color: #ff9800;
        border-color: #ff9800;
        background-color: transparent;
    }
    .btn-outline-warning:hover {
        color: white;
        background-color: #ff9800;
        border-color: #ff9800;
    }
    .btn-outline-success {
        color: #4caf50;
        border-color: #4caf50;
        background-color: transparent;
    }
    .btn-outline-success:hover {
        color: white;
        background-color: #4caf50;
        border-color: #4caf50;
    }
    .btn-outline-danger {
        color: #f44336;
        border-color: #f44336;
        background-color: transparent;
    }
    .btn-outline-danger:hover {
        color: white;
        background-color: #f44336;
        border-color: #f44336;
    }
    .btn-group {
        display: inline-flex;
        gap: 0.25rem;
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

    /* Ensure Material Icons display properly */
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }

    .stats-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.2);
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
    }

    /* Bulk Actions Styles */
    .bulk-actions-bar {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        animation: slideDown 0.3s ease-in-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .subject-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .table tbody tr.selected {
        background-color: rgba(var(--primary-color-rgb), 0.1);
    }

    #selectAll:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Subject Management</h4>
                <p class="text-muted mb-0">Manage academic subjects and their assignments</p>
            </div>
            <a href="<?= base_url('admin/subjects/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2" style="font-size: 18px;"></i>Add New Subject
            </a>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2" style="font-size: 18px;"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2" style="font-size: 18px;"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Subject Statistics -->
<div class="row mb-4">
    <?php
    $totalSubjects = count($subjects);
    $activeSubjects = count(array_filter($subjects, fn($s) => $s['is_active'] == 1));
    $categories = array_unique(array_filter(array_column($subjects, 'category')));
    $totalCategories = count($categories);
    ?>

    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $totalSubjects ?></h3>
            <p class="mb-0">Total Subjects</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $activeSubjects ?></h3>
            <p class="mb-0">Active Subjects</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $totalCategories ?></h3>
            <p class="mb-0">Categories</p>
        </div>
    </div>
</div>

<!-- Subjects Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">All Subjects</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="searchSubjects" placeholder="Search subjects...">
                            <span class="input-group-text"><i class="fas fa-search" style="font-size: 18px;"></i></span>
                        </div>
                        <select class="form-select" id="filterCategory" style="width: 180px;">
                            <option value="">All Categories</option>
                            <option value="no-category">No Category</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <select class="form-select" id="filterStatus" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" id="clearFilters" title="Clear all filters">
                            <i class="fas fa-times" style="font-size: 18px;"></i>
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions Bar -->
                <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">
                                <span id="selectedCount">0</span> subjects selected
                            </span>
                            <select class="form-select me-2" id="bulkAction" style="width: 200px;">
                                <option value="">Choose Action</option>
                                <option value="activate">Activate Selected</option>
                                <option value="deactivate">Deactivate Selected</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                            <button type="button" class="btn btn-primary btn-sm" id="applyBulkAction">
                                <i class="material-symbols-rounded me-1" style="font-size: 16px;">play_arrow</i>Apply
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSelection">
                            <i class="material-symbols-rounded me-1" style="font-size: 16px;">close</i>Clear Selection
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($subjects)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="subjectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th class="border-0 fw-semibold">Subject</th>
                                    <th class="border-0 fw-semibold">Category</th>
                                    <th class="border-0 fw-semibold">Status</th>
                                    <th class="border-0 fw-semibold">Created</th>
                                    <th class="border-0 fw-semibold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject): ?>
                                <tr data-status="<?= $subject['is_active'] ? 'active' : 'inactive' ?>"
                                    data-category="<?= !empty($subject['category']) ? esc($subject['category']) : 'no-category' ?>">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input subject-checkbox" type="checkbox"
                                                   value="<?= $subject['id'] ?>" name="subjects[]">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="subject-avatar me-3">
                                                <?= strtoupper(substr($subject['code'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($subject['name']) ?></h6>
                                                <small class="text-muted">
                                                    <?= esc($subject['code']) ?>
                                                    <?php if (!empty($subject['category'])): ?>
                                                        • <?= esc($subject['category']) ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($subject['category'])): ?>
                                            <span class="badge bg-light text-dark"><?= esc($subject['category']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No category</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="material-symbols-rounded me-2 <?= $subject['is_active'] ? 'status-active-text' : 'status-inactive-text' ?>" style="font-size: 12px;">circle</i>
                                            <span class="<?= $subject['is_active'] ? 'status-active-text' : 'status-inactive-text' ?>">
                                                <?= $subject['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?= date('M j, Y', strtotime($subject['created_at'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/subjects/edit/' . $subject['id']) ?>"
                                               class="btn btn-outline-primary btn-action" title="Edit Subject">
                                                <i class="fas fa-edit" style="font-size: 18px;"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-<?= $subject['is_active'] ? 'warning' : 'success' ?> btn-action"
                                                    title="<?= $subject['is_active'] ? 'Deactivate' : 'Activate' ?> Subject"
                                                    onclick="showToggleModal(<?= $subject['id'] ?>, '<?= esc($subject['name']) ?>', <?= $subject['is_active'] ? 'true' : 'false' ?>)">
                                                <i class="fas <?= $subject['is_active'] ? 'fa-pause' : 'fa-play' ?>" style="font-size: 18px;"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-action"
                                                    title="Delete Subject"
                                                    onclick="showDeleteModal(<?= $subject['id'] ?>, '<?= esc($subject['name']) ?>')">
                                                <i class="fas fa-trash" style="font-size: 18px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">book</i>
                        <h6 class="text-muted">No subjects found</h6>
                        <p class="text-muted small">Start by creating your first subject</p>
                        <a href="<?= base_url('admin/subjects/create') ?>" class="btn btn-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Add First Subject
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">warning</i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the subject <strong id="deleteSubjectName"></strong>?</p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">delete</i>Delete Subject
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Confirmation Modal -->
<div class="modal fade" id="toggleModal" tabindex="-1" aria-labelledby="toggleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span id="toggleAction"></span> the subject <strong id="toggleSubjectName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmToggle">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;" id="toggleIcon"></i>
                    <span id="toggleButtonText"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;" id="bulkActionIcon">warning</i>
                    <span id="bulkActionTitle">Confirm Bulk Action</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bulkActionMessage">Are you sure you want to perform this action?</p>
                <div class="alert alert-warning" id="bulkActionWarning" style="display: none;">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">warning</i>
                    <span id="bulkActionWarningText"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" id="confirmBulkAction">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;" id="bulkActionButtonIcon"></i>
                    <span id="bulkActionButtonText">Confirm</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Validation Modal -->
<div class="modal fade" id="bulkValidationModal" tabindex="-1" aria-labelledby="bulkValidationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkValidationModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                    Validation Error
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bulkValidationMessage">Please correct the following issues:</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchSubjects');
    const statusFilter = document.getElementById('filterStatus');
    const categoryFilter = document.getElementById('filterCategory');
    const table = document.getElementById('subjectsTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        const selectedCategory = categoryFilter.value;

        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const category = row.getAttribute('data-category');

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;
            const matchesCategory = !selectedCategory || category === selectedCategory;

            const isVisible = matchesSearch && matchesStatus && matchesCategory;
            row.style.display = isVisible ? '' : 'none';

            if (isVisible) visibleCount++;
        });

        // Update results count
        updateResultsCount(visibleCount, rows.length);
    }

    function updateResultsCount(visible, total) {
        let countElement = document.getElementById('resultsCount');
        if (!countElement) {
            // Create results count element if it doesn't exist
            countElement = document.createElement('small');
            countElement.id = 'resultsCount';
            countElement.className = 'text-muted ms-2';
            document.querySelector('.card-header h5').appendChild(countElement);
        }

        if (visible === total) {
            countElement.textContent = `(${total} subjects)`;
        } else {
            countElement.textContent = `(${visible} of ${total} subjects)`;
        }
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    categoryFilter.addEventListener('change', filterTable);

    // Clear filters functionality
    document.getElementById('clearFilters').addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        categoryFilter.value = '';
        filterTable();
        updateFilterStates();
    });

    // Update filter states (highlight active filters)
    function updateFilterStates() {
        const clearBtn = document.getElementById('clearFilters');
        const hasActiveFilters = searchInput.value || statusFilter.value || categoryFilter.value;

        // Toggle clear button visibility
        clearBtn.style.display = hasActiveFilters ? 'block' : 'none';

        // Add visual indicators for active filters
        [searchInput, statusFilter, categoryFilter].forEach(filter => {
            if (filter.value) {
                filter.classList.add('filter-active');
            } else {
                filter.classList.remove('filter-active');
            }
        });
    }

    // Update filter states on change
    [searchInput, statusFilter, categoryFilter].forEach(filter => {
        filter.addEventListener('input', updateFilterStates);
        filter.addEventListener('change', updateFilterStates);
    });

    // Initialize results count
    updateResultsCount(rows.length, rows.length);

    // Bulk actions functionality
    initializeBulkActions();

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Modal variables
let currentSubjectId = null;
let currentAction = null;

// Show delete modal
function showDeleteModal(subjectId, subjectName) {
    currentSubjectId = subjectId;
    currentAction = 'delete';
    document.getElementById('deleteSubjectName').textContent = subjectName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Show toggle status modal
function showToggleModal(subjectId, subjectName, isActive) {
    currentSubjectId = subjectId;
    currentAction = 'toggle';

    const action = isActive ? 'deactivate' : 'activate';
    const icon = isActive ? 'pause' : 'play_arrow';
    const buttonText = isActive ? 'Deactivate Subject' : 'Activate Subject';

    document.getElementById('toggleSubjectName').textContent = subjectName;
    document.getElementById('toggleAction').textContent = action;
    document.getElementById('toggleIcon').textContent = icon;
    document.getElementById('toggleButtonText').textContent = buttonText;

    const modal = new bootstrap.Modal(document.getElementById('toggleModal'));
    modal.show();
}

// Handle delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentSubjectId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';
        this.disabled = true;

        // Redirect to delete URL
        window.location.href = `<?= base_url('admin/subjects/delete/') ?>${currentSubjectId}`;
    }
});

// Handle toggle confirmation
document.getElementById('confirmToggle').addEventListener('click', function() {
    if (currentSubjectId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Redirect to toggle URL
        window.location.href = `<?= base_url('admin/subjects/toggle/') ?>${currentSubjectId}`;
    }
});

// Bulk Actions Functions
function initializeBulkActions() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    const clearSelectionBtn = document.getElementById('clearSelection');
    const bulkActionSelect = document.getElementById('bulkAction');

    if (!applyBulkActionBtn) {
        console.error('Apply bulk action button not found!');
        return;
    }

    // Fix the button by recreating it (resolves any HTML/CSS conflicts)
    const newApplyButton = document.createElement('button');
    newApplyButton.type = 'button';
    newApplyButton.className = 'btn btn-primary btn-sm';
    newApplyButton.id = 'applyBulkAction';
    newApplyButton.innerHTML = '<i class="material-symbols-rounded me-1" style="font-size: 16px;">play_arrow</i>Apply';

    // Replace the original button
    applyBulkActionBtn.parentElement.replaceChild(newApplyButton, applyBulkActionBtn);

    // Update reference to the new button
    const workingApplyBtn = newApplyButton;

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        const visibleCheckboxes = Array.from(subjectCheckboxes).filter(cb => {
            const row = cb.closest('tr');
            return row.style.display !== 'none';
        });

        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            updateRowSelection(checkbox);
        });
        updateBulkActionsBar();
    });

    // Individual checkbox functionality
    subjectCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateRowSelection(this);
            updateSelectAllState();
            updateBulkActionsBar();
        });
    });

    // Clear selection
    clearSelectionBtn.addEventListener('click', function() {
        subjectCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            updateRowSelection(checkbox);
        });
        selectAllCheckbox.checked = false;
        updateBulkActionsBar();
    });

    // Apply bulk action - using multiple methods to ensure it works

    // Method 1: Direct onclick
    workingApplyBtn.onclick = function(e) {
        console.log('onclick fired');
        handleBulkAction(e);
    };

    // Method 2: addEventListener
    workingApplyBtn.addEventListener('click', function(e) {
        console.log('addEventListener fired');
        handleBulkAction(e);
    });

    // Method 3: Global event delegation
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.id === 'applyBulkAction' || e.target.closest('#applyBulkAction'))) {
            console.log('global delegation fired');
            handleBulkAction(e);
        }
    });

    function handleBulkAction(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        console.log('handleBulkAction called');
        alert('Bulk action triggered!');

        const selectedAction = bulkActionSelect.value;
        const selectedSubjects = Array.from(subjectCheckboxes).filter(cb => cb.checked);

        console.log('Selected action:', selectedAction);
        console.log('Selected subjects count:', selectedSubjects.length);

        if (!selectedAction) {
            alert('Please select an action to perform.');
            return;
        }

        if (selectedSubjects.length === 0) {
            alert('Please select at least one subject.');
            return;
        }

        // For now, let's bypass the modal and go straight to form submission
        if (confirm(`Are you sure you want to ${selectedAction} ${selectedSubjects.length} selected subject(s)?`)) {
            submitBulkAction(selectedAction, selectedSubjects);
        }
    }

    function submitBulkAction(action, selectedSubjects) {
        console.log('Submitting bulk action:', action);

        // Create form and submit directly
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/subjects/bulk-action') ?>';
        form.style.display = 'none';

        // Add action input
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);

        // Add subject IDs
        selectedSubjects.forEach(checkbox => {
            const subjectInput = document.createElement('input');
            subjectInput.type = 'hidden';
            subjectInput.name = 'subjects[]';
            subjectInput.value = checkbox.value;
            form.appendChild(subjectInput);
        });

        console.log('Form created, submitting...');
        document.body.appendChild(form);
        form.submit();
    }

    // Handle bulk action confirmation - moved inside initialization
    document.getElementById('confirmBulkAction').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default behavior

        const action = this.getAttribute('data-action');

        if (selectedSubjectsForBulk.length === 0) {
            return;
        }

        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/subjects/bulk-action') ?>';
        form.style.display = 'none'; // Hide the form

        // Add action input
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);

        // Add subject IDs
        selectedSubjectsForBulk.forEach(subjectId => {
            const subjectInput = document.createElement('input');
            subjectInput.type = 'hidden';
            subjectInput.name = 'subjects[]';
            subjectInput.value = subjectId;
            form.appendChild(subjectInput);
        });

        document.body.appendChild(form);
        form.submit();
    });

    function updateRowSelection(checkbox) {
        const row = checkbox.closest('tr');
        if (checkbox.checked) {
            row.classList.add('selected');
        } else {
            row.classList.remove('selected');
        }
    }

    function updateSelectAllState() {
        const visibleCheckboxes = Array.from(subjectCheckboxes).filter(cb => {
            const row = cb.closest('tr');
            return row.style.display !== 'none';
        });

        const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
        const totalCount = visibleCheckboxes.length;

        if (checkedCount === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCount === totalCount) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
            selectAllCheckbox.checked = false;
        }
    }

    function updateBulkActionsBar() {
        const selectedCount = Array.from(subjectCheckboxes).filter(cb => cb.checked).length;
        selectedCountSpan.textContent = selectedCount;

        if (selectedCount > 0) {
            bulkActionsBar.style.display = 'block';
        } else {
            bulkActionsBar.style.display = 'none';
            bulkActionSelect.value = '';
        }
    }
}

// Bulk action variables
let selectedSubjectsForBulk = [];

// Show validation modal
function showValidationModal(message) {
    document.getElementById('bulkValidationMessage').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('bulkValidationModal'));
    modal.show();
}

// Show bulk action modal
function showBulkActionModal(action, selectedSubjects) {
    selectedSubjectsForBulk = selectedSubjects.map(cb => cb.value);
    const count = selectedSubjects.length;

    // Update modal content based on action
    const actionText = action === 'delete' ? 'delete' : action;
    const actionIcon = action === 'delete' ? 'delete' : (action === 'activate' ? 'play_arrow' : 'pause');
    const buttonClass = action === 'delete' ? 'btn-danger' : 'btn-warning';
    const buttonText = action === 'delete' ? 'Delete Selected' : (action === 'activate' ? 'Activate Selected' : 'Deactivate Selected');

    document.getElementById('bulkActionTitle').textContent = `Confirm ${action.charAt(0).toUpperCase() + action.slice(1)}`;
    document.getElementById('bulkActionIcon').textContent = actionIcon;
    document.getElementById('bulkActionMessage').textContent = `Are you sure you want to ${actionText} ${count} selected subject(s)?`;

    // Show warning for delete action
    const warningDiv = document.getElementById('bulkActionWarning');
    if (action === 'delete') {
        document.getElementById('bulkActionWarningText').textContent = 'This action cannot be undone. Subjects with associated questions or exams cannot be deleted.';
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }

    const confirmButton = document.getElementById('confirmBulkAction');
    confirmButton.className = `btn ${buttonClass}`;
    confirmButton.setAttribute('data-action', action);
    document.getElementById('bulkActionButtonIcon').textContent = actionIcon;
    document.getElementById('bulkActionButtonText').textContent = buttonText;

    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    modal.show();
}

// Bulk action confirmation is now handled inside initializeBulkActions()
</script>
<?= $this->endSection() ?>
