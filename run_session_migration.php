<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Creating Sessions Table</h2>";

try {
    // Run the session migration
    $result = $kernel->call('migrate', [
        '--path' => 'vendor/laravel/framework/src/Illuminate/Session/Console/stubs/database/migrations'
    ]);
    
    echo "<p>Migration result: " . ($result === 0 ? "Success" : "Failed") . "</p>";
    
    // Check if sessions table exists now
    $db = $app->make('db');
    $conn = $db->connection();
    $hasSessionsTable = $conn->getSchemaBuilder()->hasTable('sessions');
    
    echo "<p>Sessions table exists: " . ($hasSessionsTable ? "Yes" : "No") . "</p>";
    
    if ($hasSessionsTable) {
        echo "<p style='color: green;'>Sessions table was created successfully!</p>";
        echo "<p>Your Laravel application should now work properly. Try accessing it at: <a href='https://printm.taalabprojs.com/public/'>https://printm.taalabprojs.com/public/</a></p>";
    } else {
        echo "<p style='color: red;'>Failed to create sessions table.</p>";
    }
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}