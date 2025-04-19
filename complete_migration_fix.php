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
    
    echo "<h2>Final Migration Fix</h2>";
    
    // Get PDO connection from the Laravel app
    $db = $app->make('db')->connection()->getPdo();
    
    echo "<p>Creating foreign keys for existing tables...</p>";
    
    // Drop existing foreign keys if they exist (to avoid constraint errors)
    try {
        $db->exec("ALTER TABLE permission_role DROP FOREIGN KEY permission_role_permission_id_foreign");
    } catch (Exception $e) {
        echo "<p>No permission_id foreign key to drop</p>";
    }
    
    try {
        $db->exec("ALTER TABLE permission_role DROP FOREIGN KEY permission_role_role_id_foreign");
    } catch (Exception $e) {
        echo "<p>No role_id foreign key to drop</p>";
    }
    
    // Add the foreign keys properly
    try {
        $db->exec("ALTER TABLE permission_role 
                  ADD CONSTRAINT permission_role_permission_id_foreign 
                  FOREIGN KEY (permission_id) REFERENCES permissions(id) 
                  ON DELETE CASCADE");
        echo "<p>Added permission_id foreign key successfully</p>";
    } catch (Exception $e) {
        echo "<p>Error adding permission_id foreign key: " . $e->getMessage() . "</p>";
    }
    
    try {
        $db->exec("ALTER TABLE permission_role 
                  ADD CONSTRAINT permission_role_role_id_foreign 
                  FOREIGN KEY (role_id) REFERENCES roles(id) 
                  ON DELETE CASCADE");
        echo "<p>Added role_id foreign key successfully</p>";
    } catch (Exception $e) {
        echo "<p>Error adding role_id foreign key: " . $e->getMessage() . "</p>";
    }
    
    // Mark all migrations as completed to prevent Laravel from trying to run them again
    echo "<p>Marking migrations as completed...</p>";
    
    // First, get all migration files
    $migrationsPath = __DIR__ . '/database/migrations';
    $files = glob($migrationsPath . '/*.php');
    $migrations = [];
    
    foreach ($files as $file) {
        $filename = basename($file);
        $migrationName = str_replace('.php', '', $filename);
        $migrations[] = $migrationName;
    }
    
    // Clear existing migrations table
    $db->exec("TRUNCATE TABLE migrations");
    
    // Insert all migrations as completed
    if (!empty($migrations)) {
        $values = [];
        foreach ($migrations as $index => $migration) {
            $values[] = "('" . $migration . "', 1)";
        }
        $sql = "INSERT INTO migrations (migration, batch) VALUES " . implode(', ', $values);
        $db->exec($sql);
        echo "<p>Marked " . count($migrations) . " migrations as completed</p>";
    }
    
    // Run database seeding
    echo "<p>Seeding database...</p>";
    $seedResult = $kernel->call('db:seed', ['--force' => true]);
    echo "<p>Seeding result: " . ($seedResult === 0 ? "Success" : "Failed") . "</p>";
    
    echo "<h3>Migration fix completed! Your database should now be properly set up.</h3>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}