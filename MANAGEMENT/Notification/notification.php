<?php

include_once'../../DATABASE/dbConnector.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_date = date('Y-m-d H:i:s');

// Fetch overdue payments with tenant name and room number
$overdue_sql = "SELECT rp.tenant_id, rp.rent_period_end, rp.balance, t.fname, t.lname, r.room_number 
                FROM rental_payments rp
                JOIN tenant_details t ON rp.tenant_id = t.tc_id
                JOIN booking b ON rp.tenant_id = b.tenant_id
                JOIN room r ON b.room_id = r.room_id
                WHERE rp.balance > 0 AND rp.rent_period_end < ?";
$overdue_stmt = $conn->prepare($overdue_sql);
$overdue_stmt->bind_param("s", $current_date);
$overdue_stmt->execute();
$overdue_result = $overdue_stmt->get_result();

$maintenance_sql = "SELECT message, created_at FROM notifications WHERE type = 'maintenance' ORDER BY created_at DESC";
$maintenance_result = $conn->query($maintenance_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="notification.css">
</head>
<body>

    <div class="wrapper">
        <div class="notif-wrapper">
            <h1>Notifications</h1>

            <?php
            // Display overdue payment notifications
            if ($overdue_result->num_rows > 0) {
                while ($overdue = $overdue_result->fetch_assoc()) {
                    $tenant_name = htmlspecialchars($overdue['fname']) . ' ' . htmlspecialchars($overdue['lname']);
                    $room_number = htmlspecialchars($overdue['room_number']);
                    $rent_period_end = date('F Y', strtotime($overdue['rent_period_end']));
                    $message = "$tenant_name from Room $room_number has an overdue bill from the month of $rent_period_end.";

                    echo '<div class="notif-container">';
                    echo '<p><span class="data" style="font-weight: 300;">' . $message . '</span></p>';
                    echo '<span class="date">' . date('F d, Y', strtotime($current_date)) . '</span>';
                    echo '</div>';
                }
            }

            // Display maintenance notifications
            if ($maintenance_result->num_rows > 0) {
                while ($maintenance = $maintenance_result->fetch_assoc()) {
                    echo '<div class="notif-container">';
                    echo '<p><span class="data" style="font-weight: 300;">' . htmlspecialchars($maintenance['message']) . '</span></p>';
                    echo '<span class="date">' . date('F d, Y', strtotime($maintenance['created_at'])) . '</span>';
                    echo '</div>';
                }
            }
            ?>

        </div>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
