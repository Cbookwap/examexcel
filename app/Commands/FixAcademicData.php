<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixAcademicData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'fix:academic';
    protected $description = 'Fix academic session and term data';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('Checking academic sessions...', 'yellow');

        // Check academic sessions
        $sessions = $db->table('academic_sessions')->get()->getResultArray();
        CLI::write('Found ' . count($sessions) . ' academic sessions:', 'green');
        foreach ($sessions as $session) {
            CLI::write("- {$session['session_name']} (Current: {$session['is_current']}, Active: {$session['is_active']})");
        }

        CLI::write('Checking academic terms...', 'yellow');

        // Check academic terms
        $terms = $db->table('academic_terms')->get()->getResultArray();
        CLI::write('Found ' . count($terms) . ' academic terms:', 'green');
        foreach ($terms as $term) {
            CLI::write("- {$term['term_name']} (Session ID: {$term['session_id']}, Current: {$term['is_current']}, Active: {$term['is_active']})");
        }

        // Fix the data if needed
        if (count($sessions) > 0 && count($terms) > 0) {
            CLI::write('Fixing current session and term flags...', 'yellow');
            
            // Set the first session as current
            $db->table('academic_sessions')->update(['is_current' => 0]); // Reset all
            $db->table('academic_sessions')->where('id', $sessions[0]['id'])->update(['is_current' => 1, 'is_active' => 1]);
            
            // Set the first term as current
            $db->table('academic_terms')->update(['is_current' => 0]); // Reset all
            $db->table('academic_terms')->where('session_id', $sessions[0]['id'])->where('term_number', 1)->update(['is_current' => 1, 'is_active' => 1]);
            
            CLI::write('Fixed current session and term flags.', 'green');
        } else {
            CLI::write('Creating academic session and terms...', 'yellow');
            
            // Create academic session
            $sessionData = [
                'session_name' => '2024/2025',
                'start_date' => '2024-09-01',
                'end_date' => '2025-07-31',
                'is_current' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $sessionId = $db->table('academic_sessions')->insert($sessionData, true);
            
            // Create terms
            $termsData = [
                [
                    'session_id' => $sessionId,
                    'term_number' => 1,
                    'term_name' => 'First Term',
                    'start_date' => '2024-09-01',
                    'end_date' => '2024-12-20',
                    'is_current' => 1,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'session_id' => $sessionId,
                    'term_number' => 2,
                    'term_name' => 'Second Term',
                    'start_date' => '2025-01-08',
                    'end_date' => '2025-04-11',
                    'is_current' => 0,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'session_id' => $sessionId,
                    'term_number' => 3,
                    'term_name' => 'Third Term',
                    'start_date' => '2025-04-28',
                    'end_date' => '2025-07-31',
                    'is_current' => 0,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ];
            
            $db->table('academic_terms')->insertBatch($termsData);
            CLI::write('Created academic session and terms.', 'green');
        }

        CLI::write('Done! Academic data should now be properly set up.', 'green');
    }
}
