<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.investigation-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.investigation-card-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px 12px 0 0;
}

.investigation-card-body {
    padding: 1.5rem;
}

.timeline-item {
    border-left: 3px solid #e5e7eb;
    padding-left: 1rem;
    margin-bottom: 1rem;
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 0.5rem;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #6b7280;
}

.timeline-item.severity-high::before {
    background-color: #dc2626;
}

.timeline-item.severity-critical::before {
    background-color: #7f1d1d;
    animation: pulse 2s infinite;
}

.timeline-item.severity-medium::before {
    background-color: #f59e0b;
}

.timeline-item.severity-low::before {
    background-color: #10b981;
}

.alert-danger {
    background-color: #fef2f2;
    border-color: #fecaca;
    color: #991b1b;
}

.alert-warning {
    background-color: #fffbeb;
    border-color: #fed7aa;
    color: #92400e;
}

.severity-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.severity-low { background-color: #d1fae5; color: #065f46; }
.severity-medium { background-color: #fef3c7; color: #92400e; }
.severity-high { background-color: #fee2e2; color: #991b1b; }
.severity-critical { background-color: #fecaca; color: #7f1d1d; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
        <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
    </div>
    <div>
        <a href="<?= base_url('admin/security') ?>" class="btn btn-secondary">
            <i class="material-symbols-rounded me-2">arrow_back</i>
            Back to Security
        </a>
    </div>
</div>

<!-- Alert for Critical Events -->
<?php if ($log['severity'] === 'critical'): ?>
<div class="alert alert-danger" role="alert">
    <div class="d-flex align-items-center">
        <i class="material-symbols-rounded me-2">warning</i>
        <div>
            <strong>Critical Security Event!</strong> This event requires immediate attention and investigation.
        </div>
    </div>
</div>
<?php elseif ($log['severity'] === 'high'): ?>
<div class="alert alert-warning" role="alert">
    <div class="d-flex align-items-center">
        <i class="material-symbols-rounded me-2">priority_high</i>
        <div>
            <strong>High Priority Event!</strong> This security event should be reviewed carefully.
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <!-- Primary Event Details -->
        <div class="investigation-card">
            <div class="investigation-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">gavel</i>
                    Primary Security Event
                </h5>
            </div>
            <div class="investigation-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Event Information</h6>
                        <p><strong>Event ID:</strong> #<?= $log['id'] ?></p>
                        <p><strong>Type:</strong> <span class="badge bg-secondary"><?= esc($log['event_type']) ?></span></p>
                        <p><strong>Severity:</strong> <span class="severity-badge severity-<?= $log['severity'] ?>"><?= strtoupper($log['severity']) ?></span></p>
                        <p><strong>Timestamp:</strong> <?= date('M j, Y g:i:s A', strtotime($log['created_at'])) ?></p>
                        <p><strong>IP Address:</strong> <code><?= esc($log['ip_address']) ?></code></p>
                    </div>
                    <div class="col-md-6">
                        <?php if ($log['first_name']): ?>
                        <h6>Student Information</h6>
                        <p><strong>Name:</strong> <?= esc($log['first_name'] . ' ' . $log['last_name']) ?></p>
                        <p><strong>Student ID:</strong> <?= esc($log['student_id']) ?></p>
                        <p><strong>Exam:</strong> <?= esc($log['exam_title']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($eventData): ?>
                <div class="mt-3">
                    <h6>Event Data</h6>
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0" style="font-size: 0.875rem;"><?= json_encode($eventData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Events Timeline -->
        <?php if (!empty($relatedEvents)): ?>
        <div class="investigation-card">
            <div class="investigation-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">timeline</i>
                    Related Security Events
                </h5>
            </div>
            <div class="investigation-card-body">
                <div class="timeline">
                    <?php foreach ($relatedEvents as $event): ?>
                    <div class="timeline-item severity-<?= $event['severity'] ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?= esc($event['event_type']) ?></h6>
                                <p class="text-muted mb-1"><?= esc($event['exam_title']) ?></p>
                                <small class="text-muted"><?= date('M j, Y g:i A', strtotime($event['created_at'])) ?></small>
                            </div>
                            <div>
                                <span class="severity-badge severity-<?= $event['severity'] ?>">
                                    <?= strtoupper($event['severity']) ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($event['event_data']): ?>
                        <div class="mt-2">
                            <button class="btn btn-outline-info btn-sm" onclick="toggleEventData(<?= $event['id'] ?>)">
                                <i class="material-symbols-rounded">info</i> View Data
                            </button>
                            <div id="event-data-<?= $event['id'] ?>" class="mt-2 d-none">
                                <div class="bg-light p-2 rounded">
                                    <pre class="mb-0" style="font-size: 0.75rem;"><?php
                                        $eventDataForDisplay = is_string($event['event_data']) ? json_decode($event['event_data'], true) : $event['event_data'];
                                        echo json_encode($eventDataForDisplay, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                    ?></pre>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <!-- Investigation Statistics -->
        <?php if (!empty($relatedEvents)): ?>
        <div class="investigation-card">
            <div class="investigation-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">analytics</i>
                    Investigation Summary
                </h5>
            </div>
            <div class="investigation-card-body">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?= count($relatedEvents) + 1 ?></div>
                        <div class="stat-label">Total Events</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                            $criticalCount = array_filter($relatedEvents, function($e) { return $e['severity'] === 'critical'; });
                            echo count($criticalCount) + ($log['severity'] === 'critical' ? 1 : 0);
                            ?>
                        </div>
                        <div class="stat-label">Critical</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                            $highCount = array_filter($relatedEvents, function($e) { return $e['severity'] === 'high'; });
                            echo count($highCount) + ($log['severity'] === 'high' ? 1 : 0);
                            ?>
                        </div>
                        <div class="stat-label">High</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Investigation Actions -->
        <div class="investigation-card">
            <div class="investigation-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">build</i>
                    Investigation Actions
                </h5>
            </div>
            <div class="investigation-card-body">
                <div class="d-grid gap-2">
                    <?php if ($log['first_name']): ?>
                    <a href="<?= base_url('admin/student-violations/' . $log['student_id']) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="material-symbols-rounded me-2">person_search</i>
                        View Student Profile
                    </a>
                    <a href="<?= base_url('admin/violations') ?>?student_id=<?= $log['student_id'] ?>" class="btn btn-outline-warning btn-sm">
                        <i class="material-symbols-rounded me-2">history</i>
                        Full Violation History
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-info btn-sm" onclick="exportInvestigation()">
                        <i class="material-symbols-rounded me-2">download</i>
                        Export Report
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="material-symbols-rounded me-2">print</i>
                        Print Investigation
                    </button>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="investigation-card">
            <div class="investigation-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">lightbulb</i>
                    Recommendations
                </h5>
            </div>
            <div class="investigation-card-body">
                <?php if ($log['severity'] === 'critical'): ?>
                <div class="alert alert-danger">
                    <strong>Immediate Action Required:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Review student's exam access immediately</li>
                        <li>Consider temporary suspension pending investigation</li>
                        <li>Check for similar patterns in other students</li>
                    </ul>
                </div>
                <?php elseif ($log['severity'] === 'high'): ?>
                <div class="alert alert-warning">
                    <strong>Recommended Actions:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Monitor student's future exam attempts</li>
                        <li>Review security settings for this exam type</li>
                        <li>Consider additional proctoring measures</li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <strong>Monitoring Suggestions:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Continue monitoring for patterns</li>
                        <li>Document for future reference</li>
                        <li>No immediate action required</li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEventData(eventId) {
    const element = document.getElementById('event-data-' + eventId);
    element.classList.toggle('d-none');
}

function exportInvestigation() {
    // Create a simple text export of the investigation
    const content = `Security Investigation Report
Event ID: #<?= $log['id'] ?>
Type: <?= $log['event_type'] ?>
Severity: <?= strtoupper($log['severity']) ?>
Timestamp: <?= date('M j, Y g:i:s A', strtotime($log['created_at'])) ?>
<?php if ($log['first_name']): ?>
Student: <?= $log['first_name'] . ' ' . $log['last_name'] ?> (<?= $log['student_id'] ?>)
<?php endif; ?>

Related Events: <?= count($relatedEvents) ?>
`;

    const blob = new Blob([content], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'security_investigation_<?= $log['id'] ?>.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Auto-close window if opened in popup
if (window.opener) {
    document.addEventListener('DOMContentLoaded', function() {
        const backBtn = document.querySelector('a[href*="admin/security"]');
        if (backBtn) {
            backBtn.innerHTML = '<i class="material-symbols-rounded me-2">close</i>Close Window';
            backBtn.onclick = function(e) { 
                e.preventDefault(); 
                window.close(); 
            };
        }
    });
}
</script>

<?= $this->endSection() ?>
