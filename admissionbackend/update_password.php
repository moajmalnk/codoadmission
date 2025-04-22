<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Database configuration
    $host = "localhost";
    $dbname = "u262074081_admission";
    $username = "u262074081_admission";
    $password = "4muogEVkB/";
    
    // Create database connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // New password and its hash
    $new_password = 'admin123';
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the password for user 'ajmal'
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $result = $stmt->execute([$new_hash, 'ajmal']);
    
    if ($result) {
        echo "Password updated successfully!\n";
        echo "New password is: admin123\n";
        echo "Please try logging in with these credentials:\n";
        echo "Username: ajmal\n";
        echo "Password: admin123\n";
    } else {
        echo "Failed to update password.\n";
    }
    
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 