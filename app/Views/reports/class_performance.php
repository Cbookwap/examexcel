<?php
// Determine layout based on user role
$userRole = session()->get('role');
$layout = 'layouts/dashboard'; // default to admin layout
$contentSection = 'page_content';

if ($userRole === 'principal') {
    $layout = 'layouts/principal';
    $contentSection = 'page_content';
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
                <h4 class="fw-bold mb-1" style="color: black;">
                    <i class="material-symbols-rounded me-2" style="font-size: 24px;">assessment</i>
                    <?= $pageTitle ?>
                </h4>
                <p class="text-muted mb-0"><?= $pageSubtitle ?></p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">print</i>Print Report
                </button>
                <button class="btn btn-outline-success" onclick="exportToExcel()">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">download</i>Export CSV
                </button>
<?php
                $backUrl = 'admin/reports'; // default
                if ($userRole === 'principal') {
                    $backUrl = 'principal/reports';
                } elseif ($userRole === 'teacher') {
                    $backUrl = 'teacher/reports';
                }
                ?>
                <a href="<?= base_url($backUrl) ?>" class="btn btn-secondary">
                    <i class="material-symbols-rounded me-2" style="font-size: 18px;">arrow_back</i>Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<?php if (!empty($classData)): ?>
<?php
$totalAttempts = array_sum(array_column($classData, 'total_attempts'));
$totalPassed = array_sum(array_column($classData, 'passed_attempts'));
$overallPassRate = $totalAttempts > 0 ? ($totalPassed / $totalAttempts) * 100 : 0;
$avgPerformance = count($classData) > 0 ? array_sum(array_column($classData, 'average_percentage')) / count($classData) : 0;
?>
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="material-symbols-rounded text-primary" style="font-size: 24px;">groups</i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: var(--theme-color, #007bff);"><?= count($classData) ?></h3>
                <p class="text-muted mb-0 small">Total Classes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="material-symbols-rounded text-info" style="font-size: 24px;">quiz</i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-info"><?= $totalAttempts ?></h3>
                <p class="text-muted mb-0 small">Total Attempts</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="material-symbols-rounded text-success" style="font-size: 24px;">check_circle</i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-success"><?= number_format($overallPassRate, 1) ?>%</h3>
                <p class="text-muted mb-0 small">Overall Pass Rate</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="material-symbols-rounded text-warning" style="font-size: 24px;">trending_up</i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-warning"><?= number_format($avgPerformance, 1) ?>%</h3>
                <p class="text-muted mb-0 small">Average Performance</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Class Performance Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold" style="color: black;">
                        <i class="material-symbols-rounded me-2" style="font-size: 20px;">groups</i>
                        Class Performance Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="material-symbols-rounded" style="font-size: 18px;">search</i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="searchClass" placeholder="Search classes...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($classData)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="classPerformanceTable">
                        <thead style="background: linear-gradient(135deg, var(--theme-color, #007bff) 0%, var(--theme-color-dark, #0056b3) 100%); color: white;">
                            <tr>
                                <th class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">school</i>
                                        Class Name
                                    </div>
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">quiz</i>
                                        Total Attempts
                                    </div>
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">check_circle</i>
                                        Passed
                                    </div>
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">percent</i>
                                        Pass Rate
                                    </div>
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">trending_up</i>
                                        Avg Performance
                                    </div>
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">grade</i>
                                        Grade
                                    </div>
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded me-2" style="font-size: 18px;">analytics</i>
                                        Progress
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classData as $index => $class): ?>
                            <?php
                            $passRate = $class['total_attempts'] > 0 ? ($class['passed_attempts'] / $class['total_attempts']) * 100 : 0;
                            $gradeClass = 'secondary';
                            $grade = 'N/A';
                            $gradeIcon = 'help';

                            if ($class['average_percentage'] >= 80) {
                                $grade = 'Excellent';
                                $gradeClass = 'success';
                                $gradeIcon = 'star';
                            } elseif ($class['average_percentage'] >= 70) {
                                $grade = 'Good';
                                $gradeClass = 'info';
                                $gradeIcon = 'thumb_up';
                            } elseif ($class['average_percentage'] >= 60) {
                                $grade = 'Average';
                                $gradeClass = 'warning';
                                $gradeIcon = 'remove';
                            } elseif ($class['average_percentage'] >= 50) {
                                $grade = 'Below Average';
                                $gradeClass = 'danger';
                                $gradeIcon = 'thumb_down';
                            } else {
                                $grade = 'Poor';
                                $gradeClass = 'dark';
                                $gradeIcon = 'warning';
                            }
                            ?>
                            <tr class="class-row" data-class-name="<?= strtolower(esc($class['class_name'])) ?>">
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                            <i class="material-symbols-rounded text-primary" style="font-size: 16px;">school</i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($class['class_name']) ?></div>
                                            <small class="text-muted">Class #<?= $index + 1 ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-light text-dark fs-6 px-3 py-2"><?= $class['total_attempts'] ?></span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success fs-6 px-3 py-2"><?= $class['passed_attempts'] ?></span>
                                </td>
                                <td class="text-center py-3">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-<?= $passRate >= 50 ? 'success' : 'danger' ?> fs-6 px-3 py-2 mb-1">
                                            <?= number_format($passRate, 1) ?>%
                                        </span>
                                        <div class="progress" style="width: 60px; height: 4px;">
                                            <div class="progress-bar bg-<?= $passRate >= 50 ? 'success' : 'danger' ?>"
                                                 style="width: <?= min($passRate, 100) ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-<?= $class['average_percentage'] >= 50 ? 'success' : 'danger' ?> fs-6 px-3 py-2 mb-1">
                                            <?= number_format($class['average_percentage'], 1) ?>%
                                        </span>
                                        <div class="progress" style="width: 60px; height: 4px;">
                                            <div class="progress-bar bg-<?= $class['average_percentage'] >= 50 ? 'success' : 'danger' ?>"
                                                 style="width: <?= min($class['average_percentage'], 100) ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-<?= $gradeClass ?> fs-6 px-3 py-2">
                                        <i class="material-symbols-rounded me-1" style="font-size: 14px;"><?= $gradeIcon ?></i>
                                        <?= $grade ?>
                                    </span>
                                </td>
                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="progress" style="width: 80px; height: 8px;">
                                            <div class="progress-bar bg-gradient"
                                                 style="width: <?= min($class['average_percentage'], 100) ?>%;
                                                        background: linear-gradient(90deg,
                                                        <?= $class['average_percentage'] >= 80 ? '#28a745' :
                                                           ($class['average_percentage'] >= 60 ? '#ffc107' : '#dc3545') ?> 0%,
                                                        <?= $class['average_percentage'] >= 80 ? '#20c997' :
                                                           ($class['average_percentage'] >= 60 ? '#fd7e14' : '#e74c3c') ?> 100%);">
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1"><?= number_format($class['average_percentage'], 0) ?>%</small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="material-symbols-rounded text-muted" style="font-size: 40px;">groups</i>
                        </div>
                    </div>
                    <h5 class="text-muted mb-2">No class performance data available</h5>
                    <p class="text-muted mb-4">Class performance data will appear here once students complete exams.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-primary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">add</i>Create Exam
                        </a>
                        <a href="<?= base_url('admin/students') ?>" class="btn btn-outline-secondary">
                            <i class="material-symbols-rounded me-2" style="font-size: 18px;">person_add</i>Add Students
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Class Performance Report loaded');

    // Search functionality
    const searchInput = document.getElementById('searchClass');
    const classRows = document.querySelectorAll('.class-row');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();

            classRows.forEach(row => {
                const className = row.getAttribute('data-class-name');
                if (className.includes(searchTerm)) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    row.style.display = 'none';
                }
            });

            // Show "no results" message if no rows are visible
            const visibleRows = Array.from(classRows).filter(row => row.style.display !== 'none');
            const tableBody = document.querySelector('#classPerformanceTable tbody');

            if (visibleRows.length === 0 && searchTerm !== '') {
                if (!document.getElementById('noResultsRow')) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noResultsRow';
                    noResultsRow.innerHTML = `
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="material-symbols-rounded mb-2" style="font-size: 48px;">search_off</i>
                                <h6>No classes found</h6>
                                <p class="mb-0">Try adjusting your search terms</p>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(noResultsRow);
                }
            } else {
                const noResultsRow = document.getElementById('noResultsRow');
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        });
    }

    // Add hover effects to table rows
    classRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(var(--bs-primary-rgb), 0.05)';
            this.style.transform = 'translateY(-1px)';
            this.style.transition = 'all 0.2s ease';
        });

        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = '';
        });
    });
});

// Export to Excel function (CSV format for offline compatibility)
function exportToExcel() {
    const table = document.getElementById('classPerformanceTable');
    if (!table) {
        alert('No data to export');
        return;
    }

    // Extract data from table
    const headers = ['Class Name', 'Total Attempts', 'Passed Attempts', 'Pass Rate', 'Average Performance', 'Grade'];
    const rows = [];

    // Add headers
    rows.push(headers.join(','));

    // Extract data rows
    const tableRows = table.querySelectorAll('tbody tr:not(#noResultsRow)');
    tableRows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const rowData = [];

            // Class name
            const className = cells[0].querySelector('.fw-semibold');
            rowData.push(className ? `"${className.textContent.trim()}"` : '');

            // Total attempts
            const totalAttempts = cells[1].querySelector('.badge');
            rowData.push(totalAttempts ? totalAttempts.textContent.trim() : '0');

            // Passed attempts
            const passedAttempts = cells[2].querySelector('.badge');
            rowData.push(passedAttempts ? passedAttempts.textContent.trim() : '0');

            // Pass rate
            const passRate = cells[3].querySelector('.badge');
            rowData.push(passRate ? passRate.textContent.trim() : '0%');

            // Average performance
            const avgPerformance = cells[4].querySelector('.badge');
            rowData.push(avgPerformance ? avgPerformance.textContent.trim() : '0%');

            // Grade
            const grade = cells[5].querySelector('.badge');
            rowData.push(grade ? `"${grade.textContent.trim()}"` : 'N/A');

            rows.push(rowData.join(','));
        }
    });

    // Create CSV content
    const csvContent = rows.join('\n');

    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const fileName = `class_performance_report_${new Date().toISOString().split('T')[0]}.csv`;

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', fileName);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        alert('Export not supported in this browser');
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>
