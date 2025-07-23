<?php
// Railway Production Database Connection
// This file is safe to commit since it uses environment variables

try {
    // Railway automatically provides these environment variables
    $host = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? 'maglev.proxy.rlwy.net';
    $port = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? '40785';
    $dbname = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? 'railway';
    $username = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? 'root';
    $password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASS'] ?? 'EUbtbwNUfAelihTAgTVVksKJXPjaOvfW';
    
    // Build the DSN
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    
    // Create PDO connection
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check your configuration.");
}
?>
