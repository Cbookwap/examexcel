<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class InitializeStudentHistory extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'init:student-history';
    protected $description = 'Initialize academic history for all existing students';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('Initializing student academic history...', 'yellow');

        // Get current session and term
        $currentSession = $db->table('academic_sessions')
                            ->where('is_current', 1)
                            ->where('is_active', 1)
                            ->get()
                            ->getRowArray();

        $currentTerm = $db->table('academic_terms')
                         ->where('is_current', 1)
                         ->where('is_active', 1)
                         ->get()
                         ->getRowArray();

        if (!$currentSession) {
            CLI::write('No current academic session found. Please run "php spark fix:academic" first.', 'red');
            return;
        }

        if (!$currentTerm) {
            CLI::write('No current academic term found. Please run "php spark fix:academic" first.', 'red');
            return;
        }

        CLI::write("Current Session: {$currentSession['session_name']}", 'green');
        CLI::write("Current Term: {$currentTerm['term_name']}", 'green');

        // Get all active students
        $students = $db->table('users')
                      ->where('role', 'student')
                      ->where('is_active', 1)
                      ->get()
                      ->getResultArray();

        CLI::write('Found ' . count($students) . ' active students', 'green');

        $initialized = 0;
        $skipped = 0;

        foreach ($students as $student) {
            // Check if student already has academic history for current session/term
            $existing = $db->table('student_academic_history')
                          ->where('student_id', $student['id'])
                          ->where('session_id', $currentSession['id'])
                          ->where('term_id', $currentTerm['id'])
                          ->get()
                          ->getRowArray();

            if ($existing) {
                $skipped++;
                CLI::write("Skipped {$student['first_name']} {$student['last_name']} (already has record)", 'yellow');
                continue;
            }

            // Initialize academic history
            if ($student['class_id']) {
                $historyData = [
                    'student_id' => $student['id'],
                    'session_id' => $currentSession['id'],
                    'term_id' => $currentTerm['id'],
                    'class_id' => $student['class_id'],
                    'enrollment_date' => date('Y-m-d'),
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($db->table('student_academic_history')->insert($historyData)) {
                    $initialized++;
                    CLI::write("Initialized {$student['first_name']} {$student['last_name']}", 'green');
                } else {
                    CLI::write("Failed to initialize {$student['first_name']} {$student['last_name']}", 'red');
                }
            } else {
                CLI::write("Skipped {$student['first_name']} {$student['last_name']} (no class assigned)", 'yellow');
                $skipped++;
            }
        }

        CLI::write("Initialization complete!", 'green');
        CLI::write("Initialized: {$initialized} students", 'green');
        CLI::write("Skipped: {$skipped} students", 'yellow');

        // Ask if user wants to create sample term results for testing
        if (CLI::prompt('Do you want to create sample term results for testing? (y/n)', ['y', 'n']) === 'y') {
            $this->createSampleTermResults($db, $currentSession, $currentTerm);
        }
    }

    /**
     * Create sample term results for testing
     */
    private function createSampleTermResults($db, $currentSession, $currentTerm)
    {
        CLI::write('Creating sample term results...', 'yellow');

        // Get all students with academic history
        $students = $db->table('student_academic_history')
                      ->select('student_academic_history.*, users.first_name, users.last_name')
                      ->join('users', 'users.id = student_academic_history.student_id')
                      ->where('student_academic_history.session_id', $currentSession['id'])
                      ->where('student_academic_history.term_id', $currentTerm['id'])
                      ->get()
                      ->getResultArray();

        $created = 0;

        foreach ($students as $student) {
            // Check if term result already exists
            $existing = $db->table('student_term_results')
                          ->where('student_id', $student['student_id'])
                          ->where('session_id', $currentSession['id'])
                          ->where('term_id', $currentTerm['id'])
                          ->get()
                          ->getRowArray();

            if ($existing) {
                continue;
            }

            // Generate random but realistic academic data
            $totalSubjects = rand(6, 10);
            $subjectsPassed = rand(4, $totalSubjects);
            $subjectsFailed = $totalSubjects - $subjectsPassed;
            $totalMarksPossible = $totalSubjects * 100;
            $totalMarksObtained = rand(300, 850);
            $overallPercentage = ($totalMarksObtained / $totalMarksPossible) * 100;

            // Determine grade based on percentage
            $grade = 'F';
            if ($overallPercentage >= 80) $grade = 'A';
            elseif ($overallPercentage >= 70) $grade = 'B';
            elseif ($overallPercentage >= 60) $grade = 'C';
            elseif ($overallPercentage >= 50) $grade = 'D';
            elseif ($overallPercentage >= 40) $grade = 'E';

            $termResultData = [
                'student_id' => $student['student_id'],
                'session_id' => $currentSession['id'],
                'term_id' => $currentTerm['id'],
                'class_id' => $student['class_id'],
                'total_subjects' => $totalSubjects,
                'subjects_passed' => $subjectsPassed,
                'subjects_failed' => $subjectsFailed,
                'total_marks_obtained' => $totalMarksObtained,
                'total_marks_possible' => $totalMarksPossible,
                'overall_percentage' => round($overallPercentage, 2),
                'grade' => $grade,
                'position_in_class' => rand(1, 30),
                'total_students' => 30,
                'attendance_percentage' => rand(85, 100),
                'conduct_grade' => 'Good',
                'teacher_remarks' => 'Good performance. Keep it up!',
                'is_promoted' => $overallPercentage >= 40,
                'promotion_status' => $overallPercentage >= 40 ? 'promoted' : 'repeated',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($db->table('student_term_results')->insert($termResultData)) {
                $created++;
                CLI::write("Created term result for {$student['first_name']} {$student['last_name']}", 'green');
            }
        }

        CLI::write("Sample term results creation complete!", 'green');
        CLI::write("Created: {$created} term results", 'green');
    }
}
