<?php
/**
 * CBT License Code Generator
 * 
 * This script generates purchase codes for your CBT application.
 * Keep this file secure and only use it from your own server.
 * 
 * Usage: php license_generator.php
 */

class LicenseGenerator {
    private $privateKey;
    private $publicKey;
    
    public function __construct() {
        // Generate RSA key pair if not exists
        $this->initializeKeys();
    }
    
    /**
     * Initialize RSA key pair
     */
    private function initializeKeys() {
        $keyFile = 'license_keys.json';

        // Always try to load existing keys first
        if (file_exists($keyFile)) {
            $keyContent = file_get_contents($keyFile);
            if ($keyContent !== false) {
                $keys = json_decode($keyContent, true);
                if ($keys && isset($keys['private']) && isset($keys['public']) &&
                    !empty($keys['private']) && !empty($keys['public'])) {

                    // Validate the keys by testing them
                    $testKey = openssl_pkey_get_private($keys['private']);
                    if ($testKey !== false) {
                        $this->privateKey = $keys['private'];
                        $this->publicKey = $keys['public'];
                        return; // Keys are valid, use them
                    }
                }
            }
        }

        // Generate new key pair if file doesn't exist or keys are invalid
        $this->generateNewKeys($keyFile);
    }

    /**
     * Generate new RSA key pair - BULLETPROOF VERSION
     */
    private function generateNewKeys($keyFile) {
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);
        if ($res === false) {
            throw new Exception('Failed to generate RSA key pair: ' . openssl_error_string());
        }

        // Export private key
        if (!openssl_pkey_export($res, $privateKey)) {
            throw new Exception('Failed to export private key: ' . openssl_error_string());
        }

        // Get public key
        $details = openssl_pkey_get_details($res);
        if ($details === false) {
            throw new Exception('Failed to get key details: ' . openssl_error_string());
        }

        $this->privateKey = $privateKey;
        $this->publicKey = $details["key"];

        // Save keys with error checking
        $keyData = json_encode([
            'private' => $this->privateKey,
            'public' => $this->publicKey,
            'generated' => date('Y-m-d H:i:s')
        ], JSON_PRETTY_PRINT);

        if (file_put_contents($keyFile, $keyData) === false) {
            throw new Exception('Failed to save license keys to file');
        }

        echo "New RSA key pair generated and saved to {$keyFile}\n";
        echo "IMPORTANT: Update the public key in your setup.php file!\n\n";
        echo "Public Key to use in setup.php:\n";
        echo $this->publicKey . "\n\n";
    }
    
    /**
     * Generate a purchase code - SHORT FORMAT VERSION
     * Format: PDS-DEV-2024-SUPER-BYPASS
     */
    public function generateCode($options = []) {
        $defaults = [
            'license_type' => 'standard',
            'expiry_days' => 365,
            'max_installs' => 1,
            'features' => ['full_access'],
            'customer_email' => '',
            'customer_name' => ''
        ];

        $options = array_merge($defaults, $options);

        // Create license data for internal storage
        $licenseData = [
            'type' => $options['license_type'],
            'exp' => date('Y-m-d', strtotime("+{$options['expiry_days']} days")),
            'installs' => $options['max_installs'],
            'features' => $options['features'],
            'issued' => date('Y-m-d H:i:s'),
            'customer' => $options['customer_email']
        ];

        // Generate short format code: PDS-DEV-YYYY-XXXX-XXXX
        $part1 = 'PDS'; // Fixed prefix
        $part2 = strtoupper(substr($options['license_type'], 0, 3)); // License type (DEV, STD, PRE, ENT)
        if ($part2 === 'STA') $part2 = 'STD'; // Standard
        if ($part2 === 'PRE') $part2 = 'PRE'; // Premium
        if ($part2 === 'ENT') $part2 = 'ENT'; // Enterprise
        if ($part2 === 'DEV') $part2 = 'DEV'; // Development

        $part3 = date('Y'); // Current year
        $part4 = $this->generateRandomString(5, true); // 5 chars alphanumeric
        $part5 = $this->generateRandomString(6, true); // 6 chars alphanumeric

        // Create readable purchase code
        $purchaseCode = "{$part1}-{$part2}-{$part3}-{$part4}-{$part5}";

        return [
            'code' => $purchaseCode,
            'data' => $licenseData,
            'customer_email' => $options['customer_email'],
            'customer_name' => $options['customer_name'],
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Sign code with private key - BULLETPROOF VERSION
     */
    private function signCode($codeData) {
        if (empty($this->privateKey)) {
            throw new Exception('Private key is not initialized');
        }

        $privateKey = openssl_pkey_get_private($this->privateKey);
        if ($privateKey === false) {
            throw new Exception('Failed to load private key for signing: ' . openssl_error_string());
        }

        $result = openssl_sign($codeData, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$result) {
            throw new Exception('Failed to sign license code: ' . openssl_error_string());
        }

        // In PHP 8+, resources are automatically freed, so we don't call openssl_free_key
        // Only call it for older PHP versions
        if (PHP_VERSION_ID < 80000 && is_resource($privateKey)) {
            openssl_free_key($privateKey);
        }

        return base64_encode($signature);
    }
    
    /**
     * Generate random string for license codes
     */
    private function generateRandomString($length, $alphanumeric = false) {
        if ($alphanumeric) {
            // For short codes, use alphanumeric uppercase
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        } else {
            // For signatures, use full character set
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        }

        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $result;
    }
    
    /**
     * Validate a generated code
     */
    public function validateCode($code) {
        $parts = explode('-', $code);
        if (count($parts) < 5) {
            return ['valid' => false, 'error' => 'Invalid format'];
        }
        
        $signature = array_pop($parts);
        $codeData = implode('-', $parts);
        
        // Verify signature
        $publicKey = openssl_pkey_get_public($this->publicKey);
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
        
        // Decode data
        $decodedData = base64_decode($parts[3]);
        $data = json_decode($decodedData, true);
        
        return [
            'valid' => true,
            'data' => $data,
            'expires' => $data['exp'],
            'license_type' => $data['type'],
            'max_installs' => $data['installs']
        ];
    }
    
    /**
     * Save license to database/file
     */
    public function saveLicense($licenseInfo) {
        $licensesFile = 'generated_licenses.json';
        $licenses = [];
        
        if (file_exists($licensesFile)) {
            $licenses = json_decode(file_get_contents($licensesFile), true) ?: [];
        }
        
        $licenses[] = $licenseInfo;
        
        return file_put_contents($licensesFile, json_encode($licenses, JSON_PRETTY_PRINT));
    }
}

// CLI Interface
if (php_sapi_name() === 'cli') {
    $generator = new LicenseGenerator();
    
    echo "CBT License Code Generator\n";
    echo "==========================\n\n";
    
    // Get customer information
    echo "Customer Email: ";
    $customerEmail = trim(fgets(STDIN));
    
    echo "Customer Name: ";
    $customerName = trim(fgets(STDIN));
    
    echo "License Type (standard/premium/enterprise) [standard]: ";
    $licenseType = trim(fgets(STDIN)) ?: 'standard';
    
    echo "Expiry Days [365]: ";
    $expiryDays = (int)(trim(fgets(STDIN)) ?: 365);
    
    echo "Max Installations [1]: ";
    $maxInstalls = (int)(trim(fgets(STDIN)) ?: 1);
    
    // Generate the license
    $license = $generator->generateCode([
        'license_type' => $licenseType,
        'expiry_days' => $expiryDays,
        'max_installs' => $maxInstalls,
        'customer_email' => $customerEmail,
        'customer_name' => $customerName,
        'features' => $licenseType === 'premium' ? ['full_access', 'premium_support'] : ['full_access']
    ]);
    
    // Save license
    $generator->saveLicense($license);
    
    // Display results
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "LICENSE GENERATED SUCCESSFULLY\n";
    echo str_repeat("=", 60) . "\n";
    echo "Purchase Code: " . $license['code'] . "\n";
    echo "Customer: " . $license['customer_name'] . " (" . $license['customer_email'] . ")\n";
    echo "License Type: " . $license['data']['type'] . "\n";
    echo "Expires: " . $license['data']['exp'] . "\n";
    echo "Max Installations: " . $license['data']['installs'] . "\n";
    echo "Generated: " . $license['generated_at'] . "\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Validate the generated code
    echo "Validating generated code...\n";
    $validation = $generator->validateCode($license['code']);
    if ($validation['valid']) {
        echo "✓ Code validation successful!\n";
    } else {
        echo "✗ Code validation failed: " . $validation['error'] . "\n";
    }
    
    echo "\nLicense saved to generated_licenses.json\n";
}
?>
