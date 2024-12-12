<?php
session_start();
require_once '../../DATABASE/dbConnector.php';

// Enable detailed error reporting for debugging in development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log errors
function log_error($message) {
    // Log errors to a file
    $log_file = '../../logs/errors.log';  // Set your log file path here
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($log_file, "[$timestamp] - $message" . PHP_EOL, FILE_APPEND);
}

// Set header to indicate we're returning JSON
header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['tc_id'])) {
    log_error("User not logged in. Maintenance request attempted.");
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to submit a maintenance request.'
    ]);
    exit();
}

// Ensure form data is valid
if (isset($_POST['item_name'], $_POST['item_desc'])) {
    $tenant_id = $_SESSION['tc_id'];  // Make sure this session variable matches with the database
    $item_name = mysqli_real_escape_string($conn, trim($_POST['item_name']));
    $item_desc = mysqli_real_escape_string($conn, trim($_POST['item_desc']));

    // Insert maintenance request into the database
    $query = "INSERT INTO maintenance_requests (tenant_id, item_name, item_desc) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        log_error("Database query preparation failed: " . $conn->error);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare the database query. Please try again later.'
        ]);
        exit();
    }

    $stmt->bind_param('iss', $tenant_id, $item_name, $item_desc);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Request submitted successfully.'
        ]);
    } else {
        log_error("Failed to submit maintenance request: " . $stmt->error);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit the request. Please try again later.'
        ]);
    }
} else {
    log_error("Invalid input: Missing item name or description.");
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input. Please provide all required fields.'
    ]);
}
?>
