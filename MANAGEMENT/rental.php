<?php
include_once '../../DATABASE/dbConnector.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tenants with 'Occupied' status
$sql = "SELECT b.booking_id, b.tenant_id, b.room_id, r.rental_rates, b.booking_end_date 
        FROM booking b 
        JOIN room r ON b.room_id = r.room_id 
        WHERE b.status = 'occupied'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tenant_id = $row['tenant_id'];
        $rental_rate = $row['rental_rates'];
        $booking_end_date = $row['booking_end_date'];

        // Assuming rental period starts the day after the booking ends
        $rent_period_start = date('Y-m-d', strtotime($booking_end_date . ' +1 day'));
        $rent_period_end = date('Y-m-t', strtotime($rent_period_start));  // End of the month

        // Insert into rental_payments table
        $insert_sql = "INSERT INTO rental_payments (tenant_id, rent_period_start, rent_period_end, total_rent, amount_paid, balance, payment_date, status)
                       VALUES (?, ?, ?, ?, 0, ?, NOW(), 'pending')";

        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isssd", $tenant_id, $rent_period_start, $rent_period_end, $rental_rate, $rental_rate);  // Assuming balance = total_rent initially
        $stmt->execute();

        // Fetch tenant's email for notification
        $tenant_sql = "SELECT email_address FROM tenant_details WHERE tc_id = ?";
        $tenant_stmt = $conn->prepare($tenant_sql);
        $tenant_stmt->bind_param("i", $tenant_id);
        $tenant_stmt->execute();
        $tenant_result = $tenant_stmt->get_result();
        $tenant_data = $tenant_result->fetch_assoc();
        $tenant_email = $tenant_data['email_address'];

        // Send notification message (Example: Using mail function or an internal notification system)
        $message = "Your payment due in the month of " . date('F', strtotime($rent_period_start)) . " is $" . number_format($rental_rate, 2) . ". Please do mind your responsibilities.";

        $notif_sql = "INSERT INTO notifications (user, type, message, status, created_at, updated_at) 
                      VALUES (?, 'payment_due', ?, 'unread', NOW(), NOW())";
        
        $notif_stmt = $conn->prepare($notif_sql);
        $notif_stmt->bind_param("is", $tenant_id, $message);
        $notif_stmt->execute();
    }
} else {
    echo "No tenants with occupied rooms found.";
}

$conn->close();
?>
