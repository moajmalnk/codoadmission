<?php
// Prevent any output before headers
ob_start();

// Get the origin
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Allow from any origin
if ($origin) {
    header("Access-Control-Allow-Origin: $origin");
}

// Other CORS headers
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 3600');
header('Content-Type: application/json; charset=UTF-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    http_response_code(200);
    exit();
}

// Clear any output buffers before returning
while (ob_get_level()) {
    ob_end_clean();
}

// Start fresh output buffer
ob_start();
?> 