<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .assignment-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .assignment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .level-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }
    .level-primary { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; }
    .level-jss { background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; }
    .level-sss { background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); color: white; }
    .level-other { background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%); color: white; }

    .btn-action {
        border-radius: 8px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        margin: 0 0.125rem;
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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

    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }

    .level-section {
        margin-bottom: 2rem;
    }
    .level-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--primary-color);
        cursor: pointer;
        transition: all 0.3s ease;
        user-select: none;
    }
    .level-header:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .level-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .level-content.collapsed {
        max-height: 0;
        opacity: 0;
        margin-bottom: 0;
    }
    .level-content.expanded {
        max-height: none;
        opacity: 1;
    }
    .toggle-btn {
        transition: all 0.3s ease;
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 500;
    }
    .toggle-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .level-stats {
        font-size: 0.85rem;
        opacity: 0.8;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div class="d-flex gap-2">
                <?php if (!empty($groupedByLevel)): ?>
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="expandAllLevels()">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">unfold_more</i>Expand All
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="collapseAllLevels()">
                        <i class="material-symbols-rounded me-1" style="font-size: 16px;">unfold_less</i>Collapse All
                    </button>
                </div>
                <?php endif; ?>
                <a href="<?= base_url('admin/subject-assignments/create') ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Bulk Assign Subjects
                </a>
            </div>
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

<!-- Assignment Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['total_assignments'] ?></h3>
            <p class="mb-0">Total Assignments</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['subjects_with_classes'] ?></h3>
            <p class="mb-0">Subjects Assigned</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <h3 class="mb-1 text-white"><?= $stats['classes_with_subjects'] ?></h3>
            <p class="mb-0">Classes with Subjects</p>
        </div>
    </div>
</div>

<!-- Assignments by Academic Level -->
<?php if (!empty($groupedByLevel)): ?>
    <?php foreach ($groupedByLevel as $level => $levelAssignments): ?>
        <?php if (!empty($levelAssignments)): ?>
            <div class="level-section">
                <div class="level-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <span class="level-badge level-<?= strtolower($level) ?>">
                                    <?= $level ?> Level
                                </span>
                            </h5>
                            <div class="level-stats">
                                <small class="text-muted">
                                    <?= count($levelAssignments) ?> subject assignments •
                                    <?php
                                    // Count unique classes
                                    $uniqueClasses = [];
                                    foreach ($levelAssignments as $assignment) {
                                        $classKey = $assignment['class_name'] . ' ' . $assignment['class_section'];
                                        $uniqueClasses[$classKey] = true;
                                    }
                                    echo count($uniqueClasses);
                                    ?> classes
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-primary btn-sm toggle-btn" id="toggle-<?= strtolower($level) ?>" onclick="toggleLevelSection('<?= strtolower($level) ?>')">
                                <span class="toggle-text">Hide</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="level-content expanded" id="content-<?= strtolower($level) ?>">
                    <div class="row">
                    <?php
                    // Group by class for better organization
                    $classesByLevel = [];
                    foreach ($levelAssignments as $assignment) {
                        $classKey = $assignment['class_name'] . ' ' . $assignment['class_section'];
                        if (!isset($classesByLevel[$classKey])) {
                            $classesByLevel[$classKey] = [
                                'class_info' => $assignment,
                                'subjects' => []
                            ];
                        }
                        $classesByLevel[$classKey]['subjects'][] = $assignment;
                    }
                    ?>

                    <?php foreach ($classesByLevel as $classKey => $classData): ?>
                        <div class="col-lg-6 mb-3">
                            <div class="card assignment-card">
                                <div class="card-header bg-white border-bottom-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-semibold"><?= esc($classData['class_info']['class_name']) ?></h6>
                                            <small class="text-muted">
                                                <?= esc($classData['class_info']['class_section']) ?> •
                                                <?= esc($classData['class_info']['academic_year']) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-light text-dark"><?= count($classData['subjects']) ?> subjects</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($classData['subjects'] as $assignment): ?>
                                            <div class="col-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                                    <div>
                                                        <span class="fw-medium"><?= esc($assignment['subject_name']) ?></span>
                                                        <small class="text-muted d-block"><?= esc($assignment['subject_code']) ?></small>
                                                    </div>
                                                    <button type="button"
                                                            class="btn btn-outline-danger btn-sm btn-action"
                                                            title="Remove Assignment"
                                                            onclick="removeAssignment(<?= $assignment['subject_id'] ?>, <?= $assignment['class_id'] ?>, '<?= esc($assignment['subject_name']) ?>', '<?= esc($classKey) ?>')">
                                                        <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 48px;">assignment</i>
        <h6 class="text-muted">No subject assignments found</h6>
        <p class="text-muted small">Start by assigning subjects to classes</p>
        <a href="<?= base_url('admin/subject-assignments/create') ?>" class="btn btn-primary">
            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create First Bulk Assignment
        </a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>


function removeAssignment(subjectId, classId, subjectName, className) {
    if (confirm(`Are you sure you want to remove "${subjectName}" from "${className}"?`)) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        button.disabled = true;

        // Send AJAX request
        fetch('<?= base_url('admin/subject-assignments/remove-assignment') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `subject_id=${subjectId}&class_id=${classId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the assignment row with animation
                const assignmentRow = button.closest('.col-12');
                assignmentRow.style.transition = 'all 0.3s ease';
                assignmentRow.style.opacity = '0';
                assignmentRow.style.transform = 'translateX(100%)';

                setTimeout(() => {
                    assignmentRow.remove();
                    // Show success message
                    showAlert('success', data.message);

                    // Reload page if no more subjects in this class
                    const remainingSubjects = button.closest('.card-body').querySelectorAll('.col-12').length;
                    if (remainingSubjects <= 1) {
                        setTimeout(() => location.reload(), 1000);
                    }
                }, 300);
            } else {
                showAlert('error', data.message);
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while removing the assignment.');
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'check_circle' : 'error';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="material-symbols-rounded me-2" style="font-size: 18px;">${icon}</i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Insert at the top of the page content
    const pageContent = document.querySelector('.row.mb-4');
    pageContent.insertAdjacentHTML('afterend', alertHtml);

    // Auto-hide after 5 seconds
    setTimeout(() => {
        const newAlert = document.querySelector('.alert:last-of-type');
        if (newAlert) {
            const bsAlert = new bootstrap.Alert(newAlert);
            bsAlert.close();
        }
    }, 5000);
}

// Level section expand/collapse functionality
function toggleLevelSection(level) {
    const content = document.getElementById(`content-${level}`);
    const toggleBtn = document.getElementById(`toggle-${level}`);
    const toggleText = toggleBtn.querySelector('.toggle-text');

    if (content.classList.contains('expanded')) {
        // Collapse
        content.classList.remove('expanded');
        content.classList.add('collapsed');
        toggleText.textContent = 'Show';
        toggleBtn.classList.remove('btn-outline-primary');
        toggleBtn.classList.add('btn-outline-success');

        // Save state to localStorage
        localStorage.setItem(`level-${level}-collapsed`, 'true');
    } else {
        // Expand
        content.classList.remove('collapsed');
        content.classList.add('expanded');
        toggleText.textContent = 'Hide';
        toggleBtn.classList.remove('btn-outline-success');
        toggleBtn.classList.add('btn-outline-primary');

        // Save state to localStorage
        localStorage.setItem(`level-${level}-collapsed`, 'false');
    }
}

// Initialize collapse states from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Get all level sections
    const levelSections = document.querySelectorAll('.level-content');

    levelSections.forEach(function(section) {
        const level = section.id.replace('content-', '');
        const isCollapsed = localStorage.getItem(`level-${level}-collapsed`) === 'true';
        const toggleBtn = document.getElementById(`toggle-${level}`);
        const toggleText = toggleBtn ? toggleBtn.querySelector('.toggle-text') : null;

        if (isCollapsed) {
            section.classList.remove('expanded');
            section.classList.add('collapsed');
            if (toggleBtn && toggleText) {
                toggleText.textContent = 'Show';
                toggleBtn.classList.remove('btn-outline-primary');
                toggleBtn.classList.add('btn-outline-success');
            }
        }
    });
});



// Expand all level sections
function expandAllLevels() {
    const levelSections = document.querySelectorAll('.level-content');
    levelSections.forEach(function(section) {
        const level = section.id.replace('content-', '');
        const toggleBtn = document.getElementById(`toggle-${level}`);
        const toggleText = toggleBtn ? toggleBtn.querySelector('.toggle-text') : null;

        section.classList.remove('collapsed');
        section.classList.add('expanded');
        if (toggleBtn && toggleText) {
            toggleText.textContent = 'Hide';
            toggleBtn.classList.remove('btn-outline-success');
            toggleBtn.classList.add('btn-outline-primary');
        }

        localStorage.setItem(`level-${level}-collapsed`, 'false');
    });
}

// Collapse all level sections
function collapseAllLevels() {
    const levelSections = document.querySelectorAll('.level-content');
    levelSections.forEach(function(section) {
        const level = section.id.replace('content-', '');
        const toggleBtn = document.getElementById(`toggle-${level}`);
        const toggleText = toggleBtn ? toggleBtn.querySelector('.toggle-text') : null;

        section.classList.remove('expanded');
        section.classList.add('collapsed');
        if (toggleBtn && toggleText) {
            toggleText.textContent = 'Show';
            toggleBtn.classList.remove('btn-outline-primary');
            toggleBtn.classList.add('btn-outline-success');
        }

        localStorage.setItem(`level-${level}-collapsed`, 'true');
    });
}
</script>
<?= $this->endSection() ?>
