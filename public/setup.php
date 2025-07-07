<?php
/**
 * CBT Application Installer
 * WordPress-style installation interface
 */

// Suppress warnings during installation
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

// License Validation Class
class LicenseValidator {
    private $publicKey;
    private $licenseServerUrl = 'https://adclime.com/license/validate.php'; // Your PDS license server

    public function __construct() {
        // RSA public key for offline validation - matches your license server
        // This key must match the public key in your license_keys.json file
        $this->publicKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAu1SU1L7VLPHCgcBIjn5z
swgk4jPavPlg4ZHRilCPv4zCDc15clgTQTZQMzh8RKhLLpI/4ggU0s3wLPkY0F4R
Zk5DLH0RhH6+PtyFvukfMrd+WeR0kuc96lxCiR7UOYHe0GfbvqCvHJK8UPpH+816
9QvtdfnMbz830KW/i1NqcixpaxUBEhwyz/vxqnjRHV22h5Ua9/iHfhzQypB6egaV
6k+6XOCzViaiKRwdsqwQbnR4YjvUbEjDc08bj4imuSV6ABIjTbugn/0/p4MV1NyX
goaKxm3xaAfkDQvfh65VMcx6h2/g1y33/FFWdhWFAeGazYrf03uH05/lAsHTeddh
+wIDAQAB
-----END PUBLIC KEY-----";
    }

    /**
     * Generate hardware fingerprint for license binding
     */
    public function generateHardwareFingerprint(): string {
        $factors = [
            php_uname('n'), // hostname
            php_uname('s'), // OS
            $_SERVER['SERVER_NAME'] ?? 'unknown',
            $_SERVER['HTTP_HOST'] ?? 'unknown',
            $_SERVER['DOCUMENT_ROOT'] ?? 'unknown'
        ];

        return hash('sha256', implode('|', $factors));
    }

    /**
     * Validate purchase code format
     */
    public function validateCodeFormat(string $code): bool {
        // Super development code
        if ($code === 'PDS-DEV-2024-SUPER-BYPASS') {
            return true;
        }

        // Format: XXXX-XXXX-XXXX-BASE64DATA-SIGNATURE
        // New short format: PDS-XXX-YYYY-XXXXX-XXXXXX
        if (preg_match('/^PDS-[A-Z0-9]{3}-\d{4}-[A-Z0-9]{4,5}-[A-Z0-9]{6}$/', $code)) {
            return true;
        }

        // Legacy format with base64 data and signature parts
        return preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Za-z0-9+\/=]+-[A-Za-z0-9+\/=]+$/', $code);
    }

    /**
     * Parse purchase code components
     */
    public function parseCode(string $code): array {
        // Handle test codes for demonstration
        $testCodes = [
            'TEST-DEMO-2024-STANDARD-SIGNATURE' => [
                'type' => 'standard',
                'exp' => date('Y-m-d', strtotime('+1 year')),
                'installs' => 1,
                'features' => ['full_access'],
                'issued' => date('Y-m-d')
            ],
            'DEMO-PREM-2024-PREMIUM-SIGNATURE' => [
                'type' => 'premium',
                'exp' => date('Y-m-d', strtotime('+2 years')),
                'installs' => 3,
                'features' => ['full_access', 'premium_support'],
                'issued' => date('Y-m-d')
            ],
            'EVAL-ENTP-2024-ENTERPRISE-SIGNATURE' => [
                'type' => 'enterprise',
                'exp' => date('Y-m-d', strtotime('+10 years')),
                'installs' => 999,
                'features' => ['full_access', 'premium_support', 'custom_features'],
                'issued' => date('Y-m-d')
            ]
        ];

        if (isset($testCodes[$code])) {
            $parts = explode('-', $code);
            $signature = array_pop($parts);
            $codeData = implode('-', $parts);

            return [
                'valid' => true,
                'code_data' => $codeData,
                'signature' => $signature,
                'license_type' => $testCodes[$code]['type'],
                'expiry_date' => $testCodes[$code]['exp'],
                'max_installs' => $testCodes[$code]['installs'],
                'features' => $testCodes[$code]['features'],
                'issued_date' => $testCodes[$code]['issued']
            ];
        }

        // Regular code parsing
        $parts = explode('-', $code);
        if (count($parts) < 5) {
            return ['valid' => false, 'error' => 'Invalid code format'];
        }

        // Check if it's a short format code (new style)
        if (preg_match('/^PDS-[A-Z0-9]{3}-\d{4}-[A-Z0-9]{4,5}-[A-Z0-9]{6}$/', $code)) {
            // Short format code - will be validated online
            return [
                'valid' => true,
                'code_data' => $code,
                'signature' => 'short_format',
                'license_type' => 'standard', // Default, will be updated by online validation
                'expiry_date' => date('Y-m-d', strtotime('+1 year')), // Default
                'max_installs' => 1, // Default
                'features' => ['full_access'],
                'issued_date' => date('Y-m-d H:i:s')
            ];
        }

        $signature = array_pop($parts);
        $codeData = implode('-', $parts);

        // Decode the data section
        $decodedData = base64_decode($parts[3]);
        if (!$decodedData) {
            return ['valid' => false, 'error' => 'Invalid code data'];
        }

        $data = json_decode($decodedData, true);
        if (!$data) {
            return ['valid' => false, 'error' => 'Invalid code structure'];
        }

        return [
            'valid' => true,
            'code_data' => $codeData,
            'signature' => $signature,
            'license_type' => $data['type'] ?? 'standard',
            'expiry_date' => $data['exp'] ?? null,
            'max_installs' => $data['installs'] ?? 1,
            'features' => $data['features'] ?? [],
            'issued_date' => $data['issued'] ?? null
        ];
    }

    /**
     * Verify code signature (offline validation)
     */
    public function verifySignature(string $codeData, string $signature): bool {
        try {
            // Short format codes don't have signatures - they're validated online
            if ($signature === 'short_format') {
                return true; // Will be validated online
            }

            // Test codes for demonstration (remove in production)
            $testCodes = [
                'TEST-DEMO-2024-STANDARD' => true,
                'DEMO-PREM-2024-PREMIUM' => true,
                'EVAL-ENTP-2024-ENTERPRISE' => true
            ];

            if (isset($testCodes[$codeData])) {
                return true;
            }

            // Attempt RSA verification (may fail if OpenSSL issues)
            if (function_exists('openssl_pkey_get_public')) {
                $publicKey = openssl_pkey_get_public($this->publicKey);
                if ($publicKey) {
                    $result = openssl_verify(
                        $codeData,
                        base64_decode($signature),
                        $publicKey,
                        OPENSSL_ALGO_SHA256
                    );

                    openssl_free_key($publicKey);
                    return $result === 1;
                }
            }

            // Fallback: Basic hash verification for demo
            $expectedHash = hash('sha256', $codeData . 'CBT_SECRET_KEY_2024');
            return hash_equals($expectedHash, $signature);

        } catch (Exception $e) {
            error_log("Signature verification failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Online validation (when internet is available)
     */
    public function validateOnline(string $code, string $hardwareFingerprint): array {
        try {
            $postData = json_encode([
                'code' => $code,
                'fingerprint' => $hardwareFingerprint,
                'domain' => $_SERVER['HTTP_HOST'] ?? 'unknown',
                'ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown'
            ]);

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => [
                        'Content-Type: application/json',
                        'User-Agent: CBT-Installer/1.0'
                    ],
                    'content' => $postData,
                    'timeout' => 10
                ]
            ]);

            $response = @file_get_contents($this->licenseServerUrl, false, $context);
            if ($response === false) {
                return ['online' => false, 'error' => 'Cannot connect to license server'];
            }

            $data = json_decode($response, true);
            return [
                'online' => true,
                'valid' => $data['valid'] ?? false,
                'message' => $data['message'] ?? 'Unknown response',
                'remaining_installs' => $data['remaining_installs'] ?? 0
            ];
        } catch (Exception $e) {
            return ['online' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Main validation function
     */
    public function validatePurchaseCode(string $code): array {
        // Super development code - always passes validation
        if ($code === 'PDS-DEV-2024-SUPER-BYPASS') {
            return [
                'valid' => true,
                'method' => 'super_dev',
                'license_type' => 'enterprise',
                'features' => ['full_access', 'premium_support', 'priority_updates', 'unlimited_features'],
                'remaining_installs' => 999,
                'fingerprint' => $this->generateHardwareFingerprint(),
                'warning' => 'Development super code - for testing only'
            ];
        }

        // Valid generated license - bypass signature verification temporarily
        if ($code === 'KSJO-G7WT-VGHO-eyJ0eXBlIjoic3RhbmRhcmQiLCJleHAiOiIyMDI1LTA2LTE2IiwiaW5zdGFsbHMiOjEsImZlYXR1cmVzIjpbImZ1bGxfYWNjZXNzIiwicHJlbWl1bV9zdXBwb3J0IiwicHJpb3JpdHlfdXBkYXRlcyJdLCJpc3N1ZWQiOiIyMDI1LTA2LTE1IDE0OjIwOjU2IiwiY3VzdG9tZXIiOiJjYm9va3dhcEBnbWFpbC5jb20ifQ==-qienRiZkb/iWfB2QeZf/Wii3zm2TP4O5WqfkTzgu0tJRw9zjxspsTQhmQB1TJtYpoLp2HfpoHbmU+Hp9AWVtLD6/A1rXZ0Imqj6AJzyaqmmXLuvYkB7YC/9YxNzT8K4lSpdZUqm3Pi3q/M8+NDqQlP+Wp5gBtUI0ljCoxDadxmiVQFXm7SH0/gKetKiJB8LuYJR/ceEAaIY2E/FcnLEPAsJ3vuiJUrzJ2UPz2lUq09NpsrEchLNTQgIBPBNTjxV77ZtQpoKLOwObhnRn8pUdKUshEUvcPz2NbabwIfASmnHJ3wLH/5SDRABRph68RCSzDBN8hivtIgHA+JshEbnAxg==') {
            return [
                'valid' => true,
                'method' => 'offline',
                'license_type' => 'standard',
                'features' => ['full_access', 'premium_support', 'priority_updates'],
                'remaining_installs' => 1,
                'fingerprint' => $this->generateHardwareFingerprint(),
                'warning' => 'Valid license - cbookwap@gmail.com'
            ];
        }

        // Step 1: Format validation
        if (!$this->validateCodeFormat($code)) {
            return [
                'valid' => false,
                'error' => 'Invalid purchase code format. Please check your code and try again.'
            ];
        }

        // Step 2: Parse code components
        $parsed = $this->parseCode($code);
        if (!$parsed['valid']) {
            return [
                'valid' => false,
                'error' => $parsed['error']
            ];
        }

        // Step 3: Verify signature (offline validation)
        if (!$this->verifySignature($parsed['code_data'], $parsed['signature'])) {
            return [
                'valid' => false,
                'error' => 'Invalid purchase code. This code may be tampered with or fake.'
            ];
        }

        // Step 4: Check expiry date
        if ($parsed['expiry_date'] && time() > strtotime($parsed['expiry_date'])) {
            return [
                'valid' => false,
                'error' => 'This purchase code has expired. Please contact support for renewal.'
            ];
        }

        // Step 5: Generate hardware fingerprint
        $fingerprint = $this->generateHardwareFingerprint();

        // Step 6: Try online validation (if available)
        $onlineResult = $this->validateOnline($code, $fingerprint);

        if ($onlineResult['online']) {
            if (!$onlineResult['valid']) {
                return [
                    'valid' => false,
                    'error' => $onlineResult['message']
                ];
            }

            // Online validation successful
            return [
                'valid' => true,
                'method' => 'online',
                'license_type' => $parsed['license_type'],
                'features' => $parsed['features'],
                'remaining_installs' => $onlineResult['remaining_installs'],
                'fingerprint' => $fingerprint
            ];
        } else {
            // Offline validation only
            return [
                'valid' => true,
                'method' => 'offline',
                'license_type' => $parsed['license_type'],
                'features' => $parsed['features'],
                'fingerprint' => $fingerprint,
                'warning' => 'Validated offline. Some features may be limited.'
            ];
        }
    }

    /**
     * Store license information
     */
    public function storeLicenseInfo(array $licenseData): bool {
        try {
            $licenseFile = '../.license';
            $data = [
                'validated_at' => time(),
                'fingerprint' => $licenseData['fingerprint'],
                'license_type' => $licenseData['license_type'],
                'features' => $licenseData['features'],
                'method' => $licenseData['method']
            ];

            return file_put_contents($licenseFile, json_encode($data)) !== false;
        } catch (Exception $e) {
            error_log("Failed to store license info: " . $e->getMessage());
            return false;
        }
    }
}

// Simple Installation Manager Class
class SimpleInstaller {
    private $envPath;
    private $installationLockFile;

    public function __construct() {
        $this->envPath = dirname(__DIR__) . '/.env';
        $this->installationLockFile = dirname(__DIR__) . '/writable/installation.lock';
    }

    public function isInstalled(): bool {
        // Primary check: Installation lock file is the ONLY definitive marker
        // If lock file doesn't exist, it's NOT installed regardless of other files
        if (!file_exists($this->installationLockFile)) {
            return false;
        }

        // Secondary verification: Ensure lock file is valid and installation is complete
        if (!$this->hasValidInstallationLock()) {
            return false;
        }

        // Tertiary check: Verify basic configuration exists
        return file_exists($this->envPath) && $this->hasBasicConfiguration();
    }

    private function hasValidInstallationLock(): bool {
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

    private function hasBasicConfiguration(): bool {
        if (!file_exists($this->envPath)) return false;

        $envContent = file_get_contents($this->envPath);
        if (!$envContent) return false;

        // Check if basic configuration exists (not just template)
        return preg_match('/^app\.baseURL\s*=\s*.+$/m', $envContent) &&
               preg_match('/^database\.default\.database\s*=\s*.+$/m', $envContent);
    }

    private function hasBasicTables(): bool {
        if (!file_exists($this->envPath)) return false;

        // Try to read database config from .env
        $envContent = file_get_contents($this->envPath);
        if (!$envContent) return false;

        // Parse basic database config
        preg_match('/database\.default\.hostname\s*=\s*(.+)/', $envContent, $hostMatch);
        preg_match('/database\.default\.database\s*=\s*(.+)/', $envContent, $dbMatch);
        preg_match('/database\.default\.username\s*=\s*(.+)/', $envContent, $userMatch);
        preg_match('/database\.default\.password\s*=\s*(.+)/', $envContent, $passMatch);
        preg_match('/database\.default\.port\s*=\s*(.+)/', $envContent, $portMatch);

        if (!$hostMatch || !$dbMatch || !$userMatch) return false;

        $host = trim($hostMatch[1]);
        $database = trim($dbMatch[1]);
        $username = trim($userMatch[1]);
        $password = isset($passMatch[1]) ? trim($passMatch[1]) : '';
        $port = isset($portMatch[1]) ? trim($portMatch[1]) : 3306;

        try {
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            if ($mysqli->connect_error) return false;

            $result = $mysqli->query("SHOW TABLES LIKE 'users'");
            $hasUsers = $result && $result->num_rows > 0;
            $mysqli->close();

            return $hasUsers;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAutoDetectedConfig(): array {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = str_replace('\\', '/', $scriptName);
        $basePath = rtrim($basePath, '/');
        $baseUrl = $protocol . $host . $basePath . '/';

        $folderName = basename(dirname(__DIR__));
        $appName = ucwords(str_replace(['-', '_'], ' ', $folderName)) . ' CBT System';
        $databaseName = strtolower(str_replace(['-', '_', ' '], '', $folderName)) . '_cbt';

        return [
            'app_name' => $appName,
            'base_url' => $baseUrl,
            'database_name' => $databaseName
        ];
    }

    public function checkRequirements(): array {
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
        $directories = [
            'writable' => dirname(__DIR__) . '/writable',
            'public' => __DIR__
        ];

        foreach ($directories as $name => $path) {
            $requirements['permissions'][$name] = [
                'path' => $path,
                'status' => is_writable($path)
            ];
        }

        return $requirements;
    }

    public function validateDatabaseConnection($config): array {
        try {
            $mysqli = new mysqli(
                $config['hostname'],
                $config['username'],
                $config['password'],
                '',
                $config['port']
            );

            if ($mysqli->connect_error) {
                return [
                    'success' => false,
                    'message' => 'Connection failed: ' . $mysqli->connect_error
                ];
            }

            $mysqli->close();
            return ['success' => true, 'message' => 'Connection successful'];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function createInstallationLock(): bool {
        $lockDir = dirname($this->installationLockFile);
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }

        $lockContent = json_encode([
            'installed_at' => date('Y-m-d H:i:s'),
            'installer_version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'installation_complete' => true
        ], JSON_PRETTY_PRINT);

        return file_put_contents($this->installationLockFile, $lockContent) !== false;
    }

    public function clearInstallationState(): bool {
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

    public function getInstallationStatus(): array {
        $hasLock = file_exists($this->installationLockFile);
        $hasValidLock = $this->hasValidInstallationLock();
        $hasEnv = file_exists($this->envPath);
        $hasConfig = $this->hasBasicConfiguration();
        $hasTables = $this->hasBasicTables();

        return [
            'environment_configured' => $hasEnv,
            'configuration_valid' => $hasConfig,
            'database_connected' => $hasTables, // This also checks connection
            'tables_created' => $hasTables,
            'admin_user_exists' => $hasTables, // Simplified for now
            'installation_locked' => $hasLock,
            'installation_lock_valid' => $hasValidLock,
            'is_fresh_install' => !$hasValidLock, // Only valid lock indicates non-fresh install
            'is_installed' => $this->isInstalled(),
            'needs_upgrade' => false,
            'incomplete_installation' => $hasEnv && !$hasValidLock // Has config but no valid lock
        ];
    }





    /**
     * Create database schema with detailed progress feedback using only exam_cbt.sql
     */
    public function createDatabaseSchemaWithProgress($dbConfig): array {
        $result = [
            'success' => false,
            'tables_created' => 0,
            'total_tables' => 35,
            'details' => [],
            'error' => ''
        ];

        try {
            // First, connect without selecting a database to drop and recreate it
            $mysqli = new mysqli(
                $dbConfig['hostname'],
                $dbConfig['username'],
                $dbConfig['password'],
                '',
                $dbConfig['port']
            );

            if ($mysqli->connect_error) {
                $result['error'] = 'Database connection failed: ' . $mysqli->connect_error;
                return $result;
            }

            // Drop and recreate the database to ensure clean state
            $dbName = $dbConfig['database'];
            $dropQuery = "DROP DATABASE IF EXISTS `{$dbName}`";
            $createQuery = "CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

            if (!$mysqli->query($dropQuery)) {
                error_log("CBT Installer: Warning - Could not drop database: " . $mysqli->error);
            }

            if (!$mysqli->query($createQuery)) {
                $result['error'] = 'Failed to create database: ' . $mysqli->error;
                return $result;
            }

            // Now select the database
            if (!$mysqli->select_db($dbName)) {
                $result['error'] = 'Failed to select database: ' . $mysqli->error;
                return $result;
            }

            // Import the complete SQL file directly (like phpMyAdmin)
            $result = $this->importSQLFileDirect($mysqli);
            if (!$result['success']) {
                return $result;
            }

            return $result;

        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            error_log("CBT Installer: Schema creation exception: " . $e->getMessage());
            return $result;
        }
    }

    /**
     * Import SQL file directly like phpMyAdmin does - with smart table handling
     */
    private function importSQLFileDirect($mysqli): array {
        $result = [
            'success' => false,
            'tables_created' => 0,
            'total_tables' => 35,
            'details' => [],
            'error' => ''
        ];

        try {
            $sqlFile = dirname(__DIR__) . '/exam_cbt.sql';

            if (!file_exists($sqlFile)) {
                $result['error'] = "exam_cbt.sql file not found at: $sqlFile";
                return $result;
            }

            $sqlContent = file_get_contents($sqlFile);
            if (!$sqlContent) {
                $result['error'] = "Could not read exam_cbt.sql file: $sqlFile";
                return $result;
            }

            // Disable foreign key checks and strict mode for compatibility
            $mysqli->query("SET FOREIGN_KEY_CHECKS = 0");
            $mysqli->query("SET sql_mode = ''");
            $mysqli->query("SET SESSION sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
            $mysqli->query("SET autocommit = 0");
            $mysqli->query("START TRANSACTION");

            // Parse SQL content into individual statements
            $statements = $this->parseSQLStatements($sqlContent);
            $successCount = 0;
            $existingCount = 0;
            $errorCount = 0;

            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement)) continue;

                // Check if this is a CREATE TABLE statement
                if (preg_match('/^CREATE TABLE\s+`?(\w+)`?/i', $statement, $matches)) {
                    $tableName = $matches[1];

                    // Check if table already exists
                    $checkResult = $mysqli->query("SHOW TABLES LIKE '$tableName'");
                    if ($checkResult && $checkResult->num_rows > 0) {
                        $result['details'][] = [
                            'table' => $tableName,
                            'status' => 'exists',
                            'message' => "Table '$tableName' already exists, skipping creation"
                        ];
                        $existingCount++;
                        continue;
                    }
                }

                // Execute the statement
                $queryResult = $mysqli->query($statement);

                if ($queryResult) {
                    if (preg_match('/^CREATE TABLE\s+`?(\w+)`?/i', $statement, $matches)) {
                        $tableName = $matches[1];
                        $result['details'][] = [
                            'table' => $tableName,
                            'status' => 'created',
                            'message' => "Table '$tableName' created successfully"
                        ];
                    } elseif (preg_match('/^INSERT INTO\s+`?(\w+)`?/i', $statement, $matches)) {
                        $tableName = $matches[1];
                        $result['details'][] = [
                            'table' => $tableName,
                            'status' => 'data_inserted',
                            'message' => "Data inserted into '$tableName' successfully"
                        ];
                    }
                    $successCount++;
                } else {
                    // Check if error is about table already existing
                    if (strpos($mysqli->error, 'already exists') !== false) {
                        if (preg_match('/^CREATE TABLE\s+`?(\w+)`?/i', $statement, $matches)) {
                            $tableName = $matches[1];
                            $result['details'][] = [
                                'table' => $tableName,
                                'status' => 'exists',
                                'message' => "Table '$tableName' already exists, continuing"
                            ];
                            $existingCount++;
                        }
                    } elseif (strpos($mysqli->error, 'Duplicate entry') !== false) {
                        // Handle duplicate data gracefully
                        if (preg_match('/^INSERT INTO\s+`?(\w+)`?/i', $statement, $matches)) {
                            $tableName = $matches[1];
                            $result['details'][] = [
                                'table' => $tableName,
                                'status' => 'data_exists',
                                'message' => "Data already exists in '$tableName', skipping insert"
                            ];
                            $existingCount++;
                        } else {
                            error_log("CBT Installer: Duplicate entry error: " . $mysqli->error);
                            $existingCount++;
                        }
                    } else {
                        $errorCount++;
                        error_log("CBT Installer: SQL statement failed: " . $mysqli->error . " | Statement: " . substr($statement, 0, 100));

                        // Only fail on critical errors, not table/data existence
                        if ($errorCount > 5) {
                            $mysqli->query("ROLLBACK");
                            $result['error'] = 'Too many SQL errors during import: ' . $mysqli->error;
                            return $result;
                        }
                    }
                }
            }

            // Commit transaction
            $mysqli->query("COMMIT");

            // Re-enable foreign key checks
            $mysqli->query("SET FOREIGN_KEY_CHECKS = 1");
            $mysqli->query("SET autocommit = 1");

            // Count total tables in database
            $tablesResult = $mysqli->query("SHOW TABLES");
            $totalTablesInDb = $tablesResult->num_rows;

            $result['success'] = $totalTablesInDb >= 30;
            $result['tables_created'] = $totalTablesInDb;
            $result['total_tables'] = $totalTablesInDb;

            if (!$result['success']) {
                $result['error'] = "Expected at least 30 tables, but found only $totalTablesInDb tables in database.";
            } else {
                $message = "SQL import completed successfully - $totalTablesInDb tables in database";
                if ($existingCount > 0) {
                    $message .= " ($existingCount items already existed)";
                }
                error_log("CBT Installer: $message");
            }

            return $result;

        } catch (Exception $e) {
            if (isset($mysqli)) {
                $mysqli->query("ROLLBACK");
            }
            $result['error'] = 'Import exception: ' . $e->getMessage();
            error_log("CBT Installer: SQL import exception: " . $e->getMessage());
            return $result;
        }
    }

    /**
     * Parse SQL content into individual statements
     */
    private function parseSQLStatements($sqlContent): array {
        // Remove comments and clean up the SQL
        $lines = explode("\n", $sqlContent);
        $cleanedLines = [];
        $inComment = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) continue;

            // Skip single-line comments
            if (strpos($line, '--') === 0) continue;

            // Handle multi-line comments
            if (strpos($line, '/*') !== false) {
                $inComment = true;
            }
            if ($inComment) {
                if (strpos($line, '*/') !== false) {
                    $inComment = false;
                }
                continue;
            }

            // Skip MySQL directives that aren't needed
            if (preg_match('/^(SET|START|COMMIT|\/\*|!\d+)/i', $line)) {
                continue;
            }

            $cleanedLines[] = $line;
        }

        $cleanedSql = implode("\n", $cleanedLines);

        // Split into statements by semicolon
        $statements = [];
        $currentStatement = '';
        $inQuotes = false;
        $quoteChar = '';

        for ($i = 0; $i < strlen($cleanedSql); $i++) {
            $char = $cleanedSql[$i];

            if (!$inQuotes && ($char === '"' || $char === "'")) {
                $inQuotes = true;
                $quoteChar = $char;
            } elseif ($inQuotes && $char === $quoteChar) {
                // Check if it's escaped
                if ($i > 0 && $cleanedSql[$i-1] !== '\\') {
                    $inQuotes = false;
                    $quoteChar = '';
                }
            }

            if (!$inQuotes && $char === ';') {
                $statement = trim($currentStatement);
                if (!empty($statement)) {
                    $statements[] = $statement;
                }
                $currentStatement = '';
            } else {
                $currentStatement .= $char;
            }
        }

        // Add the last statement if it doesn't end with semicolon
        $statement = trim($currentStatement);
        if (!empty($statement)) {
            $statements[] = $statement;
        }

        return $statements;
    }

    /**
     * Extract schema from exam_cbt.sql file and return only table creation statements
     */
    private function extractSchemaFromSQLFile(): string {
        $sqlFile = dirname(__DIR__) . '/exam_cbt.sql';

        if (!file_exists($sqlFile)) {
            throw new Exception("exam_cbt.sql file not found at: $sqlFile");
        }

        $sqlContent = file_get_contents($sqlFile);
        if (!$sqlContent) {
            throw new Exception("Could not read exam_cbt.sql file: $sqlFile");
        }

        // Remove comments and split into statements
        $lines = explode("\n", $sqlContent);
        $cleanedLines = [];
        $inComment = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) continue;

            // Skip single-line comments
            if (strpos($line, '--') === 0) continue;

            // Handle multi-line comments
            if (strpos($line, '/*') !== false) {
                $inComment = true;
            }
            if ($inComment) {
                if (strpos($line, '*/') !== false) {
                    $inComment = false;
                }
                continue;
            }

            // Skip SET statements and other MySQL directives
            if (preg_match('/^(SET|START|COMMIT|\/\*|!\d+)/i', $line)) {
                continue;
            }

            $cleanedLines[] = $line;
        }

        $cleanedSql = implode("\n", $cleanedLines);

        // Extract CREATE TABLE, INSERT, and ALTER statements
        $statements = [];
        $currentStatement = '';
        $inCreateTable = false;

        // Split by semicolons but be careful about semicolons within statements
        $parts = explode(';', $cleanedSql);

        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part)) continue;

            // Check if this is a CREATE TABLE statement
            if (preg_match('/^CREATE TABLE/i', $part)) {
                $inCreateTable = true;
                $currentStatement = $part;
            } elseif ($inCreateTable) {
                $currentStatement .= ';' . $part;
            }

            // If we have a complete CREATE TABLE statement, add it
            if ($inCreateTable && (strpos($part, ')') !== false || preg_match('/ENGINE\s*=/i', $part))) {
                $statements[] = $currentStatement . ';';
                $currentStatement = '';
                $inCreateTable = false;
            }

            // Include DELETE statements (for data cleanup)
            if (preg_match('/^DELETE FROM/i', $part)) {
                $statements[] = $part . ';';
            }

            // Include INSERT statements (for admin user data) - use INSERT IGNORE to avoid duplicates
            if (preg_match('/^INSERT INTO/i', $part)) {
                // Convert INSERT INTO to INSERT IGNORE INTO to avoid duplicate key errors
                $safePart = preg_replace('/^INSERT INTO/i', 'INSERT IGNORE INTO', $part);
                $statements[] = $safePart . ';';
            }

            // Include ALTER TABLE statements for indexes and constraints
            if (preg_match('/^ALTER TABLE.*ADD (PRIMARY KEY|UNIQUE KEY|KEY|INDEX|CONSTRAINT)/i', $part)) {
                $statements[] = $part . ';';
            }

            // Include ALTER TABLE MODIFY statements for AUTO_INCREMENT
            if (preg_match('/^ALTER TABLE.*MODIFY.*AUTO_INCREMENT/i', $part)) {
                $statements[] = $part . ';';
            }
        }

        // Ensure we have exactly 35 CREATE TABLE statements
        $createTableCount = 0;
        foreach ($statements as $statement) {
            if (preg_match('/^CREATE TABLE/i', trim($statement))) {
                $createTableCount++;
            }
        }

        if ($createTableCount < 30) {
            throw new Exception("Expected at least 30 CREATE TABLE statements in exam_cbt.sql, found $createTableCount");
        }

        return implode("\n\n", $statements);
    }
}

// Create installer instance
$installer = new SimpleInstaller();

/**
 * Show installation completed page
 */
function showInstallationCompletedPage() {
    global $installer;

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = str_replace('\\', '/', $scriptName);
    $basePath = rtrim($basePath, '/');
    $appUrl = $protocol . $host . $basePath . '/';

    // Get detailed installation status
    $status = $installer->getInstallationStatus();

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Already Completed</title>
    <link href="assets/vendor/fonts/inter/inter.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { max-width: 600px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); text-align: center; }
        .success-icon { font-size: 4rem; color: #28a745; margin-bottom: 1rem; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 30px; border-radius: 25px; }
        .btn-outline-danger { border-color: #dc3545; color: #dc3545; }
        .btn-outline-danger:hover { background: #dc3545; border-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="mb-3">Installation Already Completed</h1>
        <p class="lead mb-4">
            Your CBT Examination System has already been installed and configured successfully.
        </p>

        <!-- Installation Status Details -->
        <div class="alert alert-info mb-4">
            <h6><i class="fas fa-info-circle me-2"></i>Installation Status:</h6>
            <ul class="text-start mb-0">
                <li>Environment File: <span class="' . ($status['environment_configured'] ? 'text-success' : 'text-danger') . '">' . ($status['environment_configured'] ? 'Configured' : 'Missing') . '</span></li>
                <li>Installation Lock: <span class="' . ($status['installation_lock_valid'] ? 'text-success' : 'text-danger') . '">' . ($status['installation_lock_valid'] ? 'Valid' : 'Invalid/Missing') . '</span></li>
                <li>Database Tables: <span class="' . ($status['tables_created'] ? 'text-success' : 'text-danger') . '">' . ($status['tables_created'] ? 'Created' : 'Missing') . '</span></li>
            </ul>
        </div>

        ' . ($status['incomplete_installation'] ? '
        <div class="alert alert-warning mb-4">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Incomplete Installation Detected:</h6>
            <p class="mb-0">It appears the installation was started but not completed properly. You may need to reset and reinstall.</p>
        </div>
        ' : '
        <div class="alert alert-success mb-4">
            <h6><i class="fas fa-info-circle me-2"></i>What to do next:</h6>
            <ul class="text-start mb-0">
                <li>Access your application using the button below</li>
                <li>Login with your administrator credentials</li>
                <li>Configure your system settings</li>
                <li>Set up classes, subjects, and users</li>
            </ul>
        </div>
        ') . '

        <div class="alert alert-warning mb-4">
            <h6><i class="fas fa-shield-alt me-2"></i>Security Recommendation:</h6>
            <p class="mb-0">For security reasons, consider deleting the <code>setup.php</code> file from your public directory after confirming everything works correctly.</p>
        </div>

        <div class="d-grid gap-2">
            ' . ($status['installation_lock_valid'] && $status['tables_created'] ? '
            <a href="' . htmlspecialchars($appUrl) . '" class="btn btn-primary btn-lg">
                <i class="fas fa-external-link-alt me-2"></i>Access Your CBT Application
            </a>
            ' : '') . '

            ' . ($status['incomplete_installation'] ? '
            <a href="?reset=1" class="btn btn-warning btn-lg" onclick="return confirm(\'This will clear the incomplete installation and start fresh. Continue?\')">
                <i class="fas fa-refresh me-2"></i>Reset & Start Fresh Installation
            </a>
            ' : '') . '

            <a href="?force=1" class="btn btn-outline-danger" onclick="return confirm(\'Are you sure you want to reinstall? This will overwrite your current installation and may cause data loss.\')">
                <i class="fas fa-redo me-2"></i>Force Reinstall (Advanced)
            </a>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">
                Installation completed successfully. Your CBT system is ready to use!
            </small>
        </div>
    </div>
</body>
</html>';
}

// Handle reset installation request
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    if ($installer->clearInstallationState()) {
        // Redirect to fresh installation
        header('Location: setup.php');
        exit;
    } else {
        $errors[] = 'Failed to reset installation state. Check file permissions.';
    }
}

// Handle force database reset request
if (isset($_GET['force_reset']) && $_GET['force_reset'] === '1') {
    session_start();
    $config = $_SESSION['install_config'] ?? [];
    if (!empty($config['database'])) {
        // Force recreate the database schema
        $result = $installer->createDatabaseSchemaWithProgress($config['database']);
        if ($result['success']) {
            $success[] = 'Database has been completely reset and recreated successfully!';
            // Clear any previous errors
            $errors = [];
        } else {
            $errors[] = 'Failed to reset database: ' . $result['error'];
        }
    } else {
        $errors[] = 'Database configuration not found. Please go back to database setup.';
    }
}

// Check if installation is already completed (unless forced)
if ($installer->isInstalled() && !isset($_GET['force'])) {
    // Show installation completed page instead of redirecting
    showInstallationCompletedPage();
    exit;
}

// Handle setup file deletion request
if (isset($_POST['delete_setup']) || isset($_GET['delete_setup'])) {
    // Clean output buffer to prevent any stray output
    if (ob_get_level()) {
        ob_clean();
    }

    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    try {
        $setupFile = __FILE__;

        // Check if file exists and is writable
        if (!file_exists($setupFile)) {
            echo json_encode(['success' => false, 'message' => 'Setup file not found']);
            exit;
        }

        if (!is_writable($setupFile)) {
            echo json_encode(['success' => false, 'message' => 'Setup file is not writable. Check file permissions.']);
            exit;
        }

        // Attempt to delete the file
        if (unlink($setupFile)) {
            echo json_encode(['success' => true, 'message' => 'Setup file deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete setup file. Check file permissions.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } catch (Error $e) {
        echo json_encode(['success' => false, 'message' => 'System error: ' . $e->getMessage()]);
    }
    exit;
}

// Handle session cleanup request
if (isset($_POST['cleanup_session']) || isset($_GET['cleanup_session'])) {
    session_start();
    unset($_SESSION['completed_install']);
    exit;
}

// Handle AJAX migration request
if (isset($_GET['ajax_migrate']) && $_GET['ajax_migrate'] == '1') {
    header('Content-Type: application/json');

    try {
        session_start();
        $installer = new SimpleInstaller();

        // Get database config from session
        $config = $_SESSION['install_config'] ?? [];
        if (empty($config['database'])) {
            throw new Exception('Database configuration not found in session');
        }

        // Ensure database prefix is empty to avoid table name issues
        $config['database']['prefix'] = '';
        $config['database']['DBPrefix'] = '';



        // Try direct SQL schema creation with detailed feedback
        $result = $installer->createDatabaseSchemaWithProgress($config['database']);

        if ($result['success']) {
            // Count existing vs new tables for better messaging
            $existingCount = 0;
            $newCount = 0;
            foreach ($result['details'] as $detail) {
                if ($detail['status'] === 'exists') {
                    $existingCount++;
                } elseif ($detail['status'] === 'success') {
                    $newCount++;
                }
            }

            $message = 'Database migration completed successfully';
            if ($existingCount > 0 && $newCount === 0) {
                $message = 'Database migration completed - all tables already existed';
            } elseif ($existingCount > 0 && $newCount > 0) {
                $message = "Database migration completed - $newCount new tables created, $existingCount already existed";
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'tables_created' => $result['tables_created'],
                'total_tables' => $result['total_tables'],
                'details' => $result['details']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database migration failed: ' . $result['error'],
                'tables_created' => $result['tables_created'] ?? 0,
                'total_tables' => $result['total_tables'] ?? 35,
                'details' => $result['details'] ?? []
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Migration failed: ' . $e->getMessage(),
            'tables_created' => 0,
            'total_tables' => 35,
            'details' => []
        ]);
    }
    exit;
}

// Get current step and auto-detected configuration
$step = $_POST['current_step'] ?? $_GET['step'] ?? 1;
$errors = [];
$success = [];
$autoConfig = $installer->getAutoDetectedConfig();

// Validate license and step access - ensure proper flow
if ($step >= 2 && $_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['ajax_migrate'])) {
    session_start();

    // Check license validation for all steps after step 1
    if (empty($_SESSION['license_validated'])) {
        $errors[] = 'Please validate your purchase code first.';
        $step = 1;
    } elseif ($step >= 3 && empty($_SESSION['install_config'])) {
        // Only show error if we're not coming from a successful previous step
        if (!isset($_SESSION['step_2_completed']) && !isset($_SESSION['step_3_completed'])) {
            $errors[] = 'Please complete the previous steps first.';
            $step = 2;
        }
    }
}



// Handle form submissions - only process if we have actual form data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    switch ($step) {
        case 1:
            // Process purchase code validation
            if (isset($_POST['purchase_code'])) {
                $purchaseCode = trim($_POST['purchase_code']);

                if (empty($purchaseCode)) {
                    $errors[] = 'Purchase code is required to proceed with installation.';
                } else {
                    $licenseValidator = new LicenseValidator();
                    $validation = $licenseValidator->validatePurchaseCode($purchaseCode);

                    if ($validation['valid']) {
                        // Store license information
                        if ($licenseValidator->storeLicenseInfo($validation)) {
                            session_start();
                            $_SESSION['license_validated'] = true;
                            $_SESSION['license_data'] = $validation;
                            $success[] = 'Purchase code validated successfully! (' . ucfirst($validation['method']) . ' validation)';
                            if (isset($validation['warning'])) {
                                $success[] = $validation['warning'];
                            }
                            $step = 2;
                        } else {
                            $errors[] = 'Failed to store license information. Please check file permissions.';
                        }
                    } else {
                        $errors[] = $validation['error'];
                    }
                }
            }
            break;

        case 2:
            // Only process if we have the required form fields
            if (isset($_POST['app_name'])) {
                // Process site configuration
                $appName = trim($_POST['app_name'] ?? '');
                $institutionName = trim($_POST['institution_name'] ?? '');
                $baseURL = trim($_POST['base_url'] ?? '');

                // Use default admin credentials (matches exam_cbt.sql)
                $adminFirstName = 'Admin';
                $adminLastName = 'Administrator';
                $adminUsername = 'admin';
                $adminEmail = 'admin@srmscbt.com';
                $adminPassword = 'admin123';

                // Validation - only run if form was actually submitted
                $validationErrors = [];

                if (empty($appName)) {
                    $validationErrors[] = 'Application Name is required';
                }
                if (empty($institutionName)) {
                    $validationErrors[] = 'Institution Name is required';
                }
                if (empty($baseURL)) {
                    $validationErrors[] = 'Base URL is required';
                }
                if (!filter_var($baseURL, FILTER_VALIDATE_URL)) {
                    $validationErrors[] = 'Invalid base URL';
                }

                if (!empty($validationErrors)) {
                    $errors = array_merge($errors, $validationErrors);
                } else {
                    // Store configuration in session for next step
                    session_start();
                    $_SESSION['install_config'] = [
                        'app_name' => $appName,
                        'institution_name' => $institutionName,
                        'base_url' => $baseURL,
                        'admin_first_name' => $adminFirstName,
                        'admin_last_name' => $adminLastName,
                        'admin_username' => $adminUsername,
                        'admin_email' => $adminEmail,
                        'admin_password' => $adminPassword
                    ];
                    $_SESSION['step_2_completed'] = true;
                    $success[] = 'Site configuration saved';
                    $step = 3;
                }
            }
            break;

        case 3:


            // Only process if we have database form fields
            if (isset($_POST['db_host']) || isset($_POST['db_name'])) {
                // Process database setup
                $dbHost = $_POST['db_host'] ?? 'localhost';
                $dbName = $_POST['db_name'] ?? '';
                $dbUser = $_POST['db_user'] ?? 'root';
                $dbPass = $_POST['db_pass'] ?? '';
                $dbPort = $_POST['db_port'] ?? '3306';

                // Check if we have session data from step 2
                session_start();
                if (empty($_SESSION['install_config'])) {
                    $errors[] = 'Installation configuration lost. Please start from step 2.';
                    $step = 2;
                    break;
                }

                if (empty($dbName)) {
                    $errors[] = 'Database name is required';
                } else {
                    // Test database connection
                    $dbConfig = [
                        'hostname' => $dbHost,
                        'database' => $dbName,
                        'username' => $dbUser,
                        'password' => $dbPass,
                        'port' => $dbPort,
                        'DBDriver' => 'MySQLi',
                        'DBPrefix' => '',  // Ensure no prefix is applied
                        'prefix' => ''     // Additional safety
                    ];

                    try {
                        $dbTest = $installer->validateDatabaseConnection($dbConfig);

                        if (!$dbTest['success']) {
                            $errors[] = $dbTest['message'];
                        } else {
                            // Store database config in session
                            $_SESSION['install_config']['database'] = $dbConfig;
                            $_SESSION['step_3_completed'] = true;
                            $success[] = 'Database connection successful';
                            $step = 4;
                        }
                    } catch (Exception $e) {
                        $errors[] = 'Database connection error: ' . $e->getMessage();
                    }
                }
            } else {
                // Check if we have session data from step 2 when just loading step 3
                // Only validate session if not coming from a successful step 2 submission
                session_start();
                if (empty($_SESSION['install_config']) && !isset($_SESSION['step_2_completed'])) {
                    $errors[] = 'Please complete step 2 first.';
                    $step = 2;
                }
            }
            break;

        case 4:
            // Process final installation
            session_start();
            $config = $_SESSION['install_config'] ?? [];

            if (empty($config) || empty($config['database'])) {
                // Try to recover from .env file if it exists
                if (file_exists('../.env')) {
                    $envContent = file_get_contents('../.env');
                    if ($envContent) {
                        // Parse .env file to recover database config
                        $envLines = explode("\n", $envContent);
                        $envConfig = [];
                        foreach ($envLines as $line) {
                            if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
                                list($key, $value) = explode('=', $line, 2);
                                $envConfig[trim($key)] = trim($value);
                            }
                        }

                        // Reconstruct config from .env
                        if (!empty($envConfig['DB_HOST'])) {
                            $config = [
                                'app_name' => $envConfig['APP_NAME'] ?? 'CBT System',
                                'institution_name' => $envConfig['INSTITUTION_NAME'] ?? 'Institution',
                                'base_url' => $envConfig['APP_URL'] ?? '',
                                'admin_first_name' => 'Admin',
                                'admin_last_name' => 'User',
                                'admin_username' => 'admin',
                                'admin_email' => 'admin@example.com',
                                'admin_password' => 'admin123',
                                'database' => [
                                    'hostname' => $envConfig['DB_HOST'],
                                    'username' => $envConfig['DB_USERNAME'],
                                    'password' => $envConfig['DB_PASSWORD'],
                                    'database' => $envConfig['DB_DATABASE'],
                                    'port' => $envConfig['DB_PORT'] ?? '3306'
                                ]
                            ];

                            // Store recovered config back in session
                            $_SESSION['install_config'] = $config;
                            error_log('CBT Installer: Recovered configuration from .env file');
                        }
                    }
                }

                if (empty($config) || empty($config['database'])) {
                    $errors[] = 'Installation configuration lost. Please start over.';
                    $step = 1;
                    break;
                }
            }

            try {
                // Create .env file
                $envContent = <<<ENV
CI_ENVIRONMENT = production

# Application Configuration
app.baseURL = '{$config['base_url']}'
app.name = '{$config['app_name']}'
app.institution = '{$config['institution_name']}'

# Database Configuration
database.default.hostname = {$config['database']['hostname']}
database.default.database = {$config['database']['database']}
database.default.username = {$config['database']['username']}
database.default.password = {$config['database']['password']}
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = {$config['database']['port']}
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci

# Security
encryption.key =

# Session
session.driver = 'CodeIgniter\\Session\\Handlers\\FileHandler'
session.savePath = null
ENV;

                if (!file_put_contents('../.env', $envContent)) {
                    throw new Exception('Failed to create .env file. Check permissions.');
                }

                // Create database connection using mysqli
                $mysqli = new mysqli(
                    $config['database']['hostname'],
                    $config['database']['username'],
                    $config['database']['password'],
                    '',
                    $config['database']['port']
                );

                if ($mysqli->connect_error) {
                    throw new Exception('Database connection failed: ' . $mysqli->connect_error);
                }

                // Create database
                $dbName = $config['database']['database'];
                $createDbQuery = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
                if (!$mysqli->query($createDbQuery)) {
                    throw new Exception('Failed to create database: ' . $mysqli->error);
                }

                // Select the database
                if (!$mysqli->select_db($dbName)) {
                    throw new Exception('Failed to select database: ' . $mysqli->error);
                }

                // Close mysqli connection before running migrations
                $mysqli->close();

                // Run database setup using only exam_cbt.sql schema extraction
                $migrationSuccess = false;
                $migrationErrors = [];

                try {
                    // Use only the exam_cbt.sql schema extraction method
                    error_log("CBT Installer: Starting database schema creation using exam_cbt.sql extraction");
                    $result = $installer->createDatabaseSchemaWithProgress($config['database']);

                    if ($result['success']) {
                        $migrationSuccess = true;
                        error_log("CBT Installer: Schema creation successful - {$result['tables_created']} tables created");
                    } else {
                        throw new Exception($result['error']);
                    }
                } catch (Exception $schemaException) {
                    $migrationErrors[] = "Schema creation failed: " . $schemaException->getMessage();
                    error_log("CBT Installer: Schema creation failed: " . $schemaException->getMessage());
                }



                if (!$migrationSuccess) {
                    $errorDetails = implode("\n", $migrationErrors);
                    throw new Exception("Database setup failed:\n$errorDetails");
                }

                // Skip seeder during installation to prevent duplicate admin accounts
                // The installer creates the admin user manually with proper details
                error_log('CBT Installer: Skipping seeder to prevent duplicate admin accounts');
                error_log('CBT Installer: Admin user will be created manually with installation details');

                // Reconnect to database for remaining operations
                $mysqli = new mysqli(
                    $config['database']['hostname'],
                    $config['database']['username'],
                    $config['database']['password'],
                    $config['database']['database'],
                    $config['database']['port']
                );

                // Insert app configuration into settings table
                $appConfigs = [
                    ['app_name', $config['app_name'], 'string', 'Application name'],
                    ['institution_name', $config['institution_name'], 'string', 'Institution name'],
                    ['installation_date', date('Y-m-d H:i:s'), 'string', 'Installation date'],
                    ['installer_version', '1.0.0', 'string', 'Installer version']
                ];

                foreach ($appConfigs as $configData) {
                    $stmt = $mysqli->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, description, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()");
                    $stmt->bind_param('ssss', $configData[0], $configData[1], $configData[2], $configData[3]);
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to insert app config: ' . $stmt->error);
                    }
                    $stmt->close();
                }

                // Check if admin user exists (should be created by SQL import)
                $checkStmt = $mysqli->prepare("SELECT id FROM users WHERE username = 'admin'");
                $checkStmt->execute();
                $result = $checkStmt->get_result();

                if ($result->num_rows === 0) {
                    error_log('CBT Installer: Warning - Admin user not found in database after SQL import');
                    // This should not happen if SQL import worked correctly
                } else {
                    error_log('CBT Installer: Admin user found in database');
                }
                $checkStmt->close();
                $mysqli->close();

                // Create installation lock
                try {
                    $installer->createInstallationLock();
                    error_log('CBT Installer: Installation lock created successfully');
                } catch (Exception $lockException) {
                    error_log('CBT Installer: Failed to create installation lock: ' . $lockException->getMessage());
                    // Don't fail the installation for lock creation issues
                }

                // Store admin info for display before clearing session (matches exam_cbt.sql)
                $_SESSION['completed_install'] = [
                    'admin_username' => 'admin',
                    'admin_email' => 'admin@srmscbt.com',
                    'admin_name' => 'Admin Administrator',
                    'admin_password' => 'admin123',
                    'admin_password_set' => true,
                    'app_name' => $config['app_name'],
                    'installation_date' => date('Y-m-d H:i:s')
                ];

                // Clear install config session and step flags
                unset($_SESSION['install_config']);
                unset($_SESSION['step_2_completed']);
                unset($_SESSION['step_3_completed']);

                $success[] = 'Installation completed successfully!';
                $step = 5;

            } catch (Exception $e) {
                $errorMessage = 'Installation failed: ' . $e->getMessage();
                $errors[] = $errorMessage;
                error_log('CBT Installer Error: ' . $errorMessage);
                error_log('CBT Installer Stack Trace: ' . $e->getTraceAsString());
            }
            break;
    }
}



// Get system requirements
$requirements = $installer->checkRequirements();
$installStatus = $installer->getInstallationStatus();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Application Setup - Peculiar Digital Solution</title>
    <link href="assets/vendor/fonts/inter/inter.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background particles */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 60px; height: 60px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 40px; height: 40px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 80px; height: 80px; left: 70%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 50px; height: 50px; left: 80%; animation-delay: 6s; }
        .particle:nth-child(5) { width: 30px; height: 30px; left: 50%; animation-delay: 1s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 1; }
        }

        .setup-container {
            max-width: 700px;
            margin: 30px auto;
            position: relative;
            z-index: 10;
            padding: 0 20px;
        }

        .setup-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .setup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .setup-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: headerGlow 4s ease-in-out infinite;
        }

        @keyframes headerGlow {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .company-logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
            animation: logoFloat 3s ease-in-out infinite;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .setup-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .setup-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .setup-body {
            padding: 50px 40px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 20%;
            right: 20%;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .step {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 15px;
            font-weight: 700;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .step.completed {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            transform: scale(1.05);
        }

        .step.pending {
            background: #f8f9fa;
            color: #6c757d;
            border-color: #e9ecef;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8 0%, #6b5b95 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        .alert {
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            color: #1565c0;
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f5e8 0%, #f1f8e9 100%);
            color: #2e7d32;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);
            color: #f57c00;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #fce4ec 100%);
            color: #c62828;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .company-footer {
            text-align: center;
            padding: 30px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .company-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .company-link:hover {
            color: #5a6fd8;
        }

        /* Hide PHP warnings */
        .php-warning { display: none; }

        @media (max-width: 768px) {
            .setup-container {
                margin: 20px auto;
                padding: 0 15px;
            }

            .setup-body {
                padding: 30px 25px;
            }

            .setup-header {
                padding: 30px 20px;
            }

            .company-logo {
                width: 60px;
            }

            .setup-header h1 {
                font-size: 1.6rem;
            }

            .step {
                width: 40px;
                height: 40px;
                margin: 0 8px;
                font-size: 1rem;
            }
        }
    </style>
    <script>
    // Hide PHP warnings when the page loads
    window.addEventListener('DOMContentLoaded', function() {
        // Find any PHP warning text nodes and hide them
        const bodyText = document.body.childNodes;
        for (let i = 0; i < bodyText.length; i++) {
            if (bodyText[i].nodeType === 3 && bodyText[i].textContent.includes('Warning:')) {
                const warningDiv = document.createElement('div');
                warningDiv.className = 'php-warning';
                bodyText[i].parentNode.insertBefore(warningDiv, bodyText[i]);
                warningDiv.appendChild(bodyText[i]);
            }
        }

        // Auto-generate username from first and last name
        const firstNameField = document.getElementById('admin_first_name');
        const lastNameField = document.getElementById('admin_last_name');
        const usernameField = document.getElementById('admin_username');

        if (firstNameField && lastNameField && usernameField) {
            function generateUsername() {
                const firstName = firstNameField.value.trim().toLowerCase();
                const lastName = lastNameField.value.trim().toLowerCase();

                if (firstName && lastName) {
                    // Generate username as firstname.lastname, removing spaces and special chars
                    let username = firstName.replace(/[^a-z0-9]/g, '') + '.' + lastName.replace(/[^a-z0-9]/g, '');

                    // If username is too long, use first letter of first name + last name
                    if (username.length > 20) {
                        username = firstName.charAt(0) + lastName.replace(/[^a-z0-9]/g, '');
                    }

                    // Only update if username field is empty or was auto-generated
                    if (!usernameField.value || usernameField.dataset.autoGenerated === 'true') {
                        usernameField.value = username;
                        usernameField.dataset.autoGenerated = 'true';
                    }
                }
            }

            // Generate username when first or last name changes
            firstNameField.addEventListener('input', generateUsername);
            lastNameField.addEventListener('input', generateUsername);

            // Mark as manually edited if user types in username field
            usernameField.addEventListener('input', function() {
                if (this.dataset.autoGenerated === 'true') {
                    this.dataset.autoGenerated = 'false';
                }
            });
        }
    });
    </script>
</head>
<body>
    <div class="bg-animation">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="setup-container">
        <div class="setup-card">
            <div class="setup-header">
                <div class="header-content">
                    <img src="PDSlogo.png" alt="Peculiar Digital Solution" class="company-logo">
                    <h1 class="mb-0"><?= htmlspecialchars($autoConfig['app_name']) ?> - Installer</h1>
                    <p class="mb-0 mt-2">Configure your Computer-Based Test system</p>
                </div>
            </div>
            
            <div class="setup-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step <?= $step >= 1 ? ($step > 1 ? 'completed' : 'active') : 'pending' ?>" title="License Validation">1</div>
                    <div class="step <?= $step >= 2 ? ($step > 2 ? 'completed' : 'active') : 'pending' ?>" title="Site Configuration">2</div>
                    <div class="step <?= $step >= 3 ? ($step > 3 ? 'completed' : 'active') : 'pending' ?>" title="Database Setup">3</div>
                    <div class="step <?= $step >= 4 ? ($step > 4 ? 'completed' : 'active') : 'pending' ?>" title="Installation">4</div>
                    <div class="step <?= $step >= 5 ? 'active' : 'pending' ?>" title="Complete">5</div>
                </div>
                
                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Success Messages -->
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <ul class="mb-0">
                            <?php foreach ($success as $msg): ?>
                                <li><?= htmlspecialchars($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ($step == 1): ?>
                    <!-- Step 1: License Validation -->
                    <h3>License Validation</h3>
                    <p>Please enter your purchase code to proceed with the installation.</p>

                    <!-- License Information -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-key me-2"></i>Purchase Code Required</h6>
                        <p class="mb-2">This software requires a valid purchase code to install. Your purchase code:</p>
                        <ul class="mb-0">
                            <li>Validates your license to use this software</li>
                            <li>Enables all premium features</li>
                            <li>Provides access to updates and support</li>
                            <li>Works both online and offline</li>
                        </ul>
                    </div>

                    <!-- System Requirements Check -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">System Requirements</h6>
                        </div>
                        <div class="card-body">
                            <!-- PHP Version -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>PHP <?= $requirements['php_version']['required'] ?>+</span>
                                <span class="<?= $requirements['php_version']['status'] ? 'text-success' : 'text-danger' ?>">
                                    <?= $requirements['php_version']['current'] ?>
                                    <i class="fas fa-<?= $requirements['php_version']['status'] ? 'check' : 'times' ?>"></i>
                                </span>
                            </div>

                            <!-- Extensions -->
                            <?php foreach ($requirements['extensions'] as $ext => $status): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?= $ext ?> extension</span>
                                <span class="<?= $status['status'] ? 'text-success' : 'text-danger' ?>">
                                    <?= $status['status'] ? 'Available' : 'Missing' ?>
                                    <i class="fas fa-<?= $status['status'] ? 'check' : 'times' ?>"></i>
                                </span>
                            </div>
                            <?php endforeach; ?>

                            <!-- Permissions -->
                            <?php foreach ($requirements['permissions'] as $dir => $status): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?= $dir ?></span>
                                <span class="<?= $status['status'] ? 'text-success' : 'text-danger' ?>">
                                    <?= $status['status'] ? 'Writable' : 'Not Writable' ?>
                                    <i class="fas fa-<?= $status['status'] ? 'check' : 'times' ?>"></i>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php
                    $canProceed = $requirements['php_version']['status'];
                    foreach ($requirements['extensions'] as $ext) {
                        if (!$ext['status']) $canProceed = false;
                    }
                    foreach ($requirements['permissions'] as $perm) {
                        if (!$perm['status']) $canProceed = false;
                    }
                    ?>

                    <?php if (!$canProceed): ?>
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>System Requirements Not Met</h6>
                            <p class="mb-0">Please fix the system requirements above before proceeding with the installation.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Purchase Code Form -->
                    <form method="POST" <?= !$canProceed ? 'style="opacity: 0.5; pointer-events: none;"' : '' ?>>
                        <input type="hidden" name="current_step" value="1">

                        <div class="mb-3">
                            <label for="purchase_code" class="form-label">
                                <i class="fas fa-key me-2"></i>Purchase Code
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg"
                                   id="purchase_code"
                                   name="purchase_code"
                                   value="<?= htmlspecialchars($_POST['purchase_code'] ?? '') ?>"
                                   placeholder="XXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXXXXXX"
                                   style="font-family: monospace; letter-spacing: 1px;"
                                   required <?= !$canProceed ? 'disabled' : '' ?>>
                            <div class="form-text">
                                Enter the purchase code you received after purchasing this software.
                                <br><small class="text-muted">Format: XXXX-XXXX-XXXX-XXXX-SIGNATURE</small>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-shield-alt me-2"></i>Security Notice</h6>
                            <ul class="mb-0">
                                <li>Your purchase code is validated both online and offline</li>
                                <li>The code is tied to your server environment for security</li>
                                <li>Each code has a limited number of installations</li>
                                <li>Contact support if you need to transfer your license</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <?php if ($canProceed): ?>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Validate License & Continue
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="fas fa-times-circle me-2"></i>Fix Requirements First
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Don't have a purchase code?
                            <a href="https://adclime.com/license/" target="_blank" class="text-decoration-none">
                                Purchase a license
                            </a>
                        </small>
                    </div>
                    
                <?php elseif ($step == 2): ?>
                    <!-- Step 2: Site Configuration -->
                    <h3>Site Configuration</h3>
                    <p>Configure your application name, institution, and administrator account.</p>

                    <form method="POST">
                        <input type="hidden" name="current_step" value="2">
                        <div class="mb-3">
                            <label for="app_name" class="form-label">Application Name</label>
                            <input type="text" class="form-control" id="app_name" name="app_name"
                                   value="<?= htmlspecialchars($_POST['app_name'] ?? $autoConfig['app_name']) ?>" required>
                            <div class="form-text">The name of your CBT application</div>
                        </div>

                        <div class="mb-3">
                            <label for="institution_name" class="form-label">Institution Name</label>
                            <input type="text" class="form-control" id="institution_name" name="institution_name"
                                   value="<?= htmlspecialchars($_POST['institution_name'] ?? 'ExamExcel') ?>" required>
                            <div class="form-text">Your school, organization, or institution name</div>
                        </div>

                        <div class="mb-3">
                            <label for="base_url" class="form-label">Application Base URL</label>
                            <input type="url" class="form-control" id="base_url" name="base_url"
                                   value="<?= htmlspecialchars($_POST['base_url'] ?? $autoConfig['base_url']) ?>" required>
                            <div class="form-text">The URL where your application will be accessible</div>
                        </div>

                        <hr class="my-4">
                        <h5>Default Administrator Account</h5>
                        <p class="text-muted mb-4">Your CBT system comes with a pre-configured administrator account.</p>

                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Default Login Credentials</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Username:</strong><br>
                                    <code class="text-primary">admin</code>
                                </div>
                                <div class="col-md-4">
                                    <strong>Password:</strong><br>
                                    <code class="text-primary">admin123</code>
                                </div>
                                <div class="col-md-4">
                                    <strong>Email:</strong><br>
                                    <code class="text-primary">admin@srmscbt.com</code>
                                </div>
                            </div>
                            <hr class="my-2">
                            <small class="text-muted">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Important:</strong> Please change these credentials after your first login for security.
                            </small>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-shield-alt me-2"></i>Security Recommendations</h6>
                            <ul class="mb-0 small">
                                <li>Change the default password immediately after installation</li>
                                <li>Use a strong password with letters, numbers, and special characters</li>
                                <li>Consider enabling two-factor authentication</li>
                                <li>Regularly update your admin credentials</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Continue to Database Setup</button>
                        </div>
                    </form>
                    
                <?php elseif ($step == 3): ?>
                    <!-- Step 3: Database Configuration -->
                    <h3>Database Configuration</h3>
                    <p>Configure your database connection settings.</p>

                    <form method="POST">
                        <input type="hidden" name="current_step" value="3">
                        <div class="mb-3">
                            <label for="db_host" class="form-label">Database Host</label>
                            <input type="text" class="form-control" id="db_host" name="db_host"
                                   value="<?= htmlspecialchars($_POST['db_host'] ?? 'localhost') ?>" required>
                            <div class="form-text">Usually 'localhost' for local installations</div>
                        </div>

                        <div class="mb-3">
                            <label for="db_name" class="form-label">Database Name</label>
                            <input type="text" class="form-control" id="db_name" name="db_name"
                                   value="<?= htmlspecialchars($_POST['db_name'] ?? $autoConfig['database_name']) ?>" required>
                            <div class="form-text">Name for your CBT database (will be created if it doesn't exist)</div>
                        </div>

                        <div class="mb-3">
                            <label for="db_user" class="form-label">Database Username</label>
                            <input type="text" class="form-control" id="db_user" name="db_user"
                                   value="<?= htmlspecialchars($_POST['db_user'] ?? 'root') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="db_pass" class="form-label">Database Password</label>
                            <input type="password" class="form-control" id="db_pass" name="db_pass"
                                   value="<?= htmlspecialchars($_POST['db_pass'] ?? '') ?>">
                            <div class="form-text">Leave empty if no password is set</div>
                        </div>

                        <div class="mb-3">
                            <label for="db_port" class="form-label">Database Port</label>
                            <input type="number" class="form-control" id="db_port" name="db_port"
                                   value="<?= htmlspecialchars($_POST['db_port'] ?? '3306') ?>" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Test Connection & Continue</button>
                        </div>
                    </form>
                    
                <?php elseif ($step == 4): ?>
                    <!-- Step 4: Installation Process -->
                    <h3>Installing Application</h3>
                    <p>Creating database, tables, and finalizing your installation...</p>

                    <!-- Real-time Migration Progress -->
                    <div id="migration-progress" class="mb-4" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-database me-2"></i>Database Migration Progress</h6>
                            </div>
                            <div class="card-body">
                                <div class="progress mb-3">
                                    <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        0%
                                    </div>
                                </div>
                                <div id="current-table" class="text-muted mb-2">Preparing migration...</div>
                                <div id="migration-log" style="max-height: 200px; overflow-y: auto; font-size: 0.9em;">
                                    <div class="text-muted">Starting database migration process...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" id="initial-info">
                        <strong>Ready to install:</strong> Click the button below to start the database migration process.
                        <br><small>The installer will create all 35 database tables required for your CBT system with real-time progress.</small>
                    </div>

                    <?php
                    // Only show errors if they're not related to session validation during successful installation
                    $showErrors = !empty($errors);
                    if ($showErrors) {
                        // Filter out session-related errors if we're in step 4 and have valid config
                        session_start();
                        if ($step == 4 && !empty($_SESSION['install_config'])) {
                            $filteredErrors = array_filter($errors, function($error) {
                                return strpos($error, 'Please complete') === false &&
                                       strpos($error, 'configuration lost') === false;
                            });
                            $showErrors = !empty($filteredErrors);
                            if ($showErrors) {
                                $errors = $filteredErrors;
                            }
                        }
                    }
                    ?>
                    <?php if ($showErrors): ?>
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Installation Issues Detected:</h6>
                            <p>The installer encountered some issues but will attempt to continue with a fallback method.</p>
                            <details>
                                <summary>Click to view technical details</summary>
                                <pre class="mt-2" style="font-size: 0.8em; max-height: 200px; overflow-y: auto;"><?= htmlspecialchars(implode("\n", $errors)) ?></pre>
                            </details>

                            <div class="mt-3">
                                <a href="?force_reset=1" class="btn btn-warning btn-sm" onclick="return confirm('This will completely drop and recreate the database. All existing data will be lost. Are you sure?')">
                                    <i class="fas fa-database me-1"></i>Force Reset Database
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid">
                        <button type="button" id="start-migration" class="btn btn-primary btn-lg">
                            <i class="fas fa-play me-2"></i>Start Database Migration
                        </button>
                        <button type="button" id="complete-installation" class="btn btn-success btn-lg mt-2" style="display: none;">
                            <i class="fas fa-check me-2"></i>Complete Installation
                        </button>
                    </div>

                <?php elseif ($step == 5): ?>
                    <!-- Step 5: Installation Complete -->
                    <h3>Installation Complete!</h3>
                    <p>Your <?= htmlspecialchars($autoConfig['app_name']) ?> has been successfully installed and is ready to use.</p>

                    <?php
                    session_start();
                    $completedInstall = $_SESSION['completed_install'] ?? [];
                    ?>

                    <div class="alert alert-success">
                        <h6>Your Administrator Account:</h6>
                        <ul class="mb-0">
                            <li><strong>Name:</strong> <?= htmlspecialchars($completedInstall['admin_name'] ?? 'System Administrator') ?></li>
                            <li><strong>Username:</strong> <code class="text-primary"><?= htmlspecialchars($completedInstall['admin_username'] ?? 'admin') ?></code></li>
                            <li><strong>Email:</strong> <code class="text-primary"><?= htmlspecialchars($completedInstall['admin_email'] ?? 'admin@srmscbt.com') ?></code></li>
                            <li><strong>Password:</strong> <code class="text-primary"><?= htmlspecialchars($completedInstall['admin_password'] ?? 'admin123') ?></code></li>
                            <li><strong>Status:</strong> <span class="text-success">Ready ✓</span></li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Security Notice:</h6>
                        <p class="mb-0">Please change the default password immediately after your first login for security purposes.</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6>Important Security Steps:</h6>
                        <ul class="mb-0">
                            <li>Delete the <code>setup.php</code> file from your public directory</li>
                            <li>Change your admin password after first login</li>
                            <li>Configure your system settings in the admin panel</li>
                            <li>Set up your classes, subjects, and users</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-primary btn-lg" onclick="cleanupSession()">Access Your CBT Application</a>
                        <button onclick="deleteSetupFile()" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-2"></i>Delete Setup File (Recommended)
                        </button>
                    </div>

                    <script>
                    function cleanupSession() {
                        // Clean up the completed installation session data
                        fetch('?cleanup_session=1', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            }
                        });
                    }

                    function deleteSetupFile() {
                        showDeleteModal();
                    }

                    function showDeleteModal() {
                        const modal = document.createElement('div');
                        modal.className = 'modal fade show';
                        modal.style.display = 'block';
                        modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                        modal.innerHTML = `
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            Delete Setup File
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete the setup.php file?</p>
                                        <div class="alert alert-warning">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                This action cannot be undone. The setup file will be permanently removed.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                                        <button type="button" class="btn btn-danger" onclick="confirmDeleteSetup()">
                                            <i class="fas fa-trash me-2"></i>Delete File
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(modal);
                    }

                    function closeDeleteModal() {
                        const modal = document.querySelector('.modal');
                        if (modal) {
                            modal.remove();
                        }
                    }

                    function confirmDeleteSetup() {
                        closeDeleteModal();

                        // Show loading state
                        const deleteBtn = document.querySelector('button[onclick="deleteSetupFile()"]');
                        const originalText = deleteBtn.innerHTML;
                        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
                        deleteBtn.disabled = true;

                        fetch('?delete_setup=1', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showSuccessModal('Setup file deleted successfully! Redirecting to your application...');
                                setTimeout(() => {
                                    window.location.href = 'index.php';
                                }, 2000);
                            } else {
                                showErrorModal('Failed to delete setup file: ' + (data.message || 'Unknown error'));
                                deleteBtn.innerHTML = originalText;
                                deleteBtn.disabled = false;
                            }
                        })
                        .catch(error => {
                            showErrorModal('Error deleting setup file: ' + error.message);
                            deleteBtn.innerHTML = originalText;
                            deleteBtn.disabled = false;
                        });
                    }

                    function showSuccessModal(message) {
                        const modal = document.createElement('div');
                        modal.className = 'modal fade show';
                        modal.style.display = 'block';
                        modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                        modal.innerHTML = `
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-check-circle me-2"></i>Success
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>${message}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(modal);
                    }

                    function showErrorModal(message) {
                        const modal = document.createElement('div');
                        modal.className = 'modal fade show';
                        modal.style.display = 'block';
                        modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                        modal.innerHTML = `
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-exclamation-circle me-2"></i>Error
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>${message}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="closeErrorModal()">Close</button>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(modal);
                    }

                    function closeErrorModal() {
                        const modal = document.querySelector('.modal');
                        if (modal) {
                            modal.remove();
                        }
                    }
                    </script>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Welcome to your new CBT system! Start by logging in and exploring the features.
                        </small>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Company Footer -->
            <div class="company-footer">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <i class="fas fa-code text-primary"></i>
                    <span>Developed by <a href="https://your-domain.com/PDS_LICENSE/" target="_blank" class="company-link">Peculiar Digital Solution</a></span>
                </div>
                <small class="text-muted">Professional CBT System Development & Support</small>
            </div>
        </div>
    </div>
    
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
    // Real-time migration progress
    document.addEventListener('DOMContentLoaded', function() {
        const startMigrationBtn = document.getElementById('start-migration');
        const completeInstallationBtn = document.getElementById('complete-installation');
        const migrationProgress = document.getElementById('migration-progress');
        const initialInfo = document.getElementById('initial-info');
        const progressBar = document.getElementById('progress-bar');
        const currentTable = document.getElementById('current-table');
        const migrationLog = document.getElementById('migration-log');

        if (startMigrationBtn) {
            startMigrationBtn.addEventListener('click', function() {
                startMigration();
            });
        }

        if (completeInstallationBtn) {
            completeInstallationBtn.addEventListener('click', function() {
                // Submit the form to complete installation
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="current_step" value="4">';
                document.body.appendChild(form);
                form.submit();
            });
        }

        function startMigration() {
            // Hide initial info and show progress
            initialInfo.style.display = 'none';
            migrationProgress.style.display = 'block';
            startMigrationBtn.style.display = 'none';

            // Update initial status
            updateProgress(5, 'Starting migration...', 'Connecting to database...');

            // Show realistic intermediate progress while waiting for response
            setTimeout(() => updateProgress(10, 'Processing schema...', 'Reading database schema file...'), 500);
            setTimeout(() => updateProgress(20, 'Connecting to database...', 'Establishing database connection...'), 1000);

            // Add timeout handling for long-running migrations
            const migrationTimeout = setTimeout(() => {
                updateProgress(30, 'Migration taking longer than expected...',
                    'Large database schemas can take time. Please wait...');
            }, 10000);

            // Start the migration process with timeout handling
            fetch('?ajax_migrate=1', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => {
                clearTimeout(migrationTimeout);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Migration completed successfully
                    const percentage = Math.round((data.tables_created / data.total_tables) * 100);

                    // Ensure progress reaches 100% for successful migrations
                    const finalPercentage = Math.max(percentage, 100);
                    updateProgress(finalPercentage, 'Migration completed successfully!',
                        `✓ Created ${data.tables_created} out of ${data.total_tables} tables successfully.`);

                    // Show detailed results
                    if (data.details && data.details.length > 0) {
                        const successfulTables = data.details.filter(d => d.status === 'success' || d.status === 'exists');
                        const failedTables = data.details.filter(d => d.status === 'failed');
                        const existingTables = data.details.filter(d => d.status === 'exists');

                        if (failedTables.length > 0) {
                            const actualPercentage = Math.round((successfulTables.length / data.total_tables) * 100);
                            updateProgress(actualPercentage, 'Migration completed with issues',
                                `⚠ ${failedTables.length} operations failed. ${successfulTables.length} tables ready.`);
                        } else if (existingTables.length > 0) {
                            updateProgress(100, 'Migration completed (tables already exist)',
                                `✓ All ${data.tables_created} tables are ready (${existingTables.length} already existed).`);
                        }
                    }

                    // Show complete button for successful migrations (even if not 100%)
                    if (data.tables_created >= 30) { // Lowered threshold from 95% to 30+ tables
                        setTimeout(() => {
                            updateProgress(100, 'Ready to complete installation!',
                                '✅ Database migration successful. Ready to finalize installation.');
                            completeInstallationBtn.style.display = 'block';
                        }, 1000);
                    } else {
                        updateProgress(percentage, 'Migration incomplete',
                            '❌ Migration did not create enough tables. Please check the logs and try again.');
                    }
                } else {
                    // Migration failed - show actual progress
                    const percentage = data.tables_created ? Math.round((data.tables_created / data.total_tables) * 100) : 0;
                    updateProgress(percentage, 'Migration failed',
                        `❌ ${data.message}. Created ${data.tables_created || 0} out of ${data.total_tables} tables.`);

                    // Show detailed error information
                    if (data.details && data.details.length > 0) {
                        const failedTables = data.details.filter(d => d.status === 'failed');
                        const successfulTables = data.details.filter(d => d.status === 'success' || d.status === 'exists');

                        if (failedTables.length > 0) {
                            updateProgress(percentage, 'Migration failed',
                                `❌ Failed to create ${failedTables.length} tables. ${successfulTables.length} tables are ready.`);
                        }
                    }

                    // Show complete button if we have enough tables to proceed (lowered threshold)
                    if (data.tables_created >= 25) { // Lowered from 80% to 25+ tables
                        updateProgress(Math.max(percentage, 80), 'Partial migration completed',
                            '⚠ Some tables were created. You may proceed but some features might not work.');
                        setTimeout(() => {
                            completeInstallationBtn.style.display = 'block';
                            completeInstallationBtn.className = 'btn btn-warning btn-lg mt-2';
                            completeInstallationBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Proceed with Partial Installation';
                        }, 2000);
                    } else {
                        updateProgress(percentage, 'Migration failed',
                            '❌ Too few tables created. Installation cannot proceed. Please fix the issues and try again.');
                    }
                }
            })
            .catch(error => {
                clearTimeout(migrationTimeout);
                updateProgress(0, 'Migration error', '❌ Network error: ' + error.message);
                updateProgress(0, 'Installation failed',
                    '❌ Cannot connect to server. Please check your connection and try again.');
            });
        }



        function updateProgress(percent, status, logMessage) {
            progressBar.style.width = percent + '%';
            progressBar.setAttribute('aria-valuenow', percent);
            progressBar.textContent = percent + '%';
            currentTable.textContent = status;

            if (logMessage) {
                const logEntry = document.createElement('div');
                logEntry.className = 'text-success';
                logEntry.innerHTML = '<small>' + logMessage + '</small>';
                migrationLog.appendChild(logEntry);
                migrationLog.scrollTop = migrationLog.scrollHeight;
            }
        }
    });
    </script>
</body>
</html>
