<?php
include('../../DATABASE/dbConnector.php');

function sendError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_id = $_POST['tenant_id'];  // tc_id from tenant_details
    $room_number = $_POST['room_number'];

    // Start database transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch user account id using tenant_id (tc_id) from tenant_details
        $tenant_query = "SELECT id FROM tenant_details WHERE tc_id = ?";
        $stmt = $conn->prepare($tenant_query);
        $stmt->bind_param("i", $tenant_id);  
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        // If no user id found, there's an issue
        if (!$user_id) {
            sendError('Tenant not found.');
        }

        // Fetch room deposit
        $room_query = "SELECT room_deporate FROM room WHERE room_number = ?";
        $stmt = $conn->prepare($room_query);
        $stmt->bind_param("s", $room_number);  
        $stmt->execute();
        $stmt->bind_result($room_deposit);
        $stmt->fetch();
        $stmt->close();

        // Check if deposit exists
        if (!$room_deposit) {
            sendError('Room deposit not found.');
        }

        // Current date
        $current_date = date('Y-m-d');

        // Update user status to 'approved' in user_accounts table
        $update_status = "UPDATE user_accounts SET status = 'approved' WHERE id = ?";
        $stmt = $conn->prepare($update_status);
        $stmt->bind_param("i", $user_id);  // Use user_id here, not tenant_id
        if (!$stmt->execute()) {
            sendError('Failed to approve tenant.');
        }

        // Update booking end date to current date
        $update_booking = "UPDATE booking SET booking_end_date = ? WHERE tenant_id = ?";
        $stmt = $conn->prepare($update_booking);
        $stmt->bind_param("si", $current_date, $tenant_id); 
        if (!$stmt->execute()) {
            sendError('Failed to update booking.');
        }

        // Update room status to 'occupied'
        $update_room = "UPDATE room SET room_status = 'occupied' WHERE room_number = ?";
        $stmt = $conn->prepare($update_room);
        $stmt->bind_param("s", $room_number);  
        if (!$stmt->execute()) {
            sendError('Failed to update room status.');
        }

        // Insert rental payment record
        $rental_query = "INSERT INTO rental_payments (tenant_id, rent_period_start, rent_period_end, total_rent, amount_paid, balance, payment_date, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'paid')";
        $amount_paid = $room_deposit;
        $balance = 0.00;

        $stmt = $conn->prepare($rental_query);
        $stmt->bind_param("issddds", $tenant_id, $current_date, $current_date, $room_deposit, $amount_paid, $balance, $current_date);
        if (!$stmt->execute()) {
            sendError('Failed to record rental payment.');
        }

        // Get payment ID after insertion
        $payment_id = $stmt->insert_id;

        // Insert payment history record
        $payment_query = "INSERT INTO payment_history (payment_id, tenant_id, payment_date, payment_amount, balance, payment_type, payment_status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $payment_status = 'completed';
        $payment_type = 'deposit';

        $stmt = $conn->prepare($payment_query);
        $stmt->bind_param("iisddss", $payment_id, $tenant_id, $current_date, $amount_paid, $balance, $payment_type, $payment_status);
        if (!$stmt->execute()) {
            sendError('Failed to record payment history.');
        }

        // Commit the transaction
        mysqli_commit($conn);

        // Respond with success
        echo json_encode(['status' => 'success', 'message' => 'Tenant approved successfully!']);
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($conn);
        sendError('Transaction failed: ' . $e->getMessage());
    }
}

$conn->close();
?>
