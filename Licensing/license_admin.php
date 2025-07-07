<?php
/**
 * CBT License Administration Panel
 * 
 * Web interface for generating and managing licenses.
 * Secure this file with authentication in production!
 */

// Simple authentication (change this password!)
session_start();
$admin_password = 'your_secure_admin_password_here';

if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        showLoginForm();
        exit;
    }
}

require_once 'license_generator.php';

$generator = new LicenseGenerator();
$message = '';
$error = '';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'generate':
                try {
                    $license = $generator->generateCode([
                        'license_type' => $_POST['license_type'],
                        'expiry_days' => (int)$_POST['expiry_days'],
                        'max_installs' => (int)$_POST['max_installs'],
                        'customer_email' => $_POST['customer_email'],
                        'customer_name' => $_POST['customer_name'],
                        'features' => $_POST['license_type'] === 'premium' ? ['full_access', 'premium_support'] : ['full_access']
                    ]);
                    
                    $generator->saveLicense($license);
                    $message = "License generated successfully!";
                    $generated_license = $license;
                } catch (Exception $e) {
                    $error = "Error generating license: " . $e->getMessage();
                }
                break;
                
            case 'validate':
                try {
                    $validation = $generator->validateCode($_POST['test_code']);
                    if ($validation['valid']) {
                        $message = "Code is valid!";
                        $validation_result = $validation;
                    } else {
                        $error = "Invalid code: " . $validation['error'];
                    }
                } catch (Exception $e) {
                    $error = "Validation error: " . $e->getMessage();
                }
                break;
                
            case 'logout':
                session_destroy();
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
        }
    }
}

function showLoginForm() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>License Admin - Login</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 400px; margin: 100px auto; padding: 20px; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; }
            input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
            button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
            button:hover { background: #005a87; }
        </style>
    </head>
    <body>
        <h2>License Administration</h2>
        <form method="POST">
            <div class="form-group">
                <label>Admin Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </body>
    </html>
    <?php
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CBT License Administration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #007cba; color: white; padding: 20px; margin: -20px -20px 20px -20px; }
        .card { background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        button:hover { background: #005a87; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .code-display { font-family: monospace; font-size: 18px; background: #f8f9fa; padding: 15px; border: 2px solid #007cba; border-radius: 4px; word-break: break-all; }
        .two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .logout { float: right; background: #dc3545; }
        .logout:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CBT License Administration Panel</h1>
        <form method="POST" style="display: inline;">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="logout">Logout</button>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="two-column">
        <!-- License Generation -->
        <div class="card">
            <h2>Generate New License</h2>
            <form method="POST">
                <input type="hidden" name="action" value="generate">
                
                <div class="form-group">
                    <label>Customer Name:</label>
                    <input type="text" name="customer_name" required>
                </div>
                
                <div class="form-group">
                    <label>Customer Email:</label>
                    <input type="email" name="customer_email" required>
                </div>
                
                <div class="form-group">
                    <label>License Type:</label>
                    <select name="license_type" required>
                        <option value="standard">Standard (1 install, 1 year)</option>
                        <option value="premium">Premium (3 installs, 2 years)</option>
                        <option value="enterprise">Enterprise (unlimited, lifetime)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Expiry Days:</label>
                    <input type="number" name="expiry_days" value="365" min="1" max="3650" required>
                </div>
                
                <div class="form-group">
                    <label>Maximum Installations:</label>
                    <input type="number" name="max_installs" value="1" min="1" max="100" required>
                </div>
                
                <button type="submit">Generate License</button>
            </form>
        </div>

        <!-- License Validation -->
        <div class="card">
            <h2>Validate License Code</h2>
            <form method="POST">
                <input type="hidden" name="action" value="validate">
                
                <div class="form-group">
                    <label>Purchase Code:</label>
                    <textarea name="test_code" rows="3" placeholder="XXXX-XXXX-XXXX-XXXX-SIGNATURE" required></textarea>
                </div>
                
                <button type="submit">Validate Code</button>
            </form>
            
            <?php if (isset($validation_result)): ?>
                <div style="margin-top: 20px; padding: 15px; background: #d4edda; border-radius: 4px;">
                    <h4>Validation Result:</h4>
                    <p><strong>Status:</strong> Valid ✓</p>
                    <p><strong>License Type:</strong> <?= htmlspecialchars($validation_result['license_type']) ?></p>
                    <p><strong>Expires:</strong> <?= htmlspecialchars($validation_result['expires']) ?></p>
                    <p><strong>Max Installs:</strong> <?= htmlspecialchars($validation_result['max_installs']) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($generated_license)): ?>
        <div class="card">
            <h2>Generated License Details</h2>
            <div style="margin-bottom: 15px;">
                <strong>Customer:</strong> <?= htmlspecialchars($generated_license['customer_name']) ?> 
                (<?= htmlspecialchars($generated_license['customer_email']) ?>)
            </div>
            <div style="margin-bottom: 15px;">
                <strong>License Type:</strong> <?= htmlspecialchars($generated_license['data']['type']) ?>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Expires:</strong> <?= htmlspecialchars($generated_license['data']['exp']) ?>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Max Installations:</strong> <?= htmlspecialchars($generated_license['data']['installs']) ?>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Generated:</strong> <?= htmlspecialchars($generated_license['generated_at']) ?>
            </div>
            
            <h3>Purchase Code:</h3>
            <div class="code-display">
                <?= htmlspecialchars($generated_license['code']) ?>
            </div>
            
            <div style="margin-top: 15px;">
                <button onclick="copyToClipboard('<?= htmlspecialchars($generated_license['code']) ?>')">Copy Code</button>
                <button onclick="emailCustomer('<?= htmlspecialchars($generated_license['customer_email']) ?>', '<?= htmlspecialchars($generated_license['code']) ?>')">Email Customer</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recent Licenses -->
    <div class="card">
        <h2>Recent Licenses</h2>
        <?php
        $licensesFile = 'generated_licenses.json';
        if (file_exists($licensesFile)) {
            $licenses = json_decode(file_get_contents($licensesFile), true) ?: [];
            $recentLicenses = array_slice(array_reverse($licenses), 0, 10);
            
            if ($recentLicenses): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Customer</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Type</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Expires</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Generated</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Code</th>
                    </tr>
                    <?php foreach ($recentLicenses as $license): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <?= htmlspecialchars($license['customer_name']) ?><br>
                                <small><?= htmlspecialchars($license['customer_email']) ?></small>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($license['data']['type']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($license['data']['exp']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($license['generated_at']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd; font-family: monospace; font-size: 12px;">
                                <?= htmlspecialchars(substr($license['code'], 0, 20)) ?>...
                                <button onclick="copyToClipboard('<?= htmlspecialchars($license['code']) ?>')" style="margin-left: 5px; padding: 2px 6px; font-size: 11px;">Copy</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No licenses generated yet.</p>
            <?php endif;
        } else { ?>
            <p>No license database found.</p>
        <?php } ?>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Purchase code copied to clipboard!');
            });
        }
        
        function emailCustomer(email, code) {
            const subject = encodeURIComponent('Your CBT Application Purchase Code');
            const body = encodeURIComponent(`Dear Customer,

Thank you for purchasing our CBT Application!

Your purchase code is: ${code}

Please use this code during the installation process. Keep this code safe as you'll need it to install the application.

Installation Instructions:
1. Upload the application files to your server
2. Navigate to setup.php in your browser
3. Enter your purchase code when prompted
4. Follow the installation wizard

If you need any assistance, please don't hesitate to contact our support team.

Best regards,
CBT Application Team`);
            
            window.open(`mailto:${email}?subject=${subject}&body=${body}`);
        }
    </script>
</body>
</html>
