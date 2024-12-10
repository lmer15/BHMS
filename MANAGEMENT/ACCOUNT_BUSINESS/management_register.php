<?php
require_once '../../DATABASE/dbConnector.php';

$registrationError = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input fields
    if (empty($fname) || !preg_match('/^[a-zA-Z\s]+$/', $fname)) {
        $registrationError = 'Invalid first name.';
    }
    if (empty($lname) || !preg_match('/^[a-zA-Z\s]+$/', $lname)) {
        $registrationError = 'Invalid last name.';
    }
    if (!preg_match('/^(\+?[0-9]{1,3})?[-.\s]?(\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4})$/', $contact_number)) {
        $registrationError = 'Invalid contact number format.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registrationError = 'Invalid email address.';
    }

    // Check if email already exists in the management_accounts table
    if (empty($registrationError)) {
        $emailCheckQuery = "SELECT id FROM tenant_details WHERE email_address = ?";
        $stmtEmailCheck = mysqli_prepare($conn, $emailCheckQuery);
        mysqli_stmt_bind_param($stmtEmailCheck, "s", $email);
        mysqli_stmt_execute($stmtEmailCheck);
        if (mysqli_num_rows(mysqli_stmt_get_result($stmtEmailCheck)) > 0) {
            $registrationError = 'Email already exists.';
        }
    }

    // Check if username already exists in the user_accounts table
    if (empty($registrationError)) {
        $usernameCheckQuery = "SELECT id FROM user_accounts WHERE username = ?";
        $stmtUsernameCheck = mysqli_prepare($conn, $usernameCheckQuery);
        mysqli_stmt_bind_param($stmtUsernameCheck, "s", $username);
        mysqli_stmt_execute($stmtUsernameCheck);
        if (mysqli_num_rows(mysqli_stmt_get_result($stmtUsernameCheck)) > 0) {
            $registrationError = 'Username already exists.';
        }
    }

    // Validate password
    if (empty($registrationError)) {
        if (strlen($password) < 8) {
            $registrationError = 'Password must be at least 8 characters long.';
        } elseif ($password !== $confirm_password) {
            $registrationError = 'Passwords do not match.';
        }
    }

    // Proceed with registration if no errors
    if (empty($registrationError)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user account into the user_accounts table
        $queryUserAccount = "INSERT INTO user_accounts (username, password, status, type) VALUES (?, ?, 'active', 'admin')";
        $stmtUserAccount = mysqli_prepare($conn, $queryUserAccount);
        mysqli_stmt_bind_param($stmtUserAccount, "ss", $username, $hashed_password);

        if (mysqli_stmt_execute($stmtUserAccount)) {
            $user_account_id = mysqli_insert_id($conn);

            // Insert the management account into the management_accounts table
            $queryManagementAccount = "INSERT INTO tenant_details (id, fname, lname, contact_number, email_address) VALUES (?, ?, ?, ?, ?)";
            $stmtManagementAccount = mysqli_prepare($conn, $queryManagementAccount);
            mysqli_stmt_bind_param($stmtManagementAccount, "issss", $user_account_id, $fname, $lname, $contact_number, $email);

            if (mysqli_stmt_execute($stmtManagementAccount)) {
                // Send a success response to JavaScript
                echo json_encode(['status' => 'success', 'message' => 'Registration successful! Please log in.']);
                exit();
            } else {
                $registrationError = 'Failed to register management account.';
            }
        } else {
            $registrationError = 'Failed to register user account.';
        }
    }
}

if ($registrationError) {
    echo json_encode(['status' => 'error', 'message' => $registrationError]);
    exit();
}
?>
