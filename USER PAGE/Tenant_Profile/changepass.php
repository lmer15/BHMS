<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  // Database connection

// Initialize an empty error message variable
$errorMessage = "";
$successMessage = "";

// Check if form is submitted
if (isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmPassword'])) {
    // Get form data
    $oldpass = trim($_POST['oldPassword']);
    $newpass = trim($_POST['newPassword']);
    $confirmpass = trim($_POST['confirmPassword']);

    // Check if new password and confirm password match
    if ($newpass !== $confirmpass) {
        $errorMessage = "Passwords do not match.";
    } elseif (strlen($newpass) < 8) {
        $errorMessage = "Password is too short. Must be at least 8 characters.";
    } else {
        // Get the user's email from the session
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
        } else {
            $errorMessage = "You are not logged in.";
        }

        // If no error, proceed to update the password
        if (empty($errorMessage)) {
            // Use prepared statements to prevent SQL injection
            $query = "SELECT * FROM tenant_accounts WHERE email_address = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                // Verify the old password with the hashed password in the database
                if (password_verify($oldpass, $user['password'])) {
                    // Hash the new password
                    $newpass_hashed = password_hash($newpass, PASSWORD_BCRYPT);

                    // Update the password in the database
                    $update_query = "UPDATE tenant_accounts SET password = ? WHERE email_address = ?";
                    $update_stmt = mysqli_prepare($conn, $update_query);
                    mysqli_stmt_bind_param($update_stmt, 'ss', $newpass_hashed, $email);

                    if (mysqli_stmt_execute($update_stmt)) {
                        $successMessage = "Password updated successfully.";
                    } else {
                        $errorMessage = "Failed to update password. Please try again.";
                    }
                } else {
                    $errorMessage = "The old password you entered is incorrect.";
                }
            } else {
                $errorMessage = "User not found.";
            }
        }
    }
}
?>