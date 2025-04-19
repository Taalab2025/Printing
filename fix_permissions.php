<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Fixing Laravel Permissions</h2>";

// Define directories that need to be writable
$directories = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

// Create directories if they don't exist and set permissions
foreach ($directories as $directory) {
    $fullPath = __DIR__ . '/' . $directory;
    
    // Create directory if it doesn't exist
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "<p>Created directory: {$directory}</p>";
        } else {
            echo "<p>Failed to create directory: {$directory}</p>";
        }
    }
    
    // Set permissions to 755 for directories
    if (chmod($fullPath, 0755)) {
        echo "<p>Set permissions for directory: {$directory}</p>";
    } else {
        echo "<p>Failed to set permissions for: {$directory}</p>";
    }
}

// Create a .gitignore file in the storage directory if it doesn't exist
$gitignorePath = __DIR__ . '/storage/.gitignore';
if (!file_exists($gitignorePath)) {
    file_put_contents($gitignorePath, "*\n!.gitignore\n");
    echo "<p>Created .gitignore in storage directory</p>";
}

// Make sure Laravel can write to storage/logs/laravel.log
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    if (file_put_contents($logFile, '')) {
        echo "<p>Created empty laravel.log file</p>";
    } else {
        echo "<p>Failed to create laravel.log file</p>";
    }
}

// Set permissions on the log file
if (file_exists($logFile) && chmod($logFile, 0664)) {
    echo "<p>Set permissions for laravel.log</p>";
} else {
    echo "<p>Failed to set permissions for laravel.log</p>";
}

echo "<h3>Permission fixing completed!</h3>";
echo "<p>Try accessing your site again now: <a href='https://printm.taalabprojs.com'>https://printm.taalabprojs.com</a></p>";