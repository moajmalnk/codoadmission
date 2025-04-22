<?php
// Database configuration
$host = "127.0.0.1"; // Use IP instead of 'localhost'
$port = 3306;        // Default MySQL port
$dbname = "admission_system";
$username = "root";
$password = "";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Function to get database connection
function getConnection() {
    global $host, $port, $dbname, $username, $password;
    
    try {
        // Try connecting to MySQL server first
        $pdo = new PDO(
            "mysql:host=$host;port=$port;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true
            ]
        );
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Connect to the specific database
        $pdo->exec("USE `$dbname`");
        
        // Create tables if they don't exist
        createTables($pdo);
        
        return $pdo;
    } catch(PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        throw new PDOException("Database connection failed: " . $e->getMessage());
    }
}

function createTables($pdo) {
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        role VARCHAR(20) NOT NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        UNIQUE INDEX idx_username (username),
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Create applications table
    $pdo->exec("CREATE TABLE IF NOT EXISTS applications (
        application_id INT AUTO_INCREMENT PRIMARY KEY,
        student_name VARCHAR(100) NOT NULL,
        father_name VARCHAR(100),
        mother_name VARCHAR(100),
        date_of_birth DATE,
        gender VARCHAR(10),
        address TEXT,
        phone VARCHAR(20),
        email VARCHAR(100),
        guardian_phone VARCHAR(20),
        previous_school VARCHAR(100),
        previous_class VARCHAR(50),
        applying_for_class VARCHAR(50),
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_student_name (student_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

// Test connection on script load
try {
    $testConn = getConnection();
    // Keep the connection open
} catch(PDOException $e) {
    error_log("Initial connection test failed: " . $e->getMessage());
    if (!headers_sent()) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Database configuration error: ' . $e->getMessage()]);
        exit();
    }
}
?> 