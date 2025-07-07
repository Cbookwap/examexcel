<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
.stats-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.term-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
    margin-bottom: 1rem;
}

.term-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.current-badge {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.term-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    margin-right: 1rem;
}

.term-1 { background: linear-gradient(135deg, #4CAF50, #45a049); }
.term-2 { background: linear-gradient(135deg, #2196F3, #1976D2); }
.term-3 { background: linear-gradient(135deg, #FF9800, #F57C00); }

.info-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    background: white;
}

.btn-action {
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
    transform: translateY(-1px);
}

.btn-outline-primary {
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>


<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="material-symbols-rounded">check_circle</i></span>
        <span class="alert-text"><?= session()->getFlashdata('success') ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="material-symbols-rounded">error</i></span>
        <span class="alert-text"><?= session()->getFlashdata('error') ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><?= esc($session['session_name']) ?></h4>
                <p class="text-muted mb-0">Academic session details and term management</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/sessions/edit/' . $session['id']) ?>" class="btn btn-outline-primary btn-action">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">edit</i>Edit Session
                </a>
                <a href="<?= base_url('admin/sessions') ?>" class="btn btn-primary btn-action">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Sessions
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Session Information -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="info-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">info</i>
                    Session Information
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Session Name</label>
                        <p class="fw-semibold mb-0"><?= esc($session['session_name']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Status</label>
                        <div>
                            <?php if ($session['is_current']): ?>
                                <span class="current-badge">Current Session</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Start Date</label>
                        <p class="fw-semibold mb-0"><?= date('F j, Y', strtotime($session['start_date'])) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">End Date</label>
                        <p class="fw-semibold mb-0"><?= date('F j, Y', strtotime($session['end_date'])) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Duration</label>
                        <p class="fw-semibold mb-0">
                            <?php
                            $start = new DateTime($session['start_date']);
                            $end = new DateTime($session['end_date']);
                            $diff = $start->diff($end);
                            echo $diff->days . ' days';
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Created</label>
                        <p class="fw-semibold mb-0"><?= date('F j, Y', strtotime($session['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stats-card">
            <div class="card-body text-center p-4">
                <div class="icon-shape bg-gradient-primary shadow text-center border-radius-md mb-3 mx-auto" style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;">
                    <i class="material-symbols-rounded text-white" style="font-size: 24px; line-height: 56px;">school</i>
                </div>
                <h5 class="text-dark mb-0"><?= count($terms) ?></h5>
                <p class="text-sm mb-0 text-muted">Academic Terms</p>
            </div>
        </div>
    </div>
</div>

<!-- Terms Management -->
<div class="row">
    <div class="col-12">
        <div class="info-card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">calendar_month</i>
                    Academic Terms
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($terms)): ?>
                    <?php foreach ($terms as $term): ?>
                        <div class="term-card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="term-number term-<?= $term['term_number'] ?>">
                                            <?= $term['term_number'] ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold"><?= esc($term['term_name']) ?></h6>
                                            <p class="text-sm text-muted mb-0">
                                                <?= date('M j, Y', strtotime($term['start_date'])) ?> -
                                                <?= date('M j, Y', strtotime($term['end_date'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($term['is_current']): ?>
                                            <span class="current-badge">Current Term</span>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="setCurrentTerm(<?= $term['id'] ?>)" title="Set as Current Term">
                                                <i class="material-symbols-rounded" style="font-size: 16px;">play_arrow</i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded text-muted" style="font-size: 48px;">calendar_month</i>
                        <h6 class="text-muted mt-2">No terms found for this session</h6>
                        <p class="text-sm text-muted">Terms are automatically created when a session is created.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Set Current Term Modal -->
<div class="modal fade" id="setCurrentTermModal" tabindex="-1" aria-labelledby="setCurrentTermModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setCurrentTermModalLabel">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">play_arrow</i>
                    Set Current Term
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to set this as the current term?</p>
                <div class="alert alert-warning">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">warning</i>
                    This will deactivate the current term.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSetCurrentTerm">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">play_arrow</i>Set Current Term
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Modal variables
let currentTermId = null;

function setCurrentTerm(termId) {
    currentTermId = termId;
    const modal = new bootstrap.Modal(document.getElementById('setCurrentTermModal'));
    modal.show();
}

// Handle set current term confirmation
document.getElementById('confirmSetCurrentTerm').addEventListener('click', function() {
    if (currentTermId) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        this.disabled = true;

        // Redirect to set current term URL
        window.location.href = `<?= base_url('admin/sessions/set-current-term/') ?>${currentTermId}`;
    }
});
</script>
<?= $this->endSection() ?>
