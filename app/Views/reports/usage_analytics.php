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

<!-- Usage Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= $usageData['total_events'] ?? 0 ?></h5>
                        <p class="card-text mb-0">Total Events</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">analytics</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?= count($usageData['event_types'] ?? []) ?></h5>
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
                        <h5 class="card-title mb-1"><?= count($usageData['daily_activity'] ?? []) ?></h5>
                        <p class="card-text mb-0">Active Days</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">calendar_today</i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">
                            <?php
                            $criticalEvents = 0;
                            if (!empty($usageData['severity_stats'])) {
                                foreach ($usageData['severity_stats'] as $severity) {
                                    if ($severity['severity'] === 'critical') {
                                        $criticalEvents = $severity['count'];
                                        break;
                                    }
                                }
                            }
                            echo $criticalEvents;
                            ?>
                        </h5>
                        <p class="card-text mb-0">Critical Events</p>
                    </div>
                    <i class="material-symbols-rounded" style="font-size: 48px; opacity: 0.7;">warning</i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Types Chart -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">bar_chart</i>
                    Event Types Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="eventTypesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">pie_chart</i>
                    Severity Breakdown
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($usageData['severity_stats'])): ?>
                    <?php foreach ($usageData['severity_stats'] as $severity): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-<?= $severity['severity'] === 'critical' ? 'danger' : ($severity['severity'] === 'high' ? 'warning' : 'info') ?> me-2">
                                <?= ucfirst($severity['severity']) ?>
                            </span>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold"><?= $severity['count'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 48px;">pie_chart</i>
                        <p class="text-muted">No severity data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Event Types Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">table_view</i>
                    Event Types Details
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($usageData['event_types'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Event Type</th>
                                <th>Count</th>
                                <th>Percentage</th>
                                <th>Visual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = $usageData['total_events'] ?? 1;
                            foreach ($usageData['event_types'] as $eventType):
                                $percentage = ($eventType['count'] / $total) * 100;
                            ?>
                            <tr>
                                <td class="fw-medium"><?= esc(str_replace('_', ' ', ucwords($eventType['event_type'], '_'))) ?></td>
                                <td><?= $eventType['count'] ?></td>
                                <td><?= number_format($percentage, 1) ?>%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary"
                                             role="progressbar"
                                             style="width: <?= $percentage ?>%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 64px;">analytics</i>
                    <h5 class="text-muted">No usage analytics available</h5>
                    <p class="text-muted">Usage data will appear here once system activities are logged.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/vendor/chartjs/chart.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event Types Chart
    const ctx = document.getElementById('eventTypesChart').getContext('2d');
    const eventTypes = <?= json_encode($usageData['event_types'] ?? []) ?>;

    if (eventTypes.length > 0) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: eventTypes.map(item => item.event_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())),
                datasets: [{
                    label: 'Event Count',
                    data: eventTypes.map(item => item.count),
                    backgroundColor: '#0d6efd',
                    borderColor: '#0d6efd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
