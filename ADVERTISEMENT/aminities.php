<?php
include_once '../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch amenities from the database
$sql = "SELECT amiser_title, amiser_desc FROM aminities_services WHERE amiser_status = 'aminities'";
$result = $conn->query($sql);

$amenities = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $amenities[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amenities</title>
    <link rel="stylesheet" href="style.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section id="wrapper">
        <div id="aminities">
            <?php foreach ($amenities as $amenity): ?>
                <div class="aminities-info">
                    <img src="images/urban.png" alt="Amenity Icon">
                    <h1><?php echo htmlspecialchars($amenity['amiser_title']); ?></h1>
                    <p><?php echo nl2br(htmlspecialchars($amenity['amiser_desc'])); ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Add Amenities Button -->
            <div class="add-aminities">
                <a href="ADD/addAminities.php"><i class='bx bx-plus icon-add'></i></a>
            </div>
        </div>
    </section>
</body>
</html>
