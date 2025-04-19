<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(300); // Set a longer timeout

try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "<h2>Running Individual Seeders</h2>";
    
    // Run each seeder separately
    $seeders = [
        'RolesAndPermissionsSeeder',
        'UsersSeeder',
        'CategoriesSeeder',
        'VendorsSeeder'
    ];
    
    foreach ($seeders as $seeder) {
        echo "<h3>Running $seeder:</h3>";
        try {
            $result = $kernel->call('db:seed', [
                '--class' => $seeder,
                '--force' => true
            ]);
            echo "<p>Result: " . ($result === 0 ? "Success" : "Failed (Code: $result)") . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>Seeding process completed!</h3>";
    echo "<p>You can now continue with the remaining deployment steps.</p>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}