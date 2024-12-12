<?php
include_once '../../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query to fetch maintenance request details
    $query = "SELECT mr.tenant_id, mr.item_name, mr.date_requested, td.fname, td.lname, r.room_number
              FROM maintenance_requests mr
              JOIN tenant_details td ON mr.tenant_id = td.tc_id
              JOIN booking b ON td.tc_id = b.tenant_id
              JOIN room r ON b.room_id = r.room_id
              WHERE mr.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tenant_id = $row['tenant_id'];
        $tenant_name = $row['fname'] . ' ' . $row['lname'];
        $room_number = $row['room_number'];
        $item_name = $row['item_name'];
        $date_requested = $row['date_requested'];

        // Delete the maintenance request from the database
        $delete_query = "DELETE FROM maintenance_requests WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();

        if ($delete_stmt->affected_rows > 0) {
            // Construct the notification message for deletion
            $notification_message = "{$tenant_name} from {$room_number} deleted his request to fix his '{$item_name}' that was submitted on {$date_requested}.";
            $notification_type = "maintenance";
            $status = "unseen";

            // Insert a new notification for the deletion of the request
            $insert_notification_query = "INSERT INTO notifications (user, type, message, status) VALUES (?, ?, ?, ?)";
            $insert_notification_stmt = $conn->prepare($insert_notification_query);
            $insert_notification_stmt->bind_param("isss", $tenant_id, $notification_type, $notification_message, $status);
            $insert_notification_stmt->execute();

            // Respond with success or failure message
            if ($insert_notification_stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Maintenance request deleted and notification added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Maintenance request deleted, but failed to add notification.']);
            }
        } else {
            // If no maintenance request was deleted
            echo json_encode(['success' => false, 'message' => 'No such maintenance request found.']);
        }

        $delete_stmt->close();
        $insert_notification_stmt->close();
    } else {
        // If no maintenance request was found
        echo json_encode(['success' => false, 'message' => 'No such maintenance request found.']);
    }

    $stmt->close();
} else {
    // If no ID is provided
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
