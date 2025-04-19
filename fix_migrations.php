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
    
    // First, clear any previous migration attempts
    echo "<h2>Resetting migrations</h2>";
    $kernel->call('migrate:reset', ['--force' => true]);
    
    // Run fresh migrations, which will handle the order automatically
    echo "<h2>Running migrations</h2>";
    $result = $kernel->call('migrate:fresh', ['--force' => true]);
    echo "<p>Migration result: " . ($result === 0 ? "Success" : "Failed") . "</p>";
    
    // Seed the database
    echo "<h2>Seeding database</h2>";
    $seedResult = $kernel->call('db:seed', ['--force' => true]);
    echo "<p>Seeding result: " . ($seedResult === 0 ? "Success" : "Failed") . "</p>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}