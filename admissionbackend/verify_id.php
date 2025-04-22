<?php
// Prevent any output before headers
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://admission.moajmalnk.in');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

try {
    // Get database connection
    $conn = getConnection();

    // Get ID from URL
    $id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(['error' => 'No ID provided']));

    // Prepare query
    $query = "SELECT COUNT(*) as count FROM admissions WHERE application_id = :id";
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':id', $id);
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return response
    echo json_encode([
        'exists' => $result['count'] > 0
    ]);

} catch(PDOException $e) {
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} 