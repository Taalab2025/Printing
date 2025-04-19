<?php
// env_checker.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$envPath = __DIR__ . '/.env';

if (file_exists($envPath)) {
    echo "<p>.env file exists</p>";
    
    // Check if it's readable
    if (is_readable($envPath)) {
        echo "<p>.env file is readable</p>";
        
        // Get file permissions
        echo "<p>.env file permissions: " . substr(sprintf('%o', fileperms($envPath)), -4) . "</p>";
        
        // Check for basic required variables (without showing sensitive data)
        $env = file_get_contents($envPath);
        $requiredVars = [
            'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
            'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'
        ];
        
        echo "<h3>Required Environment Variables:</h3>";
        echo "<ul>";
        foreach ($requiredVars as $var) {
            $hasVar = preg_match('/^' . $var . '=(.*)$/m', $env);
            echo "<li>" . $var . ": " . ($hasVar ? "Found" : "Missing") . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Error: .env file is not readable</p>";
    }
} else {
    echo "<p>Error: .env file does not exist</p>";
    
    // Check if .env.example exists
    if (file_exists(__DIR__ . '/.env.example')) {
        echo "<p>.env.example exists - you need to copy it to .env</p>";
    } else {
        echo "<p>.env.example does not exist either</p>";
    }
}