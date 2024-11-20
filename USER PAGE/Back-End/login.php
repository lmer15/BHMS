<?php
session_start();
require_once '../../DATABASE/dbConnector.php';  // Database connection

// Check if form is submitted
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);  
    $password = trim($_POST['password']);  

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM tenant_accounts WHERE email_address = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session hijacking
            session_regenerate_id(true);
            
            $_SESSION['ad_ID'] = $user['ad_ID'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['email'] = $user['email'];

            // Check if "Remember Me" is checked
            if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on') {
                // Set cookie for username (avoid storing password in cookie)
                setcookie("remember_username", $email, time() + (30 * 24 * 60 * 60), "/");  // 30 days
            } else {
                // Clear cookies if "Remember Me" is not checked
                setcookie("remember_username", "", time() - 3600, "/");
            }

            // Redirect to dashboard
            header("Location: ../tenant.php");
            exit();
        } else {
            // Invalid password
            header("Location: ../login.html?error=invalid_password");
            exit();
        }
    } else {
        // Email not found
        header("Location: ../login.html?error=email_not_found");
        exit();
    }
} else {
    // Missing data (username or password)
    header("Location: ../login.html?error=missing_data");
    exit();
}
?>
