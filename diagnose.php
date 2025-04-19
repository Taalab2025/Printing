<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Diagnostics</h2>";

// Check basic PHP
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if vendor directory exists
echo "<p>Vendor directory exists: " . (is_dir(__DIR__ . '/vendor') ? 'Yes' : 'No') . "</p>";

// Check if autoload.php exists
echo "<p>Autoload file exists: " . (file_exists(__DIR__ . '/vendor/autoload.php') ? 'Yes' : 'No') . "</p>";

// Check if bootstrap/app.php exists
echo "<p>Bootstrap app file exists: " . (file_exists(__DIR__ . '/bootstrap/app.php') ? 'Yes' : 'No') . "</p>";

// Check if app/Http/Kernel.php exists
echo "<p>Kernel file exists: " . (file_exists(__DIR__ . '/app/Http/Kernel.php') ? 'Yes' : 'No') . "</p>";

// If Kernel file exists, show its content
if (file_exists(__DIR__ . '/app/Http/Kernel.php')) {
    echo "<h3>Content of app/Http/Kernel.php:</h3>";
    echo "<pre>" . htmlspecialchars(file_get_contents(__DIR__ . '/app/Http/Kernel.php')) . "</pre>";
}

// Check composer.json
if (file_exists(__DIR__ . '/composer.json')) {
    echo "<h3>Composer.json namespace configuration:</h3>";
    $composerJson = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
    if (isset($composerJson['autoload']['psr-4'])) {
        echo "<pre>" . print_r($composerJson['autoload']['psr-4'], true) . "</pre>";
    } else {
        echo "<p>No PSR-4 autoloading configuration found</p>";
    }
}

// List directories in app to confirm structure
echo "<h3>Directories in app folder:</h3>";
$appDirs = glob(__DIR__ . '/app/*', GLOB_ONLYDIR);
echo "<ul>";
foreach ($appDirs as $dir) {
    echo "<li>" . basename($dir) . "</li>";
}
echo "</ul>";