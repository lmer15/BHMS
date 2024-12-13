<?php
session_start();
if (!isset($_SESSION['tc_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['tc_id']; 

include_once '../../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM notifications WHERE user = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="notif.css?v=1.0">
</head>
<body>
    
    <div class="wrapper">
        <h1>Notifications</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $message = htmlspecialchars($row['message']);
                $status = htmlspecialchars($row['status']);
                $created_at = date('F j, Y', strtotime($row['created_at'])); // Format the created_at date
                ?>
                <div class="notif-container">
                    <p><?php echo $message; ?></p>
                    <span><?php echo $created_at; ?></span>
                </div>
                <?php
            }
        } else {
            echo "<p>No notifications available.</p>";
        }
        $conn->close();
        ?>

    </div>

</body>
</html>
