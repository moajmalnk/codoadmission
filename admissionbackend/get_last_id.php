<?php
// Prevent any output before headers
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

try {
    // Get database connection from config.php
    $conn = getConnection();
    
    // Get the last application ID from the database
    $sql = "SELECT application_id FROM admissions ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send JSON response
    echo json_encode([
        'success' => true,
        'lastId' => $lastId ? $lastId['application_id'] : null,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
} catch(PDOException $e) {
    // Log database errors
    error_log("Database error in get_last_id.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
} catch(JsonException $e) {
    // Log JSON encoding errors
    error_log("JSON encoding error in get_last_id.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error processing response',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
} catch(Exception $e) {
    // Log general errors
    error_log("General error in get_last_id.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An unexpected error occurred',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
}

// Ensure no additional output and flush the response
if (ob_get_level()) {
    ob_end_flush();
}
exit();
?> 