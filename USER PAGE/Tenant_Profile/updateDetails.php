<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['tc_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to update your details.']);
    exit();
}

if (isset($_POST['name'], $_POST['gender'], $_POST['email'], $_POST['contact'], $_POST['religion'], $_POST['nationality'], $_POST['occupation'], $_POST['username'])) {
    
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $contact = mysqli_real_escape_string($conn, trim($_POST['contact']));
    $religion = mysqli_real_escape_string($conn, trim($_POST['religion']));
    $nationality = mysqli_real_escape_string($conn, trim($_POST['nationality']));
    $occupation = mysqli_real_escape_string($conn, trim($_POST['occupation']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));

    list($fname, $lname) = explode(" ", $name, 2); 

    $profileImagePath = '';
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = realpath(dirname(__FILE__)) . "/uploads/"; 
        $imageFileType = strtolower(pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION));

        $uniqueFileName = uniqid('profile_', true) . '.' . $imageFileType;
        $targetFile = $targetDir . $uniqueFileName;

        if ($_FILES["profileImage"]["size"] > 5000000) {
            echo json_encode(['success' => false, 'message' => 'Sorry, your file is too large.']);
            exit();
        }

        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo json_encode(['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
            exit();
        }

        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {

            $profileImagePath = $uniqueFileName; 
        } else {
            echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
            exit();
        }
    }

    $updateTenantQuery = "UPDATE tenant_details SET profile = ?, fname = ?, lname = ?, gender = ?, email_address = ?, contact_number = ?, religion = ?, nationality = ?, occupation = ? WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $updateTenantQuery);
    mysqli_stmt_bind_param($stmt, 'sssssssssi', $profileImagePath, $fname, $lname, $gender, $email, $contact, $religion, $nationality, $occupation, $_SESSION['tc_id']);
    
    if (mysqli_stmt_execute($stmt)) {

        $updateUsernameQuery = "UPDATE user_accounts SET username = ? WHERE id = ?";
        $stmt2 = mysqli_prepare($conn, $updateUsernameQuery);
        mysqli_stmt_bind_param($stmt2, 'si', $username, $_SESSION['tc_id']);
        
        if (mysqli_stmt_execute($stmt2)) {
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['gender'] = $gender;
            $_SESSION['email_address'] = $email;
            $_SESSION['contact_number'] = $contact;
            $_SESSION['religion'] = $religion;
            $_SESSION['nationality'] = $nationality;
            $_SESSION['occupation'] = $occupation;
            $_SESSION['username'] = $username;
            $_SESSION['profile_picture'] = $profileImagePath; 

            echo json_encode(['success' => true, 'message' => 'Details updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update username.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update details.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>
