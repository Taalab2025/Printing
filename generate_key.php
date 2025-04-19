<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set execution time to avoid timeouts
set_time_limit(120);

echo "<h2>Application Key Generation Diagnostics</h2>";

try {
    echo "<p>Loading autoloader...</p>";
    require __DIR__.'/vendor/autoload.php';
    
    echo "<p>Loading application...</p>";
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    echo "<p>Bootstrapping kernel...</p>";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "<p>Generating application key...</p>";
    $result = $kernel->call('key:generate');
    
    echo "<p>Result: " . json_encode($result) . "</p>";
    echo "<p>Application key set successfully!</p>";
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}