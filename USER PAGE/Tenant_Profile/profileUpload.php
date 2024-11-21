<?php
session_start();
require_once '../../DATABASE/dbConnector.php'; 

// Check if the user is logged in and the profile image is uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profileImage"])) {
    // Get the uploaded file details
    $file = $_FILES["profileImage"];
    $uploadDir = "../Tenant Profile Image/"; // Directory where images will be uploaded
    $userId = $_SESSION['user_id']; // Fetch user ID from the session

    // Check if the file was uploaded successfully
    if ($file["error"] === UPLOAD_ERR_OK) {
        // Check the file type
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $validImageTypes = ["jpg", "jpeg", "png", "gif"];

        // Validate if the file is an allowed image type
        if (in_array($imageFileType, $validImageTypes)) {
            // Generate a unique name for the file to avoid conflicts
            $fileName = uniqid('profile_', true) . '.' . $imageFileType;
            $filePath = $uploadDir . $fileName;

            // Check if the uploaded file is actually an image (extra security)
            $mimeType = mime_content_type($file["tmp_name"]);
            if (strpos($mimeType, "image") === false) {
                echo "Uploaded file is not a valid image!";
                exit;
            }

            // Attempt to move the uploaded file to the server
            if (move_uploaded_file($file["tmp_name"], $filePath)) {
                // Prepare SQL query to update profile image in the database
                $sql = "UPDATE tenant_profiles SET t_img = ?, t_dateUpload = NOW() WHERE tc_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $filePath, $userId);

                // Execute the query and provide feedback
                if ($stmt->execute()) {
                    echo "Profile picture updated successfully!";
                } else {
                    echo "Error updating profile picture: " . $stmt->error;
                }
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Only image files (JPG, JPEG, PNG, GIF) are allowed!";
        }
    } else {
        echo "Error: " . $file["error"];
    }
}

// Close the database connection
$conn->close();
?>
