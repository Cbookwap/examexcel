<?php
$userRole = session()->get('role');
$currentUri = uri_string();

// Helper function to check if current route is active
function isActive($route, $currentUri) {
    return strpos($currentUri, $route) === 0 ? 'active bg-gradient-dark text-white' : 'text-dark';
}

// Helper function to check if any route in a group is active
function isGroupActive($routes, $currentUri) {
    foreach ($routes as $route) {
        if (strpos($currentUri, $route) === 0) {
            return true;
        }
    }
    return false;
}
?>

<!-- Professional Collapsible Navigation Menu -->
<ul class="navbar-nav">
<?php if ($userRole === 'admin'): ?>
    <!-- Main Dashboard -->
    <li class="nav-item">
        <a class="nav-link <?= isActive('admin/dashboard', $currentUri) ?>" href="<?= base_url('admin/dashboard') ?>">
            <i class="fas fa-tachometer-alt opacity-5"></i>
            <span class="nav-link-text ms-1">Dashboard</span>
        </a>
    </li>

    <!-- User Management Section -->
    <li class="nav-item mt-3">
        <a class="nav-link nav-section-toggle collapsed" href="#userManagement" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-users opacity-5"></i>
            <span class="nav-link-text ms-1">User Management</span>
        </a>
        <div class="collapse" id="userManagement">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/users', $currentUri) ?>" href="<?= base_url('admin/users') ?>">
                        <span class="nav-link-text">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/students', $currentUri) ?>" href="<?= base_url('admin/students') ?>">
                        <span class="nav-link-text">Student List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/teachers', $currentUri) ?>" href="<?= base_url('admin/teachers') ?>">
                        <span class="nav-link-text">Teacher List</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Academic Structure Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#academicStructure" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-graduation-cap opacity-5"></i>
            <span class="nav-link-text ms-1">Academic Structure</span>
        </a>
        <div class="collapse" id="academicStructure">
            <ul class="nav nav-sm flex-column">
			 <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('academic/', $currentUri) ?>" href="<?= base_url('academic/') ?>">
                        <span class="nav-link-text">Academic Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/sessions', $currentUri) ?>" href="<?= base_url('admin/sessions') ?>">
                        <span class="nav-link-text">Academic Sessions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/classes', $currentUri) ?>" href="<?= base_url('admin/classes') ?>">
                        <span class="nav-link-text">Classes & Class Teachers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/subject-categories', $currentUri) ?>" href="<?= base_url('admin/subject-categories') ?>">
                        <span class="nav-link-text">Subject Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/subjects', $currentUri) ?>" href="<?= base_url('admin/subjects') ?>">
                        <span class="nav-link-text">Subjects</span>
                    </a>
                </li>
				 <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('academic/class-promotion', $currentUri) ?>" href="<?= base_url('academic/class-promotion') ?>">
                        <span class="nav-link-text">Class Promotion</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('academic/term-results', $currentUri) ?>" href="<?= base_url('academic/term-results') ?>">
                        <span class="nav-link-text">Term Results</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Academic Management Section -->
   
    <!-- Assignment Management Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#assignments" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-tasks opacity-5"></i>
            <span class="nav-link-text ms-1">Assignments</span>
        </a>
        <div class="collapse" id="assignments">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/assignments', $currentUri) ?>" href="<?= base_url('admin/assignments') ?>">
                        <span class="nav-link-text">Teacher Assignments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/subject-assignments', $currentUri) ?>" href="<?= base_url('admin/subject-assignments') ?>">
                        <span class="nav-link-text">Subject-Class Assignments</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Exam & Assessment Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#examAssessment" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-clipboard-list opacity-5"></i>
            <span class="nav-link-text ms-1">Exam Management</span>
        </a>
        <div class="collapse" id="examAssessment">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/exam-settings', $currentUri) ?>" href="<?= base_url('admin/exam-settings') ?>">
                        <span class="nav-link-text">CBT Exam Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/exams', $currentUri) ?>" href="<?= base_url('admin/exams') ?>">
                        <span class="nav-link-text">Set Exam</span>
                    </a>
                </li>
                
            </ul>
        </div>
    </li>
<!-- Question Bank -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#questionBank" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-question-circle opacity-5"></i>
            <span class="nav-link-text ms-1">Question Management</span>
        </a>
        <div class="collapse" id="questionBank">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('questions', $currentUri) ?>" href="<?= base_url('questions') ?>">
                        <span class="nav-link-text">Question Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/ai-generator', $currentUri) ?>" href="<?= base_url('admin/ai-generator') ?>">
                        <span class="nav-link-text">AI Question Generator</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/practice-questions', $currentUri) ?>" href="<?= base_url('admin/practice-questions') ?>">
                        <span class="nav-link-text">Practice Questions</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <!-- Analytics & Reports Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#analyticsReports" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-chart-bar opacity-5"></i>
            <span class="nav-link-text ms-1">Analytics & Reports</span>
        </a>
        <div class="collapse" id="analyticsReports">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/results', $currentUri) ?>" href="<?= base_url('admin/results') ?>">
                        <span class="nav-link-text">Results & Analytics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/reports', $currentUri) ?>" href="<?= base_url('admin/reports') ?>">
                        <span class="nav-link-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- System Administration Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#systemAdmin" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-cogs opacity-5"></i>
            <span class="nav-link-text ms-1">Settings</span>
        </a>
        <div class="collapse" id="systemAdmin">
            <ul class="nav nav-sm flex-column">
                 <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/settings', $currentUri) ?>" href="<?= base_url('admin/settings') ?>">
                        <span class="nav-link-text">General Settings</span>
                    </a>
                </li>
				<li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/security', $currentUri) ?>" href="<?= base_url('admin/security') ?>">
                        <span class="nav-link-text">Security Monitoring</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/violations', $currentUri) ?>" href="<?= base_url('admin/violations') ?>">
                        <span class="nav-link-text">Violation Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/activity-log', $currentUri) ?>" href="<?= base_url('admin/activity-log') ?>">
                        <span class="nav-link-text">Activity Log</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/system-info', $currentUri) ?>" href="<?= base_url('admin/system-info') ?>">
                        <span class="nav-link-text">System Information</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/theme-settings', $currentUri) ?>" href="<?= base_url('admin/theme-settings') ?>">
                        <span class="nav-link-text">Theme Settings</span>
                    </a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('admin/backup', $currentUri) ?>" href="<?= base_url('admin/backup') ?>">
                        <span class="nav-link-text">Backup & Restore</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

<?php elseif ($userRole === 'teacher'): ?>
    <!-- Main Dashboard -->
    <li class="nav-item">
        <a class="nav-link <?= isActive('teacher/dashboard', $currentUri) ?>" href="<?= base_url('teacher/dashboard') ?>">
            <i class="fas fa-tachometer-alt opacity-5"></i>
            <span class="nav-link-text ms-1">Dashboard</span>
        </a>
    </li>

    <!-- Question Management Section -->
    <li class="nav-item mt-3">
        <a class="nav-link nav-section-toggle collapsed" href="#teacherQuestions" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-question-circle opacity-5"></i>
            <span class="nav-link-text ms-1">Question Management</span>
        </a>
        <div class="collapse" id="teacherQuestions">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('questions', $currentUri) ?>" href="<?= base_url('questions') ?>">
                        <span class="nav-link-text">Question Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('principal/ai-generator', $currentUri) ?>" href="<?= base_url('principal/ai-generator') ?>">
                        <span class="nav-link-text">AI Question Generator</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Assessment & Monitoring Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#teacherAssessment" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-eye opacity-5"></i>
            <span class="nav-link-text ms-1">Assessment & Monitoring</span>
        </a>
        <div class="collapse" id="teacherAssessment">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('teacher/results', $currentUri) ?>" href="<?= base_url('teacher/results') ?>">
                        <span class="nav-link-text">Student Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('teacher/reports', $currentUri) ?>" href="<?= base_url('teacher/reports') ?>">
                        <span class="nav-link-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

<?php elseif ($userRole === 'class_teacher'): ?>
    <!-- Main Dashboard -->
    <li class="nav-item">
        <a class="nav-link <?= isActive('class-teacher/dashboard', $currentUri) ?>" href="<?= base_url('class-teacher/dashboard') ?>">
            <i class="fas fa-tachometer-alt opacity-5"></i>
            <span class="nav-link-text ms-1">Dashboard</span>
        </a>
    </li>

    <!-- Class Management Section -->
    <li class="nav-item mt-3">
        <a class="nav-link nav-section-toggle collapsed" href="#classTeacherManagement" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-users opacity-5"></i>
            <span class="nav-link-text ms-1">Class Management</span>
        </a>
        <div class="collapse" id="classTeacherManagement">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('class-teacher/marksheet', $currentUri) ?>" href="<?= base_url('class-teacher/marksheet') ?>">
                        <span class="nav-link-text">Class Marksheet</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

<?php elseif ($userRole === 'student'): ?>
    <!-- Main Dashboard -->
    <li class="nav-item">
        <a class="nav-link <?= isActive('student/dashboard', $currentUri) ?>" href="<?= base_url('student/dashboard') ?>">
            <i class="fas fa-tachometer-alt opacity-5"></i>
            <span class="nav-link-text ms-1">Dashboard</span>
        </a>
    </li>

    <!-- Exams & Practice Section -->
    <li class="nav-item mt-3">
        <a class="nav-link nav-section-toggle collapsed" href="#studentExams" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-file-alt opacity-5"></i>
            <span class="nav-link-text ms-1">Exams & Practice</span>
        </a>
        <div class="collapse" id="studentExams">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('student/exams', $currentUri) ?>" href="<?= base_url('student/exams') ?>">
                        <span class="nav-link-text">Take CBT	 Exams</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('student/practice', $currentUri) ?>" href="<?= base_url('student/practice') ?>">
                        <span class="nav-link-text">Practice Tests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('student/schedule', $currentUri) ?>" href="<?= base_url('student/schedule') ?>">
                        <span class="nav-link-text">Exam Schedule</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Results & Progress Section -->
    <li class="nav-item">
        <a class="nav-link nav-section-toggle collapsed" href="#studentResults" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-chart-line opacity-5"></i>
            <span class="nav-link-text ms-1">Results & Progress</span>
        </a>
        <div class="collapse" id="studentResults">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('student/results', $currentUri) ?>" href="<?= base_url('student/results') ?>">
                        <span class="nav-link-text">My Results</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('student/progress', $currentUri) ?>" href="<?= base_url('student/progress') ?>">
                        <span class="nav-link-text">Progress Report</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-sub-link <?= isActive('student/academic-history', $currentUri) ?>" href="<?= base_url('student/academic-history') ?>">
                        <span class="nav-link-text">Academic History</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

<?php endif; ?>

    <!-- Account Section -->
    <li class="nav-item mt-3">
        <a class="nav-link nav-section-toggle collapsed" href="#accountSection" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-user opacity-5"></i>
            <span class="nav-link-text ms-1">Account</span>
        </a>
        <div class="collapse" id="accountSection">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <?php
                    $profileUrl = match($userRole) {
                        'admin' => 'admin/profile',
                        'teacher' => 'teacher/profile',
                        'principal' => 'principal/profile',
                        'class_teacher' => 'class-teacher/profile',
                        default => 'student/profile'
                    };
                    ?>
                    <a class="nav-link nav-sub-link <?= isActive($profileUrl, $currentUri) ?>" href="<?= base_url($profileUrl) ?>">
                        <span class="nav-link-text">My Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
</ul>
