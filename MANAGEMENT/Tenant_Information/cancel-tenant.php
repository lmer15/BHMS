<?php
include('../../DATABASE/dbConnector.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_id = $_POST['tenant_id'];  // tenant_id is the tc_id from tenant_details
    $room_number = $_POST['room_number'];

    // Begin a transaction to ensure all operations are executed together
    $conn->begin_transaction();

    try {
        // First, delete the related booking (Booking has a foreign key reference to tenant_details)
        $delete_booking = "DELETE FROM booking WHERE tenant_id = ?";
        $stmt = $conn->prepare($delete_booking);
        $stmt->bind_param("i", $tenant_id);
        if (!$stmt->execute()) {
            throw new Exception('Error deleting booking records.');
        }

        // Now, fetch the user_id from tenant_details to delete the user account
        $fetch_user_id = "SELECT id FROM tenant_details WHERE tc_id = ?";
        $stmt = $conn->prepare($fetch_user_id);
        $stmt->bind_param("i", $tenant_id);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        // Now delete from tenant_details (tenant_details.tc_id is used for deletion)
        $delete_tenant_details = "DELETE FROM tenant_details WHERE tc_id = ?";
        $stmt = $conn->prepare($delete_tenant_details);
        $stmt->bind_param("i", $tenant_id);
        if (!$stmt->execute()) {
            throw new Exception('Error deleting from tenant_details.');
        }


        if ($user_id) {
            // Now delete from user_accounts using the id obtained from tenant_details
            $delete_user_account = "DELETE FROM user_accounts WHERE id = ?";
            $stmt = $conn->prepare($delete_user_account);
            $stmt->bind_param("i", $user_id);  // Use the id from tenant_details
            if (!$stmt->execute()) {
                throw new Exception('Error deleting from user_accounts.');
            }
        } else {
            throw new Exception('No associated user account found.');
        }

        // Update room status to available (this does not have any foreign key constraints)
        $update_room = "UPDATE room SET room_status = 'available' WHERE room_number = ?";
        $stmt = $conn->prepare($update_room);
        $stmt->bind_param("s", $room_number);
        if (!$stmt->execute()) {
            throw new Exception('Error updating room status.');
        }

        // Commit transaction
        $conn->commit();

        // Return success response
        echo json_encode([
            'status' => 'success',
            'message' => 'Tenant cancelled successfully!'
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();

        // Return error response
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

$conn->close();
?>
