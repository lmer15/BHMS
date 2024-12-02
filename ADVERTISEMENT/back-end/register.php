<?php
require_once '../../DATABASE/dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
    $num_occupants = (int) $_POST['num_occupants'];
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $moving_in_date = mysqli_real_escape_string($conn, $_POST['moving_in_date']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Password and confirm password validation
    if ($password !== $confirm_password) {
        header("Location: ../register-form.php?error=password_mismatch");
        exit();
    }

    // Fetch room type and status based on room number
    $roomQuery = "SELECT room_id, room_type, room_status FROM room WHERE room_number = ?";
    $stmt = mysqli_prepare($conn, $roomQuery);
    mysqli_stmt_bind_param($stmt, "s", $room_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $room_id = $row['room_id'];
        $room_type = $row['room_type'];
        $room_status = $row['room_status'];
    } else {
        header("Location: ../register-form.php?error=invalid_room_number");
        exit();
    }

    // Room type validation
    $valid = true;
    $error_message = '';
    if ($room_type == 'Single' && $num_occupants > 1) {
        $valid = false;
        $error_message = 'Single rooms can only accommodate 1 occupant.';
    } elseif ($room_type == 'Double' && $num_occupants > 2) {
        $valid = false;
        $error_message = 'Double rooms can only accommodate 2 occupants.';
    } elseif ($room_type == 'Family' && $num_occupants > 5) {
        $valid = false;
        $error_message = 'Family rooms can accommodate a maximum of 5 occupants.';
    }

    if (!$valid) {
        header("Location: ../register-form.php?error=" . urlencode($error_message));
        exit();
    }

    // Check if username already exists
    $user_check_query = "SELECT id FROM user_accounts WHERE username = ?";
    $stmt_check_user = mysqli_prepare($conn, $user_check_query);
    mysqli_stmt_bind_param($stmt_check_user, "s", $username);
    mysqli_stmt_execute($stmt_check_user);
    $result_check = mysqli_stmt_get_result($stmt_check_user);

    if (mysqli_num_rows($result_check) > 0) {
        header("Location: ../register-form.php?error=username_exists");
        exit();
    }

    // Check if email already exists
    $email_check_query = "SELECT id FROM tenant_details WHERE email = ?";
    $stmt_check_email = mysqli_prepare($conn, $email_check_query);
    mysqli_stmt_bind_param($stmt_check_email, "s", $email);
    mysqli_stmt_execute($stmt_check_email);
    $result_check_email = mysqli_stmt_get_result($stmt_check_email);

    if (mysqli_num_rows($result_check_email) > 0) {
        header("Location: ../register-form.php?error=email_exists");
        exit();
    }

    // Hash the password before inserting into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into user_accounts table
    $query_user = "INSERT INTO user_accounts (username, password, status, type) VALUES (?, ?, 'booked', 'tenant')";
    $stmt_user = mysqli_prepare($conn, $query_user);
    mysqli_stmt_bind_param($stmt_user, "ss", $username, $hashed_password);
    mysqli_stmt_execute($stmt_user);
    $user_id = mysqli_insert_id($conn);

    // Insert into tenant_details table
    $query = "INSERT INTO tenant_details (user_id, number_of_occupants, email_address, contact_number) 
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiss", $user_id, $num_occupants, $email, $contact_number);

    if (mysqli_stmt_execute($stmt)) {
        $tenant_id = mysqli_insert_id($conn);

        // Insert the booking details into the booking table
        $booking_query = "INSERT INTO booking (tenant_id, room_id, booking_start_date, booking_end_date, status) 
                          VALUES (?, ?, ?, ?, 'reserved')";
        $stmt_booking = mysqli_prepare($conn, $booking_query);
        mysqli_stmt_bind_param($stmt_booking, "iiss", $tenant_id, $room_id, $moving_in_date, $moving_out_date);

        if (mysqli_stmt_execute($stmt_booking)) {
            $update_room_status_query = "UPDATE room SET room_status = 'reserved' WHERE room_id = ?";
            $stmt_update_room_status = mysqli_prepare($conn, $update_room_status_query);
            mysqli_stmt_bind_param($stmt_update_room_status, "i", $room_id);

            if (mysqli_stmt_execute($stmt_update_room_status)) {
                header("Location: ../index.html?success=true");
                exit();
            } else {
                header("Location: ../register-form.php?error=room_status_update_failed");
                exit();
            }
        } else {
            header("Location: ../register-form.php?error=booking_insert_failed");
            exit();
        }
    } else {
        header("Location: ../register-form.php?error=tenant_details_insert_failed");
        exit();
    }
}
?>
