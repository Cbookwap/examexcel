<?php
/**
 * CBT License Server API
 * 
 * This script handles online license validation and tracking.
 * Deploy this on your license server (separate from customer installations).
 * 
 * Endpoints:
 * POST /license_server.php - Validate license
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

class LicenseServer {
    private $dbFile = 'license_database.json';
    private $publicKey;
    
    public function __construct() {
        // Load public key (same as in setup.php)
        $keys = json_decode(file_get_contents('license_keys.json'), true);
        $this->publicKey = $keys['public'];
    }
    
    /**
     * Handle license validation request
     */
    public function handleValidation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->error('Method not allowed', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['code']) || !isset($input['fingerprint'])) {
            return $this->error('Invalid request data');
        }
        
        $code = $input['code'];
        $fingerprint = $input['fingerprint'];
        $domain = $input['domain'] ?? 'unknown';
        $ip = $input['ip'] ?? 'unknown';
        
        // Validate code format and signature
        $validation = $this->validateCodeStructure($code);
        if (!$validation['valid']) {
            return $this->error($validation['error']);
        }
        
        // Check license database
        $licenseCheck = $this->checkLicenseDatabase($code, $fingerprint, $domain, $ip);
        
        return $this->success($licenseCheck);
    }
    
    /**
     * Validate code structure and signature
     */
    private function validateCodeStructure($code) {
        $parts = explode('-', $code);
        if (count($parts) < 5) {
            return ['valid' => false, 'error' => 'Invalid code format'];
        }
        
        $signature = array_pop($parts);
        $codeData = implode('-', $parts);
        
        // Verify signature
        try {
            $publicKey = openssl_pkey_get_public($this->publicKey);
            if (!$publicKey) {
                return ['valid' => false, 'error' => 'Invalid public key'];
            }
            
            $result = openssl_verify(
                $codeData,
                base64_decode($signature),
                $publicKey,
                OPENSSL_ALGO_SHA256
            );
            
            openssl_free_key($publicKey);
            
            if ($result !== 1) {
                return ['valid' => false, 'error' => 'Invalid signature'];
            }
        } catch (Exception $e) {
            return ['valid' => false, 'error' => 'Signature verification failed'];
        }
        
        // Decode license data
        $decodedData = base64_decode($parts[3]);
        $data = json_decode($decodedData, true);
        
        if (!$data) {
            return ['valid' => false, 'error' => 'Invalid license data'];
        }
        
        // Check expiry
        if (isset($data['exp']) && time() > strtotime($data['exp'])) {
            return ['valid' => false, 'error' => 'License has expired'];
        }
        
        return [
            'valid' => true,
            'data' => $data
        ];
    }
    
    /**
     * Check license in database and track usage
     */
    private function checkLicenseDatabase($code, $fingerprint, $domain, $ip) {
        $db = $this->loadDatabase();
        
        // Find license record
        $licenseKey = hash('sha256', $code);
        
        if (!isset($db['licenses'][$licenseKey])) {
            // First time activation
            $db['licenses'][$licenseKey] = [
                'code' => $code,
                'activations' => [],
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ];
        }
        
        $license = &$db['licenses'][$licenseKey];
        
        // Check if this fingerprint is already activated
        $existingActivation = null;
        foreach ($license['activations'] as &$activation) {
            if ($activation['fingerprint'] === $fingerprint) {
                $existingActivation = &$activation;
                break;
            }
        }
        
        if ($existingActivation) {
            // Update existing activation
            $existingActivation['last_seen'] = date('Y-m-d H:i:s');
            $existingActivation['domain'] = $domain;
            $existingActivation['ip'] = $ip;
            $existingActivation['access_count']++;
            
            $this->saveDatabase($db);
            
            return [
                'valid' => true,
                'message' => 'License validated successfully',
                'remaining_installs' => $this->getRemainingInstalls($license),
                'activation_date' => $existingActivation['activated_at']
            ];
        } else {
            // New activation
            $validation = $this->validateCodeStructure($code);
            $maxInstalls = $validation['data']['installs'] ?? 1;
            
            if (count($license['activations']) >= $maxInstalls) {
                return [
                    'valid' => false,
                    'message' => 'Maximum number of installations reached',
                    'remaining_installs' => 0
                ];
            }
            
            // Add new activation
            $license['activations'][] = [
                'fingerprint' => $fingerprint,
                'domain' => $domain,
                'ip' => $ip,
                'activated_at' => date('Y-m-d H:i:s'),
                'last_seen' => date('Y-m-d H:i:s'),
                'access_count' => 1
            ];
            
            $this->saveDatabase($db);
            
            return [
                'valid' => true,
                'message' => 'License activated successfully',
                'remaining_installs' => $this->getRemainingInstalls($license),
                'activation_date' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    /**
     * Get remaining installations for a license
     */
    private function getRemainingInstalls($license) {
        $validation = $this->validateCodeStructure($license['code']);
        $maxInstalls = $validation['data']['installs'] ?? 1;
        return max(0, $maxInstalls - count($license['activations']));
    }
    
    /**
     * Load license database
     */
    private function loadDatabase() {
        if (!file_exists($this->dbFile)) {
            return ['licenses' => []];
        }
        
        $data = json_decode(file_get_contents($this->dbFile), true);
        return $data ?: ['licenses' => []];
    }
    
    /**
     * Save license database
     */
    private function saveDatabase($data) {
        return file_put_contents($this->dbFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    /**
     * Return success response
     */
    private function success($data) {
        echo json_encode(array_merge(['success' => true], $data));
        exit;
    }
    
    /**
     * Return error response
     */
    private function error($message, $code = 400) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'valid' => false,
            'message' => $message
        ]);
        exit;
    }
}

// Handle the request
try {
    $server = new LicenseServer();
    $server->handleValidation();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'valid' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
