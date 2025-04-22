<?php
header('Content-Type: text/plain');
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
    
    // User details
    $newUser = [
        'username' => 'admin2',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'email' => 'admin2@codoacademy.com',
        'role' => 'admin',
        'is_active' => 1
    ];
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$newUser['username'], $newUser['email']]);
    
    if ($stmt->fetch()) {
        echo "User already exists with this username or email\n";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, password, email, role, is_active) VALUES (:username, :password, :email, :role, :is_active)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($newUser);
        
        echo "User created successfully!\n\n";
        echo "User Details:\n";
        echo "-------------\n";
        echo "Username: {$newUser['username']}\n";
        echo "Password: admin123\n";
        echo "Email: {$newUser['email']}\n";
        echo "Role: {$newUser['role']}\n";
        echo "Is Active: {$newUser['is_active']}\n\n";
        
        // Verify the user was created
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$newUser['username']]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "User verification successful!\n";
            echo "You can now log in with:\n";
            echo "Username: {$newUser['username']}\n";
            echo "Password: admin123\n";
        }
    }
    
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 