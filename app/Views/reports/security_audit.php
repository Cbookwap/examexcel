<?php
// Determine layout based on user role
$userRole = session()->get('role');
$layout = 'layouts/dashboard'; // default to admin layout
$contentSection = 'page_content';

if ($userRole === 'principal') {
    $layout = 'layouts/principal';
    $contentSection = 'page_content'; // Principal layout uses page_content section
} elseif ($userRole === 'teacher') {
    $layout = 'layouts/teacher';
    $contentSection = 'content';
}
?>

<?= $this->extend($layout) ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
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
</style>
<?= $this->endSection() ?>

<?= $this->section($contentSection) ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">print</i>Print Report
                </button>
<?php
                $backUrl = 'admin/reports'; // default
                if ($userRole === 'principal') {
                    $backUrl = 'principal/reports';
                } elseif ($userRole === 'teacher') {
                    $backUrl = 'teacher/reports';
                }
                ?>
                <a href="<?= base_url($backUrl) ?>" class="btn btn-primary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Security Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $stats['total_events'] ?? 0 ?></h5>
                        <p class="card-text mb-0">Total Events</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">security</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $stats['critical_events'] ?? 0 ?></h5>
                        <p class="card-text mb-0">Critical Events</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">warning</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= count($stats['event_types'] ?? []) ?></h5>
                        <p class="card-text mb-0">Event Types</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">category</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= count($securityLogs ?? []) ?></h5>
                        <p class="card-text mb-0">Recent Logs</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">history</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Security Logs Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">list</i>
                    Security Audit Log
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($securityLogs)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>Event Type</th>
                                <th>Severity</th>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>IP Address</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($securityLogs as $log): ?>
                            <tr>
                                <td><?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= esc(str_replace('_', ' ', ucwords($log['event_type'], '_'))) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?=
                                        $log['severity'] === 'critical' ? 'danger' :
                                        ($log['severity'] === 'high' ? 'warning' :
                                        ($log['severity'] === 'medium' ? 'info' : 'secondary'))
                                    ?>">
                                        <?= ucfirst($log['severity']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($log['first_name']): ?>
                                        <div>
                                            <div class="fw-medium"><?= esc($log['first_name'] . ' ' . $log['last_name']) ?></div>
                                            <small class="text-muted"><?= esc($log['student_id']) ?></small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log['exam_title'] ?? 'N/A') ?></td>
                                <td><code><?= esc($log['ip_address']) ?></code></td>
                                <td>
                                    <?php if ($log['event_data']): ?>
                                        <button class="btn btn-sm btn-outline-info"
                                                onclick="showEventDetails('<?= esc(json_encode($log['event_data'])) ?>')">
                                            View Details
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">No details</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">security</i>
                    <h5 class="text-muted">No security logs available</h5>
                    <p class="text-muted">Security events will appear here once system activities are logged.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="eventDetailsContent" class="bg-light p-3 rounded"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function showEventDetails(eventData) {
    try {
        const data = JSON.parse(eventData);
        document.getElementById('eventDetailsContent').textContent = JSON.stringify(data, null, 2);
        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        modal.show();
    } catch (e) {
        document.getElementById('eventDetailsContent').textContent = eventData;
        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        modal.show();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Security Audit Report loaded');
});
</script>
<?= $this->endSection() ?>
