<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckQuestions extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'check:questions';
    protected $description = 'Check questions in the database';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('=== Question Database Analysis ===', 'yellow');

        // Check total questions
        $totalQuestions = $db->table('questions')->countAllResults();
        CLI::write("Total questions in database: $totalQuestions", 'green');

        // Check active questions
        $activeQuestions = $db->table('questions')->where('is_active', 1)->countAllResults();
        CLI::write("Active questions: $activeQuestions", 'green');

        // Check inactive questions
        $inactiveQuestions = $db->table('questions')->where('is_active', 0)->countAllResults();
        CLI::write("Inactive questions: $inactiveQuestions", 'red');

        // Check questions by created_by
        $questionsByUser = $db->table('questions')
            ->select('created_by, COUNT(*) as count')
            ->groupBy('created_by')
            ->get()
            ->getResultArray();

        CLI::write("\n=== Questions by User ===", 'yellow');
        foreach ($questionsByUser as $user) {
            CLI::write("User ID {$user['created_by']}: {$user['count']} questions");
        }

        // Check questions by subject
        $questionsBySubject = $db->table('questions q')
            ->select('s.name as subject_name, COUNT(*) as count')
            ->join('subjects s', 's.id = q.subject_id', 'left')
            ->groupBy('q.subject_id')
            ->get()
            ->getResultArray();

        CLI::write("\n=== Questions by Subject ===", 'yellow');
        foreach ($questionsBySubject as $subject) {
            CLI::write("{$subject['subject_name']}: {$subject['count']} questions");
        }

        // Check recent questions (last 10)
        $recentQuestions = $db->table('questions q')
            ->select('q.id, q.question_text, q.is_active, q.created_at, s.name as subject_name')
            ->join('subjects s', 's.id = q.subject_id', 'left')
            ->orderBy('q.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        CLI::write("\n=== Recent 10 Questions ===", 'yellow');
        foreach ($recentQuestions as $q) {
            $status = $q['is_active'] ? 'Active' : 'Inactive';
            CLI::write("ID: {$q['id']}, Subject: {$q['subject_name']}, Status: $status, Created: {$q['created_at']}");
            CLI::write("Text: " . substr($q['question_text'], 0, 100) . "...\n");
        }

        // Check if there are any bulk created questions
        $bulkQuestions = $db->table('questions')
            ->where('created_at >', date('Y-m-d', strtotime('-30 days')))
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        CLI::write("\n=== Questions Created in Last 30 Days ===", 'yellow');
        CLI::write("Count: " . count($bulkQuestions));

        if (count($bulkQuestions) > 50) {
            CLI::write("WARNING: Large number of questions created recently!", 'red');

            // Group by creation date
            $byDate = [];
            foreach ($bulkQuestions as $q) {
                $date = date('Y-m-d', strtotime($q['created_at']));
                if (!isset($byDate[$date])) {
                    $byDate[$date] = 0;
                }
                $byDate[$date]++;
            }

            CLI::write("\nQuestions by creation date:");
            foreach ($byDate as $date => $count) {
                CLI::write("$date: $count questions");
            }
        }

        // Check other question-related tables
        CLI::write("\n=== Other Question Tables ===", 'yellow');

        $practiceQuestions = $db->table('practice_questions')->countAllResults();
        CLI::write("Practice questions: $practiceQuestions");

        $examQuestions = $db->table('exam_questions')->countAllResults();
        CLI::write("Exam questions (assignments): $examQuestions");

        // Check if pagination might be querying wrong table
        CLI::write("\n=== Debugging Pagination Query ===", 'yellow');

        // Simulate the pagination query
        $builder = $db->table('questions q');
        $builder->select('q.*, s.name as subject_name, s.code as subject_code, u.first_name, u.last_name,
                         sess.session_name, term.term_name, term.term_number, c.name as class_name');
        $builder->join('subjects s', 's.id = q.subject_id', 'left');
        $builder->join('users u', 'u.id = q.created_by', 'left');
        $builder->join('academic_sessions sess', 'sess.id = q.session_id', 'left');
        $builder->join('academic_terms term', 'term.id = q.term_id', 'left');
        $builder->join('classes c', 'c.id = q.class_id', 'left');
        $builder->where('q.is_active', 1);

        $paginationCount = $builder->countAllResults(false);
        CLI::write("Pagination query count (with joins): $paginationCount");

        // Check if there are any duplicate joins causing multiplication
        $simpleCount = $db->table('questions')->where('is_active', 1)->countAllResults();
        CLI::write("Simple count (no joins): $simpleCount");

        if ($paginationCount != $simpleCount) {
            CLI::write("WARNING: Join query is returning different count!", 'red');
            CLI::write("This suggests the joins are causing row multiplication.", 'red');
        }
    }
}
