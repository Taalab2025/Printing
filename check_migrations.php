<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Migration Analysis</h2>";
$migrationsPath = __DIR__ . '/database/migrations';

// List all migration files
$files = glob($migrationsPath . '/*.php');
$permissions_file = null;
$permission_role_file = null;

foreach ($files as $file) {
    $filename = basename($file);
    
    // Check for permissions table migration
    if (strpos($filename, 'create_permissions_table') !== false) {
        $permissions_file = $file;
        echo "<p>Found permissions table migration: $filename</p>";
    }
    
    // Check for permission_role table migration
    if (strpos($filename, 'create_permission_role_table') !== false) {
        $permission_role_file = $file;
        echo "<p>Found permission_role table migration: $filename</p>";
    }
}

// Analyze permissions table structure
if ($permissions_file) {
    $content = file_get_contents($permissions_file);
    echo "<h3>Permissions Table Migration:</h3>";
    echo "<pre>" . htmlspecialchars($content) . "</pre>";
    
    // Check if id column is properly defined
    if (strpos($content, '$table->id()') !== false) {
        echo "<p>✅ Permissions table uses id() method (bigInteger unsigned)</p>";
    } else if (strpos($content, 'increments') !== false) {
        echo "<p>✅ Permissions table uses increments (integer unsigned)</p>";
    } else {
        echo "<p>⚠️ Could not determine primary key type for permissions table</p>";
    }
}

// Analyze permission_role table structure
if ($permission_role_file) {
    $content = file_get_contents($permission_role_file);
    echo "<h3>Permission_Role Table Migration:</h3>";
    echo "<pre>" . htmlspecialchars($content) . "</pre>";
    
    // Check foreign key definition
    if (strpos($content, 'unsigned') !== false) {
        echo "<p>✅ Permission_role table has unsigned foreign keys</p>";
    } else {
        echo "<p>⚠️ Permission_role table might be missing unsigned attribute on foreign keys</p>";
    }
}