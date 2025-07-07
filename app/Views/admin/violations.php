<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
    .violation-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 15px;
        color: white;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 32px rgba(var(--primary-color-rgb), 0.3);
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary-color);
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .violation-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .violation-badge.critical {
        background: #dc3545;
        color: white;
    }

    .violation-badge.high {
        background: #fd7e14;
        color: white;
    }

    .violation-badge.medium {
        background: #ffc107;
        color: #212529;
    }

    .violation-badge.low {
        background: #28a745;
        color: white;
    }

    .punishment-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .punishment-badge.warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .punishment-badge.temporary_suspension {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .punishment-badge.permanent_ban {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .event-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .event-badge.critical {
        background: #dc3545;
        color: white;
    }

    .event-badge.high {
        background: #fd7e14;
        color: white;
    }

    .event-badge.medium {
        background: #6f42c1;
        color: white;
    }

    .event-badge.low {
        background: #6c757d;
        color: white;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .violator-item {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-left: 4px solid #dc3545;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .violation-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .violation-timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .violation-timeline-item {
        position: relative;
        margin-bottom: 1rem;
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .violation-timeline-item::before {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 1rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #dee2e6;
    }

    .quick-action-btn {
        transition: all 0.3s ease;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .bulk-actions-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin-bottom: 1rem;
    }

    .student-checkbox-container {
        transition: all 0.2s ease;
    }

    .student-checkbox-container:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .violator-item {
        transition: all 0.2s ease;
    }

    .violator-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="violation-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 font-weight-bolder text-white">
                        <i class="fas fa-shield-alt me-2 text-white"></i>
                        <?= $pageTitle ?>
                    </h3>
                    <p class="mb-0 opacity-75"><?= $pageSubtitle ?></p>
                </div>
                <div class="text-end">
                    <div class="h4 mb-0"><?= count($recentViolations) + count($violations) ?></div>
                    <small>Total Violations</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Total Violations</div>
                    <div class="h4 mb-0 text-primary"><?= number_format($violationStats['total_violations'] ?? 0) ?></div>
                </div>
                <div class="text-primary">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Warnings</div>
                    <div class="h4 mb-0 text-warning"><?= number_format($violationStats['warnings'] ?? 0) ?></div>
                </div>
                <div class="text-warning">
                    <i class="fas fa-exclamation fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Suspensions</div>
                    <div class="h4 mb-0 text-danger"><?= number_format($violationStats['suspensions'] ?? 0) ?></div>
                </div>
                <div class="text-danger">
                    <i class="fas fa-user-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Permanent Bans</div>
                    <div class="h4 mb-0 text-dark"><?= number_format($violationStats['bans'] ?? 0) ?></div>
                </div>
                <div class="text-dark">
                    <i class="fas fa-user-slash fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Violations</h5>
    <form method="GET" action="<?= base_url('admin/violations') ?>">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-select">
                    <option value="">All Students</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student['id'] ?>" <?= $filters['student_id'] == $student['id'] ? 'selected' : '' ?>>
                            <?= esc($student['first_name'] . ' ' . $student['last_name']) ?> (<?= esc($student['student_id']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="punishment_type" class="form-label">Punishment</label>
                <select name="punishment_type" id="punishment_type" class="form-select">
                    <option value="">All Types</option>
                    <?php foreach ($punishmentTypes as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $filters['punishment_type'] == $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="severity" class="form-label">Severity</label>
                <select name="severity" id="severity" class="form-select">
                    <option value="">All Levels</option>
                    <?php foreach ($severityLevels as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $filters['severity'] == $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="<?= $filters['date_from'] ?>">
            </div>
            <div class="col-md-2 mb-3">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="<?= $filters['date_to'] ?>">
            </div>
            <div class="col-md-1 mb-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <!-- Recent Violations -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Recent Violations</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentViolations)): ?>
                    <div class="violation-timeline">
                        <?php foreach ($recentViolations as $violation): ?>
                            <div class="violation-timeline-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <strong><?= esc($violation['first_name'] . ' ' . $violation['last_name']) ?></strong>
                                            <span class="ms-2 text-muted">(<?= esc($violation['student_number']) ?>)</span>
                                            <span class="violation-badge <?= $violation['severity'] ?> ms-2"><?= ucfirst($violation['severity']) ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <?php if (isset($violation['event_type'])): ?>
                                                <!-- Security Event -->
                                                <span class="event-badge <?= $violation['severity'] ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $violation['event_type'])) ?>
                                                </span>
                                                <?php if (isset($violation['exam_title'])): ?>
                                                    <small class="text-muted ms-2">in <?= esc($violation['exam_title']) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- Applied Punishment -->
                                                <span class="punishment-badge <?= $violation['punishment_type'] ?>">
                                                    <?= $punishmentTypes[$violation['punishment_type']] ?? ucfirst(str_replace('_', ' ', $violation['punishment_type'])) ?>
                                                </span>
                                                <?php if ($violation['punishment_duration']): ?>
                                                    <small class="text-muted ms-2">(<?= $violation['punishment_duration'] ?> days)</small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('M j, Y g:i A', strtotime($violation['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('admin/student-violations/' . $violation['student_id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-shield-alt text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">No Violations Found</h5>
                        <p class="text-muted">All students are following security protocols.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Violators & Suspended Students -->
    <div class="col-lg-4">
        <!-- Top Violators -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Top Violators</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topViolators)): ?>
                    <?php foreach ($topViolators as $violator): ?>
                        <div class="violator-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= esc($violator['first_name'] . ' ' . $violator['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($violator['student_number']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger"><?= $violator['violation_count'] ?? $violator['max_violations'] ?? 0 ?> violations</div>
                                    <small class="text-muted">
                                        Last: <?= date('M j', strtotime($violator['last_violation'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-smile text-success mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">No repeat violators</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Suspended Students -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-slash me-2"></i>Suspended/Banned Students</h5>
                <div class="btn-group">
                    <?php if (!empty($suspendedStudents)): ?>
                        <button type="button" class="btn btn-sm btn-success quick-action-btn" onclick="showBulkLiftModal()">
                            <i class="fas fa-unlock me-1"></i>Bulk Lift Bans
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-warning" onclick="showClearIncorrectBansModal()" title="Clear all bans that were applied when strict security mode was disabled">
                        <i class="fas fa-broom me-1"></i>Clear Incorrect Bans
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($suspendedStudents)): ?>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllSuspended" onchange="toggleAllSuspended()">
                            <label class="form-check-label" for="selectAllSuspended">
                                <small>Select All</small>
                            </label>
                        </div>
                    </div>
                    <?php foreach ($suspendedStudents as $student): ?>
                        <div class="violator-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-3">
                                        <input class="form-check-input suspended-student-checkbox" type="checkbox"
                                               value="<?= $student['id'] ?>" id="student_<?= $student['id'] ?>">
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></div>
                                        <small class="text-muted"><?= esc($student['student_id']) ?></small>
                                        <?php if ($student['exam_banned']): ?>
                                            <div><span class="badge bg-danger">Permanently Banned</span></div>
                                        <?php elseif ($student['exam_suspended_until'] && strtotime($student['exam_suspended_until']) > time()): ?>
                                            <div><span class="badge bg-warning">Suspended until <?= date('M j', strtotime($student['exam_suspended_until'])) ?></span></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <?php if ($student['exam_banned']): ?>
                                        <button type="button" class="btn btn-sm btn-success quick-action-btn"
                                                onclick="quickLiftBan(<?= $student['id'] ?>, '<?= esc($student['first_name'] . ' ' . $student['last_name']) ?>')">
                                            <i class="fas fa-unlock me-1"></i>Lift Ban
                                        </button>
                                    <?php elseif ($student['exam_suspended_until'] && strtotime($student['exam_suspended_until']) > time()): ?>
                                        <button type="button" class="btn btn-sm btn-warning quick-action-btn"
                                                onclick="quickRemoveSuspension(<?= $student['id'] ?>, '<?= esc($student['first_name'] . ' ' . $student['last_name']) ?>')">
                                            <i class="fas fa-clock me-1"></i>Remove Suspension
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?= base_url('admin/student-violations/' . $student['id']) ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-check-circle text-success mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">No suspended students</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Lift Ban Modal -->
<div class="modal fade" id="quickLiftBanModal" tabindex="-1" aria-labelledby="quickLiftBanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickLiftBanModalLabel">
                    <i class="fas fa-unlock text-success me-2"></i>
                    Lift Ban
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Quick Action:</strong> This will immediately lift the ban and restore exam access.
                </div>
                <p>Are you sure you want to lift the ban for <strong id="liftBanStudentName"></strong>?</p>
                <div class="mb-3">
                    <label for="liftBanReason" class="form-label">Reason (Optional)</label>
                    <textarea class="form-control" id="liftBanReason" rows="2" placeholder="Enter reason for lifting the ban..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="confirmLiftBan()">
                    <i class="fas fa-unlock me-1"></i>Yes, Lift Ban
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Remove Suspension Modal -->
<div class="modal fade" id="quickRemoveSuspensionModal" tabindex="-1" aria-labelledby="quickRemoveSuspensionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickRemoveSuspensionModalLabel">
                    <i class="fas fa-clock text-warning me-2"></i>
                    Remove Suspension
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Quick Action:</strong> This will immediately remove the suspension and restore exam access.
                </div>
                <p>Are you sure you want to remove the suspension for <strong id="removeSuspensionStudentName"></strong>?</p>
                <div class="mb-3">
                    <label for="removeSuspensionReason" class="form-label">Reason (Optional)</label>
                    <textarea class="form-control" id="removeSuspensionReason" rows="2" placeholder="Enter reason for removing the suspension..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="confirmRemoveSuspension()">
                    <i class="fas fa-clock me-1"></i>Yes, Remove Suspension
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Lift Bans Modal -->
<div class="modal fade" id="bulkLiftModal" tabindex="-1" aria-labelledby="bulkLiftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkLiftModalLabel">
                    <i class="fas fa-unlock text-success me-2"></i>
                    Bulk Lift Bans/Suspensions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Bulk Action:</strong> This will lift bans/suspensions for all selected students.
                </div>
                <p>Are you sure you want to lift bans/suspensions for <strong id="bulkLiftCount">0</strong> selected students?</p>
                <div class="mb-3">
                    <label for="bulkLiftReason" class="form-label">Reason (Optional)</label>
                    <textarea class="form-control" id="bulkLiftReason" rows="2" placeholder="Enter reason for bulk lifting bans/suspensions..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="confirmBulkLift()">
                    <i class="fas fa-unlock me-1"></i>Yes, Lift All Selected
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Incorrect Bans Modal -->
<div class="modal fade" id="clearIncorrectBansModal" tabindex="-1" aria-labelledby="clearIncorrectBansModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearIncorrectBansModalLabel">
                    <i class="fas fa-broom text-warning me-2"></i>
                    Clear Incorrect Bans
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>System Fix:</strong> This will clear all bans/suspensions that were incorrectly applied when strict security mode was disabled.
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> This action will clear ALL current bans and suspensions for ALL students. Use this only if you're sure that students were incorrectly banned when strict security mode was turned off.
                </div>
                <p>Are you sure you want to clear all incorrect bans and suspensions? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="confirmClearIncorrectBans()">
                    <i class="fas fa-broom me-1"></i>Yes, Clear All Incorrect Bans
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables for quick actions
let currentStudentId = null;
let currentAction = null;

// Quick lift ban function
function quickLiftBan(studentId, studentName) {
    currentStudentId = studentId;
    currentAction = 'lift_ban';
    document.getElementById('liftBanStudentName').textContent = studentName;
    document.getElementById('liftBanReason').value = '';

    const modal = new bootstrap.Modal(document.getElementById('quickLiftBanModal'));
    modal.show();
}

// Quick remove suspension function
function quickRemoveSuspension(studentId, studentName) {
    currentStudentId = studentId;
    currentAction = 'remove_suspension';
    document.getElementById('removeSuspensionStudentName').textContent = studentName;
    document.getElementById('removeSuspensionReason').value = '';

    const modal = new bootstrap.Modal(document.getElementById('quickRemoveSuspensionModal'));
    modal.show();
}

// Confirm lift ban
function confirmLiftBan() {
    const reason = document.getElementById('liftBanReason').value;
    performQuickAction('clear', reason);
}

// Confirm remove suspension
function confirmRemoveSuspension() {
    const reason = document.getElementById('removeSuspensionReason').value;
    performQuickAction('clear', reason);
}

// Perform quick action
function performQuickAction(punishmentType, reason) {
    if (!currentStudentId) return;

    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/apply-punishment') ?>';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Add form fields
    const fields = {
        'student_id': currentStudentId,
        'punishment_type': punishmentType,
        'reason': reason || 'Quick action by admin'
    };

    for (const [name, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

// Toggle all suspended students checkboxes
function toggleAllSuspended() {
    const selectAll = document.getElementById('selectAllSuspended');
    const checkboxes = document.querySelectorAll('.suspended-student-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });

    updateBulkLiftCount();
}

// Update bulk lift count
function updateBulkLiftCount() {
    const checkedBoxes = document.querySelectorAll('.suspended-student-checkbox:checked');
    document.getElementById('bulkLiftCount').textContent = checkedBoxes.length;
}

// Show clear incorrect bans modal
function showClearIncorrectBansModal() {
    const modal = new bootstrap.Modal(document.getElementById('clearIncorrectBansModal'));
    modal.show();
}

// Confirm clear incorrect bans
function confirmClearIncorrectBans() {
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/clear-incorrect-bans') ?>';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    document.body.appendChild(form);
    form.submit();
}

// Show bulk lift modal
function showBulkLiftModal() {
    const checkedBoxes = document.querySelectorAll('.suspended-student-checkbox:checked');

    if (checkedBoxes.length === 0) {
        alert('Please select at least one student to lift bans/suspensions.');
        return;
    }

    updateBulkLiftCount();
    document.getElementById('bulkLiftReason').value = '';

    const modal = new bootstrap.Modal(document.getElementById('bulkLiftModal'));
    modal.show();
}

// Confirm bulk lift
function confirmBulkLift() {
    const checkedBoxes = document.querySelectorAll('.suspended-student-checkbox:checked');
    const reason = document.getElementById('bulkLiftReason').value;

    if (checkedBoxes.length === 0) {
        alert('No students selected.');
        return;
    }

    // Create and submit form for bulk action
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('admin/bulk-lift-bans') ?>';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Add student IDs
    checkedBoxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'student_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    // Add reason
    const reasonInput = document.createElement('input');
    reasonInput.type = 'hidden';
    reasonInput.name = 'reason';
    reasonInput.value = reason || 'Bulk action by admin';
    form.appendChild(reasonInput);

    document.body.appendChild(form);
    form.submit();
}

// Add event listeners for checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.suspended-student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkLiftCount);
    });
});
</script>

<?= $this->endSection() ?>
