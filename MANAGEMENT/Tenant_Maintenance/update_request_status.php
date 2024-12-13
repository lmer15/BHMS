<?php

// update_request_status.php

include_once '../../DATABASE/dbConnector.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id']) || empty($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit;
}

$requestId = $data['id'];
$newStatus = $data['status']; 

$sql = "UPDATE maintenance_requests SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newStatus, $requestId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $sql = "SELECT tenant_id FROM maintenance_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();
    $tenant = $result->fetch_assoc();

    $tenantId = $tenant['tenant_id']; 
    $sql = "SELECT item_name, item_desc FROM maintenance_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();
    $maintenance = $result->fetch_assoc();


    createNotification($requestId, $maintenance['item_name'], $tenantId, $newStatus);

    echo json_encode(['success' => true, 'message' => 'Maintenance request updated and notification created']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update maintenance request status']);
}

$stmt->close();
$conn->close();


?>
