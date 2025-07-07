<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .promotion-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .promotion-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .class-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        position: relative;
        overflow: hidden;
    }

    .class-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        pointer-events: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        padding: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: var(--primary-color);
        display: block;
    }

    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 5px;
    }

    .promotion-actions {
        padding: 20px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    .btn-promote {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-promote:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-view-students {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view-students:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
        color: white;
    }

    .session-info {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .session-badge {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
        margin-right: 15px;
    }

    .term-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .promotion-actions {
            text-align: center;
        }

        .promotion-actions .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
            <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('academic') ?>">Academic Management</a></li>
                <li class="breadcrumb-item active">Class Promotion</li>
            </ol>
        </nav>
    </div>

    <!-- Current Session/Term Info -->
    <div class="session-info">
        <div class="d-flex flex-wrap align-items-center">
            <h5 class="mb-0 me-3">Current Academic Period:</h5>
            <?php if ($currentSession && isset($currentSession['session_name'])): ?>
                <span class="session-badge">
                    <i class="fas fa-calendar-alt me-2"></i><?= esc($currentSession['session_name']) ?>
                </span>
            <?php else: ?>
                <span class="session-badge bg-secondary">
                    <i class="fas fa-exclamation-triangle me-2"></i>No Active Session
                </span>
            <?php endif; ?>
            <?php if ($currentTerm && isset($currentTerm['term_name'])): ?>
                <span class="term-badge">
                    <i class="fas fa-clock me-2"></i><?= esc($currentTerm['term_name']) ?>
                </span>
            <?php else: ?>
                <span class="term-badge bg-secondary">
                    <i class="fas fa-exclamation-triangle me-2"></i>No Active Term
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Classes Grid -->
    <?php if (!empty($classes)): ?>
        <div class="row">
            <?php foreach ($classes as $class): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="promotion-card">
                        <div class="class-header">
                            <h5 class="mb-1 text-white"><?= esc($class['name']) ?></h5>
                            <p class="mb-0 opacity-75">Section: <?= esc($class['section']) ?></p>
                        </div>

                        <div class="stats-grid">
                            <?php
                            $stats = $promotionStats[$class['id']] ?? [
                                'total' => 0,
                                'promoted' => 0,
                                'active' => 0,
                                'repeated' => 0
                            ];
                            ?>
                            <div class="stat-item">
                                <span class="stat-number"><?= $stats['total'] ?></span>
                                <div class="stat-label">Total Students</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-success"><?= $stats['promoted'] ?></span>
                                <div class="stat-label">Promoted</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-info"><?= $stats['active'] ?></span>
                                <div class="stat-label">Active</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-warning"><?= $stats['repeated'] ?></span>
                                <div class="stat-label">Repeated</div>
                            </div>
                        </div>

                        <div class="promotion-actions">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-view-students flex-fill"
                                        onclick="viewClassStudents(<?= $class['id'] ?>)">
                                    <i class="fas fa-users me-2"></i>View Students
                                </button>
                                <button class="btn btn-promote flex-fill"
                                        onclick="showPromotionModal(<?= $class['id'] ?>, '<?= htmlspecialchars($class['name']) ?>')"
                                        <?= $stats['total'] == 0 ? 'disabled' : '' ?>>
                                    <i class="fas fa-level-up-alt me-2"></i>Promote Students
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-graduation-cap"></i>
            <h4>No Classes Found</h4>
            <p>No active classes are available for promotion management.</p>
            <a href="<?= base_url('admin/classes') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Classes
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Promotion Modal -->
<div class="modal fade" id="promotionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-level-up-alt me-2"></i>Promote Students - <span id="currentClassName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="targetClass" class="form-label">Promote to Class:</label>
                        <select class="form-select" id="targetClass" required>
                            <option value="">Select target class...</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['display_name'] ?? $class['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Promotion Type:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="promotionType" id="promoteAll" value="all" checked>
                            <label class="form-check-label" for="promoteAll">
                                Promote All Students
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="promotionType" id="promoteSelected" value="selected">
                            <label class="form-check-label" for="promoteSelected">
                                Select Students to Promote
                            </label>
                        </div>
                    </div>
                </div>

                <div id="studentsList" style="display: none;">
                    <h6>Select Students to Promote:</h6>
                    <div class="student-selection-container" style="max-height: 300px; overflow-y: auto;">
                        <div class="mb-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllStudents()">
                                <i class="fas fa-check-square me-1"></i>Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllStudents()">
                                <i class="fas fa-square me-1"></i>Deselect All
                            </button>
                        </div>
                        <div id="studentsCheckboxes">
                            <!-- Students will be loaded here -->
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> This promotion system helps to move already onboarded students to the next class, instead of editing their class individually.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPromoteBtn" disabled>
                    <i class="fas fa-level-up-alt me-2"></i>Promote Students
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let currentClassId = null;
let currentClassName = '';

function viewClassStudents(classId) {
    window.location.href = `<?= base_url('academic/student-history/') ?>${classId}`;
}

function showPromotionModal(classId, className) {
    currentClassId = classId;
    currentClassName = className;

    document.getElementById('currentClassName').textContent = className;
    document.getElementById('targetClass').value = '';
    document.getElementById('promoteAll').checked = true;
    document.getElementById('studentsList').style.display = 'none';
    document.getElementById('confirmPromoteBtn').disabled = true;

    const modal = new bootstrap.Modal(document.getElementById('promotionModal'));
    modal.show();
}

// Handle promotion type change
document.querySelectorAll('input[name="promotionType"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const studentsList = document.getElementById('studentsList');
        if (this.value === 'selected') {
            studentsList.style.display = 'block';
            loadClassStudents();
        } else {
            studentsList.style.display = 'none';
        }
        validateForm();
    });
});

// Handle target class change
document.getElementById('targetClass').addEventListener('change', validateForm);

function validateForm() {
    const targetClass = document.getElementById('targetClass').value;
    const promotionType = document.querySelector('input[name="promotionType"]:checked').value;
    const confirmBtn = document.getElementById('confirmPromoteBtn');

    let isValid = targetClass !== '';

    if (promotionType === 'selected') {
        const selectedStudents = document.querySelectorAll('#studentsCheckboxes input[type="checkbox"]:checked');
        isValid = isValid && selectedStudents.length > 0;
    }

    confirmBtn.disabled = !isValid;
}

function loadClassStudents() {
    if (!currentClassId) return;

    const container = document.getElementById('studentsCheckboxes');
    container.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading students...</div>';

    fetch(`<?= base_url('academic/get-class-students/') ?>${currentClassId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.students) {
                let html = '';
                data.students.forEach(student => {
                    // Handle both academic history and users table data structures
                    const studentId = student.student_id || student.id;
                    const studentNumber = student.student_number || student.student_id;

                    html += `
                        <div class="form-check mb-2">
                            <input class="form-check-input student-checkbox" type="checkbox" value="${studentId}" id="student_${studentId}" onchange="validateForm()">
                            <label class="form-check-label" for="student_${studentId}">
                                ${student.first_name} ${student.last_name}
                                ${studentNumber ? `(${studentNumber})` : ''}
                            </label>
                        </div>
                    `;
                });
                container.innerHTML = html || '<div class="text-muted">No students found in this class.</div>';
            } else {
                container.innerHTML = '<div class="text-danger">Failed to load students.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="text-danger">Error loading students.</div>';
        });
}

function selectAllStudents() {
    document.querySelectorAll('#studentsCheckboxes input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = true;
    });
    validateForm();
}

function deselectAllStudents() {
    document.querySelectorAll('#studentsCheckboxes input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    validateForm();
}

document.getElementById('confirmPromoteBtn').addEventListener('click', function() {
    if (!currentClassId) return;

    const targetClassId = document.getElementById('targetClass').value;
    const promotionType = document.querySelector('input[name="promotionType"]:checked').value;

    if (!targetClassId) {
        showValidationModal('Validation Error', 'Please select a target class');
        return;
    }

    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Promoting...';
    btn.disabled = true;

    let selectedStudents = [];
    if (promotionType === 'selected') {
        selectedStudents = Array.from(document.querySelectorAll('#studentsCheckboxes input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);

        if (selectedStudents.length === 0) {
            showValidationModal('Validation Error', 'Please select at least one student to promote');
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
    }

    const formData = new URLSearchParams({
        class_id: currentClassId,
        target_class_id: targetClassId,
        session_id: <?= isset($currentSession['id']) ? $currentSession['id'] : 0 ?>,
        term_id: <?= isset($currentTerm['id']) ? $currentTerm['id'] : 0 ?>
    });

    if (promotionType === 'selected') {
        selectedStudents.forEach(studentId => {
            formData.append('selected_students[]', studentId);
        });
    }

    fetch('<?= base_url('academic/bulk-promote-students') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal('Success!', data.message);
            bootstrap.Modal.getInstance(document.getElementById('promotionModal')).hide();
            setTimeout(() => location.reload(), 2000);
        } else {
            showValidationModal('Error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showValidationModal('Error', 'An error occurred while promoting students');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });

    // Show validation modal
    function showValidationModal(title, message) {
        document.getElementById('validationModalTitle').textContent = title;
        document.getElementById('validationModalMessage').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        modal.show();
    }

    // Show success modal
    function showSuccessModal(title, message) {
        document.getElementById('successModalTitle').textContent = title;
        document.getElementById('successModalMessage').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
});
</script>

<!-- Validation Modal -->
<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="validationModalTitle">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <p id="validationModalMessage" class="mb-0"></p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="successModalTitle">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <p id="successModalMessage" class="mb-0"></p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
