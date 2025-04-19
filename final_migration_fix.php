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
    
    echo "<h2>Migration Process</h2>";
    
    // This approach will create tables in the correct order and manage the migrations table
    echo "<p>Step 1: Installing migration without foreign keys...</p>";
    
    // Get PDO connection from the Laravel app
    $db = $app->make('db')->connection()->getPdo();
    
    // Create the migrations table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL
    )");
    
    // Create the roles table
    echo "<p>Creating roles table...</p>";
    $db->exec("CREATE TABLE IF NOT EXISTS roles (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) UNIQUE,
        display_name_en VARCHAR(255),
        display_name_ar VARCHAR(255),
        description_en TEXT NULL,
        description_ar TEXT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");
    
    // Create the permissions table
    echo "<p>Creating permissions table...</p>";
    $db->exec("CREATE TABLE IF NOT EXISTS permissions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) UNIQUE,
        display_name_en VARCHAR(255),
        display_name_ar VARCHAR(255),
        description_en TEXT NULL,
        description_ar TEXT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");
    
    // Create the permission_role table
    echo "<p>Creating permission_role table...</p>";
    $db->exec("CREATE TABLE IF NOT EXISTS permission_role (
        permission_id BIGINT UNSIGNED NOT NULL,
        role_id BIGINT UNSIGNED NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        PRIMARY KEY (permission_id, role_id),
        FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
    )");
    
    // Mark these migrations as completed
    $db->exec("INSERT INTO migrations (migration, batch) VALUES 
        ('2025_04_17_000001_create_roles_table', 1),
        ('2025_04_17_114834_create_permissions_table', 1),
        ('2025_04_17_114834_create_permission_role_table', 1)
    ");
    
    // Run the remaining migrations
    echo "<p>Running remaining migrations...</p>";
    $result = $kernel->call('migrate', ['--force' => true]);
    echo "<p>Result: " . ($result === 0 ? "Success" : "Failed") . "</p>";
    
    // Seed the database
    echo "<p>Seeding database...</p>";
    $seedResult = $kernel->call('db:seed', ['--force' => true]);
    echo "<p>Result: " . ($seedResult === 0 ? "Success" : "Failed") . "</p>";
    
    echo "<h3>Migration process completed!</h3>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}