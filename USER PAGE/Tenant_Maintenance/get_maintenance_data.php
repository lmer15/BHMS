<?php
include_once '../../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM maintenance_requests";
$result = $conn->query($query);

$maintenanceData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $maintenanceData[] = [
            'dateRequested' => date('Y-m-d H:i:s', strtotime($row['date_requested'])), // Format date
            'itemRequested' => $row['item_name'],
            'details' => $row['item_desc'],
            'status' => $row['status'],
        ];
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($maintenanceData);

?>
