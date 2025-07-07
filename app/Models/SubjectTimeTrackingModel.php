<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectTimeTrackingModel extends Model
{
    protected $table = 'subject_time_tracking';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'exam_attempt_id', 'subject_id', 'start_time', 'end_time', 
        'time_spent_seconds', 'is_completed'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Start tracking time for a subject
     */
    public function startSubjectTime($attemptId, $subjectId)
    {
        // Check if there's already a record for this subject
        $existing = $this->where('exam_attempt_id', $attemptId)
                        ->where('subject_id', $subjectId)
                        ->first();

        if ($existing) {
            // If no start_time is set, set it now
            if (!$existing['start_time']) {
                $this->update($existing['id'], [
                    'start_time' => date('Y-m-d H:i:s'),
                    'end_time' => null
                ]);
                return $existing['id'];
            }

            // If there's an end_time, start a new session
            if ($existing['end_time']) {
                $data = [
                    'exam_attempt_id' => $attemptId,
                    'subject_id' => $subjectId,
                    'start_time' => date('Y-m-d H:i:s'),
                    'time_spent_seconds' => 0,
                    'is_completed' => 0
                ];
                return $this->insert($data);
            }

            // Resume existing active session
            return $existing['id'];
        }

        // Create new time tracking record
        $data = [
            'exam_attempt_id' => $attemptId,
            'subject_id' => $subjectId,
            'start_time' => date('Y-m-d H:i:s'),
            'time_spent_seconds' => 0,
            'is_completed' => 0
        ];

        return $this->insert($data);
    }

    /**
     * End tracking time for a subject
     */
    public function endSubjectTime($attemptId, $subjectId, $isCompleted = false)
    {
        $activeSession = $this->where('exam_attempt_id', $attemptId)
                             ->where('subject_id', $subjectId)
                             ->where('end_time IS NULL')
                             ->first();

        if (!$activeSession) {
            return false;
        }

        $endTime = date('Y-m-d H:i:s');
        $startTime = new \DateTime($activeSession['start_time']);
        $endTimeObj = new \DateTime($endTime);
        $timeSpent = $endTimeObj->getTimestamp() - $startTime->getTimestamp();

        // Add to existing time spent
        $totalTimeSpent = $activeSession['time_spent_seconds'] + $timeSpent;

        $updateData = [
            'end_time' => $endTime,
            'time_spent_seconds' => $totalTimeSpent,
            'is_completed' => $isCompleted ? 1 : 0
        ];

        return $this->update($activeSession['id'], $updateData);
    }

    /**
     * Get time spent on each subject for an attempt (aggregated)
     */
    public function getSubjectTimeSpent($attemptId)
    {
        $builder = $this->db->table('subject_time_tracking stt');
        return $builder->select('stt.subject_id, s.name as subject_name,
                                SUM(stt.time_spent_seconds) as time_spent_seconds,
                                MAX(stt.is_completed) as is_completed')
                      ->join('subjects s', 's.id = stt.subject_id')
                      ->where('stt.exam_attempt_id', $attemptId)
                      ->groupBy('stt.subject_id, s.name')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get total time spent on a specific subject
     */
    public function getTotalSubjectTime($attemptId, $subjectId)
    {
        $sessions = $this->where('exam_attempt_id', $attemptId)
                        ->where('subject_id', $subjectId)
                        ->findAll();

        $totalSeconds = 0;
        foreach ($sessions as $session) {
            if ($session['end_time']) {
                $totalSeconds += $session['time_spent_seconds'];
            } else {
                // Calculate current session time if still active
                $startTime = new \DateTime($session['start_time']);
                $currentTime = new \DateTime();
                $currentSessionTime = $currentTime->getTimestamp() - $startTime->getTimestamp();
                $totalSeconds += $session['time_spent_seconds'] + $currentSessionTime;
            }
        }

        return $totalSeconds;
    }

    /**
     * Update time spent for active session (called periodically)
     */
    public function updateActiveSessionTime($attemptId, $subjectId)
    {
        $activeSession = $this->where('exam_attempt_id', $attemptId)
                             ->where('subject_id', $subjectId)
                             ->where('end_time IS NULL')
                             ->first();

        if (!$activeSession) {
            return false;
        }

        $currentTime = date('Y-m-d H:i:s');
        $startTime = new \DateTime($activeSession['start_time']);
        $currentTimeObj = new \DateTime($currentTime);
        $timeSpent = $currentTimeObj->getTimestamp() - $startTime->getTimestamp();

        // Update time spent
        $updateData = [
            'time_spent_seconds' => $timeSpent
        ];

        return $this->update($activeSession['id'], $updateData);
    }

    /**
     * Initialize time tracking for all subjects when exam starts
     */
    public function initializeExamTimeTracking($attemptId, $examId)
    {
        // Get exam subjects
        $examSubjectModel = new \App\Models\ExamSubjectModel();
        $examSubjects = $examSubjectModel->getExamSubjects($examId);

        if (empty($examSubjects)) {
            return false;
        }

        // Check if time tracking already exists for this attempt
        $existingTracking = $this->where('exam_attempt_id', $attemptId)->first();
        if ($existingTracking) {
            return true; // Already initialized
        }

        // Create initial time tracking records for all subjects
        $trackingData = [];
        foreach ($examSubjects as $subject) {
            $trackingData[] = [
                'exam_attempt_id' => $attemptId,
                'subject_id' => $subject['subject_id'],
                'start_time' => null, // Will be set when student first accesses the subject
                'time_spent_seconds' => 0,
                'is_completed' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        return $this->insertBatch($trackingData);
    }

    /**
     * Format seconds to human readable time
     */
    public function formatTime($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' sec';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return $minutes . ' min' . ($remainingSeconds > 0 ? ' ' . $remainingSeconds . ' sec' : '');
        } else {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return $hours . ' hr' . ($minutes > 0 ? ' ' . $minutes . ' min' : '');
        }
    }
}
