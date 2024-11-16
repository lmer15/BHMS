<?php
require_once '../../DATABASE/dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $cNumber = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
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

    // Hash the password using password_hash()
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO tenant_accounts (fname, lname, contact_number, email_address, password, status) 
              VALUES (?, ?, ?, ?, ?, 'reservee')";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "sssss", $fname, $lname, $cNumber, $email, $hashed_password);

    try {
        // Attempt to execute the query and catch any exceptions
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to rooms.html after successful registration
            header("Location: ../index.html?success=true");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Check if it's a duplicate email error
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            // Duplicate entry for email
            header("Location: ../register-form.html?error=duplicate_email");
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
