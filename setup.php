<?php
/**
 * Setup Redirect File
 * Redirects setup requests to the actual setup file in public directory
 */

// Get the current base path dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$basePath = str_replace('\\', '/', $scriptName);
$basePath = rtrim($basePath, '/');

// Redirect to the actual setup file in public directory
$setupUrl = $protocol . $host . $basePath . '/public/setup.php';
header('Location: ' . $setupUrl);
exit;
?>
