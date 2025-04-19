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
    
    // Reset migrations first
    echo "<h2>Resetting migrations</h2>";
    $kernel->call('migrate:reset', ['--force' => true]);
    
    // Create a custom migration file that ensures the roles table is created first
    $migrationsPath = __DIR__ . '/database/migrations';
    $tempRoleMigrationFile = $migrationsPath . '/2025_04_17_000001_create_roles_table.php';
    
    // Check if roles migration exists
    $rolesMigrationFiles = glob($migrationsPath . '/*_create_roles_table.php');
    if (!empty($rolesMigrationFiles)) {
        // Copy the contents of the existing roles migration
        $rolesMigrationContent = file_get_contents($rolesMigrationFiles[0]);
        
        // Create a new migration file with an earlier timestamp
        file_put_contents($tempRoleMigrationFile, $rolesMigrationContent);
        echo "<p>Created temporary roles migration file with earlier timestamp</p>";
        
        // Run the roles migration first
        echo "<h3>Running roles migration</h3>";
        $rolesResult = $kernel->call('migrate', [
            '--path' => 'database/migrations/2025_04_17_000001_create_roles_table.php',
            '--force' => true
        ]);
        echo "<p>Result: " . ($rolesResult === 0 ? "Success" : "Failed") . "</p>";
        
        // Run permissions migration
        echo "<h3>Running permissions migration</h3>";
        $permsResult = $kernel->call('migrate', [
            '--path' => 'database/migrations/*_create_permissions_table.php',
            '--force' => true
        ]);
        echo "<p>Result: " . ($permsResult === 0 ? "Success" : "Failed") . "</p>";
        
        // Run permission_role migration
        echo "<h3>Running permission_role migration</h3>";
        $pivotResult = $kernel->call('migrate', [
            '--path' => 'database/migrations/*_create_permission_role_table.php',
            '--force' => true
        ]);
        echo "<p>Result: " . ($pivotResult === 0 ? "Success" : "Failed") . "</p>";
        
        // Run remaining migrations
        echo "<h3>Running remaining migrations</h3>";
        $remainingResult = $kernel->call('migrate', ['--force' => true]);
        echo "<p>Result: " . ($remainingResult === 0 ? "Success" : "Failed") . "</p>";
        
        // Remove the temporary migration file
        unlink($tempRoleMigrationFile);
        echo "<p>Removed temporary migration file</p>";
        
        // Seed the database
        echo "<h3>Seeding database</h3>";
        $seedResult = $kernel->call('db:seed', ['--force' => true]);
        echo "<p>Result: " . ($seedResult === 0 ? "Success" : "Failed") . "</p>";
    } else {
        echo "<p>Error: Could not find roles migration file</p>";
    }
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}