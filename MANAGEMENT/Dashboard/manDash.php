<?php
include_once '../../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get total room count (all rooms in the room table)
$sql_total_rooms = "SELECT COUNT(*) as count FROM room";
$result_total_rooms = $conn->query($sql_total_rooms);
$total_rooms = 0;
if ($result_total_rooms && $result_total_rooms->num_rows > 0) {
    $row = $result_total_rooms->fetch_assoc();
    $total_rooms = $row['count'];
}

// Query to get room counts grouped by status
$sql_rooms = "SELECT room_status, COUNT(*) as count FROM room GROUP BY room_status";
$result_rooms = $conn->query($sql_rooms);

// Default room counts by status
$room_counts = [
    'under_maintenance' => 0,
    'occupied' => 0,
    'reserved' => 0
];

if ($result_rooms && $result_rooms->num_rows > 0) {
    // Populate room counts from the database
    while ($row = $result_rooms->fetch_assoc()) {
        switch ($row['room_status']) {
            case 'under maintenance':
                $room_counts['under_maintenance'] = $row['count'];
                break;
            case 'occupied':
                $room_counts['occupied'] = $row['count'];
                break;
            case 'reserved':
                $room_counts['reserved'] = $row['count'];
                break;
            // If you need additional room statuses, you can add more cases here.
        }
    }
}

// Query to get notifications excluding 'payment-admin' and 'maintenance-admin'
$sql_notifications = "SELECT message, created_at FROM notifications WHERE type NOT IN ('maintenance-admin', 'payment-admin') ORDER BY created_at DESC LIMIT 5";
$result_notifications = $conn->query($sql_notifications);

$notifications = [];
if ($result_notifications && $result_notifications->num_rows > 0) {
    while ($row = $result_notifications->fetch_assoc()) {
        $notifications[] = [
            'message' => $row['message'],
            'timestamp' => date('d M Y \a\t h:i A', strtotime($row['created_at']))
        ];
    }
} else {
    // No notifications found
    $notifications[] = [
        'message' => 'No notifications available.',
        'timestamp' => ''
    ];
}

$sql_users = "
    SELECT td.profile, td.fname, td.lname, td.email_address, r.room_number, r.room_status
    FROM tenant_details td
    JOIN booking b ON td.tc_id = b.tenant_id
    JOIN room r ON b.room_id = r.room_id
    ORDER BY b.booking_start_date DESC
    LIMIT 5
";

$result_users = $conn->query($sql_users);

$users = [];
if ($result_users && $result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        if ($row['room_status'] === 'occupied') { 
            $users[] = [
                'profile' => $row['profile'],
                'full_name' => $row['fname'] . ' ' . $row['lname'],
                'email' => $row['email_address'],
                'room_number' => $row['room_number']
            ];
        }
    }
} else {
    // No results found
    $users[] = [
        'profile' => '',
        'full_name' => 'No occupants found',
        'email' => '',
        'room_number' => ''
    ];
}

$sql_maintenance_requests = "
    SELECT 
        mr.id AS request_id,
        td.fname,
        td.lname,
        td.email_address,
        mr.date_requested,
        mr.item_name,
        mr.item_desc,
        mr.status,
        r.room_number
    FROM maintenance_requests mr
    JOIN tenant_details td ON mr.tenant_id = td.tc_id
    JOIN booking b ON td.tc_id = b.tenant_id
    JOIN room r ON b.room_id = r.room_id
    ORDER BY mr.date_requested DESC
    LIMIT 5
";

$result_maintenance_requests = $conn->query($sql_maintenance_requests);

$maintenance_requests = [];

if ($result_maintenance_requests && $result_maintenance_requests->num_rows > 0) {
    while ($row = $result_maintenance_requests->fetch_assoc()) {
        $maintenance_requests[] = [
            'tenant_name' => $row['fname'] . ' ' . $row['lname'],
            'email' => $row['email_address'],
            'date_requested' => date('d M Y', strtotime($row['date_requested'])),
            'item_name' => $row['item_name'],
            'item_desc' => $row['item_desc'],
            'status' => $row['status'],
            'room_number' => $row['room_number'] // Ensure this is added to the array
        ];
    }
} else {
    $maintenance_requests[] = [
        'tenant_name' => 'No requests found',
        'email' => '',
        'date_requested' => '',
        'item_name' => '',
        'item_desc' => '',
        'status' => '',
        'room_number' => '' // Make sure this is included in case of no results
    ];
}


$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="manDash.css?v=1.10">
</head>
<body>
    
    <div class="wrapper">

        <div class="room-notif-pay">
            <div class="room">
                <div class="room1">
                    <div class="room-cat totro">
                        <span><?php echo $total_rooms; ?></span>
                        <p>TOTAL ROOMS</p>
                    </div>

                    <div class="room-cat unman">
                        <span><?php echo $room_counts['under_maintenance']; ?></span>
                        <p>UNDER MAINTENANCE</p>
                    </div>
                </div>
                <div class="room1">
                    <div class="room-cat occu">
                        <span><?php echo $room_counts['occupied']; ?></span>
                        <p>OCCUPIED</p>
                    </div>

                    <div class="room-cat reser">
                        <span><?php echo $room_counts['reserved']; ?></span>
                        <p>RESERVED</p>
                    </div>
                </div>
            </div>
                          
            <div class="notification">
                <span><i class='bx bx-bell icon'></i> Notifications</span>
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notifi">
                            <p class="description"><?php echo $notification['message']; ?></p>
                            <div class="timestamp">
                                <span>🕒</span> <?php echo $notification['timestamp']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="notifi">
                        <p>No notifications available.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="sale">
                <h1>1,000,00.35</h1>
                <span>Total Sale</span>
            </div>

        </div>

        <div class="user-notif-main">
            <div class="user">
                <?php foreach ($users as $user): ?>
                    <div class="user-card">
                        <div class="profile">
                            <img src="../../USER PAGE/Tenant_Profile/uploads/<?php echo $user['profile']; ?>" alt="profile">
                            <div class="name">
                                <h4><?php echo $user['full_name']; ?></h4>
                                <span><?php echo $user['email']; ?></span>
                            </div>
                        </div>
                        <div class="div"><?php echo $user['room_number']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="maintenance-notif">
                <div class="maintenance">
                    <div class="maintenance-table-header">
                        <div class="maintenance-header-item">Tenant</div>
                        <div class="maintenance-header-item">Room Number</div>
                        <div class="maintenance-header-item">Date Requested</div>
                        <div class="maintenance-header-item">Maintenance Requested</div>
                        <div class="maintenance-header-item">Details</div>
                        <div class="maintenance-header-item">Status</div>
                    </div>

                    <!-- Maintenance Requests -->
                    <?php foreach ($maintenance_requests as $request): ?>
                        <div class="maintenance-table-row">
                            <div class="maintenance-item"><?php echo $request['tenant_name']; ?></div>
                            <div class="maintenance-item"><?php echo $request['room_number']; ?></div>
                            <div class="maintenance-item"><?php echo $request['date_requested']; ?></div>
                            <div class="maintenance-item"><?php echo $request['item_name']; ?></div>
                            <div class="maintenance-item"><?php echo $request['item_desc']; ?></div>
                            <div class="maintenance-item"><?php echo $request['status']; ?></div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>

    </div>

</body>
</html>
