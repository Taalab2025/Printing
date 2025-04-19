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
    
    echo "<h2>Optimizing Application</h2>";
    
    // Clear all caches first
    echo "<p>Clearing cache...</p>";
    $clearResult = $kernel->call('cache:clear');
    echo "<p>Result: " . ($clearResult === 0 ? "Success" : "Failed") . "</p>";
    
    // Cache configuration
    echo "<p>Caching configuration...</p>";
    $configResult = $kernel->call('config:cache');
    echo "<p>Result: " . ($configResult === 0 ? "Success" : "Failed") . "</p>";
    
    // Cache routes
    echo "<p>Caching routes...</p>";
    $routeResult = $kernel->call('route:cache');
    echo "<p>Result: " . ($routeResult === 0 ? "Success" : "Failed") . "</p>";
    
    // Cache views
    echo "<p>Caching views...</p>";
    $viewResult = $kernel->call('view:cache');
    echo "<p>Result: " . ($viewResult === 0 ? "Success" : "Failed") . "</p>";
    
    echo "<h3>Optimization completed!</h3>";
    echo "<p>Your application is now optimized for production.</p>";
    
    echo "<h2>Next Steps</h2>";
    echo "<ol>";
    echo "<li>Set up the cron job in Hostinger's control panel:<br>";
    echo "<code>php /home/u671773932/domains/taalabprojs.com/public_html/PrintMarket/artisan schedule:run >> /dev/null 2>&1</code></li>";
    echo "<li>Test your application by visiting: <a href='https://printm.taalabprojs.com' target='_blank'>https://printm.taalabprojs.com</a></li>";
    echo "<li>Remove all the helper PHP files used during deployment (.php files in the root directory)</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}