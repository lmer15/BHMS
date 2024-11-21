<?php
session_start();
require_once '../../DATABASE/dbConnector.php';

$response = ['error' => '', 'success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['tc_id'])) {
        $response['error'] = 'Session expired. Please log in again.';
        echo json_encode($response);
        exit();
    }

    $tc_id = $_SESSION['tc_id'];
    $oldPassword = $_POST['oldPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate the passwords
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $response['error'] = 'Please fill in all fields.';
    } elseif ($newPassword !== $confirmPassword) {
        $response['error'] = 'Passwords do not match.';
    } else {
        // Check if old password is correct
        $stmt = $pdo->prepare("SELECT password FROM tenant_users WHERE tc_id = ?");
        $stmt->execute([$tc_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($oldPassword, $user['password'])) {
            // Update the password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE tenant_users SET password = ? WHERE tc_id = ?");
            if ($updateStmt->execute([$newPasswordHash, $tc_id])) {
                $response['success'] = true;
            } else {
                $response['error'] = 'Failed to change password. Please try again.';
            }
        } else {
            $response['error'] = 'Old password is incorrect.';
        }
    }
}

echo json_encode($response);
?>
