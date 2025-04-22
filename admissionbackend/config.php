<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'u262074081_admission');
define('DB_USER', 'u262074081_admission');
define('DB_PASS', '4muogEVkB/');

// CORS and security headers
header('Access-Control-Allow-Origin: https://admission.moajmalnk.in');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// API Base URLs
define('FRONTEND_URL', 'https://admission.moajmalnk.in');
define('BACKEND_URL', 'https://admissionbackend.moajmalnk.in');

// Time zone
date_default_timezone_set('Asia/Kolkata');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}

// Security functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function verifyToken($token) {
    // Add your token verification logic here
    return true; // Placeholder
}

// Response functions
function sendResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sendError($message, $status = 400) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode(['error' => $message]);
    exit;
}

// Utility functions
function generateApplicationId() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(application_id, 6) AS UNSIGNED)) as last_num FROM applications");
        $result = $stmt->fetch();
        $lastNum = $result['last_num'] ?? 0;
        $newNum = $lastNum + 1;
        return sprintf("CODO/%04d", $newNum);
    } catch (PDOException $e) {
        error_log("Error generating application ID: " . $e->getMessage());
        return null;
    }
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Session configuration
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'cookie_domain' => '.moajmalnk.in'
]);

?> 