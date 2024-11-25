<?php
include_once '../../DATABASE/dbConnector.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check the connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['roomImage']) && $_FILES['roomImage']['error'] == 0) {
        $imageDir = 'uploads/';
        $imageFileName = $_FILES['roomImage']['name'];
        $imageTmpName = $_FILES['roomImage']['tmp_name'];

        // Generate a unique file name to avoid overwrite
        $uniqueImageName = uniqid() . "_" . basename($imageFileName);
        $targetFilePath = $imageDir . $uniqueImageName;

        // Check file size limit (5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['roomImage']['size'] > $maxFileSize) {
            error_log("Error: File is too large.");
            header("Location: ../addRoom.html?error=file_too_large");
            exit();
        }

        // Try uploading the file
        if (move_uploaded_file($imageTmpName, $targetFilePath)) {
            // Collect form data
            $roomNumber = mysqli_real_escape_string($conn, $_POST['roomNumber']);
            $roomType = mysqli_real_escape_string($conn, $_POST['roomType']);
            $roomSize = (int)$_POST['roomSize'];  // Ensure integer
            $roomAmenities = mysqli_real_escape_string($conn, $_POST['amenities']);
            $roomUtilities = mysqli_real_escape_string($conn, $_POST['utilities']);
            $rentalRates = (float)$_POST['rentalRates'];  // Ensure float
            $paymentFrequency = mysqli_real_escape_string($conn, $_POST['paymentFrequency']);
            $depositRate = (float)$_POST['depositRate'];  // Ensure float

            // SQL Insert query
            $sql = "INSERT INTO room (room_image, room_number, room_type, room_size, room_aminities, room_utilities, rental_rates, room_payfre, room_deporate, room_status)
                    VALUES ('$uniqueImageName', '$roomNumber', '$roomType', '$roomSize', '$roomAmenities', '$roomUtilities', '$rentalRates', '$paymentFrequency', '$depositRate', 'available')";

            // Check if the query executes successfully
            if ($conn->query($sql) === TRUE) {
                header("Location: ../rooms.html");
                exit();
            } else {
                error_log("SQL Error: " . $conn->error);
                header("Location: ../addRoom.html?error=sql_error");
                exit();
            }
        } else {
            error_log("Error uploading image.");
            header("Location: ../addRoom.html?error=image_upload_failed");
            exit();
        }
    } else {
        error_log("No image uploaded or there was an error with the image.");
        header("Location: ../addRoom.html?error=no_image");
    }
}

$conn->close();
?>
