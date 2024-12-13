<?php
include('../../DATABASE/dbConnector.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = $_GET['room_number'];
    $new_status = $_GET['new_status'];

    if (in_array($new_status, ['available', 'under-maintenance'])) {
        // Update the room status in the database
        $update_query = "UPDATE room SET room_status = ? WHERE room_number = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $new_status, $room_number);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update room status.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
    }
}
?>
