<?php
require_once 'config.php';

// Set CORS headers
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Verify authentication
$headers = getallheaders();
$auth_header = isset($headers['Authorization']) ? $headers['Authorization'] : '';
if (!$auth_header || !preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (!isset($_GET['id'])) {
            throw new Exception('Application ID parameter is required');
        }

        $applicationId = $_GET['id'];
        
        // Get database connection
        $conn = getConnection();
        
        // Check if ID exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM admissions WHERE application_id = :id");
        $stmt->bindParam(':id', $applicationId);
        $stmt->execute();
        
        $exists = $stmt->fetchColumn() > 0;
        
        echo json_encode([
            'success' => true,
            'exists' => $exists
        ]);
        
    } catch(PDOException $e) {
        error_log("Database error in verify_id.php: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error occurred',
            'debug' => $e->getMessage()
        ]);
    } catch(Exception $e) {
        error_log("Error in verify_id.php: " . $e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Only GET requests are accepted.'
    ]);
}
?> 