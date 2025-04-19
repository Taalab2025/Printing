<?php
// log_viewer.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$logPath = __DIR__ . '/storage/logs/laravel.log';

if (file_exists($logPath)) {
    // Read the last 100 lines of the log file
    $lines = [];
    $fp = fopen($logPath, 'r');
    
    // Read the file line by line and keep the last 100 lines
    while (($line = fgets($fp)) !== false) {
        $lines[] = $line;
        if (count($lines) > 100) {
            array_shift($lines);
        }
    }
    
    fclose($fp);
    
    echo "<h2>Last 100 lines of Laravel Log</h2>";
    echo "<pre>";
    echo htmlspecialchars(implode('', $lines));
    echo "</pre>";
} else {
    echo "Log file not found at: " . $logPath;
}