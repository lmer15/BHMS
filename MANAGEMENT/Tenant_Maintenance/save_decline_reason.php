<?php
include '../../DATABASE/dbConnector.php'; 

// Read the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the required data is present
if (isset($data['id'], $data['reason'], $data['item_name'], $data['tenant_name'])) {
    $requestId = $data['id'];
    $reason = htmlspecialchars($data['reason']);
    $itemName = htmlspecialchars($data['item_name']);
    $tenantName = htmlspecialchars($data['tenant_name']);

    try {
        // Update the maintenance request status to 'Declined'
        $updateQuery = "UPDATE maintenance_requests SET status = 'Declined' WHERE id = :id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
        $stmt->execute();

        // Create the notification message
        $notificationMessage = "The maintenance request for '$itemName' in room $requestId has been declined by management. Reason: $reason";

        // Insert the notification into the notifications table
        $notificationQuery = "INSERT INTO notifications (message, user_id, created_at) 
                              VALUES (:message, 
                                      (SELECT user_id FROM tenants WHERE tenant_name = :tenant_name LIMIT 1), NOW())";
        $stmt = $pdo->prepare($notificationQuery);
        $stmt->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
        $stmt->bindParam(':tenant_name', $tenantName, PDO::PARAM_STR);
        $stmt->execute();

        // Send a success response
        echo json_encode(['success' => true, 'message' => 'Request declined and notification saved']);
    } catch (PDOException $e) {
        // Handle any errors and return an error message
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    // If required data is missing, return an error message
    echo json_encode(['success' => false, 'message' => 'Required data not found']);
}
?>
