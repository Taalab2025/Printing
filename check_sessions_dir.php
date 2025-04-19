<?php
// check_sessions_dir.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$sessionsDir = __DIR__ . '/storage/framework/sessions';

echo "<h2>Sessions Directory Check</h2>";

if (!is_dir($sessionsDir)) {
    echo "<p>Sessions directory does not exist. Creating it...</p>";
    if (mkdir($sessionsDir, 0755, true)) {
        echo "<p>Successfully created sessions directory!</p>";
    } else {
        echo "<p>Failed to create sessions directory.</p>";
    }
} else {
    echo "<p>Sessions directory exists.</p>";
}

if (is_dir($sessionsDir)) {
    if (is_writable($sessionsDir)) {
        echo "<p>Sessions directory is writable. ✅</p>";
    } else {
        echo "<p>Sessions directory is not writable. Fixing permissions...</p>";
        if (chmod($sessionsDir, 0755)) {
            echo "<p>Successfully set permissions on sessions directory!</p>";
        } else {
            echo "<p>Failed to set permissions on sessions directory.</p>";
        }
    }
}