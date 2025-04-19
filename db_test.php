<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Laravel's environment
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Database Connection Test</h2>";

try {
    // Get database connection from Laravel
    $db = $app->make('db');
    $conn = $db->connection();
    
    // Try to execute a simple query
    $results = $conn->select('SELECT 1 as test');
    
    echo "<p style='color: green;'>Database connection successful!</p>";
    echo "<p>Result: " . json_encode($results) . "</p>";
    
    // Get database name
    $dbName = $conn->getDatabaseName();
    echo "<p>Connected to database: " . $dbName . "</p>";
    
    // Check if sessions table exists
    $hasSessionsTable = $conn->getSchemaBuilder()->hasTable('sessions');
    echo "<p>Sessions table exists: " . ($hasSessionsTable ? "Yes" : "No") . "</p>";
    
    if (!$hasSessionsTable) {
        echo "<p>The 'sessions' table is missing. You need to run migrations or switch to file sessions.</p>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Database Connection Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Check your .env file database credentials.</p>";
}