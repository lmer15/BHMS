<?php
session_start();
require_once '../../DATABASE/dbConnector.php';

// Check if form is submitted
if (isset($_POST['username_or_email']) && isset($_POST['password'])) {
    $username_or_email = htmlspecialchars(trim($_POST['username_or_email']));  
    $password = trim($_POST['password']);  

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM user_accounts WHERE username = ? OR id IN (SELECT id FROM tenant_details WHERE email_address = ?);";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username_or_email, $username_or_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the user is approved
        if ($user['status'] !== 'approved') {
            header("Location: ../login.php?error=reservee_account");
            exit();
        }

        // Verify the password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session hijacking
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];  // Store username in session
            $_SESSION['type'] = $user['type'];

            // Fetch tenant details if the account type is tenant
            if ($user['type'] === 'tenant') {
                $tenantQuery = "SELECT * FROM tenant_details WHERE id = ?";
                $tenantStmt = mysqli_prepare($conn, $tenantQuery);
                mysqli_stmt_bind_param($tenantStmt, 'i', $user['id']);
                mysqli_stmt_execute($tenantStmt);
                $tenantResult = mysqli_stmt_get_result($tenantStmt);

                if (mysqli_num_rows($tenantResult) > 0) {
                    $tenant = mysqli_fetch_assoc($tenantResult);
                    $_SESSION['tc_id'] = $tenant['tc_id'];
                    $_SESSION['fname'] = $tenant['fname'];
                    $_SESSION['lname'] = $tenant['lname'];
                    $_SESSION['email_address'] = $tenant['email_address'];
                    $_SESSION['gender'] = $tenant['gender'];
                    $_SESSION['contact_number'] = $tenant['contact_number'];
                }
            }

            // Redirect based on account type
            if ($user['type'] === 'management') {
                header("Location: ../management.php");
            } elseif ($user['type'] === 'tenant') {
                header("Location: ../tenant.php");
            }

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
