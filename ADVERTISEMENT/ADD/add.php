<?php
// Include your database connection file
require_once '../../DATABASE/dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert logic for Amenities, Services, or Room based on the form submitted
    
    // Example for inserting Amenities
    if (isset($_POST['addAmenities'])) {
        // Assuming you have a connection object $conn
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        // Prepare SQL query to insert the data into the database
        $sql = "INSERT INTO amenities (title, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $description);

        if ($stmt->execute()) {
            // Redirect to aminities.html after success
            header("Location: aminities.html");
            exit();
        } else {
            // Handle insertion failure, perhaps show an error message
            echo "Error: " . $stmt->error;
        }
    }

    // Example for inserting Services
    if (isset($_POST['addServices'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $sql = "INSERT INTO services (title, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $description);

        if ($stmt->execute()) {
            // Redirect to services.html after success
            header("Location: services.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Example for inserting Room
    if (isset($_POST['addRoom'])) {
        $roomNumber = $_POST['roomNumber'];
        $roomType = $_POST['roomType'];
        $roomSize = $_POST['roomSize'];
        $amenities = $_POST['amenities'];
        $utilities = $_POST['utilities'];
        $depositAmount = $_POST['depositAmount'];
        $paymentFrequency = $_POST['paymentFrequency'];
        $depositRate = $_POST['depositRate'];

        // Handle image upload
        if ($_FILES['roomImage']['name'] != "") {
            $imagePath = 'uploads/room_' . uniqid() . '.' . pathinfo($_FILES['roomImage']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['roomImage']['tmp_name'], $imagePath);
        } else {
            $imagePath = null; 
        }

        $sql = "INSERT INTO rooms (room_number, room_type, room_size, amenities, utilities, deposit_amount, payment_frequency, deposit_rate, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $roomNumber, $roomType, $roomSize, $amenities, $utilities, $depositAmount, $paymentFrequency, $depositRate, $imagePath);

        if ($stmt->execute()) {
            // Redirect to rooms.html after success
            header("Location: ../rooms.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
