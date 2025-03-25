<?php
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