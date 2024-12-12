<?php
include('../../DATABASE/dbConnector.php');

$sql = "SELECT b.booking_id, b.booking_start_date, b.booking_end_date, b.status,
               t.fname, t.lname, t.email_address, t.contact_number, r.room_number, t.tc_id
        FROM booking b
        JOIN tenant_details t ON b.tenant_id = t.tc_id
        JOIN room r ON b.room_id = r.room_id
        JOIN user_accounts u ON t.id = u.id
        WHERE u.status = 'pending'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $guest_accounts = [];
    while ($row = $result->fetch_assoc()) {
        $guest_accounts[] = $row;
    }
    echo json_encode($guest_accounts);
} else {
    echo json_encode([]);
}

$conn->close();
?>
