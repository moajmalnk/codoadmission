<?php
require_once 'config.php';
require_once 'mail_config.php';
require __DIR__ . '/vendor/autoload.php';  // Use Composer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Remove these manual requires since we're using Composer's autoloader
// require __DIR__ . '/PHPMailer/src/Exception.php';
// require __DIR__ . '/PHPMailer/src/PHPMailer.php';
// require __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Debug logging
        error_log("POST data received: " . print_r($_POST, true));
        
        // Validate application ID
        if (!isset($_POST['applicationId']) || empty($_POST['applicationId'])) {
            error_log("Application ID is missing or empty");
            throw new Exception('Application ID cannot be empty');
        }

        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            technical_background,
            signature_path
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
            :tech,
            :signature_path
        )";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':application_id', $_POST['applicationId']);
        $stmt->bindParam(':name', $_POST['applicantName']);
        $stmt->bindParam(':batch', $_POST['batchNo']);
        $stmt->bindParam(':joining', $_POST['dateOfJoining']);
        $stmt->bindParam(':dob', $_POST['dob']);
        $stmt->bindParam(':gender', $_POST['gender']);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':education', $_POST['education']);
        $stmt->bindParam(':father_name', $_POST['fatherName']);
        $stmt->bindParam(':father_occupation', $_POST['fatherOccupation']);
        $stmt->bindParam(':mother_name', $_POST['motherName']);
        $stmt->bindParam(':guardian_phone', $_POST['guardianPhone']);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->bindParam(':tech', $_POST['technicalBackground']);
        $stmt->bindParam(':signature_path', $_POST['signature']);

        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
    } catch(Exception $e) {
        error_log("Error in process.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>