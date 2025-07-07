<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\InstallationManager;

/**
 * Installation Filter
 * 
 * Checks if the application is properly installed and redirects
 * to the installer if needed. This ensures users can't access
 * the application before it's properly configured.
 */
class InstallationFilter implements FilterInterface
{
    /**
     * Check installation status before processing request
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip check for installer routes
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Allow access to installer and setup files
        if (strpos($path, 'setup.php') !== false || 
            strpos($path, 'install') !== false ||
            strpos($path, 'assets/') !== false) {
            return;
        }
        
        // Skip check for CLI requests
        if (is_cli()) {
            return;
        }
        
        try {
            $installer = new InstallationManager();
            
            // If not installed, redirect to installer
            if (!$installer->isInstalled()) {
                $setupUrl = base_url('setup.php');
                
                // For AJAX requests, return JSON response
                if ($request->isAJAX()) {
                    $response = service('response');
                    return $response->setJSON([
                        'error' => true,
                        'message' => 'Application not installed',
                        'redirect' => $setupUrl
                    ])->setStatusCode(503);
                }
                
                // For regular requests, redirect to setup
                return redirect()->to($setupUrl);
            }
            
            // Check if upgrade is needed
            if ($installer->needsUpgrade()) {
                $upgradeUrl = base_url('setup.php?upgrade=1');
                
                if ($request->isAJAX()) {
                    $response = service('response');
                    return $response->setJSON([
                        'error' => true,
                        'message' => 'Application upgrade required',
                        'redirect' => $upgradeUrl
                    ])->setStatusCode(503);
                }
                
                return redirect()->to($upgradeUrl);
            }
            
        } catch (\Exception $e) {
            // If there's an error checking installation status,
            // assume it's not installed and redirect to setup
            log_message('error', 'Installation check failed: ' . $e->getMessage());
            
            if (!$request->isAJAX()) {
                return redirect()->to(base_url('setup.php'));
            }
        }
    }

    /**
     * Process after request (not used)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not implemented
    }
}
