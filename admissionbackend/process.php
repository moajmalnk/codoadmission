<?php
// Prevent any output before headers
ob_start();

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

// For GET requests, redirect to the form page
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header('Location: http://localhost/codoadmission/admissionfrontend/');
    exit();
}

try {
    // Debug logging
    error_log("POST data received: " . print_r($_POST, true));
    error_log("Files data received: " . print_r($_FILES, true));
    
    // Required fields validation
    $required_fields = ['applicationId', 'applicantName', 'dob', 'gender', 'phone', 'email', 'address'];
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        throw new Exception("Required fields missing: " . implode(', ', $missing_fields));
    }

    // Validate gender
    if (!in_array($_POST['gender'], ['male', 'female'])) {
        throw new Exception('Invalid gender value');
    }

    // Validate technical_background if provided
    if (isset($_POST['technicalBackground']) && !in_array($_POST['technicalBackground'], ['yes', 'no'])) {
        throw new Exception('Invalid technical background value');
    }

    // Validate date formats
    $date_fields = ['dob', 'dateOfJoining'];
    foreach ($date_fields as $field) {
        if (!empty($_POST[$field])) {
            $date = date_create_from_format('Y-m-d', $_POST[$field]);
            if (!$date) {
                throw new Exception("Invalid date format for $field");
            }
        }
    }

    // Get database connection
    $conn = getConnection();
    
    // Begin transaction
    $conn->beginTransaction();

    try {
        // Check if application_id already exists
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM admissions WHERE application_id = ?");
        $check_stmt->execute([$_POST['applicationId']]);
        if ($check_stmt->fetchColumn() > 0) {
            throw new Exception("Application ID already exists");
        }

        // Prepare SQL statement
        $sql = "INSERT INTO admissions (
            application_id,
            applicant_name,
            batch_no,
            date_of_joining,
            dob,
            gender,
            phone,
            email,
            education,
            father_name,
            father_occupation,
            mother_name,
            guardian_phone,
            address,
            technical_background
        ) VALUES (
            :application_id,
            :name,
            :batch,
            :joining,
            :dob,
            :gender,
            :phone,
            :email,
            :education,
            :father_name,
            :father_occupation,
            :mother_name,
            :guardian_phone,
            :address,
            :tech
        )";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters with proper type handling
        $params = [
            ':application_id' => $_POST['applicationId'],
            ':name' => $_POST['applicantName'],
            ':batch' => $_POST['batchNo'] ?? null,
            ':joining' => $_POST['dateOfJoining'] ?? null,
            ':dob' => $_POST['dob'],
            ':gender' => $_POST['gender'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':education' => $_POST['education'] ?? null,
            ':father_name' => $_POST['fatherName'] ?? null,
            ':father_occupation' => $_POST['fatherOccupation'] ?? null,
            ':mother_name' => $_POST['motherName'] ?? null,
            ':guardian_phone' => $_POST['guardianPhone'] ?? null,
            ':address' => $_POST['address'],
            ':tech' => $_POST['technicalBackground'] ?? 'no'
        ];

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $conn->commit();
        
        // Clear any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Application submitted successfully!',
            'applicationId' => $_POST['applicationId']
        ]);
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
} catch(PDOException $e) {
    error_log("Database error in process.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred',
        'debug' => $e->getMessage()
    ]);
} catch(Exception $e) {
    error_log("Error in process.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>