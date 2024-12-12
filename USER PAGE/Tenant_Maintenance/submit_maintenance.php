<?php
session_start();
require_once '../../DATABASE/dbConnector.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function log_error($message) {
    $log_file = '../../logs/errors.log';  
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($log_file, "[$timestamp] - $message" . PHP_EOL, FILE_APPEND);
}

header('Content-Type: application/json');

if (!isset($_SESSION['tc_id'])) {
    log_error("User not logged in. Maintenance request attempted.");
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to submit a maintenance request.'
    ]);
    exit();
}

if (isset($_POST['item_name'], $_POST['item_desc'])) {
    $tenant_id = $_SESSION['tc_id'];
    $item_name = trim($_POST['item_name']);
    $item_desc = trim($_POST['item_desc']);

    $tenant_query = "
        SELECT td.fname, td.lname, r.room_number
        FROM tenant_details td
        JOIN booking b ON td.tc_id = b.tenant_id
        JOIN room r ON b.room_id = r.room_id
        WHERE td.tc_id = ? 
        LIMIT 1
    ";

    $tenant_stmt = $conn->prepare($tenant_query);
    $tenant_stmt->bind_param('i', $tenant_id);
    $tenant_stmt->execute();
    $tenant_result = $tenant_stmt->get_result();

    if ($tenant_result->num_rows > 0) {
        $tenant_row = $tenant_result->fetch_assoc();
        $tenant_name = $tenant_row['fname'] . ' ' . $tenant_row['lname'];
        $room_number = $tenant_row['room_number'];

        $query = "INSERT INTO maintenance_requests (tenant_id, item_name, item_desc) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            log_error("Database query preparation failed: " . $conn->error);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare the database query. Please try again later.'
            ]);
            exit();
        }

        $stmt->bind_param('iss', $tenant_id, $item_name, $item_desc);

        if ($stmt->execute()) {
            $notification_message = "{$tenant_name} from {$room_number} submitted a new maintenance request to fix his/her {$item_name}.";
            $notification_type = "maintenance";
            $status = "unseen";

            $notification_query = "INSERT INTO notifications (user, type, message, status) VALUES (?, ?, ?, ?)";
            $notification_stmt = $conn->prepare($notification_query);
            if ($notification_stmt === false) {
                log_error("Failed to prepare notification query: " . $conn->error);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to prepare notification. Please try again later.'
                ]);
                exit();
            }

            $notification_stmt->bind_param("isss", $tenant_id, $notification_type, $notification_message, $status);

            if ($notification_stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Request submitted successfully.'
                ]);
            } else {
                log_error("Failed to insert notification: " . $notification_stmt->error);
                echo json_encode([
                    'success' => false,
                    'message' => 'Request submitted successfully, but failed to send notification.'
                ]);
            }

        } else {
            log_error("Failed to submit maintenance request: " . $stmt->error);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit the request. Please try again later.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tenant details not found. Please ensure the tenant is correctly booked in a room.'
        ]);
    }
} else {
    log_error("Invalid input: Missing item name or description.");
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input. Please provide all required fields.'
    ]);
}

?>
