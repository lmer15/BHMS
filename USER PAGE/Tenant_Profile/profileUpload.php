<?php
session_start();
require_once '../../DATABASE/dbConnector.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profileImage"])) {
    $file = $_FILES["profileImage"];
    $uploadDir = "uploads/";
    $userId = $_SESSION['tc_id'];

    if ($file["error"] === UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $validImageTypes = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $validImageTypes)) {
            if ($file["size"] > 5 * 1024 * 1024) {
                echo "Error: File size exceeds the limit of 5MB.";
                exit;
            }

            // Generate a unique name for the file to avoid conflicts
            $fileName = uniqid('profile_', true) . '.' . $imageFileType;
            $filePath = $uploadDir . $fileName;

            // Move the uploaded file to the server's file system
            if (move_uploaded_file($file["tmp_name"], $filePath)) {
                // Insert the image path into the database
                $sql = "INSERT INTO profile_pictures (user_id, image_path, date_uploaded) 
                        VALUES (?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $userId, $filePath); 

                // Execute the query
                if ($stmt->execute()) {
                    echo "Profile picture uploaded and saved to database successfully!";
                } else {
                    echo "Error inserting image into database: " . $stmt->error;
                }
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Error: Only image files (JPG, JPEG, PNG, GIF) are allowed!";
        }
    } else {
        echo "Error: There was an issue with the file upload. Error code: " . $file["error"];
    }
} else {
    echo "Error: No file was uploaded.";
}

// Close the database connection
$conn->close();
?>
