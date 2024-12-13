<?php
include_once '../../DATABASE/dbConnector.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = []; 
$successMessage = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO aminities_services (amiser_title, amiser_desc, amiser_status) VALUES ('$title', '$description', 'services')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Services added successfully!";
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
