<?php
/**
 * CBT Examination System - Root Index File
 *
 * This file serves as a fallback for servers that don't support .htaccess
 * or when mod_rewrite is not available. It redirects all requests to the
 * public directory to maintain clean URLs.
 *
 * @package CBT Examination System
 * @version 1.0.0
 */

// Security check - prevent direct access to sensitive files
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

// Block access to sensitive directories
$blockedPaths = ['/app/', '/system/', '/vendor/', '/writable/', '/tests/', '/.env', '/.git/'];
foreach ($blockedPaths as $path) {
    if (strpos($requestUri, $path) !== false) {
        http_response_code(403);
        exit('Access Denied');
    }
}

// Check if this is a request for a static file that should be served from public
$staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'map', 'pdf', 'txt', 'xml', 'json'];
$pathInfo = pathinfo($requestUri);
$extension = strtolower($pathInfo['extension'] ?? '');

if (in_array($extension, $staticExtensions)) {
    $publicFile = __DIR__ . '/public' . $requestUri;
    if (file_exists($publicFile)) {
        // Set appropriate content type
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'xml' => 'application/xml',
            'json' => 'application/json'
        ];
        
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        
        readfile($publicFile);
        exit;
    }
}

// Check installation status first
function checkInstallationStatus() {
    // Primary check: Installation lock file must exist and be valid
    $lockFile = __DIR__ . '/writable/installation.lock';
    if (!file_exists($lockFile)) {
        return false;
    }

    // Verify lock file is valid
    $lockContent = file_get_contents($lockFile);
    $lockData = json_decode($lockContent, true);
    if (!$lockData || !isset($lockData['installed_at']) || !isset($lockData['installer_version'])) {
        return false;
    }

    // Secondary check: Ensure .env file exists and is configured
    $envFile = __DIR__ . '/.env';
    if (!file_exists($envFile)) {
        return false;
    }

    $envContent = file_get_contents($envFile);
    return preg_match('/^app\.baseURL\s*=\s*.+$/m', $envContent) &&
           preg_match('/^database\.default\.database\s*=\s*.+$/m', $envContent);
}

// Check if this is a fresh installation
if (!checkInstallationStatus()) {
    // Not installed - redirect to setup
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = str_replace('\\', '/', $scriptName);
    $basePath = rtrim($basePath, '/');

    $setupUrl = $protocol . $host . $basePath . '/public/setup.php';

    // Show installation required page with redirect
    http_response_code(503);
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Examination System - Installation Required</title>
    <meta http-equiv="refresh" content="3;url=' . htmlspecialchars($setupUrl) . '">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 40px; height: 40px; left: 70%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 100px; height: 100px; left: 80%; animation-delay: 3s; }
        .particle:nth-child(5) { width: 50px; height: 50px; left: 50%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        .container {
            max-width: 650px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            text-align: center;
            position: relative;
            z-index: 10;
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

        .logo-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .company-logo {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
            animation: logoGlow 2s ease-in-out infinite alternate;
        }

        @keyframes logoGlow {
            from { filter: drop-shadow(0 0 10px rgba(102, 126, 234, 0.3)); }
            to { filter: drop-shadow(0 0 20px rgba(102, 126, 234, 0.6)); }
        }

        .system-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 1.1rem;
            color: #667eea;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        .installation-notice {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-left: 4px solid #667eea;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: left;
        }

        .installation-notice h3 {
            color: #2d3748;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .installation-notice p {
            color: #4a5568;
            line-height: 1.6;
            margin: 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 50px;
            margin-top: 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8 0%, #6b5b95 100%);
        }

        .countdown {
            color: #718096;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .countdown-number {
            background: #667eea;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            font-weight: 600;
            min-width: 30px;
            animation: countdownPulse 1s ease-in-out infinite;
        }

        @keyframes countdownPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .company-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 0.9rem;
        }

        .company-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .company-link:hover {
            color: #5a6fd8;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 30px 25px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .company-logo {
                width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <div class="logo-container">
            <img src="public/PDSlogo.png" alt="Peculiar Digital Solution" class="company-logo">
            <div class="system-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>

        <h1>ExamExcel</h1>
        <div class="subtitle">Configure your Computer-Based Test application</div>

        <div class="installation-notice">
            <h3>
                <i class="fas fa-info-circle" style="color: #667eea;"></i>
                Installation Required
            </h3>
            <p>This application needs to be installed before it can be used. You will be automatically redirected to the installation wizard to configure your system.</p>
        </div>

        <a href="' . htmlspecialchars($setupUrl) . '" class="btn">
            <i class="fas fa-rocket"></i>
            Start Installation Now
        </a>

        <div class="countdown">
            <i class="fas fa-clock"></i>
            Redirecting in <span class="countdown-number" id="countdown">3</span> seconds...
        </div>

        <div class="company-footer">
            Developed by <a href="https://peculiardigitals.netlify.app" target="_blank" class="company-link">Peculiar Digital Solution</a>
        </div>
    </div>

    <script>
        let count = 3;
        const countdownEl = document.getElementById("countdown");
        const timer = setInterval(() => {
            count--;
            countdownEl.textContent = count;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = "' . htmlspecialchars($setupUrl) . '";
            }
        }, 1000);
    </script>
</body>
</html>';
    exit;
}

// Application is installed - proceed normally
// For all other requests, include the public/index.php file
// This maintains the CodeIgniter 4 bootstrap process
$publicIndex = __DIR__ . '/public/index.php';

if (file_exists($publicIndex)) {
    // Set the correct script name for CodeIgniter to detect base URL properly
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';

    // Include the public index file
    require_once $publicIndex;
} else {
    // Fallback error if public/index.php doesn't exist
    http_response_code(500);
    echo '<!DOCTYPE html>
<html>
<head>
    <title>ExamExcel - Setup Required</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error { color: #d32f2f; }
        .info { color: #1976d2; margin-top: 20px; }
        code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="error">ExamExcel - Application Error</h1>
        <p>The application could not be loaded. Please ensure:</p>
        <ul>
            <li>The <code>public/index.php</code> file exists</li>
            <li>File permissions are correctly set</li>
            <li>The web server has access to the application files</li>
        </ul>
        <div class="info">
            <strong>For administrators:</strong> Please check the installation guide or contact technical support.
        </div>
    </div>
</body>
</html>';
}
?>
