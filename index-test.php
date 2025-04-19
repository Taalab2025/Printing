<?php
require __DIR__.'/../vendor/autoload.php';
echo "Autoloader works!<br>";

$app = require_once __DIR__.'/../bootstrap/app.php';
echo "Bootstrap works!<br>";

echo "Test completed successfully!";
