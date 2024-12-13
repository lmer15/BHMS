<?php
$data = json_decode(file_get_contents("php://input"));
$requestId = $data->request_id;
$user = $data->user;
$type = $data->type;
$message = $data->message;
$status = $data->status;

include_once '../../DATABASE/dbConnector.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Check the input data
// var_dump($requestId, $user, $type, $message, $status); // Uncomment for debugging

// Insert the notification into the notifications table
$sql = "INSERT INTO notifications (request_id, user, type, message, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Prepare statement failed, output the error
    die('MySQL prepare failed: ' . $conn->error);
}

$stmt->bind_param("issss", $requestId, $user, $type, $message, $status);

if ($stmt->execute()) {
    // Success
    echo json_encode(['success' => true]);
} else {
    // Error: Output the error message for debugging
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
