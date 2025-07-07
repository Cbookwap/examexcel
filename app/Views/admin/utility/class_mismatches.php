<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $pageTitle ?></h3>
                    <p class="card-subtitle"><?= $pageSubtitle ?></p>
                </div>
                <div class="card-body">
                    <?php if (empty($mismatches)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Great!</strong> No class mismatches found in the system.
                            All exam questions are properly assigned to their correct class levels.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning!</strong> Found <?= count($mismatches) ?> question(s) with class mismatches.
                            These questions are assigned to exams for different class levels.
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-danger" onclick="cleanupAllMismatches()">
                                <i class="fas fa-broom"></i> Clean Up All Mismatches
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Question</th>
                                        <th>Subject</th>
                                        <th>Exam Class</th>
                                        <th>Question Class</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mismatches as $mismatch): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($mismatch['exam_title']) ?></strong><br>
                                                <small class="text-muted">ID: <?= $mismatch['exam_id'] ?></small>
                                            </td>
                                            <td>
                                                <?= esc(substr($mismatch['question_text'], 0, 100)) ?>...
                                                <br><small class="text-muted">ID: <?= $mismatch['question_id'] ?></small>
                                            </td>
                                            <td><?= esc($mismatch['subject_name']) ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?= esc($mismatch['exam_class_name']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger"><?= esc($mismatch['question_class_name']) ?></span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" 
                                                        onclick="cleanupExamMismatches(<?= $mismatch['exam_id'] ?>)">
                                                    <i class="fas fa-broom"></i> Clean Exam
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cleanupExamMismatches(examId) {
    if (!confirm('Are you sure you want to remove all mismatched questions from this exam?')) {
        return;
    }

    fetch(`<?= base_url('admin-utility/cleanup-exam-mismatches/') ?>${examId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while cleaning up mismatches.');
    });
}

function cleanupAllMismatches() {
    showConfirmModal(
        'Confirm Cleanup All Mismatches',
        'Are you sure you want to remove ALL mismatched questions from ALL exams? This action cannot be undone.',
        function() {
            fetch(`<?= base_url('admin-utility/cleanup-all-mismatches') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlertModal('Success', data.message + '\nTotal removed: ' + data.total_removed, 'success', function() {
                        location.reload();
                    });
                } else {
                    showAlertModal('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('Error', 'An error occurred while cleaning up mismatches.', 'error');
            });
        }
    );
}

// Helper functions for custom modals
function showConfirmModal(title, message, onConfirm) {
    const modalHtml = `
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            ${title}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone.
                        </div>
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmBtn">Yes, Continue</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal
    const existingModal = document.getElementById('confirmModal');
    if (existingModal) existingModal.remove();

    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    document.getElementById('confirmBtn').addEventListener('click', function() {
        modal.hide();
        if (onConfirm) onConfirm();
    });

    modal.show();
}

function showAlertModal(title, message, type = 'info', onClose = null) {
    const iconClass = type === 'success' ? 'fas fa-check-circle text-success' :
                     type === 'error' ? 'fas fa-exclamation-circle text-danger' :
                     'fas fa-info-circle text-info';

    const modalHtml = `
        <div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="${iconClass} me-2"></i>
                            ${title}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p style="white-space: pre-line;">${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal
    const existingModal = document.getElementById('alertModal');
    if (existingModal) existingModal.remove();

    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const modal = new bootstrap.Modal(document.getElementById('alertModal'));
    if (onClose) {
        document.getElementById('alertModal').addEventListener('hidden.bs.modal', onClose);
    }

    modal.show();
}
</script>
<?= $this->endSection() ?>
