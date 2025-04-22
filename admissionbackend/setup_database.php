<?php
require_once 'config.php';

try {
    // Create database if it doesn't exist
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "Database created successfully or already exists<br>";
    
    // Switch to the database
    $pdo->exec("USE $dbname");
    
    // Create admissions table with correct field types
    $sql = "CREATE TABLE IF NOT EXISTS admissions (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        applicant_name VARCHAR(100) NOT NULL,
        batch_no VARCHAR(20) DEFAULT NULL,
        date_of_joining DATE DEFAULT NULL,
        dob DATE NOT NULL,
        gender ENUM('male', 'female') NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL,
        education VARCHAR(200) DEFAULT NULL,
        father_name VARCHAR(100) DEFAULT NULL,
        father_occupation VARCHAR(100) DEFAULT NULL,
        mother_name VARCHAR(100) DEFAULT NULL,
        guardian_phone VARCHAR(15) DEFAULT NULL,
        address TEXT NOT NULL,
        technical_background ENUM('yes', 'no') DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        application_id VARCHAR(20) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($sql);
    echo "Table 'admissions' created successfully or already exists<br>";
    
    echo "Database setup completed successfully!";
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> 