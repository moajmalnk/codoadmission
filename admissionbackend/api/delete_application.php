<?php
require_once 'cors_handler.php';
require_once '../config.php';

try {
    // Get application ID from URL parameter
    $application_id = $_GET['id'] ?? null;
    
    if (!$application_id) {
        throw new Exception("Application ID is required");
    }

    $conn = getConnection();

    // Delete the application
    $stmt = $conn->prepare("DELETE FROM admissions WHERE application_id = ?");
    
    if ($stmt->execute([$application_id])) {
        $rowCount = $stmt->rowCount();
        
        if ($rowCount > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Application deleted successfully'
            ]);
        } else {
            throw new Exception("Application not found");
        }
    } else {
        throw new Exception("Failed to delete application");
    }

} catch (PDOException $e) {
    error_log("Delete Application Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("Delete Application Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 