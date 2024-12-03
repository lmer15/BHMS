<?php
require_once '../../DATABASE/dbConnector.php';

function sendError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

function sendSuccess($message, $data = []) {
    $response = ['status' => 'success', 'message' => $message];
    echo json_encode(array_merge($response, $data));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize inputs
    $room_number = $_POST['room_number'] ?? '';
    $num_occupants = (int) ($_POST['num_occupants'] ?? 0);
    $booking_date = $_POST['booking_date'] ?? '';
    $moving_in_date = $_POST['moving_in_date'] ?? '';
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) sendError('Passwords do not match.');
    
    $today = date('Y-m-d');
    if ($moving_in_date < $today) sendError('Moving-in date cannot be in the past.');

    // Fetch room details
    $roomQuery = "SELECT room_id, room_type FROM room WHERE room_number = ? AND room_status = 'available'";
    $stmt = mysqli_prepare($conn, $roomQuery);
    mysqli_stmt_bind_param($stmt, "s", $room_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$row = mysqli_fetch_assoc($result)) sendError('Room not available.');

    $room_id = $row['room_id'];
    $room_type = $row['room_type'];

    // Validate room type and occupants
    if (($room_type == 'Single' && $num_occupants != 1) || 
        ($room_type == 'Double' && $num_occupants != 2) || 
        ($room_type == 'Family' && ($num_occupants < 3 || $num_occupants > 5))) {
        sendError("Invalid number of occupants for | Room Type: $room_type.");
    }

    // Check for existing username or email
    $userCheckQuery = "SELECT id FROM user_accounts WHERE username = ?";
    $stmtUserCheck = mysqli_prepare($conn, $userCheckQuery);
    mysqli_stmt_bind_param($stmtUserCheck, "s", $username);
    mysqli_stmt_execute($stmtUserCheck);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmtUserCheck)) > 0) sendError('Username already exists.');

    $emailCheckQuery = "SELECT id FROM tenant_details WHERE email_address = ?";
    $stmtEmailCheck = mysqli_prepare($conn, $emailCheckQuery);
    mysqli_stmt_bind_param($stmtEmailCheck, "s", $email);
    mysqli_stmt_execute($stmtEmailCheck);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmtEmailCheck)) > 0) sendError('Email already exists.');

    // Insert user and tenant details
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insertUserQuery = "INSERT INTO user_accounts (username, password, status, type) VALUES (?, ?, 'pending', 'tenant')";
    $stmtInsertUser = mysqli_prepare($conn, $insertUserQuery);
    mysqli_stmt_bind_param($stmtInsertUser, "ss", $username, $hashed_password);
    if (!mysqli_stmt_execute($stmtInsertUser)) sendError('Failed to create user account.');

    $user_id = mysqli_insert_id($conn);
    $insertTenantQuery = "INSERT INTO tenant_details (id, number_of_occupants, email_address, contact_number) VALUES (?, ?, ?, ?)";
    $stmtInsertTenant = mysqli_prepare($conn, $insertTenantQuery);
    mysqli_stmt_bind_param($stmtInsertTenant, "iiss", $user_id, $num_occupants, $email, $contact_number);
    if (!mysqli_stmt_execute($stmtInsertTenant)) sendError('Failed to insert tenant details.');

    $tenant_id = mysqli_insert_id($conn);

    // Insert booking
    $insertBookingQuery = "INSERT INTO booking (tenant_id, room_id, booking_start_date, booking_end_date, status) VALUES (?, ?, ?, ?, 'booked')";
    $stmtInsertBooking = mysqli_prepare($conn, $insertBookingQuery);
    mysqli_stmt_bind_param($stmtInsertBooking, "iiss", $tenant_id, $room_id, $booking_date, $moving_in_date);
    if (!mysqli_stmt_execute($stmtInsertBooking)) sendError('Failed to create booking.');

    // Update room status
    $updateRoomStatusQuery = "UPDATE room SET room_status = 'reserved' WHERE room_id = ?";
    $stmtUpdateRoomStatus = mysqli_prepare($conn, $updateRoomStatusQuery);
    mysqli_stmt_bind_param($stmtUpdateRoomStatus, "i", $room_id);
    if (!mysqli_stmt_execute($stmtUpdateRoomStatus)) sendError('Failed to update room status.');

    sendSuccess('Your registration was successful!', ['receipt_url' => "receipt.php?tenant_id=$tenant_id"]);
}
?>
