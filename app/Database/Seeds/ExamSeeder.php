<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Get current session and term
        $currentSession = $this->db->table('academic_sessions')->where('is_current', 1)->get()->getRowArray();
        $currentTerm = $this->db->table('academic_terms')->where('is_current', 1)->get()->getRowArray();

        if (!$currentSession || !$currentTerm) {
            echo "Error: No current session or term found.\n";
            return;
        }

        $sessionId = $currentSession['id'];
        $termId = $currentTerm['id'];

        // Get class IDs for JSS 1 A and SS 1 Science
        $jss1Class = $this->db->table('classes')->where('name', 'JSS 1')->where('section', 'A')->get()->getRowArray();
        $ss1Class = $this->db->table('classes')->where('name', 'SS 1')->where('section', 'Science')->get()->getRowArray();

        if (!$jss1Class || !$ss1Class) {
            echo "Error: Required classes not found.\n";
            return;
        }

        // Get all subjects that have questions
        $subjects = $this->db->table('subjects')
                            ->select('subjects.*, COUNT(questions.id) as question_count')
                            ->join('questions', 'questions.subject_id = subjects.id', 'left')
                            ->where('subjects.is_active', 1)
                            ->where('questions.is_active', 1)
                            ->groupBy('subjects.id')
                            ->having('question_count >', 0)
                            ->get()
                            ->getResultArray();

        // Calculate start time (now) and end time (5 days from now)
        $startTime = date('Y-m-d H:i:s');
        $endTime = date('Y-m-d H:i:s', strtotime('+5 days')); // 5 days exam duration

        $createdExams = [];

        foreach ($subjects as $subject) {
            // Create exam for JSS 1 A
            $jss1ExamData = [
                'title' => $subject['name'] . ' - JSS 1 First Term Exam',
                'description' => 'First Term examination for ' . $subject['name'] . ' - JSS 1 A',
                'subject_id' => $subject['id'],
                'class_id' => $jss1Class['id'],
                'session_id' => $sessionId,
                'term_id' => $termId,
                'exam_type' => 'final',
                'status' => 'scheduled',
                'duration_minutes' => 60,
                'total_marks' => 9, // 3 questions × 3 marks each
                'passing_marks' => 5,
                'question_count' => 3,
                'negative_marking' => 0,
                'negative_marks_per_question' => 0,
                'randomize_questions' => 1,
                'randomize_options' => 1,
                'show_result_immediately' => 1,
                'allow_review' => 1,
                'require_proctoring' => 0,
                'browser_lockdown' => 0,
                'prevent_copy_paste' => 1,
                'disable_right_click' => 1,
                'calculator_enabled' => $subject['code'] === 'MATH' ? 1 : 0,
                'exam_pause_enabled' => 1,
                'max_attempts' => 5,
                'attempt_delay_minutes' => 0,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'instructions' => json_encode([
                    'general' => 'Read all questions carefully before answering. You have 60 minutes to complete this exam.',
                    'technical' => 'Ensure stable internet connection. The exam will auto-submit when time expires.',
                    'rules' => 'No external help allowed. Answer all questions to the best of your ability.'
                ]),
                'settings' => json_encode([
                    'auto_submit' => 1,
                    'show_timer' => 1,
                    'allow_navigation' => 1
                ]),
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ];

            $jss1ExamId = $this->db->table('exams')->insert($jss1ExamData, true);
            if ($jss1ExamId) {
                $createdExams[] = ['id' => $jss1ExamId, 'class' => 'JSS 1 A', 'subject' => $subject['name']];
                $this->assignQuestionsToExam($jss1ExamId, $subject['id'], $jss1Class['id'], 3);
            }

            // Create exam for SSS 1 Science
            $sss1ExamData = [
                'title' => $subject['name'] . ' - SSS 1 First Term Exam',
                'description' => 'First Term examination for ' . $subject['name'] . ' - SSS 1 Science',
                'subject_id' => $subject['id'],
                'class_id' => $ss1Class['id'],
                'session_id' => $sessionId,
                'term_id' => $termId,
                'exam_type' => 'final',
                'status' => 'scheduled',
                'duration_minutes' => 90,
                'total_marks' => 8, // 2 questions × 4 marks each
                'passing_marks' => 5,
                'question_count' => 2,
                'negative_marking' => 0,
                'negative_marks_per_question' => 0,
                'randomize_questions' => 1,
                'randomize_options' => 1,
                'show_result_immediately' => 1,
                'allow_review' => 1,
                'require_proctoring' => 0,
                'browser_lockdown' => 0,
                'prevent_copy_paste' => 1,
                'disable_right_click' => 1,
                'calculator_enabled' => in_array($subject['code'], ['MATH', 'PHY', 'CHE']) ? 1 : 0,
                'exam_pause_enabled' => 1,
                'max_attempts' => 5,
                'attempt_delay_minutes' => 0,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'instructions' => json_encode([
                    'general' => 'Read all questions carefully before answering. You have 90 minutes to complete this exam.',
                    'technical' => 'Ensure stable internet connection. The exam will auto-submit when time expires.',
                    'rules' => 'No external help allowed. Answer all questions to the best of your ability.'
                ]),
                'settings' => json_encode([
                    'auto_submit' => 1,
                    'show_timer' => 1,
                    'allow_navigation' => 1
                ]),
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ];

            $sss1ExamId = $this->db->table('exams')->insert($sss1ExamData, true);
            if ($sss1ExamId) {
                $createdExams[] = ['id' => $sss1ExamId, 'class' => 'SSS 1 Science', 'subject' => $subject['name']];
                $this->assignQuestionsToExam($sss1ExamId, $subject['id'], $ss1Class['id'], 2);
            }
        }

        echo "Exam creation completed!\n";
        echo "Created " . count($createdExams) . " exams:\n";
        foreach ($createdExams as $exam) {
            echo "- {$exam['subject']} for {$exam['class']} (ID: {$exam['id']})\n";
        }
        echo "Exam start time: $startTime\n";
        echo "Exam end time: $endTime\n";
    }

    private function assignQuestionsToExam($examId, $subjectId, $classId, $questionCount)
    {
        // Get exam details for validation
        $exam = $this->db->table('exams')->where('id', $examId)->get()->getRowArray();
        if (!$exam) {
            echo "ERROR: Exam ID $examId not found\n";
            return;
        }

        // CRITICAL VALIDATION: Ensure class ID matches
        if ($exam['class_id'] != $classId) {
            echo "ERROR: Class mismatch for exam ID $examId. Exam class: {$exam['class_id']}, Requested class: $classId\n";
            return;
        }

        // Get available questions for this subject and class
        $questions = $this->db->table('questions')
                             ->where('subject_id', $subjectId)
                             ->where('class_id', $classId)
                             ->where('is_active', 1)
                             ->orderBy('RAND()')
                             ->limit($questionCount)
                             ->get()
                             ->getResultArray();

        if (empty($questions)) {
            echo "WARNING: No questions found for subject ID $subjectId, class ID $classId\n";
            return;
        }

        // Assign questions to exam with proper validation
        foreach ($questions as $index => $question) {
            // Double-check class match before insertion
            if ($question['class_id'] != $exam['class_id']) {
                echo "ERROR: Skipping question ID {$question['id']} - class mismatch\n";
                continue;
            }

            $this->db->table('exam_questions')->insert([
                'exam_id' => $examId,
                'question_id' => $question['id'],
                'subject_id' => $subjectId,
                'order_index' => $index + 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        echo "Assigned " . count($questions) . " questions to exam ID $examId (Subject: $subjectId, Class: $classId)\n";
    }
}
