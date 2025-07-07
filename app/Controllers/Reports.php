<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\UserModel;
use App\Models\ExamAttemptModel;
use App\Models\SubjectModel;
use App\Models\ClassModel;
use App\Models\SecurityLogModel;

class Reports extends BaseController
{
    protected $examModel;
    protected $userModel;
    protected $attemptModel;
    protected $subjectModel;
    protected $classModel;
    protected $securityLogModel;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->userModel = new UserModel();
        $this->attemptModel = new ExamAttemptModel();
        $this->subjectModel = new SubjectModel();
        $this->classModel = new ClassModel();
        $this->securityLogModel = new SecurityLogModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Reports - SRMS CBT System'
        ];

        return view('admin/reports', $data);
    }

    public function examPerformance()
    {
        try {
            // Get all exam attempts with related data
            $attempts = $this->attemptModel->getExamPerformanceData();

            // Calculate statistics
            $stats = $this->calculateExamStats($attempts);

            $data = [
                'title' => 'Exam Performance Report - SRMS CBT System',
                'pageTitle' => 'Exam Performance Report',
                'pageSubtitle' => 'Detailed analysis of exam performance across all subjects',
                'attempts' => $attempts,
                'stats' => $stats,
                'subjects' => $this->subjectModel->getActiveSubjects(),
                'exams' => $this->examModel->getActiveExams()
            ];

            return view('reports/exam_performance', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate exam performance report: ' . $e->getMessage());
        }
    }

    public function gradeDistribution()
    {
        try {
            $gradeData = $this->attemptModel->getGradeDistribution();

            $data = [
                'title' => 'Grade Distribution Report - SRMS CBT System',
                'pageTitle' => 'Grade Distribution Report',
                'pageSubtitle' => 'Analysis of grade distribution across all exams',
                'gradeData' => $gradeData,
                'subjects' => $this->subjectModel->getActiveSubjects()
            ];

            return view('reports/grade_distribution', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate grade distribution report: ' . $e->getMessage());
        }
    }

    public function examSchedule()
    {
        try {
            $schedules = $this->examModel->getExamSchedules();

            $data = [
                'title' => 'Exam Schedule Report - ExamExcel',
                'pageTitle' => 'Exam Schedule Report',
                'pageSubtitle' => 'Comprehensive exam scheduling overview',
                'schedules' => $schedules,
                'subjects' => $this->subjectModel->getActiveSubjects(),
                'classes' => $this->classModel->getActiveClasses()
            ];

            return view('reports/exam_schedule', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate exam schedule report: ' . $e->getMessage());
        }
    }

    public function studentReport()
    {
        try {
            $students = $this->userModel->getStudentsWithPerformance();

            $data = [
                'title' => 'Individual Student Report - SRMS CBT System',
                'pageTitle' => 'Individual Student Report',
                'pageSubtitle' => 'Detailed student performance analysis',
                'students' => $students,
                'classes' => $this->classModel->getActiveClasses()
            ];

            return view('reports/student_report', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate student report: ' . $e->getMessage());
        }
    }

    public function classPerformance()
    {
        try {
            $classData = $this->attemptModel->getClassPerformanceData();

            // Get user role from session
            $userRole = session()->get('role') ?? 'admin';

            $data = [
                'title' => 'Class Performance Report - SRMS CBT System',
                'pageTitle' => 'Class Performance Report',
                'pageSubtitle' => 'Performance analysis by class',
                'classData' => $classData,
                'classes' => $this->classModel->getActiveClasses(),
                'userRole' => $userRole
            ];

            return view('reports/class_performance', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate class performance report: ' . $e->getMessage());
        }
    }

    public function attendanceReport()
    {
        try {
            $attendanceData = $this->attemptModel->getAttendanceData();

            $data = [
                'title' => 'Attendance Report - SRMS CBT System',
                'pageTitle' => 'Attendance Report',
                'pageSubtitle' => 'Student attendance and participation analysis',
                'attendanceData' => $attendanceData,
                'classes' => $this->classModel->getActiveClasses()
            ];

            return view('reports/attendance', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate attendance report: ' . $e->getMessage());
        }
    }

    public function usageAnalytics()
    {
        try {
            $usageData = $this->securityLogModel->getUsageAnalytics();

            $data = [
                'title' => 'Usage Analytics - SRMS CBT System',
                'pageTitle' => 'Usage Analytics',
                'pageSubtitle' => 'System usage and activity analysis',
                'usageData' => $usageData
            ];

            return view('reports/usage_analytics', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate usage analytics: ' . $e->getMessage());
        }
    }

    public function securityAuditLog()
    {
        try {
            $securityLogs = $this->securityLogModel->getSecurityLogs();
            $stats = $this->securityLogModel->getSecurityStats();

            $data = [
                'title' => 'Security Audit Log - SRMS CBT System',
                'pageTitle' => 'Security Audit Log',
                'pageSubtitle' => 'Security events and violation tracking',
                'securityLogs' => $securityLogs,
                'stats' => $stats
            ];

            return view('reports/security_audit', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate security audit log: ' . $e->getMessage());
        }
    }

    public function databaseStatistics()
    {
        try {
            $dbStats = $this->getDatabaseStatistics();

            $data = [
                'title' => 'Database Statistics - SRMS CBT System',
                'pageTitle' => 'Database Statistics',
                'pageSubtitle' => 'System database usage and performance metrics',
                'dbStats' => $dbStats
            ];

            return view('reports/database_stats', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate database statistics: ' . $e->getMessage());
        }
    }

    public function exportExcel($reportType)
    {
        // Implementation for Excel export
        return $this->response->download('report.xlsx', null);
    }

    public function exportPDF($reportType)
    {
        // Implementation for PDF export
        return $this->response->download('report.pdf', null);
    }

    public function exportCSV($reportType)
    {
        // Implementation for CSV export
        return $this->response->download('report.csv', null);
    }

    private function calculateExamStats($attempts)
    {
        $totalAttempts = count($attempts);
        $totalMarks = array_sum(array_column($attempts, 'marks_obtained'));
        $totalPercentage = array_sum(array_column($attempts, 'percentage'));
        $passedAttempts = count(array_filter($attempts, function($attempt) {
            return $attempt['percentage'] >= 50; // Assuming 50% is pass mark
        }));

        return [
            'total_attempts' => $totalAttempts,
            'average_marks' => $totalAttempts > 0 ? round($totalMarks / $totalAttempts, 2) : 0,
            'average_percentage' => $totalAttempts > 0 ? round($totalPercentage / $totalAttempts, 2) : 0,
            'pass_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0,
            'passed_attempts' => $passedAttempts,
            'failed_attempts' => $totalAttempts - $passedAttempts
        ];
    }

    private function getDatabaseStatistics()
    {
        $db = \Config\Database::connect();

        return [
            'total_users' => $this->userModel->countAll(),
            'total_exams' => $this->examModel->countAll(),
            'total_attempts' => $this->attemptModel->countAll(),
            'total_subjects' => $this->subjectModel->countAll(),
            'total_classes' => $this->classModel->countAll(),
            'database_size' => $this->getDatabaseSize($db)
        ];
    }

    private function getDatabaseSize($db)
    {
        try {
            $query = $db->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'DB Size in MB' FROM information_schema.tables WHERE table_schema = DATABASE()");
            $result = $query->getRow();
            return $result ? $result->{'DB Size in MB'} . ' MB' : 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}
