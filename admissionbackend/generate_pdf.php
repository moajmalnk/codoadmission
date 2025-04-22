<?php
// Start output buffering
ob_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable display_errors to prevent output

require_once 'config.php';

// Check if vendor directory exists
if (!file_exists(__DIR__ . '/vendor')) {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode([
        'success' => false,
        'message' => 'Vendor directory not found. Please run: composer install'
    ]));
}

// Check if autoload exists
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode([
        'success' => false,
        'message' => 'Autoload file not found. Please run: composer install'
    ]));
}

require_once __DIR__ . '/vendor/autoload.php';

// Check if TCPDF class exists
if (!class_exists('TCPDF')) {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode([
        'success' => false,
        'message' => 'TCPDF class not found. Please run: composer require tecnickcom/tcpdf'
    ]));
}

use TCPDF;

// Handle only GET requests with application_id parameter
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || empty($_GET['application_id'])) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Application ID is required']));
}

try {
    // Get application data from database
    $conn = getConnection();
    
    // Debug: Log the application ID we're looking for
    error_log("Searching for application ID: " . $_GET['application_id']);
    
    $stmt = $conn->prepare("SELECT * FROM admissions WHERE application_id = :application_id");
    $stmt->bindParam(':application_id', $_GET['application_id']);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: Log if we found the application
    if (!$data) {
        throw new Exception('Application not found in database');
    }

    // Clean any output that might have been generated
    ob_clean();

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('CODO Admissions');
    $pdf->SetAuthor('CODO AI Innovations');
    $pdf->SetTitle('Admission Application - ' . $data['application_id']);

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set margins - optimized for better layout
    $pdf->SetMargins(15, 10, 15);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Set colors
    $primaryColor = array(0, 183, 100);    // #00b764
    $secondaryColor = array(0, 32, 63);     // #00203f
    $textColor = array(45, 52, 54);         // #2d3436
    $lightGray = array(240, 243, 255);      // #f0f3ff
    $borderColor = array(229, 231, 235);    // #e5e7eb

    // Add decorative header background with proper height
    $pdf->Rect(0, 0, $pdf->getPageWidth(), 40, 'F', array(), $lightGray);
    
    // Add subtle border at the bottom of header
    $pdf->SetLineWidth(0.1);
    $pdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
    $pdf->Line(0, 40, $pdf->getPageWidth(), 40);

    // Add logo with precise positioning
    $logoPath = 'https://codoacademy.com/uploads/system/0623b9b92a325936b0a00502d95c22e6.png';
    try {
        $pdf->Image($logoPath, 20, 18, 25); // Adjusted size and position
    } catch (Exception $e) {
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->SetXY(20, 10);
        $pdf->Cell(25, 10, 'CODO Academy', 0, 1, 'L');
    }

    // Add date and application ID box with improved alignment
    $pdf->RoundedRect(145, 10, 50, 18, 1, '1111', 'F', array(), $secondaryColor);
    
    // Add date and ID with better spacing
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetXY(148, 12);
    $pdf->Cell(44, 5, 'Date: ' . date('d/m/Y'), 0, 1, 'L');
    $pdf->SetXY(148, 19);
    $pdf->Cell(44, 5, 'App ID: ' . $data['application_id'], 0, 1, 'L');

    // Add title with proper spacing
    $pdf->Ln(30);
    $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(0, 8, 'Web Design & Development', 0, 1, 'C');
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 8, 'Admission Form', 0, 1, 'C');
    $pdf->Ln(8);

    // Function to add section header with improved styling
    function addSectionHeader($pdf, $title, $lightGray, $secondaryColor) {
        $pdf->Ln(4);
        $pdf->SetFillColor($lightGray[0], $lightGray[1], $lightGray[2]);
        $pdf->RoundedRect(15, $pdf->GetY(), $pdf->getPageWidth() - 30, 7, 1.5, '1111', 'F');
        $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 7, '   ' . $title, 0, 1, 'L');
        $pdf->SetTextColor(45, 52, 54);
        $pdf->Ln(3);
    }

    // Function to add two-column data with improved alignment
    function addTwoColumnData($pdf, $label1, $value1, $label2 = '', $value2 = '') {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(45, 6, $label1, 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(50, 6, $value1, 0, 0);
        
        if ($label2) {
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(40, 6, $label2, 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $value2, 0, 1);
        } else {
            $pdf->Ln();
        }
    }

    // Personal Information Section
    addSectionHeader($pdf, 'Personal Information', $lightGray, $secondaryColor);
    addTwoColumnData($pdf, 'Applicant Name:', $data['applicant_name']);
    addTwoColumnData($pdf, 'Date of Birth:', $data['dob'], 'Gender:', ucfirst($data['gender']));

    // Course Information Section
    addSectionHeader($pdf, 'Course Details', $lightGray, $secondaryColor);
    addTwoColumnData($pdf, 'Batch No:', $data['batch_no'], 'Date of Joining:', $data['date_of_joining']);

    // Contact Information Section
    addSectionHeader($pdf, 'Contact Information', $lightGray, $secondaryColor);
    addTwoColumnData($pdf, 'Phone No:', $data['phone'], 'Email:', $data['email']);

    // Family Information Section
    addSectionHeader($pdf, 'Family Information', $lightGray, $secondaryColor);
    addTwoColumnData($pdf, "Father's Name:", $data['father_name'], 'Occupation:', $data['father_occupation']);
    addTwoColumnData($pdf, "Mother's Name:", $data['mother_name'], "Guardian's Phone:", $data['guardian_phone']);

    // Address Section with improved spacing
    addSectionHeader($pdf, 'Address & Additional Information', $lightGray, $secondaryColor);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(30, 6, 'Address:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, $data['address'], 0, 'L');

    $pdf->Ln(3);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, 'Technical Background:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 6, 'Prior Web Development Experience: ' . ucfirst($data['technical_background']), 0, 1);

    // Declaration section with improved styling
    $pdf->Ln(4);
    $pdf->SetFillColor($lightGray[0], $lightGray[1], $lightGray[2]);
    $pdf->RoundedRect(15, $pdf->GetY(), $pdf->getPageWidth() - 30, 28, 1.5, '1111', 'F');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, '   Declaration', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $declaration = "I, " . $data['applicant_name'] . ", confirm that the information in this form is accurate to my knowledge. " .
                  "Providing false information may lead to rejection. I acknowledge that joining this mentorship program is a privilege " .
                  "and commit to following its rules. I understand that my behavior is subject to review, and any violation may result " .
                  "in termination.";
    $pdf->MultiCell(0, 5, $declaration, 0, 'L');

    // Footer section with improved positioning
    $footerHeight = 18;
    $currentY = $pdf->GetY();
    $pageHeight = $pdf->getPageHeight();
    $bottomMargin = 18;
    
    if ($currentY < ($pageHeight - $footerHeight - $bottomMargin)) {
        $pdf->SetY($pageHeight - $footerHeight - $bottomMargin);
    } else {
        $pdf->Ln(8);
    }

    // Footer design with improved styling
    $pdf->SetLineWidth(0.5);
    $pdf->SetDrawColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Line(0, $pdf->GetY(), $pdf->getPageWidth(), $pdf->GetY());
    
    $pdf->SetDrawColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->Line($pdf->getPageWidth() * 0.75, $pdf->GetY(), $pdf->getPageWidth(), $pdf->GetY());

    $pdf->Ln(4);
    $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 5, 'CODO AI Innovations', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
    $pdf->Cell(0, 4, 'Paravath Arcade, 2nd Floor, opp. Budget', 0, 1, 'C');
    $pdf->Cell(0, 4, 'Hypermarket, Varangode, Malappuram, Kerala 676519', 0, 1, 'C');
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(0, 4, 'info@codoacademy.com | www.codoacademy.com', 0, 1, 'C');

    // Clean output buffer before sending PDF
    ob_clean();

    // Set PDF headers
    header('Content-Type: application/pdf');
    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    
    // Set filename
    $filename = 'CODO_Admission_' . $data['application_id'] . '.pdf';
    header('Content-Disposition: attachment; filename="'.$filename.'"');

    // Output PDF
    $pdf->Output($filename, 'D');
    
    // End the script
    exit();

} catch (Exception $e) {
    // Clean any output
    ob_clean();
    
    error_log("PDF Generation Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error generating PDF: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
// No closing PHP tag to prevent accidental whitespace 