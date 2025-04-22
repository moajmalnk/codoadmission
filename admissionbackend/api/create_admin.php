<?php
require_once '../config.php';

try {
    $conn = getConnection();
    
    // Admin user details
    $username = 'admin';
    $plainPassword = 'password';
    $email = 'admin@codoacademy.com';
    $role = 'admin';
    
    // Create a new password hash
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    
    // First, delete existing admin user to ensure clean slate
    $deleteStmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $deleteStmt->execute([$username]);
    
    // Create new admin user
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, is_active) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$username, $hashedPassword, $email, $role]);
    
    // Verify the user was created correctly
    $verifyStmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $verifyStmt->execute([$username]);
    $user = $verifyStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Test password verification
        $verificationTest = password_verify($plainPassword, $user['password']);
        
        echo "Admin User Details:\n";
        echo "----------------\n";
        echo "Username: {$username}\n";
        echo "Password: {$plainPassword}\n";
        echo "Email: {$email}\n";
        echo "Role: {$role}\n";
        echo "Is Active: {$user['is_active']}\n";
        echo "Password Hash: " . substr($user['password'], 0, 20) . "...\n";
        echo "Password Verification Test: " . ($verificationTest ? "PASSED" : "FAILED") . "\n";
        
        if ($verificationTest) {
            echo "\nAdmin user created successfully! You can now log in with:\n";
            echo "Username: admin\n";
            echo "Password: password\n";
        } else {
            echo "\nWARNING: Password verification failed. Please try recreating the admin user.\n";
        }
    } else {
        echo "ERROR: Failed to create admin user!\n";
    }
    
} catch(PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 