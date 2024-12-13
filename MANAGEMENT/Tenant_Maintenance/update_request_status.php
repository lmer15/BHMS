<?php

$data = json_decode(file_get_contents("php://input"));
$requestId = $data->id;
$status = $data->status;
$itemName = $data->item_name;

// Check if data exists
if (!$requestId || !$status || !$itemName) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

include_once '../../DATABASE/dbConnector.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Log incoming data
error_log("Updating request ID: $requestId with status: $status");

// Update the status of the maintenance request
$sql = "UPDATE maintenance_requests SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $requestId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}

$stmt->close();
$conn->close();

?>
