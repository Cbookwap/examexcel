<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Default route - show welcome page with login options
$routes->get('/', 'Home::index');

// Custom Error Pages
$routes->group('error', function($routes) {
    $routes->get('400', 'Error::error400');
    $routes->get('403', 'Error::error403');
    $routes->get('404', 'Error::error404');
    $routes->get('500', 'Error::error500');
    $routes->get('501', 'Error::error501');
    $routes->get('502', 'Error::error502');
    $routes->get('503', 'Error::error503');
    $routes->get('maintenance', 'Error::maintenance');
    $routes->get('access-denied', 'Error::accessDenied');
    $routes->get('session-expired', 'Error::sessionExpired');
    $routes->get('feature-unavailable', 'Error::featureUnavailable');
    $routes->get('(:num)', 'Error::show/$1');
});

// Authentication routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('profile', 'Auth::profile');
    $routes->post('profile', 'Auth::profile');
});

// Admin routes
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('users/create', 'Admin::createUser');
    $routes->post('users/create', 'Admin::createUser');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');
    $routes->get('users/toggle/(:num)', 'Admin::toggleUserStatus/$1');
    $routes->get('generate-student-id', 'Admin::generateStudentId');
    $routes->get('students', 'Admin::studentList');
    $routes->get('teachers', 'Admin::teacherList');
    $routes->get('principals', 'Admin::principals');

    // Classes management
    $routes->get('classes', 'Admin::classes');
    $routes->get('classes/create', 'Admin::createClass');
    $routes->post('classes/create', 'Admin::createClass');
    $routes->get('classes/edit/(:num)', 'Admin::editClass/$1');
    $routes->post('classes/edit/(:num)', 'Admin::editClass/$1');
    $routes->get('classes/delete/(:num)', 'Admin::deleteClass/$1');
    $routes->get('classes/toggle/(:num)', 'Admin::toggleClassStatus/$1');
    $routes->get('classes/manage-teacher/(:num)', 'Admin::manageClassTeacher/$1');
    $routes->post('classes/manage-teacher/(:num)', 'Admin::manageClassTeacher/$1');
    $routes->get('classes/fix-teachers', 'Admin::fixClassTeachers');
    $routes->get('classes/debug-teachers', 'Admin::debugClassTeachers');
    $routes->get('classes/check-database', 'Admin::checkDatabase');
    $routes->get('classes/create-test-teacher', 'Admin::createTestClassTeacher');
    $routes->get('classes/test-login', 'Admin::testClassTeacherLogin');

    // Subjects management
    $routes->get('subjects', 'Admin::subjects');
    $routes->get('subjects/create', 'Admin::createSubject');
    $routes->post('subjects/create', 'Admin::createSubject');
    $routes->get('subjects/edit/(:num)', 'Admin::editSubject/$1');
    $routes->post('subjects/edit/(:num)', 'Admin::editSubject/$1');
    $routes->get('subjects/delete/(:num)', 'Admin::deleteSubject/$1');
    $routes->get('subjects/toggle/(:num)', 'Admin::toggleSubjectStatus/$1');
    $routes->post('subjects/bulk-action', 'Admin::bulkActionSubjects');

    // Subject Categories management
    $routes->get('subject-categories', 'Admin::subjectCategories');
    $routes->get('subject-categories/create', 'Admin::createSubjectCategory');
    $routes->post('subject-categories/create', 'Admin::createSubjectCategory');
    $routes->get('subject-categories/edit/(:num)', 'Admin::editSubjectCategory/$1');
    $routes->post('subject-categories/edit/(:num)', 'Admin::editSubjectCategory/$1');
    $routes->get('subject-categories/delete/(:num)', 'Admin::deleteSubjectCategory/$1');
    $routes->get('subject-categories/toggle/(:num)', 'Admin::toggleSubjectCategoryStatus/$1');
    $routes->post('subject-categories/bulk-action', 'Admin::bulkActionSubjectCategories');

    // Exams management
    $routes->get('exams', 'Admin::exams');
    $routes->get('exam/create', 'Admin::createExam');
    $routes->post('exam/create', 'Admin::createExam');
    $routes->get('exam/view/(:num)', 'Admin::viewExam/$1');
    $routes->get('exam/edit/(:num)', 'Admin::editExam/$1');
    $routes->post('exam/edit/(:num)', 'Admin::editExam/$1');
    $routes->get('exam/delete/(:num)', 'Admin::deleteExam/$1');

    // Exam Question Management
    $routes->get('exam/(:num)/questions', 'Admin::manageExamQuestions/$1');
    $routes->post('exam/(:num)/questions', 'Admin::manageExamQuestions/$1');
    $routes->get('exam/manage-questions/(:num)', 'Admin::manageExamQuestions/$1'); // Alternative route
    $routes->post('exam/manage-questions/(:num)', 'Admin::manageExamQuestions/$1'); // Alternative route

    // Multi-subject exam question management
    $routes->get('exam/(:num)/subject/(:num)/questions', 'Admin::manageExamSubjectQuestions/$1/$2');
    $routes->post('exam/(:num)/subject/(:num)/questions', 'Admin::manageExamSubjectQuestions/$1/$2');

    // Reset exam subjects configuration
    $routes->post('exam/(:num)/reset-subjects', 'Admin::resetExamSubjects/$1');

    // Remove individual subject from exam
    $routes->post('exam/(:num)/remove-subject/(:num)', 'Admin::removeExamSubject/$1/$2');

    // Teacher Assignments management
    $routes->get('assignments', 'Admin::assignments');
    $routes->get('assignments/create', 'Admin::createAssignment');
    $routes->post('assignments/create', 'Admin::createAssignment');
    $routes->get('assignments/delete/(:num)', 'Admin::deleteAssignment/$1');

    // Subject-Class Assignments management
    $routes->get('subject-assignments', 'Admin::subjectAssignments');
    $routes->get('subject-assignments/create', 'Admin::createSubjectAssignment');
    $routes->post('subject-assignments/create', 'Admin::createSubjectAssignment');
    $routes->get('subject-assignments/delete/(:num)', 'Admin::deleteSubjectAssignment/$1');
    $routes->post('subject-assignments/bulk-assign', 'Admin::bulkAssignSubjects');
    $routes->post('subject-assignments/remove-assignment', 'Admin::removeSubjectAssignment');

    // Academic Sessions management
    $routes->get('sessions', 'Admin::sessions');
    $routes->get('sessions/create', 'Admin::createSession');
    $routes->post('sessions/create', 'Admin::createSession');
    $routes->get('sessions/view/(:num)', 'Admin::viewSession/$1');
    $routes->get('sessions/edit/(:num)', 'Admin::editSession/$1');
    $routes->post('sessions/edit/(:num)', 'Admin::editSession/$1');
    $routes->get('sessions/delete/(:num)', 'Admin::deleteSession/$1');
    $routes->get('sessions/set-current/(:num)', 'Admin::setCurrentSession/$1');
    $routes->get('sessions/set-current-term/(:num)', 'Admin::setCurrentTerm/$1');

    $routes->get('settings', 'Admin::settings');
    $routes->post('settings', 'Admin::updateSettings');
    $routes->post('settings/backup', 'Admin::createBackup');
    $routes->post('settings/clear-cache', 'Admin::clearCache');
    $routes->post('settings/toggle-lock', 'Admin::toggleAppLock');
    $routes->post('test-ai-connection', 'Admin::testAiConnection');

    // AI Question Generator
    $routes->get('ai-generator', 'AIQuestionGenerator::index');
    $routes->post('ai-generator/generate', 'AIQuestionGenerator::generate');
    $routes->post('ai-generator/save-questions', 'AIQuestionGenerator::saveQuestions');

    // Exam Settings
    $routes->get('exam-settings', 'Admin::examSettings');
    $routes->post('exam-settings/update-preferences', 'Admin::updateExamPreferences');

    // Theme Settings
    $routes->get('theme-settings', 'Admin::themeSettings');
    $routes->post('theme-settings/update', 'Admin::updateThemeSettings');
    $routes->get('theme-settings/reset', 'Admin::resetThemeSettings');

    // Exam Types Management (AJAX routes)
    $routes->get('exam-types/list', 'Admin::listExamTypes');
    $routes->post('exam-types/add', 'Admin::addExamType');
    $routes->get('exam-types/get/(:num)', 'Admin::getExamType/$1');
    $routes->post('exam-types/update/(:num)', 'Admin::updateExamType/$1');
    $routes->post('exam-types/toggle/(:num)', 'Admin::toggleExamTypeStatus/$1');
    $routes->delete('exam-types/delete/(:num)', 'Admin::deleteExamType/$1');
    $routes->post('exam-types/delete/(:num)', 'Admin::deleteExamType/$1'); // For POST with _method override

    // Backup & Restore routes
    $routes->get('backup', 'Admin::backup');
    $routes->post('backup/create', 'Admin::createBackup');
    $routes->get('backup/download/(:segment)', 'Admin::downloadBackup/$1');
    $routes->post('backup/delete/(:segment)', 'Admin::deleteBackup/$1');
    $routes->get('backup/delete/(:segment)', 'Admin::deleteBackup/$1'); // Keep GET for backward compatibility
    $routes->get('reports', 'Admin::reports');
    $routes->get('results', 'Admin::results');
    $routes->get('results/view/(:num)', 'Admin::viewResult/$1');
    $routes->get('results/download/(:num)', 'Admin::downloadResult/$1');
    $routes->get('results/export', 'Admin::exportResults');
    $routes->post('results/delete/(:num)', 'Admin::deleteResult/$1');
    $routes->post('results/bulk-delete', 'Admin::bulkDeleteResults');
    $routes->get('security', 'Admin::security');
    $routes->get('security/view/(:num)', 'Admin::viewSecurityLog/$1');
    $routes->get('security/event-details/(:num)', 'Admin::getSecurityEventDetails/$1');
    $routes->get('security/investigate/(:num)', 'Admin::investigateSecurityEvent/$1');
    $routes->get('security/settings', 'Admin::securitySettings');
    $routes->post('security/settings', 'Admin::updateSecuritySettings');
    $routes->get('activity-log', 'Admin::activityLog');
    $routes->get('system-info', 'Admin::systemInfo');

    // Violation Management
    $routes->get('violations', 'Admin::violations');
    $routes->get('student-violations/(:segment)', 'Admin::studentViolations/$1');
    $routes->post('clear-violations', 'Admin::clearViolations');
    $routes->post('apply-punishment', 'Admin::applyPunishment');
    $routes->post('bulk-lift-bans', 'Admin::bulkLiftBans');
    $routes->post('clear-incorrect-bans', 'Admin::clearIncorrectBans');

    // Practice Questions Management
    $routes->get('practice-questions', 'Admin::practiceQuestions');
    $routes->get('practice-questions/test', 'Admin::testPracticeQuestions');
    $routes->get('practice-questions/create', 'Admin::createPracticeQuestion');
    $routes->post('practice-questions/store', 'Admin::storePracticeQuestion');
    $routes->get('practice-questions/edit/(:num)', 'Admin::editPracticeQuestion/$1');
    $routes->post('practice-questions/update/(:num)', 'Admin::updatePracticeQuestion/$1');
    $routes->post('practice-questions/delete/(:num)', 'Admin::deletePracticeQuestion/$1');
    // Generate sample questions route removed - questions are pre-loaded via migration

    // Profile Management
    $routes->get('profile', 'Admin::profile');
    $routes->post('profile', 'Admin::profile');
});

// Admin Utility routes for system maintenance
$routes->group('admin-utility', ['filter' => 'admin'], function($routes) {
    $routes->get('check-class-mismatches', 'AdminUtility::checkClassMismatches');
    $routes->post('cleanup-exam-mismatches/(:num)', 'AdminUtility::cleanupExamMismatches/$1');
    $routes->post('cleanup-all-mismatches', 'AdminUtility::cleanupAllMismatches');
    $routes->get('validate-all-exams', 'AdminUtility::validateAllExams');
});

// Principal routes
$routes->group('principal', ['filter' => 'principal'], function($routes) {
    $routes->get('dashboard', 'Principal::dashboard');

    // Student Management
    $routes->get('students', 'Principal::students');
    $routes->get('students/create', 'Principal::createStudent');
    $routes->post('students/create', 'Principal::createStudent');
    $routes->get('generate-student-id', 'Principal::generateStudentId');

    // Teacher Management
    $routes->get('teachers', 'Principal::teachers');
    $routes->get('teachers/create', 'Principal::createTeacher');
    $routes->post('teachers/create', 'Principal::createTeacher');

    // Class Management
    $routes->get('classes', 'Principal::classes');
    $routes->get('classes/create', 'Principal::createClass');
    $routes->post('classes/create', 'Principal::createClass');

    // Exams
    $routes->get('exams', 'Principal::exams');
    $routes->get('exams/create', 'Principal::createExam');
    $routes->post('exams/create', 'Principal::createExam');
    $routes->get('exams/edit/(:num)', 'Principal::editExam/$1');
    $routes->post('exams/edit/(:num)', 'Principal::editExam/$1');
    $routes->get('exams/delete/(:num)', 'Principal::deleteExam/$1');
    $routes->get('exams/view/(:num)', 'Principal::viewExam/$1');

    // Exam Question Management
    $routes->get('exams/(:num)/questions', 'Principal::manageExamQuestions/$1');
    $routes->post('exams/(:num)/questions', 'Principal::manageExamQuestions/$1');
    $routes->get('exams/(:num)/subject/(:num)/questions', 'Principal::manageExamSubjectQuestions/$1/$2');
    $routes->post('exams/(:num)/subject/(:num)/questions', 'Principal::manageExamSubjectQuestions/$1/$2');

    // Reset exam subjects configuration
    $routes->post('exams/(:num)/reset-subjects', 'Principal::resetExamSubjects/$1');

    // Remove individual subject from exam
    $routes->post('exams/(:num)/remove-subject/(:num)', 'Principal::removeExamSubject/$1/$2');

    // Results Management
    $routes->get('results', 'Principal::results');
    $routes->get('results/view/(:num)', 'Principal::viewResult/$1');

    // Question Bank
    $routes->get('questions', 'Questions::index');
    $routes->get('questions/create', 'Questions::create');
    $routes->post('questions/create', 'Questions::processCreate');
    $routes->get('questions/edit/(:num)', 'Questions::edit/$1');
    $routes->post('questions/edit/(:num)', 'Questions::processEdit/$1');
    $routes->get('questions/delete/(:num)', 'Questions::delete/$1');
    $routes->get('questions/preview/(:num)', 'Questions::preview/$1');
    $routes->get('questions/bulk-create', 'Questions::bulkCreate');
    $routes->post('questions/create-bulk', 'Questions::processBulkCreate');
    $routes->post('questions/check-duplicate', 'Questions::checkDuplicate');
    $routes->post('questions/create-ajax', 'Questions::createAjax');
    $routes->get('questions/get-question-count', 'Questions::getQuestionCount');
    $routes->get('questions/load-questions', 'Questions::loadQuestions');
    $routes->get('questions/get-classes-for-subject/(:num)', 'Questions::getClassesForSubject/$1');
    $routes->post('questions/ai-generate', 'Questions::aiGenerate');
    $routes->post('questions/ai-approve', 'Questions::aiApprove');

    // AI Question Generator
    $routes->get('ai-generator', 'AIQuestionGenerator::index');
    $routes->post('ai-generator/generate', 'AIQuestionGenerator::generate');
    $routes->post('ai-generator/save-questions', 'AIQuestionGenerator::saveQuestions');

    // Violations
    $routes->get('violations', 'Principal::violations');
    $routes->post('violations/lift-ban/(:num)', 'Principal::liftBan/$1');
    $routes->post('violations/delete/(:num)', 'Principal::deleteViolation/$1');

    // Reports
    $routes->get('reports', 'Principal::reports');

    // Settings (Limited)
    $routes->get('settings', 'Principal::settings');
    $routes->post('settings', 'Principal::settings');

    // Profile Management
    $routes->get('profile', 'Principal::profile');
    $routes->post('profile', 'Principal::profile');
});

// Teacher routes
$routes->group('teacher', ['filter' => 'teacher'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('questions', 'Teacher::questions');
    $routes->get('results', 'Teacher::results');
    $routes->get('reports', 'Teacher::reports');

    // Profile Management
    $routes->get('profile', 'Teacher::profile');
    $routes->post('profile', 'Teacher::profile');
});

// Class Teacher routes
$routes->group('class-teacher', ['filter' => 'class_teacher'], function($routes) {
    $routes->get('dashboard', 'ClassTeacher::dashboard');
    $routes->get('marksheet', 'ClassTeacher::marksheet');

    // Profile Management
    $routes->get('profile', 'ClassTeacher::profile');
    $routes->post('profile', 'ClassTeacher::profile');
});

// Debug route to check login issues
$routes->get('debug-login', 'ClassTeacher::debugLogin');
$routes->get('debug-login/fix-ss-one', 'ClassTeacher::fixSSOne');

// Class Teacher debug routes (no filter for testing)
$routes->get('class-teacher/debug', 'ClassTeacher::debug');
$routes->get('class-teacher/simple', 'ClassTeacher::simple');
$routes->get('class-teacher/test-dashboard', 'ClassTeacher::dashboard');

// Emergency test route
$routes->get('test-class-teacher', function() {
    echo "<h1>Emergency Class Teacher Test</h1>";
    echo "<p>This route works without any filters or authentication.</p>";
    echo "<p><a href='" . base_url('class-teacher/simple') . "'>Try Simple Dashboard</a></p>";
    echo "<p><a href='" . base_url('class-teacher/debug') . "'>Try Debug Page</a></p>";
    echo "<p><a href='" . base_url('admin/classes/check-database') . "'>Check Database</a></p>";
});

// Direct login bypass for testing
$routes->get('force-login-ss-one', function() {
    $session = \Config\Services::session();

    // Force set session data for SS-ONE
    $sessionData = [
        'user_id' => 30, // SS-ONE's ID from debug
        'username' => 'SS-ONE',
        'email' => 'ss-one@example.com',
        'full_name' => 'SS ONE',
        'role' => 'class_teacher',
        'class_id' => 1,
        'is_logged_in' => true
    ];

    $session->set($sessionData);

    echo "<h1>Force Login Complete</h1>";
    echo "<p>Session has been set for SS-ONE with class_teacher role</p>";
    echo "<h3>Session Data Set:</h3>";
    echo "<pre>" . print_r($sessionData, true) . "</pre>";
    echo "<h3>Test Links:</h3>";
    echo "<a href='" . base_url('class-teacher/dashboard') . "' style='display: block; margin: 10px; padding: 10px; background: #28a745; color: white; text-decoration: none;'>Try Dashboard Now</a>";
    echo "<a href='" . base_url('class-teacher/simple') . "' style='display: block; margin: 10px; padding: 10px; background: #007bff; color: white; text-decoration: none;'>Try Simple Dashboard</a>";
});

// Test normal login process
$routes->get('test-normal-login', function() {
    echo "<h1>Test Normal Login Process</h1>";

    // Clear any existing session
    $session = \Config\Services::session();
    $session->destroy();

    echo "<p>Session cleared. Now try normal login process:</p>";
    echo "<a href='" . base_url('auth/login') . "' style='display: block; margin: 10px; padding: 10px; background: #007bff; color: white; text-decoration: none;'>Go to Login Page</a>";

    echo "<h3>Or try direct dashboard access (should redirect to login):</h3>";
    echo "<a href='" . base_url('class-teacher/dashboard') . "' style='display: block; margin: 10px; padding: 10px; background: #28a745; color: white; text-decoration: none;'>Try Dashboard Direct</a>";
});

// Test Auth redirect functionality
$routes->get('test-auth-redirect', function() {
    $auth = new \App\Controllers\Auth();
    return $auth->testRedirect();
});

// Test form submission
$routes->get('test-form', function() {
    return '
    <form method="post" action="' . base_url('test-form-submit') . '">
        <input type="text" name="test_field" value="test_value">
        <button type="submit">Submit Test</button>
    </form>';
});

$routes->post('test-form-submit', function() {
    $request = \Config\Services::request();
    return 'Form submitted! POST data: ' . json_encode($request->getPost());
});

// Check current database structure and fix class teacher roles
$routes->get('check-database-structure', function() {
    $db = \Config\Database::connect();

    // Check current ENUM values
    $result = $db->query("SHOW COLUMNS FROM users LIKE 'role'")->getRow();
    $currentEnum = $result->Type ?? 'Not found';

    // Check current class teachers
    $classTeachers = $db->table('users')
        ->select('id, username, role, class_id')
        ->where('class_id IS NOT NULL')
        ->get()
        ->getResultArray();

    $html = "<h1>Database Structure Check</h1>";
    $html .= "<h3>Current Role ENUM:</h3>";
    $html .= "<p><code>{$currentEnum}</code></p>";

    $html .= "<h3>Class Teachers Found:</h3>";
    $html .= "<table border='1' style='border-collapse: collapse;'>";
    $html .= "<tr><th>ID</th><th>Username</th><th>Current Role</th><th>Class ID</th></tr>";
    foreach ($classTeachers as $teacher) {
        $html .= "<tr>";
        $html .= "<td>{$teacher['id']}</td>";
        $html .= "<td>{$teacher['username']}</td>";
        $html .= "<td>{$teacher['role']}</td>";
        $html .= "<td>{$teacher['class_id']}</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";

    $html .= "<br><a href='" . base_url('force-fix-enum') . "'>Force Fix ENUM</a>";

    return $html;
});

// URGENT: Fix student roles that were incorrectly changed to class_teacher
$routes->get('fix-student-roles-urgent', function() {
    $db = \Config\Database::connect();

    // Get all users currently marked as class_teacher
    $allClassTeachers = $db->table('users')
        ->select('id, username, email, student_id, employee_id, role')
        ->where('role', 'class_teacher')
        ->get()
        ->getResultArray();

    $studentsFixed = 0;
    $classTeachersKept = 0;

    foreach ($allClassTeachers as $user) {
        // If user has student_id or email contains student patterns, they should be students
        if (!empty($user['student_id']) ||
            strpos($user['email'], 'student') !== false ||
            strpos($user['username'], 'STU') === 0 ||
            is_numeric($user['username'])) {

            // Change back to student
            $db->table('users')->where('id', $user['id'])->update(['role' => 'student']);
            $studentsFixed++;
        } else {
            // Keep as class_teacher (these are the real class teachers like SS-ONE, SS-TWO, etc.)
            $classTeachersKept++;
        }
    }

    $html = "<h1>URGENT FIX: Student Roles Corrected</h1>";
    $html .= "<p style='color: green;'>✓ Fixed {$studentsFixed} student accounts</p>";
    $html .= "<p style='color: blue;'>✓ Kept {$classTeachersKept} class teacher accounts</p>";
    $html .= "<p><a href='" . base_url('check-database-structure') . "'>Check Results</a></p>";
    $html .= "<p><a href='" . base_url('test-class-teacher-login') . "'>Test Class Teacher Login</a></p>";

    return $html;
});



// Test class teacher login
$routes->get('test-class-teacher-login', function() {
    $userModel = new \App\Models\UserModel();

    // Test finding SS-ONE user
    $user = $userModel->where('username', 'SS-ONE')->first();

    $html = "<h1>Class Teacher Login Test</h1>";

    if ($user) {
        $html .= "<h3>User Found:</h3>";
        $html .= "<p><strong>ID:</strong> {$user['id']}</p>";
        $html .= "<p><strong>Username:</strong> {$user['username']}</p>";
        $html .= "<p><strong>Role:</strong> {$user['role']}</p>";
        $html .= "<p><strong>Class ID:</strong> {$user['class_id']}</p>";
        $html .= "<p><strong>Active:</strong> " . ($user['is_active'] ? 'Yes' : 'No') . "</p>";

        // Test password verification
        $passwordCheck = password_verify('class123', $user['password']);
        $html .= "<p><strong>Password 'class123' matches:</strong> " . ($passwordCheck ? 'Yes' : 'No') . "</p>";

        if ($passwordCheck && $user['role'] === 'class_teacher' && $user['is_active']) {
            $html .= "<p style='color: green; font-weight: bold;'>✓ Login should work!</p>";
            $html .= "<p><a href='" . base_url('auth/login') . "'>Try Login Now</a></p>";
        } else {
            $html .= "<p style='color: red; font-weight: bold;'>✗ Login will fail</p>";
            if (!$passwordCheck) $html .= "<p>- Password doesn't match</p>";
            if ($user['role'] !== 'class_teacher') $html .= "<p>- Role is not 'class_teacher'</p>";
            if (!$user['is_active']) $html .= "<p>- Account is not active</p>";
        }
    } else {
        $html .= "<p style='color: red;'>User 'SS-ONE' not found!</p>";
    }

    return $html;
});

// Student routes
$routes->group('student', ['filter' => 'student'], function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
    $routes->get('profile', 'Student::profile');
    $routes->post('profile', 'Student::updateProfile');
    $routes->get('exams', 'Student::exams');
    $routes->get('schedule', 'Student::schedule');
    $routes->get('practice', 'Student::practice');
    $routes->get('practiceHistory', 'Student::practiceHistory');
    $routes->get('progress', 'Student::progress');
    $routes->get('startPractice/(:segment)', 'Student::startPractice/$1');
    $routes->post('startPractice', 'Student::startPractice');
    $routes->get('takePractice/(:num)', 'Student::takePractice/$1');
    $routes->post('savePracticeAnswer', 'Student::savePracticeAnswer');
    $routes->post('submitPractice/(:num)', 'Student::submitPractice/$1');
    $routes->get('practiceResult/(:num)', 'Student::practiceResult/$1');
    $routes->get('startExam/(:num)', 'Student::startExam/$1');
    $routes->get('takeExam/(:num)', 'Student::takeExam/$1');

    $routes->post('saveAnswer', 'Student::saveAnswer');
    $routes->post('trackSubjectTime', 'Student::trackSubjectTime');
    $routes->post('submitExam/(:num)', 'Student::submitExam/$1');
    $routes->get('examResult/(:num)', 'Student::examResult/$1');
    $routes->get('results', 'Student::results');
    $routes->get('academic-history', 'Student::academicHistory');
    $routes->post('recordViolation', 'Student::recordViolation');
    $routes->post('recordPauseEvent', 'Student::recordPauseEvent');
    $routes->post('logSecurityEvent', 'Student::logSecurityEvent');
});

// Academic Management routes
$routes->group('academic', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'AcademicManagement::index');
    $routes->get('student-history/(:num)', 'AcademicManagement::studentHistory/$1');
    $routes->get('class-promotion', 'AcademicManagement::classPromotion');

    $routes->get('term-results', 'AcademicManagement::termResults');
    $routes->post('calculate-term-results', 'AcademicManagement::calculateTermResults');
    $routes->post('bulk-promote-students', 'AcademicManagement::bulkPromoteStudents');
    $routes->post('promote-student', 'AcademicManagement::promoteStudent');
    $routes->get('get-class-students/(:num)', 'AcademicManagement::getClassStudents/$1');
});

// Question routes
$routes->get('questions', 'Questions::index', ['filter' => 'auth']);
$routes->group('questions', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Questions::index');
    $routes->get('create', 'Questions::create');
    $routes->post('create', 'Questions::create');
    $routes->get('bulk-create', 'Questions::bulkCreate');
    $routes->post('create-bulk', 'Questions::processBulkCreate');
    $routes->post('create-ajax', 'Questions::createAjax');
    $routes->post('check-duplicate', 'Questions::checkDuplicate');
    $routes->get('get-question-count', 'Questions::getQuestionCount');
    $routes->get('load-questions', 'Questions::loadQuestions');
    $routes->get('get-classes-for-subject/(:num)', 'Questions::getClassesForSubject/$1');
    $routes->post('save-instruction-template', 'Questions::saveInstructionTemplate');
    $routes->get('edit/(:num)', 'Questions::edit/$1');
    $routes->post('edit/(:num)', 'Questions::edit/$1');
    $routes->get('delete/(:num)', 'Questions::delete/$1');
    $routes->get('duplicate/(:num)', 'Questions::duplicate/$1');
    $routes->get('preview/(:num)', 'Questions::preview/$1');
    $routes->post('bulk-actions', 'Questions::bulkActions');
    $routes->post('ai-generate', 'Questions::aiGenerate');
    $routes->post('ai-approve', 'Questions::aiApprove');
});

// Main exam route - redirect to login page for general access
$routes->get('exam', function() {
    return redirect()->to(base_url('auth/login'));
});
$routes->get('exam/create', function() {
    return redirect()->to(base_url('admin/exam/create'));
});
$routes->get('exam/view/(:num)', function($id) {
    return redirect()->to(base_url('admin/exam/view/' . $id));
});
$routes->get('exam/edit/(:num)', function($id) {
    return redirect()->to(base_url('admin/exam/edit/' . $id));
});
$routes->get('exam/delete/(:num)', function($id) {
    return redirect()->to(base_url('admin/exam/delete/' . $id));
});

// Class Management Routes
$routes->group('classes', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'Classes::index');
    $routes->get('create', 'Classes::create');
    $routes->post('create', 'Classes::create');
    $routes->get('view/(:num)', 'Classes::view/$1');
    $routes->get('edit/(:num)', 'Classes::edit/$1');
    $routes->post('edit/(:num)', 'Classes::edit/$1');
    $routes->get('delete/(:num)', 'Classes::delete/$1');
});

// Subject Management Routes
$routes->group('subjects', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'Subjects::index');
    $routes->get('create', 'Subjects::create');
    $routes->post('create', 'Subjects::create');
    $routes->get('view/(:num)', 'Subjects::view/$1');
    $routes->get('edit/(:num)', 'Subjects::edit/$1');
    $routes->post('edit/(:num)', 'Subjects::edit/$1');
    $routes->get('delete/(:num)', 'Subjects::delete/$1');
});

// Reports Routes
$routes->group('reports', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Reports::index');
    $routes->get('exam-performance', 'Reports::examPerformance');
    $routes->get('grade-distribution', 'Reports::gradeDistribution');
    $routes->get('exam-schedule', 'Reports::examSchedule');
    $routes->get('student-report', 'Reports::studentReport');
    $routes->get('class-performance', 'Reports::classPerformance');
    $routes->get('attendance', 'Reports::attendanceReport');
    $routes->get('usage-analytics', 'Reports::usageAnalytics');
    $routes->get('security-audit', 'Reports::securityAuditLog');
    $routes->get('database-stats', 'Reports::databaseStatistics');
    $routes->get('export/excel/(:segment)', 'Reports::exportExcel/$1');
    $routes->get('export/pdf/(:segment)', 'Reports::exportPDF/$1');
    $routes->get('export/csv/(:segment)', 'Reports::exportCSV/$1');
});


