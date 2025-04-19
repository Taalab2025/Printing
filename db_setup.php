<?php
// db_setup.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Setting Up Database Tables</h2>";

try {
    // Get database connection
    $db = $app->make('db');
    $conn = $db->connection();
    
    // Disable foreign key checks
    echo "<p>Disabling foreign key checks...</p>";
    $conn->statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Execute the SQL commands
    $sql = file_get_contents(__DIR__ . '/database_setup.sql');
    
    // Replace 'key' with '`key`' to handle the reserved keyword
    $sql = str_replace('key VARCHAR', '`key` VARCHAR', $sql);
    
    // Split SQL commands by semicolon
    $commands = explode(';', $sql);
    
    foreach ($commands as $command) {
        $command = trim($command);
        if (!empty($command)) {
            try {
                $conn->statement($command);
                echo "<p>Command executed successfully.</p>";
            } catch (Exception $e) {
                echo "<p>Error executing command: " . $e->getMessage() . "</p>";
                echo "<pre>" . htmlspecialchars($command) . "</pre>";
            }
        }
    }
    
    // Re-enable foreign key checks
    echo "<p>Re-enabling foreign key checks...</p>";
    $conn->statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "<h3>Database setup completed!</h3>";
    echo "<p>Try accessing your application now: <a href='https://printm.taalabprojs.com/public/'>https://printm.taalabprojs.com/public/</a></p>";
} catch (Exception $e) {
    echo "<h3>Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    
    // Make sure to re-enable foreign key checks in case of error
    try {
        $db = $app->make('db');
        $conn = $db->connection();
        $conn->statement('SET FOREIGN_KEY_CHECKS=1');
    } catch (Exception $e2) {
        // Just in case
    }
}