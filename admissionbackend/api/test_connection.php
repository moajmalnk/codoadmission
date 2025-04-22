<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP Version: " . PHP_VERSION . "\n\n";

echo "Checking PDO and MySQL...\n";
if (extension_loaded('pdo')) {
    echo "PDO is loaded\n";
    echo "Available PDO drivers:\n";
    print_r(PDO::getAvailableDrivers());
} else {
    echo "PDO is NOT loaded\n";
}

echo "\nTesting direct MySQL connection...\n";
try {
    $host = "localhost";
    $username = "u262074081_admission";
    $password = "4muogEVkB/";
    
    // Try connecting without database first
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Basic MySQL connection successful\n";
    
    // Check if our database exists
    $dbname = "admission_system";
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    if ($stmt->fetch()) {
        echo "Database '$dbname' exists\n";
        
        // Try connecting to the specific database
        $db_pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $db_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connection to '$dbname' successful\n";
        
        // Check tables
        $tables = ['users', 'applications'];
        foreach ($tables as $table) {
            $stmt = $db_pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "Table '$table' exists\n";
                // Show row count
                $count = $db_pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                echo "  - Row count: $count\n";
                
                if ($table === 'users') {
                    // Check admin user
                    $stmt = $db_pdo->query("SELECT username, is_active FROM users WHERE username = 'admin'");
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($admin) {
                        echo "  - Admin user exists (active: {$admin['is_active']})\n";
                    } else {
                        echo "  - Admin user does NOT exist\n";
                    }
                }
            } else {
                echo "Table '$table' does NOT exist\n";
            }
        }
    } else {
        echo "Database '$dbname' does NOT exist\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?> 