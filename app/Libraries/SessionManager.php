<?php

namespace App\Libraries;

use App\Models\ActiveSessionModel;

class SessionManager
{
    protected $activeSessionModel;
    protected $session;

    public function __construct()
    {
        $this->activeSessionModel = new ActiveSessionModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Track user login session
     */
    public function trackLogin($userId, $userRole, $request = null)
    {
        try {
            $sessionId = $this->session->session_id;
            $ipAddress = $request ? $request->getIPAddress() : null;
            $userAgent = $request ? $request->getUserAgent() : null;

            // Create session record
            $result = $this->activeSessionModel->createSession(
                $sessionId,
                $userId,
                $userRole,
                $ipAddress,
                $userAgent
            );

            if ($result) {
                log_message('info', "Session tracked for user {$userId} (role: {$userRole})");
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Failed to track login session: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Track user logout
     */
    public function trackLogout($sessionId = null)
    {
        try {
            $sessionId = $sessionId ?: $this->session->session_id;

            $result = $this->activeSessionModel->deactivateSession($sessionId);

            if ($result) {
                log_message('info', "Session deactivated: {$sessionId}");
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Failed to track logout: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update session activity
     */
    public function updateActivity($sessionId = null)
    {
        try {
            $sessionId = $sessionId ?: $this->session->session_id;
            return $this->activeSessionModel->updateActivity($sessionId);
        } catch (\Exception $e) {
            log_message('error', 'Failed to update session activity: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Force logout users by role
     */
    public function forceLogoutByRole($roles)
    {
        try {
            if (!is_array($roles)) {
                $roles = [$roles];
            }

            // Get active sessions for these roles
            $activeSessions = [];
            foreach ($roles as $role) {
                $roleSessions = $this->activeSessionModel->getActiveSessionsByRole($role);
                $activeSessions = array_merge($activeSessions, $roleSessions);
            }

            log_message('info', 'Found ' . count($activeSessions) . ' active sessions to terminate for roles: ' . implode(', ', $roles));

            // Deactivate sessions in database first
            $result = $this->activeSessionModel->deactivateRoleSessions($roles);

            // Destroy actual session files
            $destroyedCount = 0;
            foreach ($activeSessions as $sessionData) {
                if ($this->destroySessionFile($sessionData['session_id'])) {
                    $destroyedCount++;
                }
            }

            // Also perform a nuclear cleanup - destroy all session files and let users re-login
            $nuclearCleanupCount = $this->performNuclearSessionCleanup($roles);

            log_message('info', "Force logout completed: {$destroyedCount} targeted sessions + {$nuclearCleanupCount} nuclear cleanup for roles: " . implode(', ', $roles));

            return [
                'database_updated' => $result,
                'sessions_destroyed' => $destroyedCount,
                'nuclear_cleanup' => $nuclearCleanupCount,
                'total_sessions' => count($activeSessions)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Failed to force logout by role: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Force logout specific user
     */
    public function forceLogoutUser($userId)
    {
        try {
            // Get user's active sessions
            $activeSessions = $this->activeSessionModel->where('user_id', $userId)
                                                      ->where('is_active', 1)
                                                      ->findAll();

            // Deactivate in database
            $result = $this->activeSessionModel->deactivateUserSessions($userId);

            // Destroy session files
            $destroyedCount = 0;
            foreach ($activeSessions as $sessionData) {
                if ($this->destroySessionFile($sessionData['session_id'])) {
                    $destroyedCount++;
                }
            }

            log_message('info', "Force logout user {$userId}: {$destroyedCount} sessions destroyed");

            return [
                'database_updated' => $result,
                'sessions_destroyed' => $destroyedCount,
                'total_sessions' => count($activeSessions)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Failed to force logout user: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Destroy session file
     */
    private function destroySessionFile($sessionId)
    {
        try {
            $sessionPath = WRITEPATH . 'session';
            $destroyed = false;

            // Ensure session path exists
            if (!is_dir($sessionPath)) {
                log_message('warning', "Session path does not exist: {$sessionPath}");
                return false;
            }

            // Try different possible session file formats
            $possibleFiles = [
                $sessionPath . '/ci_session' . $sessionId,
                $sessionPath . '/ci_session:' . $sessionId,
                $sessionPath . '/' . $sessionId,
                $sessionPath . '/sess_' . $sessionId,
                $sessionPath . '/ci_session_' . $sessionId
            ];

            foreach ($possibleFiles as $file) {
                if (file_exists($file)) {
                    if (unlink($file)) {
                        $destroyed = true;
                        log_message('info', "Session file destroyed: {$file}");
                    }
                }
            }

            // Scan all files in session directory for pattern matches
            $files = scandir($sessionPath);
            if ($files) {
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;

                    $fullPath = $sessionPath . '/' . $file;
                    if (!is_file($fullPath)) continue;

                    // Check if filename contains session ID
                    if (strpos($file, $sessionId) !== false) {
                        if (unlink($fullPath)) {
                            $destroyed = true;
                            log_message('info', "Session file destroyed by pattern match: {$fullPath}");
                        }
                    } else {
                        // Check file content for session ID (for serialized session data)
                        $content = file_get_contents($fullPath);
                        if ($content && strpos($content, $sessionId) !== false) {
                            if (unlink($fullPath)) {
                                $destroyed = true;
                                log_message('info', "Session file destroyed by content match: {$fullPath}");
                            }
                        }
                    }
                }
            }

            if (!$destroyed) {
                log_message('warning', "No session file found for session ID: {$sessionId}");
            }

            return $destroyed;
        } catch (\Exception $e) {
            log_message('error', 'Failed to destroy session file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if current session is valid
     */
    public function isCurrentSessionValid()
    {
        try {
            $sessionId = $this->session->session_id;
            return $this->activeSessionModel->isSessionActive($sessionId);
        } catch (\Exception $e) {
            log_message('error', 'Failed to check current session validity: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active sessions statistics
     */
    public function getSessionStats()
    {
        return $this->activeSessionModel->getSessionStats();
    }

    /**
     * Get all active sessions
     */
    public function getActiveSessions()
    {
        return $this->activeSessionModel->getActiveSessions();
    }

    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions($timeoutMinutes = 120)
    {
        return $this->activeSessionModel->cleanupExpiredSessions($timeoutMinutes);
    }

    /**
     * Nuclear session cleanup - destroy all session files for locked roles
     * This is a more aggressive approach when targeted cleanup fails
     */
    private function performNuclearSessionCleanup($lockedRoles)
    {
        try {
            $sessionPath = WRITEPATH . 'session';
            $destroyedCount = 0;

            if (!is_dir($sessionPath)) {
                return 0;
            }

            // Get all users with locked roles
            $userModel = new \App\Models\UserModel();
            $lockedUsers = $userModel->whereIn('role', $lockedRoles)->findAll();
            $lockedUserIds = array_column($lockedUsers, 'id');

            log_message('info', 'Nuclear cleanup: Found ' . count($lockedUsers) . ' users with locked roles');

            // Scan all session files
            $files = scandir($sessionPath);
            if ($files) {
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;

                    $fullPath = $sessionPath . '/' . $file;
                    if (!is_file($fullPath)) continue;

                    // Read session file content
                    $content = file_get_contents($fullPath);
                    if (!$content) continue;

                    // Check if session belongs to locked user
                    $shouldDestroy = false;

                    // Check for user_id in session data
                    foreach ($lockedUserIds as $userId) {
                        if (strpos($content, '"user_id";i:' . $userId . ';') !== false ||
                            strpos($content, '"user_id":"' . $userId . '"') !== false ||
                            strpos($content, 'user_id|i:' . $userId . ';') !== false) {
                            $shouldDestroy = true;
                            break;
                        }
                    }

                    // Check for role in session data
                    if (!$shouldDestroy) {
                        foreach ($lockedRoles as $role) {
                            if (strpos($content, '"role";s:' . strlen($role) . ':"' . $role . '"') !== false ||
                                strpos($content, '"role":"' . $role . '"') !== false ||
                                strpos($content, 'role|s:' . strlen($role) . ':"' . $role . '"') !== false) {
                                $shouldDestroy = true;
                                break;
                            }
                        }
                    }

                    if ($shouldDestroy) {
                        if (unlink($fullPath)) {
                            $destroyedCount++;
                            log_message('info', "Nuclear cleanup destroyed session file: {$fullPath}");
                        }
                    }
                }
            }

            log_message('info', "Nuclear cleanup completed: {$destroyedCount} session files destroyed");
            return $destroyedCount;
        } catch (\Exception $e) {
            log_message('error', 'Nuclear session cleanup failed: ' . $e->getMessage());
            return 0;
        }
    }
}
