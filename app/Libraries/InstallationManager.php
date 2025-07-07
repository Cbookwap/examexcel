<?php

namespace App\Libraries;

use CodeIgniter\Database\Database;
use Config\Database as DatabaseConfig;

/**
 * Installation Manager
 * 
 * Handles installation state detection, validation, and management
 * for the CBT application installer system.
 */
class InstallationManager
{
    private $envPath;
    private $installationLockFile;
    
    public function __construct()
    {
        $this->envPath = ROOTPATH . '.env';
        $this->installationLockFile = WRITEPATH . 'installation.lock';
    }
    
    /**
     * Check if the application is installed
     *
     * Primary check: Installation lock file is the definitive marker
     * Secondary check: Environment file with valid configuration
     */
    public function isInstalled(): bool
    {
        // Primary check: Installation lock file must exist and be valid
        if (!$this->hasInstallationLock()) {
            return false;
        }

        // Verify lock file is valid
        if (!$this->hasValidInstallationLock()) {
            return false;
        }

        // Secondary check: Ensure environment file exists and is configured
        return $this->hasEnvironmentFile();
    }

    /**
     * Check if installation lock is valid
     */
    private function hasValidInstallationLock(): bool
    {
        if (!file_exists($this->installationLockFile)) {
            return false;
        }

        $lockContent = file_get_contents($this->installationLockFile);
        $lockData = json_decode($lockContent, true);

        // Verify lock file has required data
        return $lockData &&
               isset($lockData['installed_at']) &&
               isset($lockData['installer_version']);
    }
    
    /**
     * Get installation status
     */
    public function getInstallationStatus(): array
    {
        $hasLock = $this->hasInstallationLock();
        $hasEnv = $this->hasEnvironmentFile();
        $canConnect = $this->canConnectToDatabase();
        $hasTables = $this->hasDatabaseTables();
        $hasAdmin = $this->hasAdminUser();

        return [
            'environment_configured' => $hasEnv,
            'database_connected' => $canConnect,
            'tables_created' => $hasTables,
            'admin_user_exists' => $hasAdmin,
            'installation_locked' => $hasLock,
            'is_fresh_install' => $this->isFreshInstall(),
            'is_installed' => $this->isInstalled(),
            'needs_upgrade' => $this->needsUpgrade(),
            'installation_complete' => $hasLock && $hasEnv && $hasTables && $hasAdmin
        ];
    }
    
    /**
     * Check if this is a fresh installation
     */
    public function isFreshInstall(): bool
    {
        // Only consider it non-fresh if we have a valid installation lock
        return !$this->hasValidInstallationLock();
    }
    
    /**
     * Check if environment file exists and is configured
     */
    public function hasEnvironmentFile(): bool
    {
        if (!file_exists($this->envPath)) {
            return false;
        }
        
        $envContent = file_get_contents($this->envPath);
        
        // Check for actual configured values (not commented out)
        return preg_match('/^app\.baseURL\s*=\s*.+$/m', $envContent) &&
               preg_match('/^database\.default\.database\s*=\s*.+$/m', $envContent);
    }
    
    /**
     * Check if can connect to database
     */
    public function canConnectToDatabase(): bool
    {
        if (!$this->hasEnvironmentFile()) {
            return false;
        }
        
        try {
            $db = Database::connect();
            $db->initialize();
            return $db->connID !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if database tables exist
     */
    public function hasDatabaseTables(): bool
    {
        if (!$this->canConnectToDatabase()) {
            return false;
        }
        
        try {
            $db = Database::connect();
            $tables = $db->listTables();
            
            // Check for essential tables
            $requiredTables = ['users', 'classes', 'subjects', 'exams', 'questions'];
            foreach ($requiredTables as $table) {
                if (!in_array($table, $tables)) {
                    return false;
                }
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if admin user exists
     */
    public function hasAdminUser(): bool
    {
        if (!$this->hasDatabaseTables()) {
            return false;
        }
        
        try {
            $db = Database::connect();
            $query = $db->table('users')->where('role', 'admin')->countAllResults();
            return $query > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if installation is locked
     */
    public function hasInstallationLock(): bool
    {
        return file_exists($this->installationLockFile);
    }
    
    /**
     * Create installation lock
     */
    public function createInstallationLock(): bool
    {
        $lockData = [
            'installed_at' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'installer_version' => '1.0.0'
        ];
        
        return file_put_contents($this->installationLockFile, json_encode($lockData, JSON_PRETTY_PRINT)) !== false;
    }
    
    /**
     * Remove installation lock (for reinstallation)
     */
    public function removeInstallationLock(): bool
    {
        if (file_exists($this->installationLockFile)) {
            return unlink($this->installationLockFile);
        }
        return true;
    }

    /**
     * Clear installation state for fresh installation
     */
    public function clearInstallationState(): bool
    {
        $success = true;

        // Remove installation lock file
        if (file_exists($this->installationLockFile)) {
            $success = $success && unlink($this->installationLockFile);
        }

        // Remove .env file to force fresh configuration
        if (file_exists($this->envPath)) {
            $success = $success && unlink($this->envPath);
        }

        return $success;
    }
    
    /**
     * Check if upgrade is needed
     */
    public function needsUpgrade(): bool
    {
        if (!$this->hasInstallationLock()) {
            return false;
        }
        
        $lockData = json_decode(file_get_contents($this->installationLockFile), true);
        $installedVersion = $lockData['version'] ?? '1.0.0';
        $currentVersion = '1.0.0'; // This should come from a config file
        
        return version_compare($installedVersion, $currentVersion, '<');
    }
    
    /**
     * Get installation requirements status
     */
    public function checkRequirements(): array
    {
        $requirements = [
            'php_version' => [
                'required' => '8.1.0',
                'current' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.1.0', '>=')
            ],
            'extensions' => [],
            'permissions' => []
        ];
        
        // Check required PHP extensions
        $requiredExtensions = ['mysqli', 'mbstring', 'json', 'curl', 'openssl'];
        foreach ($requiredExtensions as $ext) {
            $requirements['extensions'][$ext] = [
                'required' => true,
                'status' => extension_loaded($ext)
            ];
        }
        
        // Check directory permissions
        $requiredDirs = [
            WRITEPATH => 'writable/',
            WRITEPATH . 'cache/' => 'writable/cache/',
            WRITEPATH . 'logs/' => 'writable/logs/',
            WRITEPATH . 'session/' => 'writable/session/',
            WRITEPATH . 'uploads/' => 'writable/uploads/'
        ];
        
        foreach ($requiredDirs as $path => $displayPath) {
            $requirements['permissions'][$displayPath] = [
                'required' => true,
                'status' => is_writable($path),
                'path' => $path
            ];
        }
        
        return $requirements;
    }
    
    /**
     * Validate database connection parameters
     */
    public function validateDatabaseConnection(array $config): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'can_create_database' => false
        ];

        try {
            // Ensure we have the required parameters
            if (empty($config['hostname'])) {
                $result['message'] = 'Database hostname is required';
                return $result;
            }

            if (empty($config['username'])) {
                $result['message'] = 'Database username is required';
                return $result;
            }

            if (empty($config['database'])) {
                $result['message'] = 'Database name is required';
                return $result;
            }

            // Use native MySQLi for more reliable connection testing
            $host = $config['hostname'];
            $port = $config['port'] ?? 3306;
            $username = $config['username'];
            $password = $config['password'] ?? '';
            $database = $config['database'];

            // Test connection to MySQL server (without specific database)
            $mysqli = new \mysqli($host, $username, $password, '', $port);

            if ($mysqli->connect_error) {
                $result['message'] = 'Cannot connect to database server: ' . $mysqli->connect_error;
                return $result;
            }

            // Test if we can create the database
            $dbName = $mysqli->real_escape_string($database);
            $createQuery = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

            if ($mysqli->query($createQuery)) {
                $result['can_create_database'] = true;

                // Test connection to the specific database
                $mysqli->select_db($database);
                if ($mysqli->error) {
                    $result['message'] = 'Database created but cannot select it: ' . $mysqli->error;
                    $mysqli->close();
                    return $result;
                }

                $result['success'] = true;
                $result['message'] = 'Database connection successful and database is ready';
            } else {
                // Try to connect to existing database
                $mysqli->select_db($database);
                if ($mysqli->error) {
                    $result['message'] = 'Cannot create or access database "' . $database . '": ' . $mysqli->error;
                    $mysqli->close();
                    return $result;
                }

                $result['success'] = true;
                $result['message'] = 'Connected to existing database successfully';
            }

            $mysqli->close();

        } catch (\Exception $e) {
            $result['message'] = 'Database connection failed: ' . $e->getMessage();
        }

        return $result;
    }
    
    /**
     * Get auto-detected configuration values
     */
    public function getAutoDetectedConfig(): array
    {
        // Auto-detect base URL
        $baseURL = '';
        if (!$this->isCli()) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            $basePath = str_replace('\\', '/', $scriptName);
            $basePath = rtrim($basePath, '/');
            
            // Remove /public from the path if present
            if (str_ends_with($basePath, '/public')) {
                $basePath = substr($basePath, 0, -7);
            }
            
            $baseURL = $protocol . $host . $basePath . '/';
        }
        
        // Auto-detect database name from folder
        $folderName = basename(ROOTPATH);
        $dbName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($folderName));
        if (!str_contains($dbName, 'cbt')) {
            $dbName .= '_cbt';
        }
        
        return [
            'base_url' => $baseURL,
            'database_name' => $dbName,
            'app_name' => 'ExamExcel',
            'folder_name' => $folderName
        ];
    }

    /**
     * Check if running in CLI mode
     */
    private function isCli(): bool
    {
        return php_sapi_name() === 'cli' || defined('STDIN');
    }
}
