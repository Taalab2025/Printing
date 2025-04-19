<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Running All Migrations</h2>";

try {
    // Reset migrations first to start clean
    echo "<p>Resetting previous migrations...</p>";
    $resetResult = $kernel->call('migrate:reset', ['--force' => true]);
    echo "<p>Reset result: " . ($resetResult === 0 ? "Success" : "Failed") . "</p>";
    
    // Run fresh migrations
    echo "<p>Running fresh migrations...</p>";
    $migrateResult = $kernel->call('migrate', ['--force' => true]);
    echo "<p>Migration result: " . ($migrateResult === 0 ? "Success" : "Failed") . "</p>";
    
    // Run database seeders
    echo "<p>Running database seeders...</p>";
    $seedResult = $kernel->call('db:seed', ['--force' => true]);
    echo "<p>Seeding result: " . ($seedResult === 0 ? "Success" : "Failed") . "</p>";
    
    echo "<h3>Migration process completed!</h3>";
    echo "<p>Try accessing your application now: <a href='https://printm.taalabprojs.com/public/'>https://printm.taalabprojs.com/public/</a></p>";
} catch (Exception $e) {
    echo "<h3>Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}