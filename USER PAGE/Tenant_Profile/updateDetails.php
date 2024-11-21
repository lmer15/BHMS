<?php
require_once '../../DATABASE/dbConnector.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $tc_id = $_SESSION['tc_id'];
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email_address = mysqli_real_escape_string($conn, $_POST['email_address']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);

    // Validate email format
    if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../updateDetails.php?error=invalid_email");
        exit();
    }

    // Check if email already exists in the database
    $query = "SELECT email_address FROM tenant_accounts WHERE email_address = ? AND tc_id != ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $email_address, $tc_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        header("Location: ../updateDetails.php?error=email_taken");
        exit();
    }

    // Prepare the SQL query for updating personal details
    $updateQuery = "UPDATE tenant_accounts SET fname = ?, lname = ?, gender = ?, email_address = ?, contact_number = ?, religion = ?, nationality = ?, occupation = ? WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $fname, $lname, $gender, $email_address, $contact_number, $religion, $nationality, $occupation, $tc_id);

    try {
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../Tenant_Profile/tProfile.php?success=true");
            exit();
        } else {
            header("Location: ../updateDetails.php?error=server_error");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: ../updateDetails.php?error=server_error");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
