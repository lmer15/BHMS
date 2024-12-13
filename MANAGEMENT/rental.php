<?php
include_once '../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_date = date('Y-m-d H:i:s');
$sql = "SELECT b.booking_id, b.tenant_id, b.room_id, r.rental_rates, b.booking_end_date 
        FROM booking b 
        JOIN room r ON b.room_id = r.room_id 
        WHERE r.room_status = 'occupied'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tenant_id = $row['tenant_id'];
        $rental_rate = $row['rental_rates'];
        $booking_end_date = $row['booking_end_date'];

        // Rent period logic
        $rent_period_sql = "SELECT rent_period_end FROM rental_payments 
                            WHERE tenant_id = ? 
                            ORDER BY rent_period_end DESC 
                            LIMIT 1";
        $rent_period_stmt = $conn->prepare($rent_period_sql);
        $rent_period_stmt->bind_param("i", $tenant_id);
        $rent_period_stmt->execute();
        $rent_period_result = $rent_period_stmt->get_result();

        if ($rent_period_result->num_rows > 0) {
            $rent_period_data = $rent_period_result->fetch_assoc();
            $previous_rent_period_end = $rent_period_data['rent_period_end'];

            if (strtotime($current_date) > strtotime($previous_rent_period_end)) {
                $rent_period_start = date('Y-m-d', strtotime($previous_rent_period_end . ' +1 day'));
                $rent_period_end = date('Y-m-t', strtotime($rent_period_start));

                $check_payment_sql = "SELECT payment_id FROM rental_payments 
                                      WHERE tenant_id = ? 
                                      AND rent_period_start = ? 
                                      AND rent_period_end = ?";
                $check_stmt = $conn->prepare($check_payment_sql);
                $check_stmt->bind_param("iss", $tenant_id, $rent_period_start, $rent_period_end);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows == 0) {
                    $insert_sql = "INSERT INTO rental_payments (tenant_id, rent_period_start, rent_period_end, total_rent, amount_paid, balance, payment_date, status)
                                   VALUES (?, ?, ?, ?, 0, ?, NOW(), 'pending')";

                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("isssd", $tenant_id, $rent_period_start, $rent_period_end, $rental_rate, $rental_rate); // Assuming balance = total_rent initially
                    $stmt->execute();
                }

                // Check if payment due notification already exists
                $notif_check_sql = "SELECT not_id FROM notifications 
                                    WHERE user = ? 
                                    AND type = 'payment_due' 
                                    AND message LIKE ?";
                $notif_check_stmt = $conn->prepare($notif_check_sql);
                $message_pattern = "%$rent_period_start%";
                $notif_check_stmt->bind_param("is", $tenant_id, $message_pattern);
                $notif_check_stmt->execute();
                $notif_check_result = $notif_check_stmt->get_result();

                if ($notif_check_result->num_rows == 0) {
                    $message = "Your payment due in the month of " . date('F', strtotime($rent_period_start)) . " is $" . number_format($rental_rate, 2) . ". Please mind your responsibilities.";
                    $notif_sql = "INSERT INTO notifications (user, type, message, status, created_at, updated_at) 
                                  VALUES (?, 'payment_due', ?, 'unread', NOW(), NOW())";

                    $notif_stmt = $conn->prepare($notif_sql);
                    $notif_stmt->bind_param("is", $tenant_id, $message);
                    $notif_stmt->execute();
                }
            }
        }
        
        $overdue_payment_sql = "SELECT rent_period_end, balance, payment_id, status FROM rental_payments 
                                WHERE tenant_id = ? 
                                AND balance > 0 
                                AND rent_period_end < ?";
        $overdue_payment_stmt = $conn->prepare($overdue_payment_sql);
        $overdue_payment_stmt->bind_param("is", $tenant_id, $current_date);
        $overdue_payment_stmt->execute();
        $overdue_payment_result = $overdue_payment_stmt->get_result();

        if ($overdue_payment_result->num_rows > 0) {
            while ($overdue_payment_data = $overdue_payment_result->fetch_assoc()) {
                $overdue_rent_period_end = $overdue_payment_data['rent_period_end'];
                $overdue_balance = $overdue_payment_data['balance'];
                $payment_id = $overdue_payment_data['payment_id'];
                $status = $overdue_payment_data['status'];

                if ($status !== 'overdue') {
                    // Update payment status to 'overdue'
                    $update_status_sql = "UPDATE rental_payments SET status = 'overdue' WHERE payment_id = ?";
                    $update_stmt = $conn->prepare($update_status_sql);
                    $update_stmt->bind_param("i", $payment_id);
                    $update_stmt->execute();
                }
            }
        }
    }
} else {
    echo "No tenants with occupied rooms found.";
}

$conn->close();
?>
