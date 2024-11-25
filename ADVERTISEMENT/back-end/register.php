<?php
require_once '../../DATABASE/dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form dat
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $cNumber = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($pass !== $confirm_password) {
        header("Location: ../register-form.html?error=password_mismatch");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register-form.html?error=invalid_email_format");
        exit();
    }

    // Check if the email already exists in the database
    $checkEmail = "SELECT * FROM tenant_accounts WHERE email_address = ?";
    $stmt = mysqli_prepare($conn, $checkEmail);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Redirect if email exists
        header("Location: ../register-form.html?error=email_exists");
        exit();
    }

    // Check if the username already exists in the database
    $checkUsername = "SELECT * FROM tenant_accounts WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkUsername);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Redirect if username exists
        header("Location: ../register-form.html?error=username_exists");
        exit();
    }

    // Hash the password using password_hash()
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO tenant_accounts 
              (fname, lname, email_address, username, contact_number, password, status) 
              VALUES (?, ?, ?, ?, ?, ?, 'reservee')";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $email, $username, $cNumber, $hashed_password);

    try {
        // Attempt to execute the query and catch any exceptions
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to index.html after successful registration
            header("Location: ../index.html?success=true");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Check if it's a duplicate email or username error
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            // Duplicate entry for email or username
            header("Location: ../register-form.html?error=duplicate_entry");
        } else {
            // Log the error if it's not a duplicate issue
            error_log("SQL Error: " . $e->getMessage());
            header("Location: ../register-form.html?error=server_error");
        }
        exit();
    }

    // Close the prepared statement and the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
