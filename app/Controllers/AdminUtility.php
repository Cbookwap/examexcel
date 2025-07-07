<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminUtility extends BaseController
{
    protected $examQuestionModel;
    protected $examModel;

    public function __construct()
    {
        $this->examQuestionModel = new \App\Models\ExamQuestionModel();
        $this->examModel = new \App\Models\ExamModel();
    }

    /**
     * Check for class mismatches in exam questions
     */
    public function checkClassMismatches()
    {
        // Only admin can access this utility
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Access denied');
        }

        $mismatches = $this->examQuestionModel->findClassMismatches();

        $data = [
            'title' => 'Class Mismatch Checker - Admin Utility',
            'pageTitle' => 'Question Class Mismatch Checker',
            'pageSubtitle' => 'Identify and fix questions assigned to wrong class levels',
            'mismatches' => $mismatches
        ];

        return view('admin/utility/class_mismatches', $data);
    }

    /**
     * Clean up class mismatches for a specific exam
     */
    public function cleanupExamMismatches($examId)
    {
        // Only admin can access this utility
        if ($this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $result = $this->examQuestionModel->cleanupClassMismatches($examId);

        return $this->response->setJSON($result);
    }

    /**
     * Clean up all class mismatches system-wide
     */
    public function cleanupAllMismatches()
    {
        // Only admin can access this utility
        if ($this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $allMismatches = $this->examQuestionModel->findClassMismatches();
        
        if (empty($allMismatches)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'No class mismatches found in the system',
                'total_removed' => 0
            ]);
        }

        // Group by exam ID
        $examGroups = [];
        foreach ($allMismatches as $mismatch) {
            $examGroups[$mismatch['exam_id']][] = $mismatch;
        }

        $totalRemoved = 0;
        $cleanupResults = [];

        foreach ($examGroups as $examId => $examMismatches) {
            $result = $this->examQuestionModel->cleanupClassMismatches($examId);
            $totalRemoved += $result['removed_count'];
            $cleanupResults[] = [
                'exam_id' => $examId,
                'exam_title' => $examMismatches[0]['exam_title'],
                'removed_count' => $result['removed_count']
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully cleaned up class mismatches from " . count($examGroups) . " exams",
            'total_removed' => $totalRemoved,
            'cleanup_details' => $cleanupResults
        ]);
    }

    /**
     * Validate all exam questions for class consistency
     */
    public function validateAllExams()
    {
        // Only admin can access this utility
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/admin')->with('error', 'Access denied');
        }

        $exams = $this->examModel->findAll();
        $validationResults = [];

        foreach ($exams as $exam) {
            $mismatches = $this->examQuestionModel->findClassMismatches($exam['id']);
            $validationResults[] = [
                'exam' => $exam,
                'mismatch_count' => count($mismatches),
                'mismatches' => $mismatches,
                'status' => empty($mismatches) ? 'valid' : 'invalid'
            ];
        }

        $data = [
            'title' => 'Exam Validation Report - Admin Utility',
            'pageTitle' => 'Exam Question Validation',
            'pageSubtitle' => 'Comprehensive validation of all exam questions',
            'validation_results' => $validationResults,
            'total_exams' => count($exams),
            'valid_exams' => count(array_filter($validationResults, fn($r) => $r['status'] === 'valid')),
            'invalid_exams' => count(array_filter($validationResults, fn($r) => $r['status'] === 'invalid'))
        ];

        return view('admin/utility/exam_validation', $data);
    }
}
