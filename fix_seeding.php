<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "<h2>Database Seeding</h2>";
    
    // List available seeders
    echo "<h3>Available Seeders:</h3>";
    $seederPath = __DIR__ . '/database/seeders';
    $files = glob($seederPath . '/*.php');
    echo "<ul>";
    foreach ($files as $file) {
        echo "<li>" . basename($file) . "</li>";
    }
    echo "</ul>";
    
    // Run database seed with verbose output and debugging
    echo "<h3>Running DatabaseSeeder:</h3>";
    ob_start();
    $seedResult = $kernel->call('db:seed', [
        '--class' => 'DatabaseSeeder',
        '--force' => true,
        '--verbose' => true
    ]);
    $output = ob_get_clean();
    
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    echo "<p>Seeding result code: " . $seedResult . "</p>";
    
    echo "<h3>Seeding process completed!</h3>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}