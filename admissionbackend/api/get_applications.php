<?php
// Prevent any output before headers
ob_start();

require_once 'cors_handler.php';
require_once '../config.php';

try {
    $conn = getConnection();
    
    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admissions ORDER BY created_at DESC");
    $stmt->execute();
    
    // Fetch all applications
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Return success response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $applications
    ]);
    
} catch(PDOException $e) {
    error_log("Database error in get_applications.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch applications',
        'error' => $e->getMessage()
    ]);
} catch(Exception $e) {
    error_log("Error in get_applications.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred',
        'error' => $e->getMessage()
    ]);
}

// Ensure all output is flushed
if (ob_get_level()) {
    ob_end_flush();
}
exit();
?> 