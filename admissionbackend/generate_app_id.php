<?php
// Prevent any output before headers and turn off error display
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Start output buffering
ob_start();

// Set headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

function generateApplicationId() {
    // Connect to database
    require_once 'config.php';
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get current date components
        $month = date('m');
        $year = date('y');
        $monthYear = $month . $year;
        
        // Get the latest counter for this month/year
        $stmt = $conn->prepare("
            SELECT MAX(CAST(SUBSTRING_INDEX(application_id, '/', -1) AS UNSIGNED)) as last_count
            FROM admissions 
            WHERE application_id LIKE :prefix
        ");
        
        $prefix = "CODO/{$monthYear}/%";
        $stmt->bindParam(':prefix', $prefix);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextCount = ($result['last_count'] ?? 0) + 1;
        
        // Format the application ID
        return sprintf("CODO/%s/%03d", $monthYear, $nextCount);
        
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        throw new Exception("Database error occurred");
    }
}

// Clear any existing output
ob_clean();

try {
    $appId = generateApplicationId();
    
    if (empty($appId)) {
        throw new Exception("Failed to generate application ID");
    }
    
    echo json_encode([
        'success' => true,
        'applicationId' => $appId,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    error_log("Error in generate_app_id.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to generate application ID',
        'message' => $e->getMessage()
    ]);
}

// End output buffering and send response
ob_end_flush();
exit();