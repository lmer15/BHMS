<?php

include_once'../../DATABASE/dbConnector.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM notifications WHERE type NOT IN ('MAINTENANCE-ADMIN', 'PAYMENT-ADMIN') ORDER BY created_at DESC";
$result = $conn->query($sql);

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
            if ($result->num_rows > 0) {
                while ($notif = $result->fetch_assoc()) {
                    echo '<div class="notif-container">';
                    echo '<p><span class="data" style="font-weight: 300;">' . htmlspecialchars($notif['message']) . '</span></p>';
                    echo '<span class="date">' . date('F d, Y', strtotime($notif['created_at'])) . '</span>';
                    echo '</div>';
                }
            } else {
                echo '<p>No notifications available.</p>';
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
