<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['tc_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to update your details.']);
    exit();
}

// Check if the form is submitted
if (isset($_POST['name'], $_POST['gender'], $_POST['email'], $_POST['contact'], $_POST['religion'], $_POST['nationality'], $_POST['occupation'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $contact = mysqli_real_escape_string($conn, trim($_POST['contact']));
    $religion = mysqli_real_escape_string($conn, trim($_POST['religion']));
    $nationality = mysqli_real_escape_string($conn, trim($_POST['nationality']));
    $occupation = mysqli_real_escape_string($conn, trim($_POST['occupation']));

    // Split the name into first and last names
    list($fname, $lname) = explode(" ", $name, 2);

    // Update query
    $query = "UPDATE tenant_accounts SET fname = ?, lname = ?, gender = ?, email_address = ?, contact_number = ?, religion = ?, nationality = ?, occupation = ? WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssssi', $fname, $lname, $gender, $email, $contact, $religion, $nationality, $occupation, $_SESSION['tc_id']);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update session variables
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['gender'] = $gender;
        $_SESSION['email_address'] = $email;
        $_SESSION['contact_number'] = $contact;
        $_SESSION['religion'] = $religion;
        $_SESSION['nationality'] = $nationality;
        $_SESSION['occupation'] = $occupation;

        echo json_encode(['success' => true, 'message' => 'Your details have been updated successfully.']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update details, please try again.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit();
}
