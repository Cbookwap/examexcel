<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1" style="color: #1f2937; font-weight: 600;"><?= $pageTitle ?></h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="exportActivityLog()">
                    <i class="fas fa-download me-2"></i>Export Log
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Activities</p>
                        <h4 class="mb-0 fw-bold"><?= $stats['total_activities'] ?></h4>
                    </div>
                    <div class="icon-shape bg-primary text-white rounded-circle">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">User Registrations</p>
                        <h4 class="mb-0 fw-bold"><?= $stats['user_registrations'] ?></h4>
                    </div>
                    <div class="icon-shape bg-success text-white rounded-circle">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Exam Attempts</p>
                        <h4 class="mb-0 fw-bold"><?= $stats['exam_attempts'] ?></h4>
                    </div>
                    <div class="icon-shape bg-warning text-white rounded-circle">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Today's Activities</p>
                        <h4 class="mb-0 fw-bold"><?= $stats['today_activities'] ?></h4>
                    </div>
                    <div class="icon-shape bg-info text-white rounded-circle">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h6 class="mb-0 fw-semibold">
            <i class="fas fa-filter me-2"></i>Filter Activities
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('admin/activity-log') ?>" class="row g-3">
            <div class="col-md-3">
                <label for="user_role" class="form-label">User Role</label>
                <select name="user_role" id="user_role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="admin" <?= $filters['user_role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="teacher" <?= $filters['user_role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                    <option value="student" <?= $filters['user_role'] === 'student' ? 'selected' : '' ?>>Student</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="action_type" class="form-label">Action Type</label>
                <select name="action_type" id="action_type" class="form-select">
                    <option value="">All Actions</option>
                    <option value="registration" <?= $filters['action_type'] === 'registration' ? 'selected' : '' ?>>User Registration</option>
                    <option value="exam" <?= $filters['action_type'] === 'exam' ? 'selected' : '' ?>>Exam Attempt</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="<?= $filters['date_from'] ?>">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="<?= $filters['date_to'] ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="<?= base_url('admin/activity-log') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activity Log Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="fas fa-history me-2"></i>Recent Activities
            </h6>
            <span class="badge bg-light text-dark"><?= isset($totalActivities) ? number_format($totalActivities) : count($activities) ?> activities</span>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($activities)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold" style="color: black !important;">User</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Activity</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Description</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Date & Time</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">Status</th>
                            <th class="border-0 fw-semibold" style="color: black !important;">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            <?= strtoupper(substr($activity['user_name'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-medium"><?= esc($activity['user_name']) ?></div>
                                            <small class="text-muted"><?= esc($activity['user_email']) ?></small>
                                            <br><span class="badge bg-light text-dark small"><?= ucfirst($activity['user_role']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= esc($activity['activity_type']) ?></span>
                                </td>
                                <td><?= esc($activity['description']) ?></td>
                                <td>
                                    <div><?= date('M j, Y', strtotime($activity['created_at'])) ?></div>
                                    <small class="text-muted"><?= date('g:i A', strtotime($activity['created_at'])) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($activity['status']) {
                                        'success' => 'bg-success',
                                        'in_progress' => 'bg-warning',
                                        'failed' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($activity['status']) ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= esc($activity['ip_address']) ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No activities found</h6>
                <p class="text-muted small">Try adjusting your filters to see more activities.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modern Pagination Controls -->
    <?php if (isset($pager) && isset($totalActivities) && $totalActivities > 0): ?>
        <div class="card-footer border-0 bg-transparent">
            <div class="pagination-container">
                <!-- Results Info -->
                <div class="pagination-info">
                    <div class="results-count">
                        <span class="showing-text">Showing</span>
                        <span class="count-range">
                            <?= ($currentPage - 1) * $perPage + 1 ?> - <?= min($currentPage * $perPage, $totalActivities) ?>
                        </span>
                        <span class="of-text">of</span>
                        <span class="total-count"><?= number_format($totalActivities) ?></span>
                        <span class="results-text">activities</span>
                    </div>
                </div>

                <!-- Pagination Controls -->
                <div class="pagination-controls">
                    <!-- Per Page Selector -->
                    <div class="per-page-selector">
                        <label for="perPageSelect" class="form-label small text-muted me-2">Show:</label>
                        <select id="perPageSelect" class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>

                    <?php if ($pager->getPageCount() > 1): ?>
                    <!-- Page Navigation -->
                    <div class="page-navigation">
                        <!-- Previous button -->
                        <button class="pagination-btn prev-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>"
                                onclick="goToPage(<?= $currentPage - 1 ?>)"
                                <?= $currentPage <= 1 ? 'disabled' : '' ?>>
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <!-- Page numbers -->
                        <div class="page-numbers">
                            <?php
                            $totalPages = $pager->getPageCount();
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $currentPage + 2);

                            // Show first page if not in range
                            if ($start > 1): ?>
                                <button class="pagination-btn page-number" onclick="goToPage(1)">1</button>
                                <?php if ($start > 2): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Page number buttons -->
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <button class="pagination-btn page-number <?= $i == $currentPage ? 'active' : '' ?>"
                                        onclick="goToPage(<?= $i ?>)"><?= $i ?></button>
                            <?php endfor; ?>

                            <!-- Show last page if not in range -->
                            <?php if ($end < $totalPages): ?>
                                <?php if ($end < $totalPages - 1): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                                <button class="pagination-btn page-number" onclick="goToPage(<?= $totalPages ?>)"><?= $totalPages ?></button>
                            <?php endif; ?>
                        </div>

                        <!-- Next button -->
                        <button class="pagination-btn next-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"
                                onclick="goToPage(<?= $currentPage + 1 ?>)"
                                <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Page Jump -->
                    <div class="page-input">
                        <label for="pageJumpInput" class="form-label small text-muted me-2">Go to:</label>
                        <input type="number" id="pageJumpInput" class="form-control form-control-sm"
                               style="width: 70px;" min="1" max="<?= $totalPages ?>"
                               value="<?= $currentPage ?>" onkeypress="handlePageJump(event)">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Modern Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1.5rem 0;
}

.pagination-info .results-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.pagination-info .count-range {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 2px 8px rgba(var(--primary-color-rgb), 0.3);
}

.pagination-info .total-count {
    color: var(--primary-color);
    font-weight: 700;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.per-page-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-navigation {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-numbers {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #e2e8f0;
    background: white;
    color: #64748b;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.pagination-btn:hover:not(.disabled) {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: rgba(var(--primary-color-rgb), 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.3);
}

.pagination-btn.active {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-color: var(--primary-color);
    color: white;
    box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.4);
}

.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-ellipsis {
    color: #94a3b8;
    font-weight: 600;
    padding: 0 0.5rem;
}

.page-input {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-input .form-control {
    text-align: center;
    font-weight: 600;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.page-input .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .pagination-container {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .pagination-controls {
        flex-direction: column;
        gap: 1rem;
    }

    .page-navigation {
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-btn {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }

    .per-page-selector,
    .page-input {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .pagination-btn {
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
    }

    .pagination-btn i {
        font-size: 1rem;
    }

    .page-numbers {
        gap: 0.125rem;
    }
}
</style>

<script>
function exportActivityLog() {
    // Get current filter parameters
    const params = new URLSearchParams(window.location.search);
    const exportUrl = '<?= base_url("admin/activity-log") ?>?' + params.toString() + '&export=csv';

    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'activity_log_' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Pagination functions
function goToPage(page) {
    <?php if (isset($pager)): ?>
    if (page < 1 || page > <?= $pager->getPageCount() ?>) {
        return false;
    }
    <?php else: ?>
    if (page < 1) {
        return false;
    }
    <?php endif; ?>

    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('page', page);
    window.location.href = currentUrl.toString();
    return false;
}

function changePerPage(perPage) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.set('page', 1); // Reset to first page
    window.location.href = currentUrl.toString();
}

function handlePageJump(event) {
    if (event.key === 'Enter') {
        const page = parseInt(event.target.value);
        <?php if (isset($pager)): ?>
        const totalPages = <?= $pager->getPageCount() ?>;
        <?php else: ?>
        const totalPages = 1;
        <?php endif; ?>

        if (page >= 1 && page <= totalPages) {
            goToPage(page);
        } else {
            event.target.value = <?= $currentPage ?? 1 ?>;
            alert('Please enter a valid page number between 1 and ' + totalPages);
        }
    }
}

// Initialize pagination on page load
document.addEventListener('DOMContentLoaded', function() {
    // Update page jump input max value
    const pageJumpInput = document.getElementById('pageJumpInput');
    if (pageJumpInput) {
        <?php if (isset($pager)): ?>
        pageJumpInput.max = <?= $pager->getPageCount() ?>;
        <?php else: ?>
        pageJumpInput.max = 1;
        <?php endif; ?>
    }
});
</script>

<?= $this->endSection() ?>
