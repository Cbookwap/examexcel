<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .results-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .results-card:hover {
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
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
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

    .results-actions {
        padding: 20px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    .btn-calculate {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-calculate:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        color: white;
    }

    .btn-view-results {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view-results:hover {
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

    .control-panel {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

    .grade-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .grade-a { background: #d4edda; color: #155724; }
    .grade-b { background: #cce7ff; color: #004085; }
    .grade-c { background: #fff3cd; color: #856404; }
    .grade-d { background: #f8d7da; color: #721c24; }
    .grade-f { background: #f5c6cb; color: #721c24; }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .results-actions {
            text-align: center;
        }

        .results-actions .btn {
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
                <li class="breadcrumb-item active">Term Results</li>
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

    <!-- Control Panel -->
    <div class="control-panel">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-2">Results Management</h5>
                <p class="text-muted mb-0">Calculate and manage term results for all classes</p>
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-calculate" onclick="calculateAllResults()">
                    <i class="fas fa-calculator me-2"></i>Calculate All Results
                </button>
            </div>
        </div>
    </div>

    <!-- Classes Results Grid -->
    <?php if (!empty($classes) && $currentSession && $currentTerm): ?>
        <div class="row">
            <?php foreach ($classes as $class): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="results-card">
                        <div class="class-header">
                            <h5 class="mb-1 text-white"><?= esc($class['name']) ?></h5>
                            <p class="mb-0 opacity-75">Section: <?= esc($class['section']) ?></p>
                        </div>

                        <div class="stats-grid">
                            <?php
                            $stats = $classResults[$class['id']]['stats'] ?? [
                                'total' => 0,
                                'promoted' => 0,
                                'repeated' => 0,
                                'conditional' => 0,
                                'average_percentage' => 0
                            ];
                            ?>
                            <div class="stat-item">
                                <span class="stat-number"><?= $stats['total'] ?></span>
                                <div class="stat-label">Students</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-success"><?= $stats['promoted'] ?></span>
                                <div class="stat-label">Promoted</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-warning"><?= $stats['repeated'] ?></span>
                                <div class="stat-label">Repeated</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-info"><?= $stats['conditional'] ?></span>
                                <div class="stat-label">Conditional</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number text-primary"><?= number_format($stats['average_percentage'], 1) ?>%</span>
                                <div class="stat-label">Average</div>
                            </div>
                        </div>

                        <div class="results-actions">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-view-results flex-fill"
                                        onclick="viewClassResults(<?= $class['id'] ?>)">
                                    <i class="fas fa-chart-bar me-2"></i>View Results
                                </button>
                                <button class="btn btn-calculate flex-fill"
                                        onclick="calculateClassResults(<?= $class['id'] ?>)">
                                    <i class="fas fa-calculator me-2"></i>Calculate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (!$currentSession || !$currentTerm): ?>
        <div class="empty-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h4>No Active Session/Term</h4>
            <p>Please set up an active academic session and term to view results.</p>
            <a href="<?= base_url('admin/settings') ?>" class="btn btn-primary">
                <i class="fas fa-cog me-2"></i>Configure Settings
            </a>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-graduation-cap"></i>
            <h4>No Classes Found</h4>
            <p>No active classes are available for results management.</p>
            <a href="<?= base_url('admin/classes') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Classes
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Calculate Results Modal -->
<div class="modal fade" id="calculateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calculate Term Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to calculate term results?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    This will process all exam results and generate term reports for students.
                </div>
                <div id="calculationProgress" class="d-none">
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <p class="text-center mb-0">Processing results...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" id="confirmCalculateBtn">
                    <i class="fas fa-calculator me-2"></i>Calculate Results
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let currentClassId = null;

function viewClassResults(classId) {
    window.location.href = `<?= base_url('admin/results?class_id=') ?>${classId}&session_id=<?= isset($currentSession['id']) ? $currentSession['id'] : 0 ?>&term_id=<?= isset($currentTerm['id']) ? $currentTerm['id'] : 0 ?>`;
}

function calculateClassResults(classId) {
    currentClassId = classId;
    const modal = new bootstrap.Modal(document.getElementById('calculateModal'));
    modal.show();
}

function calculateAllResults() {
    currentClassId = null;
    const modal = new bootstrap.Modal(document.getElementById('calculateModal'));
    modal.show();
}

document.getElementById('confirmCalculateBtn').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    const progressDiv = document.getElementById('calculationProgress');

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Calculating...';
    btn.disabled = true;
    progressDiv.classList.remove('d-none');

    const formData = new URLSearchParams({
        session_id: <?= isset($currentSession['id']) ? $currentSession['id'] : 0 ?>,
        term_id: <?= isset($currentTerm['id']) ? $currentTerm['id'] : 0 ?>
    });

    if (currentClassId) {
        formData.append('class_id', currentClassId);
    }

    fetch('<?= base_url('academic/calculate-term-results') ?>', {
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
            showNotification('Success', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error', data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error', 'An error occurred while calculating results.', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        progressDiv.classList.add('d-none');
        bootstrap.Modal.getInstance(document.getElementById('calculateModal')).hide();
    });
});

function showNotification(title, message, type) {
    // Implementation depends on your notification system
    console.log(`${type}: ${title} - ${message}`);
}
</script>
<?= $this->endSection() ?>
