<?php
// Ensure this file includes the database connection
require_once '../../DATABASE/dbConnector.php'; // Ensure this file connects to the database properly
session_start();

$response = ['error' => '', 'success' => false];

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the session is still active
    if (!isset($_SESSION['tc_id'])) {
        $response['error'] = 'Session expired. Please log in again.';
        echo json_encode($response);
        exit();
    }

    // Get the session ID and form inputs
    $tc_id = $_SESSION['tc_id'];
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $occupation = $_POST['occupation'] ?? '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($contact)) {
        $response['error'] = 'Please fill all required fields.';
    } else {
        // Update user details in the database
        try {
            $stmt = $pdo->prepare("UPDATE tenant_users SET name = ?, gender = ?, email = ?, contact_number = ?, religion = ?, nationality = ?, occupation = ? WHERE tc_id = ?");
            if ($stmt->execute([$name, $gender, $email, $contact, $religion, $nationality, $occupation, $tc_id])) {
                // Update session variables
                $_SESSION['fname'] = explode(" ", $name)[0];
                $_SESSION['lname'] = explode(" ", $name)[1];
                $_SESSION['gender'] = $gender;
                $_SESSION['email_address'] = $email;
                $_SESSION['contact_number'] = $contact;
                $_SESSION['religion'] = $religion;
                $_SESSION['nationality'] = $nationality;
                $_SESSION['occupation'] = $occupation;

                $response['success'] = true;
            } else {
                $response['error'] = 'Failed to update details. Please try again.';
            }
        } catch (Exception $e) {
            $response['error'] = 'An error occurred: ' . $e->getMessage();
        }
    }
}

// Return the response in JSON format
echo json_encode($response);
?>
