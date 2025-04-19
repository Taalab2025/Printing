<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Clearing Laravel Cache</h2>";

try {
    echo "<p>Config cache: ";
    $result = $kernel->call('config:clear');
    echo ($result === 0 ? "Cleared successfully" : "Failed") . "</p>";
    
    echo "<p>Route cache: ";
    $result = $kernel->call('route:clear');
    echo ($result === 0 ? "Cleared successfully" : "Failed") . "</p>";
    
    echo "<p>Application cache: ";
    $result = $kernel->call('cache:clear');
    echo ($result === 0 ? "Cleared successfully" : "Failed") . "</p>";
    
    echo "<p>View cache: ";
    $result = $kernel->call('view:clear');
    echo ($result === 0 ? "Cleared successfully" : "Failed") . "</p>";
    
    echo "<h3>All caches cleared!</h3>";
    echo "<p>Now try accessing your application at: <a href='https://printm.taalabprojs.com/public/'>https://printm.taalabprojs.com/public/</a></p>";
} catch (Exception $e) {
    echo "<h3>Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}