<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Installation Structure Check</h2>";

// Check key directories and files
$required = [
    "app" => "Directory",
    "bootstrap" => "Directory",
    "config" => "Directory",
    "database" => "Directory",
    "public" => "Directory",
    "resources" => "Directory",
    "routes" => "Directory",
    "storage" => "Directory",
    "vendor" => "Directory",
    ".env" => "File",
    "artisan" => "File",
    "public/index.php" => "File"
];

$status = [];
foreach ($required as $path => $type) {
    $fullPath = __DIR__ . '/' . $path;
    
    if ($type == "Directory") {
        $exists = is_dir($fullPath);
    } else {
        $exists = file_exists($fullPath);
    }
    
    $status[$path] = $exists ? "✅ Exists" : "❌ Missing";
}

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>Path</th><th>Type</th><th>Status</th></tr>";

foreach ($required as $path => $type) {
    echo "<tr>";
    echo "<td>{$path}</td>";
    echo "<td>{$type}</td>";
    echo "<td>{$status[$path]}</td>";
    echo "</tr>";
}
echo "</table>";

// Check Laravel version
if (file_exists(__DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/Application.php')) {
    $content = file_get_contents(__DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/Application.php');
    if (preg_match("/const VERSION = '(.*)';/", $content, $matches)) {
        echo "<p>Laravel Version: {$matches[1]}</p>";
    } else {
        echo "<p>Could not determine Laravel version</p>";
    }
} else {
    echo "<p>Laravel framework files not found</p>";
}

// Check public/index.php content
if (file_exists(__DIR__ . '/public/index.php')) {
    $content = file_get_contents(__DIR__ . '/public/index.php');
    $hasAutoload = strpos($content, "require __DIR__.'/../vendor/autoload.php';") !== false;
    $hasBootstrap = strpos($content, "require_once __DIR__.'/../bootstrap/app.php';") !== false;
    
    echo "<p>Public/index.php content check:</p>";
    echo "<ul>";
    echo "<li>Has autoload include: " . ($hasAutoload ? "Yes" : "No") . "</li>";
    echo "<li>Has bootstrap include: " . ($hasBootstrap ? "Yes" : "No") . "</li>";
    echo "</ul>";
}

echo "<h3>Directory Permission Check</h3>";
$writableDirs = [
    "storage" => 0755,
    "storage/app" => 0755,
    "storage/framework" => 0755,
    "storage/logs" => 0755,
    "bootstrap/cache" => 0755
];

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>Directory</th><th>Exists</th><th>Writable</th><th>Current Permissions</th></tr>";

foreach ($writableDirs as $dir => $perm) {
    $fullPath = __DIR__ . '/' . $dir;
    $exists = is_dir($fullPath);
    $writable = is_writable($fullPath);
    $currentPerm = $exists ? substr(sprintf('%o', fileperms($fullPath)), -4) : "N/A";
    
    echo "<tr>";
    echo "<td>{$dir}</td>";
    echo "<td>" . ($exists ? "Yes" : "No") . "</td>";
    echo "<td>" . ($writable ? "Yes" : "No") . "</td>";
    echo "<td>{$currentPerm}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>Next Steps</h3>";
echo "<p>1. If all looks good, rename 'new.htaccess' to '.htaccess'</p>";
echo "<p>2. Try accessing your Laravel application at: <a href='https://printm.taalabprojs.com'>https://printm.taalabprojs.com</a></p>";
echo "<p>3. If you get a 500 error, edit your .env file to set APP_DEBUG=true and try again to see detailed error messages</p>";