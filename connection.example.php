<?php
// Database configuration template for Railway deployment
// This file shows how to use environment variables for production deployment

// Railway automatically sets these environment variables when you add a MySQL service:
$db_host = getenv('DB_HOST') ?: 'localhost';           // Railway: your-mysql-host
$db_user = getenv('DB_USER') ?: 'your_username';       // Railway: auto-generated username  
$db_pass = getenv('DB_PASS') ?: 'your_password';       // Railway: auto-generated password
$db_name = getenv('DB_NAME') ?: 'calendar';            // Railway: your database name
$db_port = getenv('DB_PORT') ?: '3306';                // Railway: usually 3306

try {
    // Build DSN with port for Railway compatibility
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    
    $conn = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check your configuration.");
}
?>
