<?php
require_once '../../DATABASE/dbConnector.php';

function sendError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

function sendSuccess($message, $data = []) {
    $response = ['status' => 'success', 'message' => $message, 'redirect_after_ok' => true];
    if (isset($data['tenant_id'])) {
        $response['tenant_id'] = $data['tenant_id'];
    }

    echo json_encode(array_merge($response, $data));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    if (empty($room_number) || !preg_match('/^[a-zA-Z0-9]+$/', $room_number)) {
        sendError('Invalid room number.');
    }
    if ($num_occupants <= 0) {
        sendError('Number of occupants must be greater than 0.');
    }
    $today = date('Y-m-d');
    if ($booking_date < $today) {
        sendError('Booking date cannot be in the past.');
    }
    if ($moving_in_date < $today) {
        sendError('Moving-in date cannot be in the past.');
    }
    if (empty($fname) || !preg_match('/^[a-zA-Z\s]+$/', $fname)) {
        sendError('Invalid first name.');
    }
    if (empty($lname) || !preg_match('/^[a-zA-Z\s]+$/', $lname)) {
        sendError('Invalid last name.');
    }
    if (!preg_match('/^(\+?[0-9]{1,3})?[-.\s]?(\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4})$/', $contact_number)) {
        sendError('Invalid contact number format.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendError('Invalid email address.');
    }

    $emailCheckQuery = "SELECT id FROM tenant_details WHERE email_address = ?";
    $stmtEmailCheck = mysqli_prepare($conn, $emailCheckQuery);
    mysqli_stmt_bind_param($stmtEmailCheck, "s", $email);
    mysqli_stmt_execute($stmtEmailCheck);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmtEmailCheck)) > 0) {
        sendError('Email already exists.');
    }

    if (empty($username)) {
        sendError('Username is required.');
    }
    $userCheckQuery = "SELECT id FROM user_accounts WHERE username = ?";
    $stmtUserCheck = mysqli_prepare($conn, $userCheckQuery);
    mysqli_stmt_bind_param($stmtUserCheck, "s", $username);
    mysqli_stmt_execute($stmtUserCheck);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmtUserCheck)) > 0) {
        sendError('Username already exists.');
    }

    if (strlen($password) < 8) {
        sendError('Password must be at least 8 characters long.');
    }
    if ($password !== $confirm_password) {
        sendError('Passwords do not match.');
    }

    mysqli_begin_transaction($conn);

    try {
        $roomQuery = "SELECT room_id, room_type FROM room WHERE room_number = ? AND room_status = 'available'";
        $stmt = mysqli_prepare($conn, $roomQuery);
        mysqli_stmt_bind_param($stmt, "s", $room_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$row = mysqli_fetch_assoc($result)) {
            sendError('Room not available.');
        }

        $room_id = $row['room_id'];
        $room_type = $row['room_type'];
        if (($room_type == 'Single' && $num_occupants != 1) || 
            ($room_type == 'Double' && $num_occupants != 2) || 
            ($room_type == 'Family' && ($num_occupants < 3 || $num_occupants > 5))) {
            sendError("Invalid number of occupants for Room Type: $room_type.");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $insertUserQuery = "INSERT INTO user_accounts (username, password, status, type) VALUES (?, ?, 'pending', 'tenant')";
        $stmtInsertUser = mysqli_prepare($conn, $insertUserQuery);
        mysqli_stmt_bind_param($stmtInsertUser, "ss", $username, $hashed_password);
        if (!mysqli_stmt_execute($stmtInsertUser)) sendError('Failed to create user account.');

        $user_id = mysqli_insert_id($conn);
        $insertTenantQuery = "INSERT INTO tenant_details (id, fname, lname, number_of_occupants, email_address, contact_number) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertTenant = mysqli_prepare($conn, $insertTenantQuery);
        mysqli_stmt_bind_param($stmtInsertTenant, "ississ", $user_id, $fname, $lname, $num_occupants, $email, $contact_number);
        if (!mysqli_stmt_execute($stmtInsertTenant)) sendError('Failed to insert tenant details.');

        $tenant_id = mysqli_insert_id($conn);

        $insertBookingQuery = "INSERT INTO booking (tenant_id, room_id, booking_start_date, booking_end_date, status) VALUES (?, ?, ?, ?, 'booked')";
        $stmtInsertBooking = mysqli_prepare($conn, $insertBookingQuery);
        mysqli_stmt_bind_param($stmtInsertBooking, "iiss", $tenant_id, $room_id, $booking_date, $moving_in_date);
        if (!mysqli_stmt_execute($stmtInsertBooking)) sendError('Failed to create booking.');

        $updateRoomStatusQuery = "UPDATE room SET room_status = 'reserved' WHERE room_id = ?";
        $stmtUpdateRoomStatus = mysqli_prepare($conn, $updateRoomStatusQuery);
        mysqli_stmt_bind_param($stmtUpdateRoomStatus, "i", $room_id);
        if (!mysqli_stmt_execute($stmtUpdateRoomStatus)) sendError('Failed to update room status.');

        mysqli_commit($conn);
        sendSuccess('Registration successful!', ['tenant_id' => $tenant_id]);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        sendError($e->getMessage());
    }
}
?>
