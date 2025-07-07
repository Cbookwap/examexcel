<?php
/**
 * Test Online License Validation
 */

$licenseKey = 'KSJO-G7WT-VGHO-eyJ0eXBlIjoic3RhbmRhcmQiLCJleHAiOiIyMDI1LTA2LTE2IiwiaW5zdGFsbHMiOjEsImZlYXR1cmVzIjpbImZ1bGxfYWNjZXNzIiwicHJlbWl1bV9zdXBwb3J0IiwicHJpb3JpdHlfdXBkYXRlcyJdLCJpc3N1ZWQiOiIyMDI1LTA2LTE1IDE0OjIwOjU2IiwiY3VzdG9tZXIiOiJjYm9va3dhcEBnbWFpbC5jb20ifQ==-qienRiZkb/iWfB2QeZf/Wii3zm2TP4O5WqfkTzgu0tJRw9zjxspsTQhmQB1TJtYpoLp2HfpoHbmU+Hp9AWVtLD6/A1rXZ0Imqj6AJzyaqmmXLuvYkB7YC/9YxNzT8K4lSpdZUqm3Pi3q/M8+NDqQlP+Wp5gBtUI0ljCoxDadxmiVQFXm7SH0/gKetKiJB8LuYJR/ceEAaIY2E/FcnLEPAsJ3vuiJUrzJ2UPz2lUq09NpsrEchLNTQgIBPBNTjxV77ZtQpoKLOwObhnRn8pUdKUshEUvcPz2NbabwIfASmnHJ3wLH/5SDRABRph68RCSzDBN8hivtIgHA+JshEbnAxg==';

echo "Testing Online License Validation\n";
echo "=================================\n\n";

// Test direct API call to license server
$url = 'http://localhost/exam/pds_license/validate.php';
$fingerprint = 'test-fingerprint-123';

$postData = json_encode([
    'code' => $licenseKey,
    'fingerprint' => $fingerprint,
    'domain' => 'localhost',
    'ip' => '127.0.0.1'
]);

echo "Calling: $url\n";
echo "Data: " . substr($postData, 0, 100) . "...\n\n";

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'User-Agent: CBT-Test/1.0'
        ],
        'content' => $postData,
        'timeout' => 10
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Failed to connect to license server\n";
    echo "Error: " . error_get_last()['message'] . "\n";
} else {
    echo "✅ Response received:\n";
    echo $response . "\n\n";
    
    $data = json_decode($response, true);
    if ($data) {
        echo "Parsed Response:\n";
        echo "- Success: " . ($data['success'] ?? 'unknown') . "\n";
        echo "- Valid: " . ($data['valid'] ?? 'unknown') . "\n";
        echo "- Message: " . ($data['message'] ?? 'none') . "\n";
    } else {
        echo "❌ Failed to parse JSON response\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Testing Bypass Code:\n";

$bypassData = json_encode([
    'code' => 'PDS-DEV-2024-SUPER-BYPASS',
    'fingerprint' => $fingerprint,
    'domain' => 'localhost',
    'ip' => '127.0.0.1'
]);

$bypassResponse = @file_get_contents($url, false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'User-Agent: CBT-Test/1.0'
        ],
        'content' => $bypassData,
        'timeout' => 10
    ]
]));

if ($bypassResponse === false) {
    echo "❌ Failed to test bypass code\n";
} else {
    echo "✅ Bypass code response:\n";
    echo $bypassResponse . "\n";
}

echo "\nTest completed!\n";
?>
