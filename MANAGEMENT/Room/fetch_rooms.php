<?php
include('../../DATABASE/dbConnector.php');

// Get the status from the request (default to 'available' if not set)
$status = $_GET['status'] ?? 'available';

// Query to fetch rooms based on status
$sql = "SELECT * FROM room WHERE room_status = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the rooms
$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

$stmt->close();
$conn->close();

// Return the result as JSON
echo json_encode($rooms);
?>
