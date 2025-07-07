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

<?= $this->section($contentSection) ?>
<style>
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

<!-- Grade Distribution Chart -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">bar_chart</i>
                    Grade Distribution Chart
                </h5>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">analytics</i>
                    Grade Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($gradeData)): ?>
                    <?php
                    $total = array_sum($gradeData);
                    $grades = ['A', 'B', 'C', 'D', 'F'];
                    $colors = ['success', 'info', 'warning', 'secondary', 'danger'];
                    ?>
                    <?php foreach ($grades as $index => $grade): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-<?= $colors[$index] ?> me-2"><?= $grade ?></span>
                            <span>Grade <?= $grade ?></span>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold"><?= $gradeData[$grade] ?? 0 ?></div>
                            <small class="text-muted">
                                <?= $total > 0 ? number_format(($gradeData[$grade] ?? 0) / $total * 100, 1) : 0 ?>%
                            </small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="material-symbols-rounded mb-3 text-muted" style="font-size: 48px;">trending_up</i>
                        <p class="text-muted">No grade data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Grade Details Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="material-symbols-rounded me-2" style="font-size: 20px;">table_view</i>
                    Grade Distribution Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="stats-card">
                            <tr>
                                <th>Grade</th>
                                <th>Range</th>
                                <th>Count</th>
                                <th>Percentage</th>
                                <th>Visual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $gradeRanges = [
                                'A' => '80-100%',
                                'B' => '70-79%',
                                'C' => '60-69%',
                                'D' => '50-59%',
                                'F' => 'Below 50%'
                            ];
                            $total = array_sum($gradeData ?? []);
                            ?>
                            <?php foreach ($gradeRanges as $grade => $range): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-<?= $colors[array_search($grade, $grades)] ?> fs-6">
                                        <?= $grade ?>
                                    </span>
                                </td>
                                <td><?= $range ?></td>
                                <td class="fw-bold"><?= $gradeData[$grade] ?? 0 ?></td>
                                <td>
                                    <?php $percentage = $total > 0 ? ($gradeData[$grade] ?? 0) / $total * 100 : 0; ?>
                                    <?= number_format($percentage, 1) ?>%
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-<?= $colors[array_search($grade, $grades)] ?>"
                                             role="progressbar"
                                             style="width: <?= $percentage ?>%"
                                             aria-valuenow="<?= $percentage ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/vendor/chartjs/chart.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grade Distribution Chart
    const ctx = document.getElementById('gradeChart').getContext('2d');
    const gradeData = <?= json_encode($gradeData ?? []) ?>;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Grade A', 'Grade B', 'Grade C', 'Grade D', 'Grade F'],
            datasets: [{
                data: [
                    gradeData.A || 0,
                    gradeData.B || 0,
                    gradeData.C || 0,
                    gradeData.D || 0,
                    gradeData.F || 0
                ],
                backgroundColor: [
                    '#198754', // success
                    '#0dcaf0', // info
                    '#ffc107', // warning
                    '#6c757d', // secondary
                    '#dc3545'  // danger
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
