<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['tc_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to change your password.']);
    exit();
}

// Check if the form is submitted
if (isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['confirmPassword'])) {
    $oldPassword = trim($_POST['oldPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Check if the new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'New password and confirmation password do not match.']);
        exit();
    }

    // Get the current hashed password from the database
    $query = "SELECT password FROM tenant_accounts WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['tc_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the current password with the stored hashed password
        if (password_verify($oldPassword, $user['password'])) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateQuery = "UPDATE tenant_accounts SET password = ? WHERE tc_id = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, 'si', $hashedNewPassword, $_SESSION['tc_id']);

            if (mysqli_stmt_execute($updateStmt)) {
                echo json_encode(['success' => true, 'message' => 'Your password has been updated successfully.']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update password, please try again.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit();
}
