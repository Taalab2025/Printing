<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Get database configuration from .env
    $envFile = file_get_contents(__DIR__ . '/.env');
    preg_match('/DB_DATABASE=(.*)/', $envFile, $dbNameMatches);
    preg_match('/DB_USERNAME=(.*)/', $envFile, $dbUserMatches);
    preg_match('/DB_PASSWORD=(.*)/', $envFile, $dbPassMatches);
    preg_match('/DB_HOST=(.*)/', $envFile, $dbHostMatches);
    
    $dbName = trim($dbNameMatches[1]);
    $dbUser = trim($dbUserMatches[1]);
    $dbPass = trim($dbPassMatches[1]);
    $dbHost = trim($dbHostMatches[1]);
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if permissions table exists and get its structure
    echo "<h2>Checking tables structure</h2>";
    
    // Check permissions table
    $stmt = $pdo->query("SHOW CREATE TABLE permissions");
    if ($stmt) {
        $table = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h3>Permissions Table Structure:</h3>";
        echo "<pre>" . $table['Create Table'] . "</pre>";
    } else {
        echo "<p>Permissions table does not exist</p>";
    }
    
    // Check roles table
    $stmt = $pdo->query("SHOW CREATE TABLE roles");
    if ($stmt) {
        $table = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h3>Roles Table Structure:</h3>";
        echo "<pre>" . $table['Create Table'] . "</pre>";
    } else {
        echo "<p>Roles table does not exist</p>";
    }
    
    // Check permission_role table
    $stmt = $pdo->query("SHOW CREATE TABLE permission_role");
    if ($stmt) {
        $table = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h3>Permission_Role Table Structure:</h3>";
        echo "<pre>" . $table['Create Table'] . "</pre>";
    } else {
        echo "<p>Permission_role table does not exist</p>";
    }
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}