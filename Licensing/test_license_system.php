<?php
/**
 * Test script for license system
 */

require_once 'license_generator.php';

echo "Testing License System\n";
echo "======================\n\n";

try {
    // Create generator instance (this will generate keys if they don't exist)
    $generator = new LicenseGenerator();
    echo "✓ License generator initialized\n";
    
    // Generate a test license
    $license = $generator->generateCode([
        'license_type' => 'standard',
        'expiry_days' => 365,
        'max_installs' => 1,
        'customer_email' => 'test@example.com',
        'customer_name' => 'Test Customer',
        'features' => ['full_access']
    ]);
    
    echo "✓ Test license generated\n";
    echo "Purchase Code: " . $license['code'] . "\n\n";
    
    // Validate the generated license
    $validation = $generator->validateCode($license['code']);
    if ($validation['valid']) {
        echo "✓ License validation successful\n";
        echo "License Type: " . $validation['license_type'] . "\n";
        echo "Expires: " . $validation['expires'] . "\n";
        echo "Max Installs: " . $validation['max_installs'] . "\n\n";
    } else {
        echo "✗ License validation failed: " . $validation['error'] . "\n";
    }
    
    // Save the license
    $generator->saveLicense($license);
    echo "✓ License saved to generated_licenses.json\n\n";
    
    // Display public key for setup.php
    if (file_exists('license_keys.json')) {
        $keys = json_decode(file_get_contents('license_keys.json'), true);
        echo "Public Key for setup.php:\n";
        echo "========================\n";
        echo $keys['public'] . "\n\n";
        
        echo "IMPORTANT: Copy the public key above and update it in setup.php\n";
        echo "Look for the \$publicKey variable in the LicenseValidator class\n\n";
    }
    
    echo "Test completed successfully!\n";
    echo "You can now use this purchase code to test the installation: " . $license['code'] . "\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
