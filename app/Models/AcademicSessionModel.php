<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicSessionModel extends Model
{
    protected $table = 'academic_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'session_name', 'start_date', 'end_date', 'is_current', 'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'session_name' => 'required|min_length[7]|max_length[20]|is_unique[academic_sessions.session_name,id,{id}]',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'session_name' => [
            'required' => 'Academic session name is required',
            'min_length' => 'Session name must be at least 7 characters (e.g., 2024/25)',
            'max_length' => 'Session name cannot exceed 20 characters',
            'is_unique' => 'This academic session already exists'
        ],
        'start_date' => [
            'required' => 'Start date is required',
            'valid_date' => 'Please enter a valid start date'
        ],
        'end_date' => [
            'required' => 'End date is required',
            'valid_date' => 'Please enter a valid end date'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getCurrentSession()
    {
        return $this->where('is_current', 1)->where('is_active', 1)->first();
    }

    public function getActiveSessions()
    {
        return $this->where('is_active', 1)->orderBy('session_name', 'DESC')->findAll();
    }

    public function setCurrentSession($sessionId)
    {
        // First, unset all current sessions
        $this->where('is_current', 1)->set(['is_current' => 0])->update();

        // Then set the new current session
        return $this->update($sessionId, ['is_current' => 1]);
    }

    public function getSessionWithTerms($sessionId)
    {
        $session = $this->find($sessionId);
        if (!$session) {
            return null;
        }

        $termModel = new AcademicTermModel();
        $session['terms'] = $termModel->where('session_id', $sessionId)
                                     ->orderBy('term_number', 'ASC')
                                     ->findAll();

        return $session;
    }

    public function createSessionWithTerms($sessionData)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Create session
        $sessionId = $this->insert($sessionData);

        if ($sessionId) {
            // Create three terms for the session
            $termModel = new AcademicTermModel();
            $terms = [
                [
                    'session_id' => $sessionId,
                    'term_number' => 1,
                    'term_name' => 'First Term',
                    'start_date' => $sessionData['start_date'],
                    'end_date' => date('Y-m-d', strtotime($sessionData['start_date'] . ' +4 months')),
                    'is_active' => 1
                ],
                [
                    'session_id' => $sessionId,
                    'term_number' => 2,
                    'term_name' => 'Second Term',
                    'start_date' => date('Y-m-d', strtotime($sessionData['start_date'] . ' +5 months')),
                    'end_date' => date('Y-m-d', strtotime($sessionData['start_date'] . ' +8 months')),
                    'is_active' => 1
                ],
                [
                    'session_id' => $sessionId,
                    'term_number' => 3,
                    'term_name' => 'Third Term',
                    'start_date' => date('Y-m-d', strtotime($sessionData['start_date'] . ' +9 months')),
                    'end_date' => $sessionData['end_date'],
                    'is_active' => 1
                ]
            ];

            foreach ($terms as $term) {
                $termModel->insert($term);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return false;
        }

        return $sessionId;
    }

    public function getSessionStats()
    {
        $stats = [];

        $stats['total'] = $this->countAll();
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        $stats['current'] = $this->where('is_current', 1)->countAllResults();

        // Calculate upcoming and completed sessions
        $today = date('Y-m-d');
        $stats['upcoming'] = $this->where('start_date >', $today)->where('is_active', 1)->countAllResults();
        $stats['completed'] = $this->where('end_date <', $today)->where('is_active', 1)->countAllResults();

        return $stats;
    }

    public function generateSessionName($startYear)
    {
        $endYear = $startYear + 1;
        return $startYear . '/' . $endYear;
    }

    public function isSessionNameValid($sessionName)
    {
        // Check format: YYYY/YYYY
        return preg_match('/^\d{4}\/\d{4}$/', $sessionName);
    }
}
