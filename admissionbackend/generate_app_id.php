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
        $applicationId = sprintf("CODO/%s/%03d", $monthYear, $nextCount);
        
        return $applicationId;
        
    } catch(PDOException $e) {
        error_log("Error generating application ID: " . $e->getMessage());
        return null;
    }
}

try {
    // Generate the application ID
    $appId = generateApplicationId();
    
    if ($appId === null) {
        throw new Exception('Failed to generate application ID');
    }
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send JSON response
    echo json_encode([
        'success' => true,
        'applicationId' => $appId,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
    
} catch (Exception $e) {
    // Log the error
    error_log("Error in generate_app_id.php: " . $e->getMessage());
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Send error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to generate application ID',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_THROW_ON_ERROR);
}

// Ensure no additional output and flush the response
if (ob_get_level()) {
    ob_end_flush();
}
exit();