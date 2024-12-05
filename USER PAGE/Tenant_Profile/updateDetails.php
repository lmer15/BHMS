<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['tc_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to update your details.']);
    exit();
}

// Check if the form is submitted with the necessary fields
if (isset($_POST['name'], $_POST['gender'], $_POST['email'], $_POST['contact'], $_POST['religion'], $_POST['nationality'], $_POST['occupation'], $_POST['username'])) {
    
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $contact = mysqli_real_escape_string($conn, trim($_POST['contact']));
    $religion = mysqli_real_escape_string($conn, trim($_POST['religion']));
    $nationality = mysqli_real_escape_string($conn, trim($_POST['nationality']));
    $occupation = mysqli_real_escape_string($conn, trim($_POST['occupation']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    
    // Split the name into first and last names
    list($fname, $lname) = explode(" ", $name, 2); 
    
    // Check if the email is already taken (excluding current user's email)
    $checkEmailQuery = "SELECT * FROM tenant_details WHERE email_address = ? AND tc_id != ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, 'si', $email, $_SESSION['tc_id']);
    mysqli_stmt_execute($stmt);
    $emailResult = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($emailResult) > 0) {
        echo json_encode(['success' => false, 'message' => 'This email address is already in use.']);
        exit();
    }

    // Check if the username is already taken (excluding current user's username)
    $checkUsernameQuery = "SELECT * FROM user_accounts WHERE username = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $checkUsernameQuery);
    mysqli_stmt_bind_param($stmt, 'si', $username, $_SESSION['tc_id']);
    mysqli_stmt_execute($stmt);
    $usernameResult = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($usernameResult) > 0) {
        echo json_encode(['success' => false, 'message' => 'This username is already in use.']);
        exit();
    }

    // Update tenant details query
    $updateTenantQuery = "UPDATE tenant_details SET fname = ?, lname = ?, gender = ?, email_address = ?, contact_number = ?, religion = ?, nationality = ?, occupation = ? WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $updateTenantQuery);
    mysqli_stmt_bind_param($stmt, 'ssssssssi', $fname, $lname, $gender, $email, $contact, $religion, $nationality, $occupation, $_SESSION['tc_id']);
    
    if (mysqli_stmt_execute($stmt)) {
        // Now update the username in the user_accounts table
        $updateUsernameQuery = "UPDATE user_accounts SET username = ? WHERE id = ?";
        $stmt2 = mysqli_prepare($conn, $updateUsernameQuery);
        mysqli_stmt_bind_param($stmt2, 'si', $username, $_SESSION['tc_id']);
        
        if (mysqli_stmt_execute($stmt2)) {
            // Update session variables to reflect the changes
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['gender'] = $gender;
            $_SESSION['email_address'] = $email;
            $_SESSION['contact_number'] = $contact;
            $_SESSION['religion'] = $religion;
            $_SESSION['nationality'] = $nationality;
            $_SESSION['occupation'] = $occupation;
            $_SESSION['username'] = $username;

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
