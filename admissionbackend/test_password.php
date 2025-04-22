<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// The password hash from your database
$stored_hash = '$2y$10$QkWgM0tYtwXzU6O30WdY2um4sO3vEQVuMN7L7oqFjx96ROn7EApMa';

// Test a password
function test_password($password, $hash) {
    echo "Testing password: " . $password . "\n";
    echo "Against hash: " . $hash . "\n";
    if (password_verify($password, $hash)) {
        echo "Password is valid!\n";
    } else {
        echo "Password is invalid!\n";
    }
}

// Test some common passwords
test_password('password', $stored_hash);
test_password('admin', $stored_hash);
test_password('123456', $stored_hash);

// Generate a new hash for reference
$new_password = 'admin123';
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);
echo "\nFor reference, here's a new hash for password 'admin123':\n";
echo $new_hash . "\n";
?> 