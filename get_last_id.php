<?php
require_once 'config.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the last application ID from the database
    $sql = "SELECT application_id FROM admissions ORDER BY id DESC LIMIT 1";
    $stmt = $conn->query($sql);
    $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'lastId' => $lastId ? $lastId['application_id'] : null
    ]);
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 