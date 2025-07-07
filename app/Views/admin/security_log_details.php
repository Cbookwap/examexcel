<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_content') ?>

<style>
.detail-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.detail-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px 12px 0 0;
    border-bottom: none;
}

.detail-card-body {
    padding: 1.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #374151;
    min-width: 150px;
}

.detail-value {
    color: #6b7280;
    flex: 1;
    text-align: right;
}

.severity-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.severity-low {
    background-color: #d1fae5;
    color: #065f46;
}

.severity-medium {
    background-color: #fef3c7;
    color: #92400e;
}

.severity-high {
    background-color: #fee2e2;
    color: #991b1b;
}

.severity-critical {
    background-color: #fecaca;
    color: #7f1d1d;
    animation: pulse 2s infinite;
}

.event-type-badge {
    background-color: #e0e7ff;
    color: #3730a3;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
}

.json-viewer {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    max-height: 400px;
    overflow-y: auto;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #2563eb;
    color: white;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    border: none;
}

.btn-secondary:hover {
    background-color: #4b5563;
    color: white;
}

.btn-warning {
    background-color: #f59e0b;
    color: white;
    border: none;
}

.btn-warning:hover {
    background-color: #d97706;
    color: white;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
        <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
    </div>
    <div class="action-buttons">
        <a href="<?= base_url('admin/security') ?>" class="btn-action btn-secondary">
            <i class="material-symbols-rounded">arrow_back</i>
            Back to Security
        </a>
        <?php if (in_array($log['severity'], ['high', 'critical'])): ?>
        <a href="<?= base_url('admin/security/investigate/' . $log['id']) ?>" class="btn-action btn-warning">
            <i class="material-symbols-rounded">search</i>
            Investigate
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Security Log Details -->
<div class="row">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">info</i>
                    Security Event Information
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row">
                    <span class="detail-label">Event ID:</span>
                    <span class="detail-value"><code>#<?= $log['id'] ?></code></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Event Type:</span>
                    <span class="detail-value">
                        <span class="event-type-badge"><?= esc($log['event_type']) ?></span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Severity:</span>
                    <span class="detail-value">
                        <span class="severity-badge severity-<?= $log['severity'] ?>">
                            <?= strtoupper($log['severity']) ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Timestamp:</span>
                    <span class="detail-value">
                        <?= date('F j, Y \a\t g:i:s A', strtotime($log['created_at'])) ?>
                        <small class="text-muted d-block"><?= date('c', strtotime($log['created_at'])) ?></small>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">IP Address:</span>
                    <span class="detail-value"><code><?= esc($log['ip_address']) ?></code></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">User Agent:</span>
                    <span class="detail-value">
                        <small><?= esc($log['user_agent']) ?></small>
                    </span>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <?php if ($log['first_name']): ?>
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">person</i>
                    Student Information
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?= esc($log['first_name'] . ' ' . $log['last_name']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Student ID:</span>
                    <span class="detail-value"><code><?= esc($log['student_id']) ?></code></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?= esc($log['email']) ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Exam Information -->
        <?php if ($log['exam_title']): ?>
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">quiz</i>
                    Exam Information
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row">
                    <span class="detail-label">Exam Title:</span>
                    <span class="detail-value"><?= esc($log['exam_title']) ?></span>
                </div>
                <?php if ($log['subject_name']): ?>
                <div class="detail-row">
                    <span class="detail-label">Subject:</span>
                    <span class="detail-value"><?= esc($log['subject_name']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($log['started_at']): ?>
                <div class="detail-row">
                    <span class="detail-label">Exam Started:</span>
                    <span class="detail-value"><?= date('M j, Y g:i A', strtotime($log['started_at'])) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($log['submitted_at']): ?>
                <div class="detail-row">
                    <span class="detail-label">Exam Submitted:</span>
                    <span class="detail-value"><?= date('M j, Y g:i A', strtotime($log['submitted_at'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <!-- Event Data -->
        <?php if ($eventData): ?>
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">data_object</i>
                    Event Data
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="json-viewer">
                    <pre><?= json_encode($eventData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-rounded me-2">settings</i>
                    Quick Actions
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="d-grid gap-2">
                    <?php if ($log['first_name']): ?>
                    <a href="<?= base_url('admin/student-violations/' . $log['student_id']) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="material-symbols-rounded me-2">history</i>
                        View Student History
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="material-symbols-rounded me-2">print</i>
                        Print Details
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="copyToClipboard()">
                        <i class="material-symbols-rounded me-2">content_copy</i>
                        Copy Event ID
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    navigator.clipboard.writeText('<?= $log['id'] ?>').then(function() {
        alert('Event ID copied to clipboard!');
    });
}

// Auto-close window if opened in popup
if (window.opener) {
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.createElement('button');
        closeBtn.className = 'btn btn-outline-secondary btn-sm';
        closeBtn.innerHTML = '<i class="material-symbols-rounded me-2">close</i>Close Window';
        closeBtn.onclick = function() { window.close(); };
        
        const actionButtons = document.querySelector('.action-buttons');
        if (actionButtons) {
            actionButtons.appendChild(closeBtn);
        }
    });
}
</script>

<?= $this->endSection() ?>
