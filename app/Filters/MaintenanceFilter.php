<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Maintenance Mode Filter
 * 
 * Checks if the application is in maintenance mode and shows
 * a maintenance page to users (except admins).
 */
class MaintenanceFilter implements FilterInterface
{
    /**
     * Check maintenance mode before processing request
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip check for CLI requests
        if (is_cli()) {
            return;
        }
        
        // Skip check for installer and admin routes
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        if (strpos($path, 'setup.php') !== false || 
            strpos($path, 'admin/maintenance') !== false ||
            strpos($path, 'assets/') !== false) {
            return;
        }
        
        try {
            helper('app_config');
            
            // Check if maintenance mode is enabled
            if (is_maintenance_mode()) {
                // Allow admin users to bypass maintenance mode
                $session = session();
                $userRole = $session->get('role');
                
                if ($userRole === 'admin') {
                    return;
                }
                
                // Show maintenance page
                return $this->showMaintenancePage($request);
            }
            
        } catch (\Exception $e) {
            // If there's an error checking maintenance mode, continue normally
            log_message('error', 'Maintenance mode check failed: ' . $e->getMessage());
        }
    }

    /**
     * Process after request (not used)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not implemented
    }

    /**
     * Show maintenance page
     */
    private function showMaintenancePage(RequestInterface $request)
    {
        $response = service('response');
        
        // For AJAX requests, return JSON
        if ($request->isAJAX()) {
            return $response->setJSON([
                'error' => true,
                'message' => 'Application is currently under maintenance',
                'maintenance' => true
            ])->setStatusCode(503);
        }
        
        // For regular requests, show maintenance page
        $html = $this->getMaintenancePageHTML();
        
        return $response->setBody($html)->setStatusCode(503);
    }

    /**
     * Get maintenance page HTML
     */
    private function getMaintenancePageHTML(): string
    {
        helper('app_config');
        
        $appName = app_name();
        $institutionName = institution_name();
        $themeColor = theme_color();
        
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - {$appName}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, {$themeColor} 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .maintenance-container {
            max-width: 600px;
            text-align: center;
            color: white;
        }
        .maintenance-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            color: #333;
        }
        .maintenance-icon {
            font-size: 4rem;
            color: {$themeColor};
            margin-bottom: 1rem;
        }
        .btn-primary {
            background: {$themeColor};
            border-color: {$themeColor};
        }
        .btn-primary:hover {
            background: #5a6fd8;
            border-color: #5a6fd8;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-card">
            <div class="maintenance-icon">
                <i class="fas fa-tools"></i>
            </div>
            <h1 class="mb-3">{$appName}</h1>
            <h2 class="h4 mb-4">Under Maintenance</h2>
            <p class="lead mb-4">
                We're currently performing scheduled maintenance to improve your experience. 
                Please check back in a few minutes.
            </p>
            <p class="text-muted mb-4">
                <strong>{$institutionName}</strong><br>
                We apologize for any inconvenience.
            </p>
            <button onclick="location.reload()" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>Try Again
            </button>
        </div>
    </div>
    
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
HTML;
    }
}
