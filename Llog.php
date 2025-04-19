<?php
// view_logs.php - upload to your root directory
ini_set('display_errors', 1);
error_reporting(E_ALL);

$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $log = file_get_contents($logFile);
    echo "<pre>" . htmlspecialchars(substr($log, -10000)) . "</pre>"; // Last 10,000 characters
} else {
    echo "Log file not found at: " . $logFile;
}

