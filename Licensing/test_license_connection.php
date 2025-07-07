<?php
/**
 * Test License Connection
 * This script tests the connection between your web app and license server
 */

// Test the license validation endpoint
function testLicenseConnection() {
    $licenseServerUrl = 'https://adclime.com/license/validate.php';
    
    // Generate a test fingerprint
    $fingerprint = hash('sha256', php_uname('n') . '|' . php_uname('s') . '|' . $_SERVER['SERVER_NAME']);
    
    // Test with super dev code
    $testCode = 'PDS-DEV-2024-SUPER-BYPASS';
    
    $postData = json_encode([
        'code' => $testCode,
        'fingerprint' => $fingerprint,
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $postData,
            'timeout' => 10
        ]
    ]);
    
    echo "<h2>Testing License Server Connection</h2>";
    echo "<p><strong>License Server URL:</strong> $licenseServerUrl</p>";
    echo "<p><strong>Test Code:</strong> $testCode</p>";
    echo "<p><strong>Fingerprint:</strong> $fingerprint</p>";
    echo "<hr>";
    
    $response = @file_get_contents($licenseServerUrl, false, $context);
    
    if ($response === false) {
        echo "<div style='color: red;'>";
        echo "<h3>❌ Connection Failed</h3>";
        echo "<p>Cannot connect to license server. Please check:</p>";
        echo "<ul>";
        echo "<li>License server is online at: $licenseServerUrl</li>";
        echo "<li>Server allows outbound HTTP requests</li>";
        echo "<li>No firewall blocking the connection</li>";
        echo "</ul>";
        echo "</div>";
        return false;
    }
    
    $data = json_decode($response, true);
    
    if ($data === null) {
        echo "<div style='color: orange;'>";
        echo "<h3>⚠️ Invalid Response</h3>";
        echo "<p>Server responded but with invalid JSON:</p>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        echo "</div>";
        return false;
    }
    
    if (isset($data['success']) && $data['success']) {
        echo "<div style='color: green;'>";
        echo "<h3>✅ Connection Successful</h3>";
        echo "<p>License server is working correctly!</p>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        return true;
    } else {
        echo "<div style='color: red;'>";
        echo "<h3>❌ Validation Failed</h3>";
        echo "<p>Server responded but validation failed:</p>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        return false;
    }
}

// Test license generation
function testLicenseGeneration() {
    $licenseServerUrl = 'https://adclime.com/license/api.php';
    
    echo "<h2>Testing License Generation</h2>";
    echo "<p><strong>API URL:</strong> $licenseServerUrl</p>";
    echo "<hr>";
    
    // Test API connection
    $testData = json_encode([
        'action' => 'stats'
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $testData,
            'timeout' => 10
        ]
    ]);
    
    $response = @file_get_contents($licenseServerUrl, false, $context);
    
    if ($response === false) {
        echo "<div style='color: red;'>";
        echo "<h3>❌ API Connection Failed</h3>";
        echo "<p>Cannot connect to license API.</p>";
        echo "</div>";
        return false;
    }
    
    $data = json_decode($response, true);
    
    if (isset($data['success']) && $data['success']) {
        echo "<div style='color: green;'>";
        echo "<h3>✅ API Working</h3>";
        echo "<p>License generation API is accessible!</p>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        return true;
    } else {
        echo "<div style='color: orange;'>";
        echo "<h3>⚠️ API Response</h3>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        return false;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>License System Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
    <h1>PDS License System Test</h1>
    <p>This script tests the connection between your web application and the license server.</p>
    
    <?php
    $validationTest = testLicenseConnection();
    echo "<hr>";
    $apiTest = testLicenseGeneration();
    
    echo "<hr>";
    echo "<h2>Summary</h2>";
    if ($validationTest && $apiTest) {
        echo "<div style='color: green; font-weight: bold;'>✅ All tests passed! Your license system is working correctly.</div>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>❌ Some tests failed. Please check the issues above.</div>";
    }
    ?>
    
    <hr>
    <h2>Next Steps</h2>
    <ol>
        <li>If tests pass, generate a new license from your license dashboard</li>
        <li>Copy the generated license code</li>
        <li>Use it in your web app setup</li>
        <li>Delete this test file for security</li>
    </ol>
</body>
</html>
