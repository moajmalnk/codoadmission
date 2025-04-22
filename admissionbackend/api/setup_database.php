<?php
// Database configuration
$host = "127.0.0.1";
$username = "root";
$password = "";

try {
    // Create connection without database selected
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS admission_system");
    echo "Database created successfully or already exists\n";
    
    // Select the database
    $conn->exec("USE admission_system");
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
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
    )";
    
    $conn->exec($sql);
    echo "Users table created successfully or already exists\n";
    
    // Create applications table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS applications (
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
    )";
    
    $conn->exec($sql);
    echo "Applications table created successfully or already exists\n";
    
    echo "\nDatabase setup completed successfully!\n";
    echo "You can now run create_admin.php to create the admin user.\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 