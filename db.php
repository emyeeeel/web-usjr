<?php
// db.php

$servername = getenv('DB_SERVER') ?: 'localhost';  // Default to localhost if not set
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'root';
$dbname = getenv('DB_NAME') ?: 'usjr';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log error (don't show sensitive info to users)
    error_log($e->getMessage());
    echo "Connection failed. Please try again later.";
    exit();  // Stop script execution if connection fails
}
?>
