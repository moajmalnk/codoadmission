<?php
// Prevent any output before headers
ob_start();

require_once 'api/cors_handler.php';
require_once 'config.php';

try {
    // Get the application ID from query string
    $applicationId = $_GET['id'] ?? '';
    
    if (empty($applicationId)) {
        throw new Exception('Application ID is required');
    }
    
    // Get database connection
    $conn = getConnection();
    
    // Check if ID exists
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM admissions WHERE application_id = ?");
    $stmt->execute([$applicationId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send JSON response
    echo json_encode([
        'success' => true,
        'exists' => $result['count'] > 0,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
    
} catch(PDOException $e) {
    error_log("Database error in verify_id.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
} catch(Exception $e) {
    error_log("Error in verify_id.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Validation error',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
}

// Ensure all output is flushed
if (ob_get_level()) {
    ob_end_flush();
}
exit(); 