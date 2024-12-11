<?php
include_once '../../DATABASE/dbConnector.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];
$successMessage = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomNumber = mysqli_real_escape_string($conn, $_POST['roomNumber']);
    $roomType = mysqli_real_escape_string($conn, $_POST['roomType']);
    $roomSize = (int)$_POST['roomSize']; 
    $roomAmenities = mysqli_real_escape_string($conn, $_POST['amenities']);
    $roomUtilities = mysqli_real_escape_string($conn, $_POST['utilities']);
    $rentalRates = (float)$_POST['rentalRates']; 
    $depositRate = (float)$_POST['depositRate'];

    $imageDir = 'uploads/';
    $uniqueImageName = '';

    if (isset($_FILES['roomImage']) && $_FILES['roomImage']['error'] == 0) {
        $imageFileName = $_FILES['roomImage']['name'];
        $imageTmpName = $_FILES['roomImage']['tmp_name'];

        $uniqueImageName = uniqid() . "_" . basename($imageFileName);
        $targetFilePath = $imageDir . $uniqueImageName;

        $maxFileSize = 5 * 1024 * 1024;
        if ($_FILES['roomImage']['size'] > $maxFileSize) {
            $errors[] = "File is too large. Maximum size is 5MB.";
        }

        $allowedFileTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['roomImage']['type'], $allowedFileTypes)) {
            $errors[] = "Invalid file type. Allowed types are JPG, PNG, GIF.";
        }

        if (empty($errors) && !move_uploaded_file($imageTmpName, $targetFilePath)) {
            $errors[] = "Error uploading image.";
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE room SET 
                    room_type = '$roomType',
                    room_size = $roomSize,
                    room_aminities = '$roomAmenities',
                    room_utilities = '$roomUtilities',
                    rental_rates = $rentalRates,
                    room_deporate = $depositRate";

        if (!empty($uniqueImageName)) {
            $sql .= ", room_image = '$uniqueImageName'";
        }

        $sql .= " WHERE room_number = '$roomNumber'";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Room details updated successfully!";
            echo json_encode(['success' => $successMessage]); 
            exit; 
        } else {
            $errors[] = "SQL Error: " . $conn->error;
        }
    }


    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit; 
    }
}

$conn->close();
?>
