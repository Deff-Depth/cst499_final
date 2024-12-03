<?php
// Use absolute path to avoid issues with relative paths
require_once __DIR__ . '/vendor/autoload.php';  // This should correctly point to the autoload.php file

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection settings using environment variables
$db_host = getenv('DB_HOST') ?: 'db'; 
$db_user = getenv('DB_USER') ?: 'root';
$db_password = getenv('DB_PASSWORD') ?: 'example';
$db_name = getenv('DB_NAME') ?: 'class_registration';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
