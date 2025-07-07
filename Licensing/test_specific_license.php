<?php
/**
 * Test Specific License Code
 * This script tests your specific license code
 */

// Test the specific license code you generated
function testSpecificLicense($code) {
    $licenseServerUrl = 'https://adclime.com/license/validate.php';
    
    // Generate a test fingerprint
    $fingerprint = hash('sha256', php_uname('n') . '|' . php_uname('s') . '|' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    
    $postData = json_encode([
        'code' => $code,
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
    
    echo "<h2>Testing Your License Code</h2>";
    echo "<p><strong>License Code:</strong> $code</p>";
    echo "<p><strong>Fingerprint:</strong> $fingerprint</p>";
    echo "<hr>";
    
    $response = @file_get_contents($licenseServerUrl, false, $context);
    
    if ($response === false) {
        echo "<div style='color: red;'>";
        echo "<h3>❌ Connection Failed</h3>";
        echo "<p>Cannot connect to license server.</p>";
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
    
    if (isset($data['success']) && $data['success'] && isset($data['valid']) && $data['valid']) {
        echo "<div style='color: green;'>";
        echo "<h3>✅ License Valid!</h3>";
        echo "<p>Your license code is working correctly!</p>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        return true;
    } else {
        echo "<div style='color: red;'>";
        echo "<h3>❌ License Invalid</h3>";
        echo "<p>License validation failed:</p>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
        echo "</div>";
        return false;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Specific License Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
        hr { margin: 20px 0; }
        .form-group { margin: 20px 0; }
        input[type="text"] { width: 400px; padding: 10px; font-family: monospace; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Test Your Specific License Code</h1>
    
    <?php if (isset($_POST['license_code']) && !empty($_POST['license_code'])): ?>
        <?php
        $licenseCode = trim($_POST['license_code']);
        $result = testSpecificLicense($licenseCode);
        ?>
        
        <hr>
        <h2>Test Another Code</h2>
        <form method="POST">
            <div class="form-group">
                <label>License Code:</label><br>
                <input type="text" name="license_code" placeholder="PDS-G10-2025-P244-RU5786" value="<?= htmlspecialchars($licenseCode) ?>">
            </div>
            <button type="submit">Test License</button>
        </form>
        
    <?php else: ?>
        <p>Enter your license code to test it:</p>
        
        <form method="POST">
            <div class="form-group">
                <label>License Code:</label><br>
                <input type="text" name="license_code" placeholder="PDS-G10-2025-P244-RU5786" required>
            </div>
            <button type="submit">Test License</button>
        </form>
        
        <hr>
        <h2>Expected Format</h2>
        <p>Your license code should look like: <code>PDS-G10-2025-P244-RU5786</code></p>
        <p>This is the new short format that doesn't require complex signatures.</p>
    <?php endif; ?>
    
</body>
</html>
