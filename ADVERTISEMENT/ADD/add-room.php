<?php
include_once '../../DATABASE/dbConnector.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = []; // To store error messages
$successMessage = ''; // To store success message

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

        // Validate file size
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['roomImage']['size'] > $maxFileSize) {
            $errors[] = "File is too large. Maximum size is 5MB.";
        }

        // Validate file type
        $allowedFileTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['roomImage']['type'], $allowedFileTypes)) {
            $errors[] = "Invalid file type. Allowed types are JPG, PNG, GIF.";
        }

        // Try uploading the file if no errors
        if (empty($errors) && move_uploaded_file($imageTmpName, $targetFilePath)) {
            // Collect form data
            $roomNumber = mysqli_real_escape_string($conn, $_POST['roomNumber']);
            $roomType = mysqli_real_escape_string($conn, $_POST['roomType']);
            $roomSize = (int)$_POST['roomSize'];  // Ensure integer
            $roomAmenities = mysqli_real_escape_string($conn, $_POST['amenities']);
            $roomUtilities = mysqli_real_escape_string($conn, $_POST['utilities']);
            $rentalRates = (float)$_POST['rentalRates'];  // Ensure float
            $depositRate = (float)$_POST['depositRate'];  // Ensure float

            // Check if room number already exists
            $checkSql = "SELECT COUNT(*) AS count FROM room WHERE room_number = '$roomNumber'";
            $result = $conn->query($checkSql);
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                $errors[] = "Room number already exists. Please use a unique room number.";
            }

            // Insert the data if no errors
            if (empty($errors)) {
                $sql = "INSERT INTO room (room_image, room_number, room_type, room_size, room_aminities, room_utilities, rental_rates, room_deporate, room_status)
                        VALUES ('$uniqueImageName', '$roomNumber', '$roomType', '$roomSize', '$roomAmenities', '$roomUtilities', '$rentalRates', '$depositRate', 'available')";

                if ($conn->query($sql) === TRUE) {
                    $successMessage = "Room added successfully!";
                    echo json_encode(['success' => $successMessage]);  // Return success response as JSON
                    exit;  // End execution after success
                } else {
                    $errors[] = "SQL Error: " . $conn->error;
                }
            }
        } else if (empty($errors)) {
            $errors[] = "Error uploading image.";
        }
    } else {
        $errors[] = "No image uploaded or there was an error with the image.";
    }

    // If errors exist, return them as JSON
    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit;  // End execution after errors
    }
}

$conn->close();
?>
