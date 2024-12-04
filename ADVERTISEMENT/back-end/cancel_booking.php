<?php
include_once(__DIR__ . "/../../DATABASE/dbConnector.php");

// Get tenant_id from the GET request
$tenant_id = isset($_GET['tenant_id']) ? (int)$_GET['tenant_id'] : 0;

$response = array();

if ($tenant_id == 0) {
    $response['errors'] = ['Invalid tenant ID.'];
    echo json_encode($response);
    exit();
}

// Start the transaction
mysqli_begin_transaction($conn);

try {
    // Fetch the booking and room details for the given tenant_id
    $query = "SELECT b.room_id, td.tc_id, ua.id FROM booking b
              JOIN tenant_details td ON b.tenant_id = td.tc_id
              JOIN user_accounts ua ON td.id = ua.id
              WHERE b.tenant_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        throw new Exception("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $tenant_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $room_id = $row['room_id'];
        $tenant_details_id = $row['id']; // The id from tenant_details table
        $user_account_id = $row['id'];  // The id from user_accounts table

        // Delete the booking record
        $deleteBookingQuery = "DELETE FROM booking WHERE tenant_id = ?";
        $stmtDeleteBooking = mysqli_prepare($conn, $deleteBookingQuery);
        mysqli_stmt_bind_param($stmtDeleteBooking, "i", $tenant_id);
        if (!mysqli_stmt_execute($stmtDeleteBooking)) {
            throw new Exception("Failed to delete booking.");
        }

        // Delete the tenant details
        $deleteTenantQuery = "DELETE FROM tenant_details WHERE id = ?";
        $stmtDeleteTenant = mysqli_prepare($conn, $deleteTenantQuery);
        mysqli_stmt_bind_param($stmtDeleteTenant, "i", $tenant_details_id);
        if (!mysqli_stmt_execute($stmtDeleteTenant)) {
            throw new Exception("Failed to delete tenant details.");
        }

        // Delete the user account
        $deleteUserQuery = "DELETE FROM user_accounts WHERE id = ?";
        $stmtDeleteUser = mysqli_prepare($conn, $deleteUserQuery);
        mysqli_stmt_bind_param($stmtDeleteUser, "i", $user_account_id);
        if (!mysqli_stmt_execute($stmtDeleteUser)) {
            throw new Exception("Failed to delete user account.");
        }

        // Revert room status to available
        $updateRoomQuery = "UPDATE room SET room_status = 'available' WHERE room_id = ?";
        $stmtUpdateRoom = mysqli_prepare($conn, $updateRoomQuery);
        mysqli_stmt_bind_param($stmtUpdateRoom, "i", $room_id);
        if (!mysqli_stmt_execute($stmtUpdateRoom)) {
            throw new Exception("Failed to update room status.");
        }

        // Commit transaction
        mysqli_commit($conn);

        // Return success response
        $response['success'] = 'Booking cancelled successfully!';
        echo json_encode($response);
        exit();
    } else {
        throw new Exception("Booking not found for the tenant.");
    }

} catch (Exception $e) {
    // Rollback the transaction in case of error
    mysqli_rollback($conn);
    $response['errors'] = [$e->getMessage()];
    echo json_encode($response);
    exit();
}
?>
