<?php
include('../../DATABASE/dbConnector.php');

$sql = "SELECT t.fname, t.lname, t.email_address, t.contact_number, 
               t.religion, t.nationality, t.occupation, r.room_number, 
               b.booking_end_date
        FROM booking b
        JOIN tenant_details t ON b.tenant_id = t.tc_id
        JOIN room r ON b.room_id = r.room_id
        JOIN user_accounts u ON t.id = u.id
        WHERE u.status = 'approved'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $active_tenants = [];
    while ($row = $result->fetch_assoc()) {
        $active_tenants[] = $row;
    }
    echo json_encode($active_tenants);
} else {
    echo json_encode([]);
}

$conn->close();
?>
