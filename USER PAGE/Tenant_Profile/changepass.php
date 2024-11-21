<?php
require_once '../../DATABASE/dbConnector.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $oldPassword = mysqli_real_escape_string($conn, $_POST['oldPassword']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirmPassword = $_POST['confirmPassword'];
    $userId = $_SESSION['tc_id'];

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        header("Location: ../changePass.php?error=password_mismatch");
        exit();
    }

    if (strlen($newPassword) < 8) {
        header("Location: ../changePass.php?error=password_length");
        exit();
    }

    // Retrieve the current password from the database
    $query = "SELECT password FROM tenant_accounts WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user || !password_verify($oldPassword, $user['password'])) {
        header("Location: ../changePass.php?error=incorrect_old_password");
        exit();
    }

    // Hash the new password and update the database
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $updateQuery = "UPDATE tenant_accounts SET password = ? WHERE tc_id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $hashedNewPassword, $userId);

    try {
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../tProfile.php?success=true");
            exit();
        } else {
            header("Location: ../changePass.php?error=server_error");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: ../changePass.php?error=server_error");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
