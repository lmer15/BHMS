<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  // Database connection

// Check if form is submitted
if (isset($_POST['username_or_email']) && isset($_POST['password'])) {
    $username_or_email = htmlspecialchars(trim($_POST['username_or_email']));  
    $password = trim($_POST['password']);  

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM tenant_accounts WHERE email_address = ? OR username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username_or_email, $username_or_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the user has the status "reservee"
        if ($user['status'] === 'reservee') {
            header("Location: ../login.php?error=reservee_account");
            exit();
        }

        // Verify the password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session hijacking
            session_regenerate_id(true);

            $_SESSION['tc_id'] = $user['tc_id'];
            $_SESSION['username'] = $user['username'];  // Store username in session
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['email_address'] = $user['email_address'];
            $_SESSION['gender'] = $user['gender'];
            $_SESSION['contact_number'] = $user['contact_number'];

            // Redirect to the dashboard
            header("Location: ../tenant.php");
            exit();
        } else {
            // Invalid password
            header("Location: ../login.php?error=invalid_password");
            exit();
        }
    } else {
        // Email or username not found
        header("Location: ../login.php?error=email_or_username_not_found");
        exit();
    }
} else {
    // Missing data from the login form
    header("Location: ../login.php?error=missing_data");
    exit();
}
?>
