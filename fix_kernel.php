<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Path to the Kernel file
$kernelPath = __DIR__ . '/app/Http/Kernel.php';

// Check if file exists
if (!file_exists($kernelPath)) {
    die("Kernel file not found!");
}

// Read the file content
$content = file_get_contents($kernelPath);

// Replace the incorrect namespace
$newContent = str_replace(
    'namespace App\Http\Kernel;',
    'namespace App\Http;',
    $content
);

// Write the corrected content back to the file
if (file_put_contents($kernelPath, $newContent)) {
    echo "Kernel namespace corrected successfully!";
} else {
    echo "Failed to write to Kernel file. Check permissions.";
}