<?php
include('../../DATABASE/dbConnector.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_id = $_POST['tenant_id'];
    $room_number = $_POST['room_number'];

    $delete_user_account = "DELETE FROM user_accounts WHERE id = ?";
    $stmt = $conn->prepare($delete_user_account);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();

    $delete_tenant_details = "DELETE FROM tenant_details WHERE tc_id = ?";
    $stmt = $conn->prepare($delete_tenant_details);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();

    $delete_booking = "DELETE FROM booking WHERE tenant_id = ?";
    $stmt = $conn->prepare($delete_booking);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();

    $update_room = "UPDATE room SET room_status = 'available' WHERE room_number = ?";
    $stmt = $conn->prepare($update_room);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();

    echo "Tenant cancelled successfully!";
}

$conn->close();
?>
