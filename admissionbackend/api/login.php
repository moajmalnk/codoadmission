<?php
// Add CORS headers
header('Access-Control-Allow-Origin: https://admission.moajmalnk.in');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Get POST data
    $rawData = file_get_contents('php://input');
    error_log("Received raw data: " . $rawData);
    
    $data = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }
    
    if (!isset($data['username']) || !isset($data['password'])) {
        throw new Exception('Username and password are required');
    }
    
    $username = $data['username'];
    $password = $data['password'];
    
    error_log("Attempting login for username: " . $username);
    
    // Database configuration
    $host = "localhost";
    $dbname = "u262074081_admission";
    $dbusername = "u262074081_admission";
    $dbpassword = "4muogEVkB/";
    
    // Create database connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    $pdo = new PDO($dsn, $dbusername, $dbpassword, $options);
    
    // Get user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        error_log("User not found: " . $username);
        throw new Exception('Invalid username or password');
    }
    
    error_log("Found user: " . $username . ", attempting password verification");
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        error_log("Password verification successful for user: " . $username);
        
        // Update last login time
        $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->execute([$user['id']]);
        
        // Generate token
        $token = bin2hex(random_bytes(32));
        
        $response = [
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ],
            'token' => $token
        ];
        
        http_response_code(200);
        echo json_encode($response);
    } else {
        error_log("Password verification failed for user: " . $username);
        error_log("Stored hash: " . $user['password']);
        throw new Exception('Invalid username or password');
    }
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
} catch(Exception $e) {
    error_log("Login error: " . $e->getMessage());
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 