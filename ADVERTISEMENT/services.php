<?php
include_once '../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch amenities from the database
$sql = "SELECT amiser_title, amiser_desc FROM aminities_services WHERE amiser_status = 'services'";
$result = $conn->query($sql);

$amenities = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $amenities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section id="wrapper">
        <div id="services">
            <div class="services-grid">
                <?php 
                foreach ($amenities as $amenity): 
                    $imagePath = "images/monitor.png";
                ?>
                    <div class="card">
                        <div class="icon-container">
                            <img src="<?php echo $imagePath; ?>" alt="Default image" class="icon">
                        </div>
                        <div class="card-content">
                            <h2><?php echo $amenity['amiser_title']; ?></h2>
                            <p><?php echo $amenity['amiser_desc']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="add-services">
                    <a href="ADD/addServices.php"><i class='bx bx-plus icon-add'></i></a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
